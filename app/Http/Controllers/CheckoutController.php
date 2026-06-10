<?php

namespace App\Http\Controllers;

use App\Models\Cart;
use App\Models\Order;
use App\Models\OrderItem;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\View\View;

class CheckoutController extends Controller
{
    /**
     * Show the checkout page with delivery address form.
     */
    public function index(): View|RedirectResponse
    {
        $cartItems = Cart::with('dish')
            ->where('user_id', Auth::id())
            ->get();

        if ($cartItems->isEmpty()) {
            return redirect()->route('cart.index')
                ->with('error', 'Your cart is empty.');
        }

        $subtotal    = $cartItems->sum(fn($i) => $i->dish->price * $i->quantity);
        $deliveryFee = 200;
        $tax         = round($subtotal * 0.16, 2);
        $total       = $subtotal + $deliveryFee + $tax;

        return view('checkout', compact('cartItems', 'subtotal', 'deliveryFee', 'tax', 'total'));
    }

    /**
     * Place the order — save order + order_items, clear cart.
     */
    public function store(Request $request): RedirectResponse
    {
        $request->validate([
            'delivery_address'    => ['required', 'string', 'max:500'],
            'phone'               => ['required', 'string', 'max:20'],
            'special_instructions'=> ['nullable', 'string', 'max:1000'],
        ]);

        $cartItems = Cart::with('dish')
            ->where('user_id', Auth::id())
            ->get();

        if ($cartItems->isEmpty()) {
            return redirect()->route('cart.index')
                ->with('error', 'Your cart is empty.');
        }

        $subtotal    = $cartItems->sum(fn($i) => $i->dish->price * $i->quantity);
        $deliveryFee = 200;
        $tax         = round($subtotal * 0.16, 2);
        $total       = $subtotal + $deliveryFee + $tax;

        DB::transaction(function () use ($request, $cartItems, $subtotal, $deliveryFee, $tax, $total) {

            // 1. Create order
            $order = Order::create([
                'user_id'              => Auth::id(),
                'subtotal'             => $subtotal,
                'delivery_fee'         => $deliveryFee,
                'tax'                  => $tax,
                'total'                => $total,
                'status'               => 'pending',
                'delivery_address'     => $request->delivery_address,
                'phone'                => $request->phone,
                'special_instructions' => $request->special_instructions,
                'estimated_delivery_time' => now()->addMinutes(45),
            ]);

            // 2. Create order items
            foreach ($cartItems as $item) {
                OrderItem::create([
                    'order_id' => $order->id,
                    'dish_id'  => $item->dish_id,
                    'quantity' => $item->quantity,
                    'price'    => $item->dish->price,
                ]);
            }

            // 3. Clear cart
            Cart::where('user_id', Auth::id())->delete();
        });

        return redirect()->route('orders.success')
            ->with('success', 'Order placed successfully! We\'ll have it ready in ~45 minutes.');
    }
}