<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Services\OtpService;
use Illuminate\Auth\Events\Registered;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Session;
use Illuminate\Validation\Rules;
use Illuminate\Validation\ValidationException;
use Illuminate\View\View;

class RegisteredUserController extends Controller
{
    /**
     * Display the registration view.
     */
    public function create(): View
    {
        return view('auth.register');
    }

    /**
     * Handle an incoming registration request.
     *
     * @throws ValidationException
     */
    public function store(Request $request): RedirectResponse
    {
        $request->validate([
            'name'     => ['required', 'string', 'max:255'],
            'email'    => ['required', 'string', 'lowercase', 'email', 'max:255', 'unique:'.User::class],
            'password' => ['required', 'confirmed', Rules\Password::defaults()],
        ]);

        $user = User::create([
            'name'     => $request->name,
            'email'    => $request->email,
            'password' => Hash::make($request->password),
            // role defaults to 'customer' via the DB column default — NOT hardcoded here
            // so if you manually set role='admin' in DB before registering, it stays admin
        ]);

        event(new Registered($user));

        // Generate and send OTP for registration verification
        $otpService = app(OtpService::class);
        $otpService->generate($user, 'registration');

        // Store user ID in session for OTP verification
        Session::put('otp_user_id', $user->id);
        Session::put('otp_type', 'registration');

        // Redirect to OTP verification page instead of logging in directly
        return redirect()->route('otp.verify')->with('success', 'Registration successful! Please check your email for the OTP code.');
    }
}