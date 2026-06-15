<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Services\OtpService;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Session;
use Illuminate\View\View;

class OtpController extends Controller
{
    public function show(): View|RedirectResponse
    {
        if (!Session::has('otp_user_id')) {
            return redirect()->route('login')->with('error', 'Session expired. Please login again.');
        }

        return view('auth.otp-verify');
    }

    public function verify(Request $request)
    {
        $request->validate([
            'otp' => ['required', 'string', 'size:6'],
        ]);

        $userId = Session::get('otp_user_id');
        $otpType = Session::get('otp_type');

        if (!$userId || !$otpType) {
            return redirect()->route('login')->with('error', 'Session expired. Please login again.');
        }

        $user = User::find($userId);
        if (!$user) {
            return redirect()->route('login')->with('error', 'User not found. Please login again.');
        }

        $otpService = app(OtpService::class);
        $isValid = $otpService->verify($user, $request->otp, $otpType);

        if (!$isValid) {
            return back()->with('error', 'Invalid or expired OTP. Please try again.');
        }

        // Clear OTP session
        Session::forget(['otp_user_id', 'otp_type']);

        // Log the user in
        Auth::login($user);

        // Log login activity
        \App\Services\AuditLogService::logLogin();

        // Redirect based on user role
        return redirect()->intended(
            $user->role === 'admin'
                ? route('admin.dashboard', absolute: false)
                : route('dashboard', absolute: false)
        );
    }

    public function resend(Request $request)
    {
        $userId = Session::get('otp_user_id');
        $otpType = Session::get('otp_type');

        if (!$userId || !$otpType) {
            return redirect()->route('login')->with('error', 'Session expired. Please login again.');
        }

        $user = User::find($userId);
        if (!$user) {
            return redirect()->route('login')->with('error', 'User not found. Please login again.');
        }

        $otpService = app(OtpService::class);
        $otpService->resend($user, $otpType);

        return back()->with('success', 'New OTP sent to your email address.');
    }
}
