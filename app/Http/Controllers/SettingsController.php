<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Http\RedirectResponse;
use Illuminate\View\View;

class SettingsController extends Controller
{
    /**
     * Display the user's settings page.
     */
    public function index(Request $request): View
    {
        $user = $request->user();
        $user->settings = $user->settings ?? [];
        
        return view('settings.index', [
            'user' => $user,
        ]);
    }

    /**
     * Update the user's settings.
     */
    public function update(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'email_notifications' => 'nullable|boolean',
            'order_updates' => 'nullable|boolean',
            'promotional_emails' => 'nullable|boolean',
        ]);

        $user = $request->user();
        $currentSettings = $user->settings ?? [];

        // Merge new settings with existing ones
        $user->settings = array_merge($currentSettings, array_map(fn($val) => (bool) $val, $validated));
        $user->save();

        return back()->with('status', 'settings-updated');
    }
}
