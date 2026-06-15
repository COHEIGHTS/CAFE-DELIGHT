<?php

namespace App\Services;

use App\Models\AuditLog;
use Illuminate\Support\Facades\Request;

class AuditLogService
{
    public static function log(string $action, ?string $description = null, ?array $oldValues = null, ?array $newValues = null): AuditLog
    {
        return AuditLog::create([
            'user_id' => auth()->id(),
            'action' => $action,
            'description' => $description,
            'ip_address' => Request::ip(),
            'user_agent' => Request::userAgent(),
            'old_values' => $oldValues,
            'new_values' => $newValues,
        ]);
    }

    public static function logLogin(): AuditLog
    {
        return self::log('login', 'User logged in');
    }

    public static function logLogout(): AuditLog
    {
        return self::log('logout', 'User logged out');
    }

    public static function logOrderCreated($order): AuditLog
    {
        return self::log('order_created', "Order #{$order->id} created", null, [
            'total' => $order->total,
            'status' => $order->status,
            'payment_method' => $order->payment_method,
        ]);
    }

    public static function logOrderUpdated($order, $oldStatus): AuditLog
    {
        return self::log('order_updated', "Order #{$order->id} status changed from {$oldStatus} to {$order->status}", [
            'old_status' => $oldStatus,
        ], [
            'new_status' => $order->status,
        ]);
    }

    public static function logPaymentApproved($order): AuditLog
    {
        return self::log('payment_approved', "Payment for Order #{$order->id} approved", null, [
            'order_id' => $order->id,
            'total' => $order->total,
        ]);
    }

    public static function logMenuCreated($dish): AuditLog
    {
        return self::log('menu_created', "Menu item '{$dish->name}' created", null, [
            'name' => $dish->name,
            'price' => $dish->price,
        ]);
    }

    public static function logMenuUpdated($dish, $oldValues): AuditLog
    {
        return self::log('menu_updated', "Menu item '{$dish->name}' updated", $oldValues, [
            'name' => $dish->name,
            'price' => $dish->price,
            'description' => $dish->description,
        ]);
    }

    public static function logMenuDeleted($dish): AuditLog
    {
        return self::log('menu_deleted', "Menu item '{$dish->name}' deleted", [
            'name' => $dish->name,
            'price' => $dish->price,
        ]);
    }
}
