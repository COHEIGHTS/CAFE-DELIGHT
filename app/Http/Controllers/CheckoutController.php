<?php

namespace App\Http\Controllers;

use App\Models\Address;
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
     * Show the checkout page.
     * Now also passes the user's saved addresses and pre-selects the default.
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

        // Load saved addresses, default first
        $addresses = Address::where('user_id', Auth::id())
            ->orderByDesc('is_default')
            ->orderByDesc('created_at')
            ->get();

        // Pre-select the default address (or the most recent one)
        $selectedAddressId = $addresses->firstWhere('is_default', true)?->id
            ?? $addresses->first()?->id;

        return view('checkout', compact(
            'cartItems',
            'subtotal',
            'deliveryFee',
            'tax',
            'total',
            'addresses',
            'selectedAddressId',
        ));
    }

    /**
     * Place the order.
     * Supports two address modes:
     *   - 'saved'  => user picked one of their saved addresses
     *   - 'manual' => user typed a one-time address at checkout
     */
    public function store(Request $request): RedirectResponse
    {
        // Validate based on which mode was submitted
        $request->validate([
            'address_mode' => ['required', 'in:saved,manual'],

            // Required only when using a saved address
            'address_id'   => ['required_if:address_mode,saved', 'nullable', 'exists:addresses,id'],

            // Required only when typing manually
            'delivery_address'     => ['required_if:address_mode,manual', 'nullable', 'string', 'max:500'],
            'phone'                => ['required_if:address_mode,manual', 'nullable', 'string', 'max:20'],

            'special_instructions' => ['nullable', 'string', 'max:1000'],
        ]);

        // Load cart
        $cartItems = Cart::with('dish')
            ->where('user_id', Auth::id())
            ->get();

        if ($cartItems->isEmpty()) {
            return redirect()->route('cart.index')
                ->with('error', 'Your cart is empty.');
        }

        // Resolve delivery address & phone
        if ($request->address_mode === 'saved') {
            // Ensure the address actually belongs to this user
            $address = Address::where('id', $request->address_id)
                ->where('user_id', Auth::id())
                ->firstOrFail();

            $deliveryAddress = $address->toOneLine();
            $phone           = $address->phone;
        } else {
            // One-time manual address typed at checkout
            $deliveryAddress = $request->delivery_address;
            $phone           = $request->phone;
        }

        // Totals
        $subtotal    = $cartItems->sum(fn($i) => $i->dish->price * $i->quantity);
        $deliveryFee = 200;
        $tax         = round($subtotal * 0.16, 2);
        $total       = $subtotal + $deliveryFee + $tax;

        // Persist inside a transaction
        DB::transaction(function () use (
            $request, $cartItems,
            $subtotal, $deliveryFee, $tax, $total,
            $deliveryAddress, $phone
        ) {
            // 1. Create order
            $order = Order::create([
                'user_id'                 => Auth::id(),
                'subtotal'                => $subtotal,
                'delivery_fee'            => $deliveryFee,
                'tax'                     => $tax,
                'total'                   => $total,
                'status'                  => 'pending',
                'delivery_address'        => $deliveryAddress,
                'phone'                   => $phone,
                'special_instructions'    => $request->special_instructions,
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