<?php

namespace App\Services;

use App\Models\Otp;
use App\Models\User;
use Illuminate\Support\Facades\Mail;
use App\Mail\OtpMail;

class OtpService
{
    public function generate(User $user, string $type = 'login'): Otp
    {
        // Delete any existing OTPs of the same type for this user
        Otp::where('user_id', $user->id)
            ->where('type', $type)
            ->delete();

        // Generate 6-digit OTP
        $code = str_pad(random_int(0, 999999), 6, '0', STR_PAD_LEFT);

        // Create OTP record
        $otp = Otp::create([
            'user_id' => $user->id,
            'code' => $code,
            'type' => $type,
            'expires_at' => now()->addMinutes(10), // Expires in 10 minutes
        ]);

        try {
            // Send OTP via email immediately
            Mail::to($user->email)->send(new OtpMail($otp, $type));
        } catch (\Exception $e) {
            // Log error for debugging
            \Log::error('OTP Email sending failed: ' . $e->getMessage());
            throw new \Exception('Failed to send OTP email. Please check your mail configuration.');
        }

        return $otp;
    }

    public function verify(User $user, string $code, string $type = 'login'): bool
    {
        $otp = Otp::where('user_id', $user->id)
            ->where('type', $type)
            ->where('code', $code)
            ->where('expires_at', '>', now())
            ->whereNull('verified_at')
            ->first();

        if (!$otp) {
            return false;
        }

        // Mark as verified
        $otp->update(['verified_at' => now()]);

        return true;
    }

    public function resend(User $user, string $type = 'login'): Otp
    {
        return $this->generate($user, $type);
    }
}
