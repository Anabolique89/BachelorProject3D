<?php

namespace App\Http\Controllers;

use App\Models\Order;
use App\Models\Payment;
use App\Models\CartItem;
use App\Services\StripeService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class StripeWebhookController extends Controller
{
    protected $stripeService;

    public function __construct(StripeService $stripeService)
    {
        $this->stripeService = $stripeService;
    }

    /**
     * Handle Stripe webhook
     * 
     * POST /api/stripe/webhook
     */
    public function handleWebhook(Request $request)
    {
        $payload = $request->getContent();
        $signature = $request->header('Stripe-Signature');

        try {
            $event = $this->stripeService->verifyWebhookSignature($payload, $signature);
        } catch (\Exception $e) {
            Log::error('Webhook signature verification failed: ' . $e->getMessage());
            return response()->json(['error' => 'Invalid signature'], 400);
        }

        // Handle the event
        switch ($event->type) {
            case 'checkout.session.completed':
                $this->handleCheckoutSessionCompleted($event->data->object);
                break;

            case 'payment_intent.succeeded':
                $this->handlePaymentIntentSucceeded($event->data->object);
                break;

            case 'payment_intent.payment_failed':
                $this->handlePaymentIntentFailed($event->data->object);
                break;

            default:
                Log::info('Unhandled webhook event: ' . $event->type);
        }

        return response()->json(['status' => 'success']);
    }

    /**
     * Handle successful checkout session
     */
    protected function handleCheckoutSessionCompleted($session)
    {
        $orderId = $session->client_reference_id ?? $session->metadata->order_id ?? null;

        if (!$orderId) {
            Log::error('No order ID in checkout session');
            return;
        }

        $order = Order::find($orderId);

        if (!$order) {
            Log::error('Order not found: ' . $orderId);
            return;
        }

        // Update order status
        $order->update([
            'status' => 'processing',
        ]);

        // Update payment status
        $payment = Payment::where('order_id', $order->id)->first();
        if ($payment) {
            $payment->update([
                'status' => 'completed',
                'transaction_id' => $session->payment_intent,
            ]);
        }

        // Award tokens to customer
        $customer = $order->customer;
        $customer->increment('total_tokens', $order->tokens_earned);
        $customer->increment('available_tokens', $order->tokens_earned);

        // Clear cart
        CartItem::where('customer_id', $customer->id)->delete();

        Log::info('Order completed: ' . $order->order_number);
    }

    /**
     * Handle successful payment intent
     */
    protected function handlePaymentIntentSucceeded($paymentIntent)
    {
        Log::info('Payment succeeded: ' . $paymentIntent->id);
    }

    /**
     * Handle failed payment intent
     */
    protected function handlePaymentIntentFailed($paymentIntent)
    {
        Log::error('Payment failed: ' . $paymentIntent->id);
        
        // You could update order status to 'failed' here
    }
}