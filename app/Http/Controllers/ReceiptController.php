<?php

namespace App\Http\Controllers;

use App\Mail\PaymentReceiptMail;
use App\Models\PaymentReceipt;
use Barryvdh\DomPDF\Facade\Pdf;
use Carbon\Carbon;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Mail;

class ReceiptController extends Controller
{
    public function confirm(Request $request): JsonResponse
    {
        $secretKey = (string) config('services.paystack.secret_key');

        if ($secretKey === '') {
            return response()->json([
                'status' => false,
                'message' => 'Paystack secret key is not configured.',
            ], 500);
        }

        $validated = $request->validate([
            'reference' => ['required', 'string', 'max:120'],
            'context' => ['required', 'array'],
            'context.type' => ['required', 'string', 'in:order,launch'],
            'context.name' => ['required', 'string', 'max:120'],
            'context.email' => ['required', 'email', 'max:120'],
            'context.phone' => ['nullable', 'string', 'max:40'],
            'context.address' => ['nullable', 'string', 'max:255'],
            'context.delivery' => ['nullable', 'string', 'max:120'],
            'context.books' => ['nullable', 'string', 'max:500'],
            'context.expected_amount_kobo' => ['nullable', 'integer', 'min:1'],
        ]);

        $reference = $validated['reference'];

        $verifyResponse = Http::acceptJson()
            ->withToken($secretKey)
            ->timeout(20)
            ->get("https://api.paystack.co/transaction/verify/{$reference}");

        $payload = $verifyResponse->json();

        if (! is_array($payload) || ! $verifyResponse->ok()) {
            return response()->json([
                'status' => false,
                'message' => $payload['message'] ?? 'Unable to verify payment with Paystack.',
            ], 422);
        }

        $transaction = $payload['data'] ?? [];

        if (! is_array($transaction) || ($transaction['status'] ?? null) !== 'success') {
            return response()->json([
                'status' => false,
                'message' => 'Payment is not marked as successful on Paystack.',
            ], 422);
        }

        $paidAmountKobo = (int) ($transaction['amount'] ?? 0);
        $expectedAmountKobo = $validated['context']['expected_amount_kobo'] ?? null;

        if ($expectedAmountKobo !== null && $paidAmountKobo !== (int) $expectedAmountKobo) {
            return response()->json([
                'status' => false,
                'message' => 'Payment amount mismatch. Please contact support.',
            ], 422);
        }

        $receipt = PaymentReceipt::query()->updateOrCreate(
            ['reference' => $reference],
            [
                'payment_type' => $validated['context']['type'],
                'customer_name' => $validated['context']['name'],
                'customer_email' => $validated['context']['email'],
                'customer_phone' => $validated['context']['phone'] ?: null,
                'customer_address' => $validated['context']['address'] ?: null,
                'delivery_preference' => $validated['context']['delivery'] ?: null,
                'items_description' => $validated['context']['books'] ?: null,
                'amount_kobo' => $paidAmountKobo,
                'currency' => strtoupper((string) ($transaction['currency'] ?? 'NGN')),
                'provider' => 'paystack',
                'status' => 'success',
                'paid_at' => isset($transaction['paid_at']) ? Carbon::parse((string) $transaction['paid_at']) : now(),
                'paystack_payload' => $transaction,
            ],
        );

        $downloadUrl = route('receipts.download', $receipt->reference);

        if (! $receipt->email_sent_at) {
            try {
                Mail::to($receipt->customer_email)->send(new PaymentReceiptMail($receipt, $downloadUrl));
                $receipt->forceFill(['email_sent_at' => now()])->save();
            } catch (\Throwable $exception) {
                Log::warning('Payment receipt email could not be sent.', [
                    'reference' => $receipt->reference,
                    'error' => $exception->getMessage(),
                ]);
            }
        }

        return response()->json([
            'status' => true,
            'message' => 'Payment confirmed.',
            'receipt_url' => route('receipts.show', $receipt->reference),
            'download_url' => $downloadUrl,
        ]);
    }

    public function show(string $reference)
    {
        $receipt = PaymentReceipt::query()
            ->where('reference', $reference)
            ->firstOrFail();

        return view('receipts.show', [
            'receipt' => $receipt,
        ]);
    }

    public function download(string $reference)
    {
        $receipt = PaymentReceipt::query()
            ->where('reference', $reference)
            ->firstOrFail();

        $pdf = Pdf::loadView('receipts.pdf', [
            'receipt' => $receipt,
        ]);

        return $pdf->download("receipt-{$receipt->reference}.pdf");
    }
}
