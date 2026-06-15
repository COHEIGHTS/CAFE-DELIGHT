<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Order;
use App\Models\User;
use App\Models\Dish;
use App\Models\Settings;
use Illuminate\Http\Request;

class SettingsController extends Controller
{
    public function index()
    {
        // Get settings
        $settings = Settings::getSettings();

        // Get real-time statistics
        $stats = [
            'total_orders' => Order::count(),
            'total_revenue' => Order::where('payment_status', 'paid')->sum('total'),
            'total_customers' => User::where('role', 'customer')->count(),
            'total_dishes' => Dish::count(),
            'pending_orders' => Order::where('status', 'pending')->count(),
            'awaiting_payments' => Order::where('payment_status', 'awaiting_approval')->count(),
            'today_orders' => Order::whereDate('created_at', today())->count(),
            'today_revenue' => Order::whereDate('created_at', today())->where('payment_status', 'paid')->sum('total'),
        ];

        return view('admin.settings', compact('stats', 'settings'));
    }

    public function update(Request $request)
    {
        $validated = $request->validate([
            'site_name' => 'required|string|max:255',
            'site_email' => 'required|email',
            'site_phone' => 'required|string|max:20',
            'delivery_fee' => 'required|numeric|min:0',
            'tax_rate' => 'required|numeric|min:0|max:100',
            'currency' => 'nullable|string|max:10',
            'address' => 'nullable|string|max:500',
            'social_facebook' => 'nullable|url|max:255',
            'social_twitter' => 'nullable|url|max:255',
            'social_instagram' => 'nullable|url|max:255',
        ]);

        // Get or create settings record
        $settings = Settings::getSettings();
        $settings->update($validated);

        return back()->with('success', 'Settings updated successfully');
    }
}
