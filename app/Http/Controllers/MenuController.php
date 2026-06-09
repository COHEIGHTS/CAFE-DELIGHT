<?php

namespace App\Http\Controllers;

use App\Models\Dish;
use Illuminate\Http\Request;

class MenuController extends Controller
{
    public function index()
    {
        $dishes = Dish::all();
        $categories = ['mains', 'appetizers', 'desserts', 'beverages', 'sides'];
        $activeCategory = null;
        
        return view('menu.index', compact('dishes', 'categories', 'activeCategory'));
    }

    public function show($id)
    {
        $dish = Dish::findOrFail($id);
        return view('menu.show', compact('dish'));
    }

    public function filterByCategory($category)
    {
        $dishes = Dish::where('category', $category)->get();
        $categories = ['mains', 'appetizers', 'desserts', 'beverages', 'sides'];
        $activeCategory = $category;
        
        return view('menu.index', compact('dishes', 'categories', 'activeCategory'));
    }
}