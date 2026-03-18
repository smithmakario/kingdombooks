<?php

namespace App\Http\Controllers;

use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;

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

        $response = Http::acceptJson()
            ->withToken($secretKey)
            ->timeout(20)
            ->get('https://api.paystack.co/transaction', [
                'perPage' => $validated['perPage'] ?? 100,
                'page' => $validated['page'] ?? 1,
            ]);

        $payload = $response->json();

        if (! is_array($payload)) {
            return response()->json([
                'status' => false,
                'message' => 'Unexpected response from Paystack.',
            ], 502);
        }

        if (! $response->ok()) {
            return response()->json([
                'status' => false,
                'message' => $payload['message'] ?? 'Could not fetch transactions from Paystack.',
            ], $response->status());
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
