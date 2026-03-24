<?php
// ── Payment Service (Razorpay Integration) ────────────────────────────────────
class PaymentService {

    /**
     * Create a Razorpay order and return the order details.
     * Amount is in paise (INR × 100).
     */
    public static function createOrder(float $amount, string $receipt): array {
        if (empty(RAZORPAY_KEY_ID)) {
            // Demo mode — return fake order for local testing
            return [
                'id'       => 'demo_' . uniqid(),
                'amount'   => (int)($amount * 100),
                'currency' => 'INR',
                'receipt'  => $receipt,
                'demo'     => true,
            ];
        }

        $url     = 'https://api.razorpay.com/v1/orders';
        $payload = [
            'amount'          => (int)($amount * 100),
            'currency'        => 'INR',
            'receipt'         => $receipt,
            'payment_capture' => 1,
        ];

        $ch = curl_init($url);
        curl_setopt_array($ch, [
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_POST           => true,
            CURLOPT_POSTFIELDS     => json_encode($payload),
            CURLOPT_USERPWD        => RAZORPAY_KEY_ID . ':' . RAZORPAY_KEY_SECRET,
            CURLOPT_HTTPHEADER     => ['Content-Type: application/json'],
        ]);
        $response = curl_exec($ch);
        $httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
        curl_close($ch);

        if ($httpCode !== 200) {
            Logger::error('Razorpay order creation failed', ['response' => $response]);
            throw new PaymentException('Payment gateway error. Please try again.');
        }

        return json_decode($response, true);
    }

    /**
     * Verify Razorpay payment signature.
     * Call this in the webhook / callback handler.
     */
    public static function verifySignature(string $orderId, string $paymentId, string $signature): bool {
        if (empty(RAZORPAY_KEY_SECRET)) return true; // demo mode
        $generated = hash_hmac('sha256', $orderId . '|' . $paymentId, RAZORPAY_KEY_SECRET);
        return hash_equals($generated, $signature);
    }
}
