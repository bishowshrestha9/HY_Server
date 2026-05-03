<?php

namespace App\Services;

use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class GetPayService
{
    private string $papInfo;
    private string $oprKey;
    private string $insKey;
    private string $baseUrl;
    private string $bundleUrl;
    private string $frontendUrl;
    private string $successUrl;
    private string $failureUrl;

    public function __construct()
    {
        $this->papInfo = config('services.getpay.pap_info');
        $this->oprKey = config('services.getpay.opr_key');
        $this->insKey = config('services.getpay.ins_key');
        $this->baseUrl = config('services.getpay.base_url');
        $this->bundleUrl = config('services.getpay.bundle_url');
        $this->frontendUrl = config('services.getpay.frontend_url');
        $this->successUrl = config('services.getpay.success_url');
        $this->failureUrl = config('services.getpay.failure_url');
    }

    /**
     * Get GetPay configuration for frontend
     */
    public function getConfig(): array
    {
        return [
            'papInfo' => $this->papInfo,
            'oprKey' => $this->oprKey,
            'insKey' => $this->insKey,
            'baseUrl' => $this->baseUrl,
            'bundleUrl' => $this->bundleUrl,
            'websiteDomain' => $this->frontendUrl,
            'successUrl' => $this->successUrl,
            'failureUrl' => $this->failureUrl,
        ];
    }

    /**
     * Verify payment status with GetPay API
     * 
     * @param string $transactionId GetPay transaction ID
     * @return array Payment verification result
     */
    public function verifyPayment(string $transactionId): array
    {
        try {
            $url = "{$this->baseUrl}/merchant-status";

            Log::info('GetPay verification request', [
                'url' => $url,
                'transaction_id' => $transactionId,
                'pap_info' => $this->papInfo,
            ]);

            $response = Http::post($url, [
                'id' => $transactionId,
                'papInfo' => $this->papInfo,
            ]);

            if ($response->failed()) {
                Log::error('GetPay verification failed', [
                    'transaction_id' => $transactionId,
                    'status' => $response->status(),
                    'response' => $response->body(),
                ]);

                return [
                    'success' => false,
                    'message' => 'Payment verification failed',
                    'data' => null,
                ];
            }

            $data = $response->json();

            Log::info('GetPay verification response', [
                'transaction_id' => $transactionId,
                'data' => $data,
            ]);

            return [
                'success' => true,
                'message' => 'Payment verified successfully',
                'data' => $data,
            ];

        } catch (\Exception $e) {
            Log::error('GetPay verification exception', [
                'transaction_id' => $transactionId,
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString(),
            ]);

            return [
                'success' => false,
                'message' => 'Payment verification error: ' . $e->getMessage(),
                'data' => null,
            ];
        }
    }

    /**
     * Verify payment signature/token from success URL
     * According to GetPay docs: generate hash using oprKey + transaction ID and compare with oprSecret
     * 
     * @param string $transactionId Transaction ID
     * @param string $oprSecret Token received from GetPay
     * @return bool Whether signature is valid
     */
    public function verifySignature(string $transactionId, string $oprSecret): bool
    {
        try {
            // Generate hash using oprKey and transaction ID
            $generatedHash = base64_encode(hash('sha256', $this->oprKey . $transactionId, true));

            // Compare with the oprSecret received
            $isValid = hash_equals($generatedHash, $oprSecret);

            Log::info('GetPay signature verification', [
                'transaction_id' => $transactionId,
                'is_valid' => $isValid,
            ]);

            return $isValid;

        } catch (\Exception $e) {
            Log::error('GetPay signature verification exception', [
                'transaction_id' => $transactionId,
                'error' => $e->getMessage(),
            ]);

            return false;
        }
    }

    /**
     * Decode token from success/failure URL
     * Token is Base64 encoded string: transactionId:oprSecret
     * 
     * @param string $token Base64 encoded token
     * @return array|null Decoded token data
     */
    public function decodeToken(string $token): ?array
    {
        try {
            $decoded = base64_decode($token, true);

            if ($decoded === false) {
                Log::error('GetPay token decode failed - invalid base64', ['token' => $token]);
                return null;
            }

            // Token format: transactionId:oprSecret
            $parts = explode(':', $decoded, 2);

            if (count($parts) !== 2) {
                Log::error('GetPay token format invalid', [
                    'decoded' => $decoded,
                    'parts_count' => count($parts),
                ]);
                return null;
            }

            return [
                'transaction_id' => $parts[0],
                'opr_secret' => $parts[1],
            ];

        } catch (\Exception $e) {
            Log::error('GetPay token decode exception', [
                'token' => $token,
                'error' => $e->getMessage(),
            ]);

            return null;
        }
    }
}
