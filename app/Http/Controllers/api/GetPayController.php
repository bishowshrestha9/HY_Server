<?php

namespace App\Http\Controllers\api;

use App\Http\Controllers\Controller;
use App\Services\GetPayService;
use App\Services\BookingService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Exception;

class GetPayController extends Controller
{
    private GetPayService $getPayService;
    private BookingService $bookingService;

    public function __construct(GetPayService $getPayService, BookingService $bookingService)
    {
        $this->getPayService = $getPayService;
        $this->bookingService = $bookingService;
    }

    /**
     * Get GetPay configuration for frontend
     * This provides the necessary credentials and URLs for frontend integration
     */
    public function getConfig()
    {
        try {
            $config = $this->getPayService->getConfig();

            return response()->json([
                'status' => true,
                'data' => $config,
            ]);

        } catch (Exception $e) {
            Log::error('Failed to get GetPay config', [
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString(),
            ]);
            
            return response()->json([
                'status' => false,
                'message' => 'Failed to get payment configuration',
            ], 500);
        }
    }

    /**
     * Verify payment and update booking status
     * This is called from frontend after successful payment
     */
    public function verifyPayment(Request $request)
    {
        try {
            $validated = $request->validate([
                'token' => 'nullable|string',
                'transaction_id' => 'nullable|string',
                'id' => 'nullable|string',
                'booking_id' => 'required|integer|exists:bookings,id',
            ]);

            $token = $request->input('token');
            $transactionId = $request->input('transaction_id') ?? $request->input('id');
            $bookingId = $validated['booking_id'];

            Log::info('GetPay payment verification started', [
                'booking_id' => $bookingId,
                'token_preview' => $token ? substr($token, 0, 20) . '...' : null,
                'transaction_id' => $transactionId,
            ]);

            if ($token) {
                // Decode token to get transaction ID and oprSecret
                $tokenData = $this->getPayService->decodeToken($token);

                if ($tokenData) {
                    $transactionId = $tokenData['transaction_id'];
                    $oprSecret = $tokenData['opr_secret'];

                    // Verify signature
                    $isSignatureValid = $this->getPayService->verifySignature($transactionId, $oprSecret);

                    if (!$isSignatureValid) {
                        Log::warning('GetPay signature verification failed', [
                            'transaction_id' => $transactionId,
                            'booking_id' => $bookingId,
                        ]);

                        return response()->json([
                            'status' => false,
                            'message' => 'Payment verification failed - invalid signature',
                        ], 400);
                    }
                }
            }

            if (!$transactionId) {
                Log::warning('GetPay verification failed - missing transaction ID and token', [
                    'booking_id' => $bookingId,
                ]);

                return response()->json([
                    'status' => false,
                    'message' => 'Missing payment transaction ID or token',
                ], 400);
            }

            // Verify payment status with GetPay API
            $verificationResult = $this->getPayService->verifyPayment($transactionId);

            if (!$verificationResult['success']) {
                Log::error('GetPay API verification failed', [
                    'transaction_id' => $transactionId,
                    'booking_id' => $bookingId,
                    'message' => $verificationResult['message'],
                ]);

                return response()->json([
                    'status' => false,
                    'message' => $verificationResult['message'],
                ], 400);
            }

            $paymentData = $verificationResult['data'];
            
            // Check if payment was successful (MachBank / CyberSource returns status: 0, message: "SUCCESS", insCode: "000")
            $rawStatus = $paymentData['status'] ?? $paymentData['transactionStatus'] ?? 'unknown';
            $paymentStatus = strtolower((string) $rawStatus);
            $paymentMessage = strtoupper((string) ($paymentData['message'] ?? ''));
            $insCode = $paymentData['data']['insCode'] ?? null;

            $isSuccessful = in_array($paymentStatus, ['success', 'completed', 'approved', '0']) 
                || $paymentMessage === 'SUCCESS' 
                || $insCode === '000' 
                || $rawStatus === 0;

            if ($isSuccessful) {
                // Update booking status to confirmed
                $booking = $this->bookingService->updateStatus($bookingId, 'confirmed');

                if (!$booking) {
                    Log::error('Booking not found after payment verification', [
                        'booking_id' => $bookingId,
                        'transaction_id' => $transactionId,
                    ]);

                    return response()->json([
                        'status' => false,
                        'message' => 'Booking not found',
                    ], 404);
                }

                Log::info('Payment verified and booking confirmed', [
                    'booking_id' => $bookingId,
                    'transaction_id' => $transactionId,
                ]);

                return response()->json([
                    'status' => true,
                    'message' => 'Payment verified and booking confirmed',
                    'data' => [
                        'booking' => $booking,
                        'transaction_id' => $transactionId,
                        'payment_status' => $paymentStatus,
                    ],
                ]);
            }

            // Payment failed or pending
            Log::warning('Payment not successful', [
                'booking_id' => $bookingId,
                'transaction_id' => $transactionId,
                'payment_status' => $paymentStatus,
            ]);

            return response()->json([
                'status' => false,
                'message' => 'Payment not successful',
                'data' => [
                    'payment_status' => $paymentStatus,
                ],
            ], 400);

        } catch (\Illuminate\Validation\ValidationException $e) {
            Log::error('GetPay validation error', [
                'errors' => $e->errors(),
            ]);

            return response()->json([
                'status' => false,
                'message' => 'Validation failed',
                'errors' => $e->errors(),
            ], 422);

        } catch (Exception $e) {
            Log::error('GetPay payment verification exception', [
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString(),
            ]);

            return response()->json([
                'status' => false,
                'message' => 'Payment verification failed',
            ], 500);
        }
    }

    /**
     * Handle payment webhook from GetPay (if they provide webhooks)
     * This endpoint should be registered with GetPay for real-time payment notifications
     */
    public function webhook(Request $request)
    {
        try {
            Log::info('GetPay webhook received', [
                'payload' => $request->all(),
                'headers' => $request->headers->all(),
            ]);

            // Implement webhook handling based on GetPay's webhook structure
            // This will depend on GetPay's webhook documentation
            // For now, we just log the webhook data

            return response()->json([
                'status' => true,
                'message' => 'Webhook received',
            ]);

        } catch (Exception $e) {
            Log::error('GetPay webhook exception', [
                'error' => $e->getMessage(),
                'payload' => $request->all(),
            ]);

            return response()->json([
                'status' => false,
                'message' => 'Webhook processing failed',
            ], 500);
        }
    }
}
