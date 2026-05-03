<?php

namespace App\Http\Controllers\payment;

use App\Http\Controllers\Controller;
use App\Services\DodoPaymentService; // Ensure this is imported
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Exception;

class DodoPaymentController extends Controller
{
    private DodoPaymentService $dodoService;

    public function __construct(DodoPaymentService $dodoService)
    {
        $this->dodoService = $dodoService;
    }

    /**
     * Create a Dodo payment session
     * 
     * POST /api/dodo/session
     * 
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function createSession(Request $request)
    {
        // Add validation logic here or use a FormRequest
        $data = $request->validate([
            'amount' => 'required|numeric',
            'currency' => 'nullable|string',
            'full_name' => 'required|string',
            'email' => 'required|email',
            'orderId' => 'required', // Assuming orderId is passed
            'description' => 'nullable|string',
            'phone_number' => 'nullable|string',
            'isAdvancePayment' => 'boolean',
            'advancePercentage' => 'nullable|numeric',
            'fullAmount' => 'nullable|numeric',
        ]);

        try {
            $result = $this->dodoService->initiatePayment($data);

            return response()->json(array_merge(
                ['success' => true],
                $result,
                ['redirectUrl' => $result['redirect_url']] // For frontend compatibility
            ), 201);

        } catch (Exception $e) {
            Log::error('Dodo session creation error', ['error' => $e->getMessage()]);

            return response()->json([
                'success' => false,
                'message' => 'An error occurred while creating payment session',
                'error' => config('app.debug') ? $e->getMessage() : null
            ], 500);
        }
    }

    /**
     * Handle Dodo webhook notifications
     * 
     * POST /api/dodo/notifications
     * 
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function handleWebhook(Request $request)
    {
        try {
            // Check for signature header. Dodo might use 'HTTP_X_DODO_SIGNATURE' or similiar
            $signature = $request->header('X-Dodo-Signature') ?? $request->header('webhook-signature');

            $result = $this->dodoService->processWebhook($request->all(), $signature);

            return response()->json([
                'success' => true,
                'message' => $result['message']
            ], 200);

        } catch (Exception $e) {
            Log::error('Dodo webhook processing error', [
                'error' => $e->getMessage(),
                'code' => $e->getCode()
            ]);

            return response()->json([
                'success' => false,
                'message' => $e->getMessage()
            ], $e->getCode() ?: 500);
        }
    }
}
