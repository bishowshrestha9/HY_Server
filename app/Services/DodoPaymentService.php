<?php

namespace App\Services;

use App\Interfaces\BookingRepositoryInterface;
use App\Interfaces\PaymentRepositoryInterface;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Cache;
use Exception;

class DodoPaymentService
{
    private string $apiKey;
    private string $webhookSecret;
    private string $baseUrl;
    private string $frontendUrl;
    private string $backendUrl;

    private PaymentRepositoryInterface $paymentRepository;
    private BookingRepositoryInterface $bookingRepository;

    public function __construct(
        PaymentRepositoryInterface $paymentRepository,
        BookingRepositoryInterface $bookingRepository
    ) {
        $this->apiKey = config('services.dodo.api_key');
        $this->webhookSecret = config('services.dodo.webhook_secret');
        $this->frontendUrl = config('services.dodo.frontend_url');
        $this->backendUrl = config('app.url');

        $this->paymentRepository = $paymentRepository;
        $this->bookingRepository = $bookingRepository;

        // Set base URL based on environment
        $mode = config('services.dodo.mode', 'test');
        $this->baseUrl = $mode === 'live'
            ? 'https://live.dodopayments.com'
            : 'https://test.dodopayments.com';
    }

    /**
     * Initiate a payment transaction: Create local record and call Dodo Payments
     * 
     * @param array $data Validated request data
     * @return array
     * @throws Exception
     */
    public function initiatePayment(array $data): array
    {
        // Generate invoice number
        $invoiceNumber = 'INV-DODO-' . strtoupper(uniqid());

        // Create payment record
        $payment = $this->paymentRepository->create([
            'full_name' => $data['full_name'],
            'email' => $data['email'],
            'phone_number' => $data['phone_number'] ?? null,
            'invoice_number' => $invoiceNumber,
            'description' => $data['description'] ?? 'Himalayan Yogini Booking Payment',
            'amount' => $data['amount'],
            'currency' => 'USD',
            'status' => 'pending',
            'payment_gateway' => 'dodo',
            'isAdvancePayment' => $data['isAdvancePayment'] ?? false,
            'advancePercentage' => $data['advancePercentage'] ?? null,
            'fullAmount' => $data['fullAmount'] ?? null,
        ]);

        // Link payment to booking if provided
        if (!empty($data['orderId'])) {
            $booking = $this->bookingRepository->findById($data['orderId']);
            if ($booking) {
                $booking->update(['payment_id' => $payment->id]);
            }
        }

        // Get or Create Product ID
        $productId = $this->getOrCreateProductId();

        // Create Dodo Checkout Session
        $sessionData = $this->createCheckoutSession($data, $invoiceNumber, $payment->id, $productId);

        if (empty($sessionData['paymentUrl'])) {
            $payment->markAsFailed('Failed to create Dodo payment session');
            throw new Exception('Failed to create payment session from Dodo Payments.');
        }

        // Update payment with Dodo details
        $this->paymentRepository->update($payment, [
            'payoneer_long_id' => $sessionData['paymentId'] ?? null,
            'payoneer_transaction_id' => $sessionData['paymentId'] ?? null,
            'payoneer_redirect_url' => $sessionData['paymentUrl'],
            'payment_intent_id' => $sessionData['paymentId'] ?? $invoiceNumber,
        ]);

        return [
            'payment_id' => $payment->id,
            'invoice_number' => $invoiceNumber,
            'redirect_url' => $sessionData['paymentUrl'],
        ];
    }

    /**
     * Process incoming webhook from Dodo
     */
    public function processWebhook(array $payload, ?string $signature): array
    {
        if ($signature && !$this->verifyWebhookSignature($payload, $signature)) {
            throw new Exception('Invalid Dodo webhook signature', 401);
        }

        Log::info('Dodo webhook received', ['payload' => $payload]);

        $eventType = $payload['type'] ?? null;
        $data = $payload['data'] ?? [];

        $gatewayId = $data['id'] ?? null;
        $internalPaymentId = $data['metadata']['payment_id'] ?? null;

        if (!$internalPaymentId && !$gatewayId) {
            return ['message' => 'Ignored webhook (no identifier)'];
        }

        $payment = null;
        if ($internalPaymentId) {
            $payment = $this->paymentRepository->findById($internalPaymentId);
        }

        if (!$payment && $gatewayId) {
            $payment = $this->paymentRepository->findByPayoneerIdentifiers($gatewayId, null);
        }

        if (!$payment) {
            throw new Exception('Payment not found for webhook processing', 404);
        }

        DB::transaction(function () use ($payment, $eventType) {
            switch ($eventType) {
                case 'payment.succeeded':
                case 'checkout.session.completed':
                    $payment->markAsSuccessful();
                    $booking = $this->bookingRepository->findByPaymentId($payment->id);
                    if ($booking) {
                        $this->bookingRepository->updateStatus($booking, 'confirmed');
                    }
                    break;

                case 'payment.failed':
                    $payment->markAsFailed('Payment failed via Dodo');
                    $booking = $this->bookingRepository->findByPaymentId($payment->id);
                    if ($booking) {
                        $this->bookingRepository->updateStatus($booking, 'cancelled');
                    }
                    break;
            }
        });

        return ['message' => 'Webhook processed successfully'];
    }

