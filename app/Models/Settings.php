<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Settings extends Model
{
    protected $fillable = [
        'site_name',
        'site_email',
        'site_phone',
        'delivery_fee',
        'tax_rate',
        'currency',
        'address',
        'social_facebook',
        'social_twitter',
        'social_instagram',
    ];

    protected $casts = [
        'delivery_fee' => 'decimal:2',
        'tax_rate' => 'decimal:2',
    ];

    /**
     * Get the first (and only) settings record
     */
    public static function getSettings()
    {
        return self::firstOrCreate([], [
            'site_name' => 'Cafe Delight',
            'site_email' => 'info@cafedelight.com',
            'site_phone' => '+254700000000',
            'delivery_fee' => 200.00,
            'tax_rate' => 16.00,
            'currency' => 'KES',
        ]);
    }
}
