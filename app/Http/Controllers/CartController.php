<?php

namespace App\Http\Controllers;

use App\Models\Cart;
use App\Models\Dish;
use Illuminate\Http\Request;

class CartController extends Controller
{
    public function index()
    {
        $cartItems = auth()->user()->cartItems()->with('dish')->get();
        $total = $cartItems->sum(fn($item) => $item->dish->price * $item->quantity);
        
        return view('cart.index', compact('cartItems', 'total'));
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
                'user_id' => auth()->id(),
                'dish_id' => $dishId,
                'quantity' => $quantity,
            ]);
        }

        if ($request->expectsJson()) {
            return response()->json([
                'success' => true,
                'message' => $dish->name . ' added to cart!',
                'cartCount' => auth()->user()->cartItems()->count(),
            ]);
        }

        return redirect()->back()->with('success', $dish->name . ' added to cart!');
    }

    public function remove($cartItemId)
    {
        $cartItem = Cart::findOrFail($cartItemId);
        
        if ($cartItem->user_id !== auth()->id()) {
            abort(403);
        }

        $cartItem->delete();

        return response()->json([
            'success' => true,
            'message' => 'Item removed from cart',
            'cartCount' => auth()->user()->cartItems()->count(),
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
            'success' => true,
            'message' => 'Cart updated',
        ]);
    }

    public function clear()
    {
        auth()->user()->cartItems()->delete();

        return response()->json([
            'success' => true,
            'message' => 'Cart cleared',
        ]);
    }

    public function count()
    {
        $count = auth()->user()->cartItems()->count();
        
        return response()->json(['count' => $count]);
    }
}