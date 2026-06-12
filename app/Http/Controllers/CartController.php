<?php

namespace App\Http\Controllers;

use App\Models\Cart;
use App\Models\Dish;
use App\Models\Settings;
use Illuminate\Http\Request;

class CartController extends Controller
{
    public function index()
    {
        $cartItems = auth()->user()->cartItems()->with('dish')->get();
        $total = $cartItems->sum(fn($item) => $item->dish->price * $item->quantity);

        // Get settings for delivery fee and tax rate
        $settings = Settings::getSettings();
        $deliveryFee = $settings->delivery_fee;
        $tax = round($total * ($settings->tax_rate / 100), 2);
        $grandTotal = $total + $deliveryFee + $tax;

        return view('cart.index', compact('cartItems', 'total', 'deliveryFee', 'tax', 'grandTotal'));
    }

    public function add(Request $request, $dishId)
    {
        $dish = Dish::findOrFail($dishId);
        $quantity = $request->input('quantity', 1);

        $cartItem = Cart::where('user_id', auth()->id())
            ->where('dish_id', $dishId)
            ->first();

        if ($cartItem) {
            $cartItem->increment('quantity', $quantity);
        } else {
            Cart::create([
                'user_id'  => auth()->id(),
                'dish_id'  => $dishId,
                'quantity' => $quantity,
            ]);
        }

        // Always return JSON — redirect and popup are handled by the blade
        return response()->json([
            'success'   => true,
            'message'   => $dish->name . ' added to cart!',
            'cartCount' => auth()->user()->cartItems()->sum('quantity'),
        ]);
    }

    public function remove($cartItemId)
    {
        $cartItem = Cart::findOrFail($cartItemId);

        if ($cartItem->user_id !== auth()->id()) {
            abort(403);
        }

        $cartItem->delete();

        return response()->json([
            'success'   => true,
            'message'   => 'Item removed from cart',
            'cartCount' => auth()->user()->cartItems()->sum('quantity'),
        ]);
    }

    public function update(Request $request, $cartItemId)
    {
        $cartItem = Cart::findOrFail($cartItemId);

        if ($cartItem->user_id !== auth()->id()) {
            abort(403);
        }

        $quantity = $request->input('quantity', 1);

        if ($quantity <= 0) {
            $cartItem->delete();
        } else {
            $cartItem->update(['quantity' => $quantity]);
        }

        return response()->json([
            'success'   => true,
            'message'   => 'Cart updated',
            'cartCount' => auth()->user()->cartItems()->sum('quantity'),
        ]);
    }

    public function clear()
    {
        auth()->user()->cartItems()->delete();

        return response()->json([
            'success'   => true,
            'message'   => 'Cart cleared',
            'cartCount' => 0,
        ]);
    }

    public function count()
    {
        $count = auth()->user()->cartItems()->sum('quantity');

        return response()->json(['count' => $count]);
    }
}