<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>{{ $type === 'registration' ? 'Verify Your Email' : 'Login Verification' }}</title>
</head>
<body style="font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif; line-height: 1.6; color: #333; margin: 0; padding: 0; background-color: #f5f5f5;">
    <div style="max-width: 600px; margin: 40px auto; background: white; border-radius: 20px; overflow: hidden; box-shadow: 0 10px 40px rgba(0,0,0,0.1);">
        <!-- Header -->
        <div style="background: linear-gradient(135deg, #f97316 0%, #ea580c 50%, #c2410c 100%); padding: 40px; text-align: center; position: relative;">
            <div style="font-size: 48px; margin-bottom: 10px;">☕</div>
            <h1 style="color: white; margin: 0; font-size: 36px; font-weight: 700; letter-spacing: 1px;">CAFE DELIGHT</h1>
            <p style="color: rgba(255,255,255,0.9); margin: 10px 0 0 0; font-size: 16px; letter-spacing: 2px;">TASTE THE EXTRAORDINARY</p>
        </div>

        <!-- Content -->
        <div style="padding: 40px;">
            @if($type === 'registration')
            <div style="text-align: center; margin-bottom: 30px;">
                <div style="font-size: 64px; margin-bottom: 10px;">🎉</div>
                <h2 style="color: #f97316; margin: 0 0 10px 0; font-size: 28px; font-weight: 600;">Welcome to Cafe Delight!</h2>
                <p style="color: #666; margin: 0; font-size: 16px;">Thank you for joining our food family. Let's get you started!</p>
            </div>
            @else
            <div style="text-align: center; margin-bottom: 30px;">
                <div style="font-size: 64px; margin-bottom: 10px;">🔐</div>
                <h2 style="color: #f97316; margin: 0 0 10px 0; font-size: 28px; font-weight: 600;">Login Verification</h2>
                <p style="color: #666; margin: 0; font-size: 16px;">We need to verify it's really you before you proceed.</p>
            </div>
            @endif

            <p style="color: #444; font-size: 16px; text-align: center; margin-bottom: 30px;">
                Please enter the following <strong>6-digit verification code</strong> to continue:
            </p>

            <!-- OTP Code Box -->
            <div style="background: linear-gradient(135deg, #fff7ed 0%, #ffedd5 100%); padding: 30px; text-align: center; border: 3px dashed #f97316; border-radius: 15px; margin: 30px 0; position: relative;">
                <div style="font-size: 48px; font-weight: 700; color: #f97316; letter-spacing: 12px; font-family: 'Courier New', monospace; text-shadow: 2px 2px 4px rgba(0,0,0,0.1);">
                    {{ $otp->code }}
                </div>
                <div style="margin-top: 15px; color: #ea580c; font-size: 14px; font-weight: 500;">
                    ⏰ Valid for 10 minutes only
                </div>
            </div>

            <!-- Security Notice -->
            <div style="background: #fef3c7; border-left: 4px solid #f59e0b; padding: 15px; margin: 30px 0; border-radius: 8px;">
                <p style="margin: 0; color: #92400e; font-size: 14px; line-height: 1.5;">
                    <strong>🔒 Security Notice:</strong> For your protection, never share this code with anyone. Our team will never ask for your verification code.
                </p>
            </div>

            <!-- Help Section -->
            <div style="text-align: center; margin-top: 30px; padding-top: 30px; border-top: 1px solid #e5e7eb;">
                <p style="color: #666; font-size: 14px; margin: 0 0 10px 0;">
                    Didn't request this code?
                </p>
                <p style="color: #999; font-size: 13px; margin: 0;">
                    You can safely ignore this email. Someone may have entered your email by mistake.
                </p>
            </div>
        </div>

        <!-- Footer -->
        <div style="background: #1f2937; padding: 30px; text-align: center;">
            <div style="display: flex; justify-content: center; gap: 20px; margin-bottom: 20px;">
                <span style="font-size: 24px;">🍕</span>
                <span style="font-size: 24px;">🍔</span>
                <span style="font-size: 24px;">🥗</span>
                <span style="font-size: 24px;">🍰</span>
                <span style="font-size: 24px;">☕</span>
            </div>
            <p style="color: rgba(255,255,255,0.7); margin: 0 0 10px 0; font-size: 14px;">
                &copy; {{ date('Y') }} Cafe Delight. All rights reserved.
            </p>
            <p style="color: rgba(255,255,255,0.5); margin: 0; font-size: 12px;">
                This is an automated email. Please do not reply.
            </p>
        </div>
    </div>
</body>
</html>
