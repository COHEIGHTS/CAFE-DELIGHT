<?php

namespace App\Http\Controllers;

use App\Models\Dish;
use App\Models\Favorite;
use App\Services\CacheService;
use Illuminate\Http\Request;

class MenuController extends Controller
{
    public function index()
    {
        $dishes         = CacheService::getAllDishes();
        $categories     = ['mains', 'appetizers', 'desserts', 'beverages', 'sides'];
        $activeCategory = null;

        $userFavorites = Favorite::where('user_id', auth()->id())
            ->pluck('dish_id');

        return view('menu.index', compact('dishes', 'categories', 'activeCategory', 'userFavorites'));
    }

    public function show($id)
    {
        $dish = CacheService::getDishById($id) ?? Dish::findOrFail($id);

        $isFavorited = Favorite::where('user_id', auth()->id())
            ->where('dish_id', $id)
            ->exists();

        return view('menu.show', compact('dish', 'isFavorited'));
    }

    public function filterByCategory($category)
    {
        $dishes         = CacheService::getDishesByCategory($category);
        $categories     = ['mains', 'appetizers', 'desserts', 'beverages', 'sides'];
        $activeCategory = $category;

        $userFavorites = Favorite::where('user_id', auth()->id())
            ->pluck('dish_id');

        return view('menu.index', compact('dishes', 'categories', 'activeCategory', 'userFavorites'));
    }
}