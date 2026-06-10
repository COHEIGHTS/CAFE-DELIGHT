<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Order extends Model
{
    protected $fillable = [
        'user_id',
        'subtotal',
        'delivery_fee',
        'tax',
        'total',
        'status',
        'delivery_address',
        'phone',
        'special_instructions',
        'estimated_delivery_time',
    ];

    protected $casts = [
        'estimated_delivery_time' => 'datetime',
        'subtotal'                => 'decimal:2',
        'delivery_fee'            => 'decimal:2',
        'tax'                     => 'decimal:2',
        'total'                   => 'decimal:2',
    ];

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function items(): HasMany
    {
        return $this->hasMany(OrderItem::class);
    }

    // Human-readable status label + color
    public function statusLabel(): string
    {
        return match($this->status) {
            'pending'    => 'Pending',
            'confirmed'  => 'Confirmed',
            'preparing'  => 'Preparing',
            'ready'      => 'Ready',
            'on_the_way' => 'On the way',
            'delivered'  => 'Delivered',
            'cancelled'  => 'Cancelled',
            default      => ucfirst($this->status),
        };
    }

    public function getStatusBadgeAttribute(): string
    {
        return '<span class="inline-flex items-center gap-1.5 rounded-full px-2.5 py-1 text-xs font-bold ' . $this->statusClass() . '">
            <span class="h-1.5 w-1.5 rounded-full bg-current"></span>
            ' . $this->statusLabel() . '
        </span>';
    }

    public function statusClass(): string
    {
        return match($this->status) {
            'pending'    => 'bg-amber-500/15 text-amber-600',
            'confirmed'  => 'bg-blue-500/15 text-blue-600',
            'preparing'  => 'bg-purple-500/15 text-purple-600',
            'ready'      => 'bg-cyan-500/15 text-cyan-600',
            'on_the_way' => 'bg-orange-500/15 text-orange-600',
            'delivered'  => 'bg-emerald-500/15 text-emerald-600',
            'cancelled'  => 'bg-red-500/15 text-red-600',
            default      => 'bg-ink/10 text-ink',
        };
    }
}