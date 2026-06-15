<?php

namespace App\Http\Controllers;

use App\Models\Dish;
use App\Models\Favourite;
use Illuminate\Http\Request;

class FavouriteController extends Controller
{
    public function toggle(Request $request, $dishId)
    {
        $dish = Dish::findOrFail($dishId);

        $existing = Favourite::where('user_id', auth()->id())
            ->where('dish_id', $dishId)
            ->first();

        if ($existing) {
            $existing->delete();
            $isFavourited = false;
        } else {
            Favourite::create([
                'user_id' => auth()->id(),
                'dish_id' => $dishId,
            ]);
            $isFavourited = true;
        }

        $count = auth()->user()->favourites()->count();

        if ($request->expectsJson()) {
            return response()->json([
                'success'      => true,
                'favourited'   => $isFavourited,
                'message'      => $isFavourited ? $dish->name . ' added to favourites!' : $dish->name . ' removed from favourites.',
                'favouriteCount' => $count,
            ]);
        }

        return redirect()->back()->with(
            'success',
            $isFavourited ? $dish->name . ' added to favourites!' : $dish->name . ' removed from favourites.'
        );
    }

    public function index()
    {
        $favourites = auth()->user()->favourites()->with('dish')->latest()->get();
        return view('favourites.index', compact('favourites'));
    }
}