<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Dish extends Model
{
    protected $fillable = [
        'name', 'description', 'category', 'price',
        'primary_image', 'secondary_image', 'tertiary_image',
        'is_vegetarian', 'is_vegan', 'is_spicy',
        'is_gluten_free', 'is_dairy_free', 'is_bestseller',
        'ingredients', 'prep_time', 'serving_size',
    ];

    protected $casts = [
        'is_vegetarian'  => 'boolean',
        'is_vegan'       => 'boolean',
        'is_spicy'       => 'boolean',
        'is_gluten_free' => 'boolean',
        'is_dairy_free'  => 'boolean',
        'is_bestseller'  => 'boolean',
    ];

    // ── Relationships ─────────────────────────────────────────────────

    public function orderItems(): HasMany
    {
        return $this->hasMany(OrderItem::class);
    }
}