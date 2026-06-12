<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Dish;
use App\Services\AuditLogService;
use App\Services\CacheService;
use Illuminate\Http\Request;

class MenuController extends Controller
{
    public function index()
    {
        $dishes = Dish::all();
        return view('admin.menu.index', compact('dishes'));
    }

    public function create()
    {
        return view('admin.menu.create');
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'description' => 'required|string',
            'category' => 'required|string',
            'price' => 'required|numeric|min:0',
            'primary_image' => 'required|image|mimes:jpeg,png,jpg,gif|max:2048',
            'secondary_image' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
            'tertiary_image' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
            'is_vegetarian' => 'nullable|boolean',
            'is_vegan' => 'nullable|boolean',
            'is_spicy' => 'nullable|boolean',
            'is_gluten_free' => 'nullable|boolean',
            'is_dairy_free' => 'nullable|boolean',
            'is_bestseller' => 'nullable|boolean',
            'ingredients' => 'nullable|string',
            'prep_time' => 'nullable|integer|min:1',
            'serving_size' => 'nullable|string',
        ]);

        // Handle image uploads
        if ($request->hasFile('primary_image')) {
            $validated['primary_image'] = $request->file('primary_image')->store('dishes', 'public');
        }
        if ($request->hasFile('secondary_image')) {
            $validated['secondary_image'] = $request->file('secondary_image')->store('dishes', 'public');
        }
        if ($request->hasFile('tertiary_image')) {
            $validated['tertiary_image'] = $request->file('tertiary_image')->store('dishes', 'public');
        }

        // Convert checkboxes to boolean
        $validated['is_vegetarian'] = $request->has('is_vegetarian');
        $validated['is_vegan'] = $request->has('is_vegan');
        $validated['is_spicy'] = $request->has('is_spicy');
        $validated['is_gluten_free'] = $request->has('is_gluten_free');
        $validated['is_dairy_free'] = $request->has('is_dairy_free');
        $validated['is_bestseller'] = $request->has('is_bestseller');

        // Save to database
        $dish = Dish::create($validated);

        // Clear cache
        CacheService::clearDishCache();

        // Log menu creation
        AuditLogService::logMenuCreated($dish);

        return redirect()->route('admin.menu.index')->with('success', '✅ Dish added successfully!');
    }

    public function edit($id)
    {
        $dish = Dish::findOrFail($id);
        return view('admin.menu.edit', compact('dish'));
    }

    public function update(Request $request, $id)
    {
        $dish = Dish::findOrFail($id);

        $oldValues = $dish->only(['name', 'price', 'description']);

        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'description' => 'required|string',
            'category' => 'required|string',
            'price' => 'required|numeric|min:0',
            'primary_image' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
            'secondary_image' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
            'tertiary_image' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
            'is_vegetarian' => 'nullable|boolean',
            'is_vegan' => 'nullable|boolean',
            'is_spicy' => 'nullable|boolean',
            'is_gluten_free' => 'nullable|boolean',
            'is_dairy_free' => 'nullable|boolean',
            'is_bestseller' => 'nullable|boolean',
            'ingredients' => 'nullable|string',
            'prep_time' => 'nullable|integer|min:1',
            'serving_size' => 'nullable|string',
        ]);

        // Handle image uploads
        if ($request->hasFile('primary_image')) {
            $validated['primary_image'] = $request->file('primary_image')->store('dishes', 'public');
        }
        if ($request->hasFile('secondary_image')) {
            $validated['secondary_image'] = $request->file('secondary_image')->store('dishes', 'public');
        }
        if ($request->hasFile('tertiary_image')) {
            $validated['tertiary_image'] = $request->file('tertiary_image')->store('dishes', 'public');
        }

        // Convert checkboxes to boolean
        $validated['is_vegetarian'] = $request->has('is_vegetarian');
        $validated['is_vegan'] = $request->has('is_vegan');
        $validated['is_spicy'] = $request->has('is_spicy');
        $validated['is_gluten_free'] = $request->has('is_gluten_free');
        $validated['is_dairy_free'] = $request->has('is_dairy_free');
        $validated['is_bestseller'] = $request->has('is_bestseller');

        $dish->update($validated);

        // Clear cache
        CacheService::clearDishCacheById($dish->id);
        CacheService::clearDishCache();

        // Log menu update
        AuditLogService::logMenuUpdated($dish, $oldValues);

        return redirect()->route('admin.menu.index')->with('success', '✅ Dish updated successfully!');
    }

    public function destroy($id)
    {
        $dish = Dish::findOrFail($id);

        // Clear cache
        CacheService::clearDishCacheById($dish->id);
        CacheService::clearDishCache();

        // Log menu deletion
        AuditLogService::logMenuDeleted($dish);

        $dish->delete();

        return redirect()->route('admin.menu.index')->with('success', '✅ Dish deleted successfully!');
    }
}