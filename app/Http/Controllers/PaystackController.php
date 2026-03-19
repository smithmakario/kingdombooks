<?php

namespace App\Http\Controllers;

use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class PaystackController extends Controller
{
    public function publicKey(): JsonResponse
    {
        $publicKey = (string) config('services.paystack.public_key');

        if ($publicKey === '') {
            return response()->json([
                'status' => false,
                'message' => 'Paystack public key is not configured.',
            ], 500);
        }

        return response()->json([
            'status' => true,
            'public_key' => $publicKey,
        ]);
    }

    public function transactions(Request $request): JsonResponse
    {
        $secretKey = (string) config('services.paystack.secret_key');

        if ($secretKey === '') {
            return response()->json([
                'status' => false,
                'message' => 'Paystack secret key is not configured.',
            ], 500);
        }

        $validated = $request->validate([
            'perPage' => ['nullable', 'integer', 'min:1', 'max:100'],
            'page' => ['nullable', 'integer', 'min:1'],
        ]);

        try {
            $response = Http::acceptJson()
                ->withToken($secretKey)
                ->timeout(20)
                ->get('https://api.paystack.co/transaction', [
                    'perPage' => $validated['perPage'] ?? 100,
                    'page' => $validated['page'] ?? 1,
                ]);
        } catch (\Throwable $exception) {
            Log::error('Paystack transactions request failed.', [
                'error' => $exception->getMessage(),
            ]);

            return response()->json([
                'status' => false,
                'message' => 'Unable to connect to Paystack right now. Please try again shortly.',
            ], 502);
        }

        $payload = $response->json();
        $statusCode = $response->status();

        if (! is_array($payload)) {
            Log::warning('Unexpected non-JSON Paystack transactions response.', [
                'status_code' => $statusCode,
                'body' => mb_substr($response->body(), 0, 500),
            ]);

            return response()->json([
                'status' => false,
                'message' => 'Unexpected response from Paystack.',
            ], 502);
        }

        if (! $response->ok()) {
            Log::warning('Paystack transactions returned an error status.', [
                'status_code' => $statusCode,
                'message' => $payload['message'] ?? null,
                'payload_status' => $payload['status'] ?? null,
            ]);

            return response()->json([
                'status' => false,
                'message' => $payload['message'] ?? 'Could not fetch transactions from Paystack. Confirm your API keys and Paystack account status.',
            ], $statusCode);
        }

        return response()->json([
            'status' => (bool) ($payload['status'] ?? false),
            'message' => $payload['message'] ?? null,
            'data' => $payload['data'] ?? [],
            'meta' => $payload['meta'] ?? null,
            'environment' => str_starts_with($secretKey, 'sk_test') ? 'test' : 'live',
        ]);
    }
}
