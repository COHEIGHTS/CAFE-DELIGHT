<?php

namespace App\Http\Controllers;

use App\Models\Dish;
use App\Models\Favorite;
use Illuminate\Http\Request;

class FavoriteController extends Controller
{
    /**
     * Show the favorites page.
     */
    public function index()
    {
        $favorites = auth()->user()
            ->favorites()
            ->with('dish')
            ->latest()
            ->get();

        $favoriteCount = $favorites->count();

        return view('favorites.index', compact('favorites', 'favoriteCount'));
    }

    /**
     * Toggle a dish as favorite / unfavorite.
     * Returns JSON for AJAX calls.
     */
    public function toggle(Request $request, $dishId)
    {
        $dish = Dish::findOrFail($dishId);
        $user = auth()->user();

        $existing = Favorite::where('user_id', $user->id)
            ->where('dish_id', $dishId)
            ->first();

        if ($existing) {
            $existing->delete();
            $isFavorited   = false;
            $message       = $dish->name . ' removed from favorites.';
        } else {
            Favorite::create([
                'user_id' => $user->id,
                'dish_id' => $dishId,
            ]);
            $isFavorited   = true;
            $message       = $dish->name . ' added to favorites!';
        }

        $favoriteCount = $user->favorites()->count();

        return response()->json([
            'success'       => true,
            'favorited'     => $isFavorited,
            'message'       => $message,
            'favoriteCount' => $favoriteCount,
        ]);
    }

    /**
     * Return the current user's favorite count (for badge).
     */
    public function count()
    {
        $count = auth()->user()->favorites()->count();

        return response()->json(['count' => $count]);
    }
}