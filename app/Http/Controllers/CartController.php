<?php

namespace App\Http\Controllers;

use App\Models\CartItem;
use App\Models\Product;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class CartController extends Controller
{
    /**
     * Get current user's cart with items
     * 
     * GET /api/cart
     */
    public function index()
    {
        $customer = Auth::user()->customer;

        if (!$customer) {
            return response()->json([
                'message' => 'Customer profile not found'
            ], 404);
        }

        $cartItems = CartItem::with(['product.category'])
            ->where('customer_id', $customer->id)
            ->get();

        // Calculate totals
        $subtotal = 0;
        $items = $cartItems->map(function ($item) use (&$subtotal) {
            $price = $item->product->is_on_sale ? $item->product->sale_price : $item->product->price;
            $itemSubtotal = $price * $item->quantity;
            $subtotal += $itemSubtotal;

            return [
                'id' => $item->id,
                'product' => [
                    'id' => $item->product->id,
                    'title' => $item->product->title,
                    'slug' => $item->product->slug,
                    'image_url' => $item->product->image_url,
                    'price' => $item->product->price,
                    'sale_price' => $item->product->sale_price,
                    'is_on_sale' => $item->product->is_on_sale,
                    'stock_quantity' => $item->product->stock_quantity,
                    'category' => $item->product->category ? [
                        'id' => $item->product->category->id,
                        'name' => $item->product->category->name,
                    ] : null,
                ],
                'quantity' => $item->quantity,
                'price' => $price,
                'subtotal' => $itemSubtotal,
            ];
        });

        $tax = $subtotal * 0.10; // 10% tax (adjust as needed)
        $total = $subtotal + $tax;

        return response()->json([
            'items' => $items,
            'summary' => [
                'subtotal' => round($subtotal, 2),
                'tax' => round($tax, 2),
                'total' => round($total, 2),
                'item_count' => $cartItems->sum('quantity'),
            ],
        ]);
    }

    /**
     * Add product to cart
     * 
     * POST /api/cart/add
     * Body: { product_id, quantity }
     */
    public function add(Request $request)
    {
        $request->validate([
            'product_id' => 'required|exists:products,id',
            'quantity' => 'integer|min:1',
        ]);

        $customer = Auth::user()->customer;

        if (!$customer) {
            return response()->json([
                'message' => 'Customer profile not found'
            ], 404);
        }

        $product = Product::findOrFail($request->product_id);

        // Check stock
        if ($product->stock_quantity < $request->quantity) {
            return response()->json([
                'message' => 'Insufficient stock available',
                'available' => $product->stock_quantity,
            ], 400);
        }

        // Check if item already exists in cart
        $cartItem = CartItem::where('customer_id', $customer->id)
            ->where('product_id', $product->id)
            ->first();

        if ($cartItem) {
            // Update quantity
            $newQuantity = $cartItem->quantity + ($request->quantity ?? 1);

            // Check stock for new quantity
            if ($product->stock_quantity < $newQuantity) {
                return response()->json([
                    'message' => 'Cannot add more items. Only ' . $product->stock_quantity . ' available',
                    'available' => $product->stock_quantity,
                    'current_in_cart' => $cartItem->quantity,
                ], 400);
            }

            $cartItem->quantity = $newQuantity;
            $cartItem->save();

            return response()->json([
                'message' => 'Cart updated successfully',
                'cart_item' => $cartItem,
            ]);
        }

        // Create new cart item
        $cartItem = CartItem::create([
            'customer_id' => $customer->id,
            'product_id' => $product->id,
            'quantity' => $request->quantity ?? 1,
        ]);

        return response()->json([
            'message' => 'Product added to cart',
            'cart_item' => $cartItem,
        ], 201);
    }

    /**
     * Update cart item quantity
     * 
     * PUT /api/cart/items/{id}
     * Body: { quantity }
     */
    public function update(Request $request, $id)
    {
        $request->validate([
            'quantity' => 'required|integer|min:1',
        ]);

        $customer = Auth::user()->customer;

        $cartItem = CartItem::where('customer_id', $customer->id)
            ->findOrFail($id);

        $product = $cartItem->product;

        // Check stock
        if ($product->stock_quantity < $request->quantity) {
            return response()->json([
                'message' => 'Insufficient stock available',
                'available' => $product->stock_quantity,
            ], 400);
        }

        $cartItem->quantity = $request->quantity;
        $cartItem->save();

        return response()->json([
            'message' => 'Cart item updated',
            'cart_item' => $cartItem,
        ]);
    }

    /**
     * Remove item from cart
     * 
     * DELETE /api/cart/items/{id}
     */
    public function remove($id)
    {
        $customer = Auth::user()->customer;

        $cartItem = CartItem::where('customer_id', $customer->id)
            ->findOrFail($id);

        $cartItem->delete();

        return response()->json([
            'message' => 'Item removed from cart',
        ]);
    }

    /**
     * Clear entire cart
     * 
     * DELETE /api/cart/clear
     */
    public function clear()
    {
        $customer = Auth::user()->customer;

        CartItem::where('customer_id', $customer->id)->delete();

        return response()->json([
            'message' => 'Cart cleared successfully',
        ]);
    }

    /**
     * Get cart item count
     * 
     * GET /api/cart/count
     */
    public function count()
    {
        $customer = Auth::user()->customer;

        if (!$customer) {
            return response()->json(['count' => 0]);
        }

        $count = CartItem::where('customer_id', $customer->id)
            ->sum('quantity');

        return response()->json(['count' => $count]);
    }

    /**
     * Sync guest cart to authenticated user cart
     * 
     * POST /api/cart/sync
     * Body: { items: [{ product_id, quantity }] }
     */
    public function sync(Request $request)
    {
        $request->validate([
            'items' => 'required|array',
            'items.*.product_id' => 'required|exists:products,id',
            'items.*.quantity' => 'required|integer|min:1',
        ]);

        $customer = Auth::user()->customer;

        if (!$customer) {
            return response()->json([
                'message' => 'Customer profile not found'
            ], 404);
        }

        DB::beginTransaction();

        try {
            foreach ($request->items as $item) {
                $product = Product::find($item['product_id']);

                if (!$product || $product->stock_quantity < $item['quantity']) {
                    continue; // Skip items that are out of stock
                }

                // Check if item already exists
                $cartItem = CartItem::where('customer_id', $customer->id)
                    ->where('product_id', $product->id)
                    ->first();

                if ($cartItem) {
                    // Update quantity (use the higher value)
                    $cartItem->quantity = max($cartItem->quantity, $item['quantity']);
                    $cartItem->save();
                } else {
                    // Create new cart item
                    CartItem::create([
                        'customer_id' => $customer->id,
                        'product_id' => $product->id,
                        'quantity' => $item['quantity'],
                    ]);
                }
            }

            DB::commit();

            return response()->json([
                'message' => 'Cart synced successfully',
            ]);

        } catch (\Exception $e) {
            DB::rollBack();

            return response()->json([
                'message' => 'Failed to sync cart',
                'error' => $e->getMessage(),
            ], 500);
        }
    }
}