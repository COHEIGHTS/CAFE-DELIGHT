<?php

namespace Tests\Feature\Auth;

use App\Models\Otp;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Mail;
use Tests\TestCase;

class RegistrationTest extends TestCase
{
    use RefreshDatabase;

    public function test_registration_screen_can_be_rendered(): void
    {
        $response = $this->get('/register');

        $response->assertStatus(200);
    }

    public function test_new_users_can_register(): void
    {
        Mail::fake();

        $response = $this->post('/register', [
            'name' => 'Test User',
            'email' => 'test@example.com',
            'password' => 'password',
            'password_confirmation' => 'password',
        ]);

        // After registration, user is redirected to OTP verification
        $response->assertRedirect(route('otp.verify'));

        // Get the OTP from the database
        $user = User::where('email', 'test@example.com')->first();
        $this->assertNotNull($user, 'User should be created');

        $otp = Otp::where('user_id', $user->id)
            ->where('type', 'registration')
            ->whereNull('verified_at')
            ->first();

        $this->assertNotNull($otp, 'OTP should be generated for the user');

        // Verify the OTP
        $otpResponse = $this->post('/otp/verify', [
            'otp' => $otp->code,
        ]);

        // After OTP verification, user should be authenticated
        $this->assertAuthenticated();
        $otpResponse->assertRedirect(route('dashboard', absolute: false));
    }
}
