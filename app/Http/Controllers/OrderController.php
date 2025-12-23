<?php

namespace App\Http\Controllers;

use App\Models\CartItem;
use App\Models\Order;
use App\Models\OrderDetail;
use App\Models\OrderItem;
use App\Models\Payment;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use App\Services\StripeService;

class OrderController extends Controller
{
    /**
     * Create a new order from cart
     * 
     * POST /api/orders
     */

   protected $stripeService;

    public function __construct(StripeService $stripeService)
    {
        $this->stripeService = $stripeService;
    }

  public function store(Request $request)
    {
        $request->validate([
            'shipping_details.first_name' => 'required|string|max:45',
            'shipping_details.last_name' => 'required|string|max:45',
            'shipping_details.email' => 'required|email|max:45',
            'shipping_details.phone' => 'nullable|string|max:45',
            'shipping_details.address1' => 'required|string|max:45',
            'shipping_details.address2' => 'nullable|string|max:45',
            'shipping_details.city' => 'required|string|max:45',
            'shipping_details.state' => 'nullable|string|max:45',
            'shipping_details.zipcode' => 'required|string|max:45',
            'shipping_details.country_code' => 'required|string|max:2',
            'payment.type' => 'required|string|max:45',
        ]);

        $user = Auth::user();
        $customer = $user->customer;

        if (!$customer) {
            return response()->json([
                'message' => 'Customer profile not found'
            ], 404);
        }

        // Get cart items
        $cartItems = CartItem::with('product')
            ->where('customer_id', $customer->id)
            ->get();

        if ($cartItems->isEmpty()) {
            return response()->json([
                'message' => 'Cart is empty'
            ], 400);
        }

        // Calculate totals
        $subtotal = 0;
        foreach ($cartItems as $item) {
            $price = $item->product->is_on_sale ? $item->product->sale_price : $item->product->price;
            $subtotal += $price * $item->quantity;

            // Check stock
            if ($item->product->stock_quantity < $item->quantity) {
                return response()->json([
                    'message' => "Insufficient stock for {$item->product->title}",
                    'product' => $item->product->title,
                    'available' => $item->product->stock_quantity,
                    'requested' => $item->quantity,
                ], 400);
            }
        }

        $tax = $subtotal * 0.10; // 10% tax
        $total = $subtotal + $tax;

        DB::beginTransaction();

        try {
            // Create order
            $order = Order::create([
                'customer_id' => $customer->id,
                'order_number' => 'EC-' . strtoupper(uniqid()),
                'status' => 'pending',
                'total_price' => $total,
                'discount_amount' => 0,
                'tokens_used' => 0,
                'tokens_earned' => floor($total), 
                'created_by' => $user->id,
            ]);

            // Create order items and reduce stock
            foreach ($cartItems as $cartItem) {
                $price = $cartItem->product->is_on_sale 
                    ? $cartItem->product->sale_price 
                    : $cartItem->product->price;

                OrderItem::create([
                    'order_id' => $order->id,
                    'product_id' => $cartItem->product_id,
                    'quantity' => $cartItem->quantity,
                    'unit_price' => $price,
                ]);

                // Reduce stock
                $cartItem->product->decrement('stock_quantity', $cartItem->quantity);
            }

            // Create order details (shipping address)
            OrderDetail::create([
                'order_id' => $order->id,
                'first_name' => $request->shipping_details['first_name'],
                'last_name' => $request->shipping_details['last_name'],
                'email' => $request->shipping_details['email'],
                'phone' => $request->shipping_details['phone'] ?? null,
                'address1' => $request->shipping_details['address1'],
                'address2' => $request->shipping_details['address2'] ?? null,
                'city' => $request->shipping_details['city'],
                'state' => $request->shipping_details['state'] ?? null,
                'zipcode' => $request->shipping_details['zipcode'],
                'country_code' => $request->shipping_details['country_code'],
            ]);

            // Create payment record (pending)
            Payment::create([
                'order_id' => $order->id,
                'amount' => $total,
                'status' => 'pending',
                'type' => $request->payment['type'],
                'created_by' => $user->id,
            ]);

            // Clear cart (will be done after successful payment)
            // CartItem::where('customer_id', $customer->id)->delete();

            DB::commit();

            // Load relationships for Stripe session
            $order->load(['items.product', 'details']);

            // CHANGE TO REAL DOMAIN LINK - but which one, main website, or /checkout? !!
            $successUrl = env('FRONTEND_URL', 'http://localhost:3000') . '/payment/success?session_id={CHECKOUT_SESSION_ID}';
            $cancelUrl = env('FRONTEND_URL', 'http://localhost:3000') . '/payment/cancel';

            $stripeSession = $this->stripeService->createCheckoutSession(
                $order,
                $successUrl,
                $cancelUrl
            );

            return response()->json([
                'message' => 'Order created successfully',
                'order' => [
                    'id' => $order->id,
                    'order_number' => $order->order_number,
                    'status' => $order->status,
                    'total' => $order->total_price,
                    'tokens_earned' => $order->tokens_earned,
                    'items_count' => $order->items->count(),
                ],
                'stripe' => [
                    'session_id' => $stripeSession->id,
                    'url' => $stripeSession->url,
                ],
            ], 201);

        } catch (\Exception $e) {
            DB::rollBack();

            return response()->json([
                'message' => 'Failed to create order',
                'error' => $e->getMessage(),
            ], 500);
        }
    }

    /**
     * Get customer's orders
     * 
     * GET /api/orders
     */
    public function index()
    {
        $customer = Auth::user()->customer;

        if (!$customer) {
            return response()->json([
                'message' => 'Customer profile not found'
            ], 404);
        }

        $orders = Order::with(['items.product', 'details', 'payment'])
            ->where('customer_id', $customer->id)
            ->orderBy('created_at', 'desc')
            ->paginate(10);

        return response()->json($orders);
    }

    /**
     * Get single order
     * 
     * GET /api/orders/{id}
     */
    public function show($id)
    {
        $customer = Auth::user()->customer;

        $order = Order::with(['items.product', 'details', 'payment'])
            ->where('customer_id', $customer->id)
            ->findOrFail($id);

        return response()->json([
            'order' => $order,
        ]);
    }

    /**
 * Verify Stripe session and get order
 * 
 * GET /api/stripe/verify-session/{sessionId}
 */
public function verifyStripeSession($sessionId)
{
    try {
        \Stripe\Stripe::setApiKey(config('stripe.secret_key'));
        
        $session = \Stripe\Checkout\Session::retrieve($sessionId);
        
        if ($session->payment_status === 'paid') {
            $orderId = $session->client_reference_id ?? $session->metadata->order_id ?? null;
            
            return response()->json([
                'success' => true,
                'order_id' => $orderId,
            ]);
        }
        
        return response()->json([
            'success' => false,
            'message' => 'Payment not completed',
        ]);
        
    } catch (\Exception $e) {
        return response()->json([
            'success' => false,
            'message' => $e->getMessage(),
        ], 500);
    }
}
}
