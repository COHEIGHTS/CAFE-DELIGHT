<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Cart extends Model
{
    protected $fillable = ['user_id', 'dish_id', 'quantity'];

    public function dish(): BelongsTo
    {
        return $this->belongsTo(Dish::class);
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function getTotalAttribute()
    {
        return $this->dish->price * $this->quantity;
    }
}