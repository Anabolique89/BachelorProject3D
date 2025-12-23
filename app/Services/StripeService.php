<?php

namespace App\Services;

use Stripe\Stripe;
use Stripe\Checkout\Session as StripeSession;
use App\Models\Order;

class StripeService
{
    public function __construct()
    {
        Stripe::setApiKey(config('stripe.secret_key')); // ✅ Changed
    }

    /**
     * Create Stripe Checkout Session
     */
    public function createCheckoutSession(Order $order, $successUrl, $cancelUrl)
    {
        $lineItems = [];

        foreach ($order->items as $item) {
            $lineItems[] = [
                'price_data' => [
                    'currency' => 'usd',
                    'product_data' => [
                        'name' => $item->product->title,
                        'description' => $item->product->description,
                        'images' => $item->product->image_url ? [$item->product->image_url] : [],
                    ],
                    'unit_amount' => (int)($item->unit_price * 100), // Convert to cents
                ],
                'quantity' => $item->quantity,
            ];
        }

        $session = StripeSession::create([
            'payment_method_types' => ['card'],
            'line_items' => $lineItems,
            'mode' => 'payment',
            'success_url' => $successUrl,
            'cancel_url' => $cancelUrl,
            'client_reference_id' => (string)$order->id,
            'customer_email' => $order->details->email,
            'metadata' => [
                'order_id' => $order->id,
                'order_number' => $order->order_number,
            ],
        ]);

        return $session;
    }

    /**
     * Verify webhook signature
     */
    public function verifyWebhookSignature($payload, $signature)
    {
        return \Stripe\Webhook::constructEvent(
            $payload,
            $signature,
            config('stripe.webhook_secret') 
        );
    }
}
