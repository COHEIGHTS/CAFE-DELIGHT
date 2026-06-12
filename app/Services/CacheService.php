<?php

namespace App\Services;

use App\Models\Dish;
use Illuminate\Support\Facades\Cache;

class CacheService
{
    /**
     * Cache duration in seconds (1 hour)
     */
    private const CACHE_DURATION = 3600;

    /**
     * Get all dishes with caching
     */
    public static function getAllDishes()
    {
        return Cache::remember('dishes.all', self::CACHE_DURATION, function () {
            return Dish::all();
        });
    }

    /**
     * Get dishes by category with caching
     */
    public static function getDishesByCategory(string $category)
    {
        return Cache::remember("dishes.category.{$category}", self::CACHE_DURATION, function () use ($category) {
            return Dish::where('category', $category)->get();
        });
    }

    /**
     * Get bestseller dishes with caching
     */
    public static function getBestsellerDishes()
    {
        return Cache::remember('dishes.bestsellers', self::CACHE_DURATION, function () {
            return Dish::where('is_bestseller', true)->get();
        });
    }

    /**
     * Get dish by ID with caching
     */
    public static function getDishById(int $id)
    {
        return Cache::remember("dishes.{$id}", self::CACHE_DURATION, function () use ($id) {
            return Dish::find($id);
        });
    }

    /**
     * Clear all dish-related cache
     */
    public static function clearDishCache(): void
    {
        Cache::forget('dishes.all');
        Cache::forget('dishes.bestsellers');
        
        // Clear category caches
        $categories = ['mains', 'appetizers', 'desserts', 'beverages', 'sides'];
        foreach ($categories as $category) {
            Cache::forget("dishes.category.{$category}");
        }
    }

    /**
     * Clear specific dish cache
     */
    public static function clearDishCacheById(int $id): void
    {
        Cache::forget("dishes.{$id}");
    }

    /**
     * Clear all cache
     */
    public static function clearAllCache(): void
    {
        Cache::flush();
    }
}