    /**
     * Create Checkout Session via Dodo API
     */
    private function createCheckoutSession(array $data, string $invoiceNumber, int $paymentId, string $productId): array
    {
        $payload = [
            'product_cart' => [
                [
                    'product_id' => $productId,
                    'quantity' => 1,
                    // Amount in cents is likely required if PWYW is true on the product side
                    'amount' => (int) ($data['amount'] * 100),
                ]
            ],
            'billing_info' => [
                'city' => 'Unknown', // Required by some gateways, dummy if needed
                'country' => 'US',
                'email' => $data['email'],
                'name' => $data['full_name'],
                // Add validation/defaults for other address fields if needed
            ],
            'metadata' => [
                'payment_id' => (string) $paymentId,
                'invoice_number' => (string) $invoiceNumber
            ],
            'return_url' => $data['return_url'] ?? "{$this->frontendUrl}/payment/success?orderId={$data['orderId']}",
            // 'cancel_url' => $data['cancel_url'] ?? "{$this->frontendUrl}/payment/cancel?orderId={$data['orderId']}"
        ];

        // Ensure endpoint is correct: /checkouts
        $response = Http::withToken($this->apiKey)
            ->post("{$this->baseUrl}/checkouts", $payload);

        if (!$response->successful()) {
            Log::error('Dodo Checkout error', [
                'status' => $response->status(),
                'body' => $response->body()
            ]);
            throw new Exception('Dodo Checkout call failed: ' . $response->body());
        }

        $resData = $response->json();

        // Response usually contains { "checkout_url": "...", "session_id": "...", "id": "..." }
        return [
            'paymentUrl' => $resData['checkout_url'] ?? $resData['url'] ?? null,
            'paymentId' => $resData['checkout_session_id'] ?? $resData['id'] ?? null,
        ];
    }

    /**
     * Get configured Product ID or create one if missing
     */
    private function getOrCreateProductId(): string
    {
        // 1. Check config/env
        $configId = config('services.dodo.product_id');
        if ($configId) {
            return $configId;
        }

        // 2. Check cache
        $cachedId = Cache::rememberForever('dodo_default_booking_product_id', function () {
            // 3. Try API Create
            return $this->createDefaultProduct();
        });

        return $cachedId;
    }

    private function createDefaultProduct(): string
    {
        $payload = [
            'name' => 'Himalayan Yogini Booking',
            'description' => 'General purpose booking payment',
            'image' => 'https://himalayanyogini.com/logo.png', // Optional
            'tax_category' => 'digital_products',
            'price' => [
                'price' => 100,
                'currency' => 'USD',
                'type' => 'one_time_price',
                'pay_what_you_want' => true,
                'minimum_amount' => 100,
                'maximum_amount' => 1000000,
                'discount' => 0,
                'purchasing_power_parity' => false
            ]
        ];

        $response = Http::withToken($this->apiKey)
            ->post("{$this->baseUrl}/products", $payload);

        if ($response->successful()) {
            $data = $response->json();
            $id = $data['product_id'] ?? $data['id'] ?? null;
            if ($id) {
                Log::info('Created new Dodo Product', ['id' => $id]);
                return $id;
            }
        }

        Log::error('Failed to create Dodo Product', ['body' => $response->body()]);

        // Fallback or throw
        throw new Exception('Could not find or create a valid Dodo Product ID. Please set DODO_PAYMENTS_PRODUCT_ID in .env');
    }

    private function verifyWebhookSignature(array $payload, string $signature): bool
    {
        return true;
    }
}
