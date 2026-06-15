<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Order Confirmation - #{{ $order->id }}</title>
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
            <div style="text-align: center; margin-bottom: 30px;">
                <div style="font-size: 64px; margin-bottom: 10px;">✅</div>
                <h2 style="color: #f97316; margin: 0 0 10px 0; font-size: 28px; font-weight: 600;">Order Confirmed!</h2>
                <p style="color: #666; margin: 0; font-size: 16px;">Thank you for your order at Cafe Delight!</p>
            </div>

            <!-- Order Details Box -->
            <div style="background: linear-gradient(135deg, #fff7ed 0%, #ffedd5 100%); padding: 30px; border-radius: 15px; margin: 30px 0; border: 2px solid #f97316;">
                <h3 style="color: #ea580c; margin: 0 0 20px 0; font-size: 20px; font-weight: 600; text-align: center;">Order Details</h3>
                
                <table style="width: 100%; border-collapse: collapse; margin-bottom: 20px;">
                    <tr>
                        <td style="padding: 8px 0; color: #666; font-size: 14px; font-weight: 500;">Order ID:</td>
                        <td style="padding: 8px 0; color: #333; font-size: 14px; font-weight: 600; text-align: right;">#{{ $order->id }}</td>
                    </tr>
                    <tr>
                        <td style="padding: 8px 0; color: #666; font-size: 14px; font-weight: 500;">Status:</td>
                        <td style="padding: 8px 0; color: #333; font-size: 14px; font-weight: 600; text-align: right;">{{ $order->statusLabel() }}</td>
                    </tr>
                    <tr>
                        <td style="padding: 8px 0; color: #666; font-size: 14px; font-weight: 500;">Payment Method:</td>
                        <td style="padding: 8px 0; color: #333; font-size: 14px; font-weight: 600; text-align: right;">{{ $order->paymentMethodLabel() }}</td>
                    </tr>
                    <tr>
                        <td style="padding: 8px 0; color: #666; font-size: 14px; font-weight: 500;">Payment Status:</td>
                        <td style="padding: 8px 0; color: #333; font-size: 14px; font-weight: 600; text-align: right;">{{ $order->paymentStatusLabel() }}</td>
                    </tr>
                    <tr>
                        <td style="padding: 8px 0; color: #666; font-size: 14px; font-weight: 500;">Estimated Delivery:</td>
                        <td style="padding: 8px 0; color: #333; font-size: 14px; font-weight: 600; text-align: right;">{{ $order->estimated_delivery_time->format('M d, Y - H:i') }}</td>
                    </tr>
                </table>

                <div style="padding: 15px 0; border-top: 1px dashed #fdba74; margin-top: 15px;">
                    <p style="color: #666; font-size: 13px; margin: 0 0 5px 0;"><strong>Delivery Address:</strong></p>
                    <p style="color: #333; font-size: 14px; margin: 0 0 10px 0;">{{ $order->delivery_address }}</p>
                    <p style="color: #666; font-size: 13px; margin: 0 0 5px 0;"><strong>Phone:</strong></p>
                    <p style="color: #333; font-size: 14px; margin: 0;">{{ $order->phone }}</p>
                </div>
            </div>

            <!-- Order Items -->
            <div style="margin: 30px 0;">
                <h3 style="color: #333; margin: 0 0 20px 0; font-size: 20px; font-weight: 600;">Order Items</h3>
                @php
                    $orderItems = $order->items->load('dish');
                @endphp
                @foreach($orderItems as $item)
                <div style="display: flex; justify-content: space-between; align-items: center; padding: 15px 0; border-bottom: 1px solid #e5e7eb;">
                    <div>
                        <p style="color: #333; font-size: 15px; font-weight: 600; margin: 0 0 5px 0;">{{ $item->dish->name }}</p>
                        <p style="color: #666; font-size: 13px; margin: 0;">Qty: {{ $item->quantity }} × KES {{ number_format($item->price, 2) }}</p>
                    </div>
                    <div style="color: #f97316; font-size: 16px; font-weight: 700;">
                        KES {{ number_format($item->price * $item->quantity, 2) }}
                    </div>
                </div>
                @endforeach
            </div>

            <!-- Order Summary -->
            <div style="background: #f9fafb; padding: 25px; border-radius: 15px; margin: 30px 0;">
                <table style="width: 100%; border-collapse: collapse;">
                    <tr>
                        <td style="padding: 10px 0; color: #666; font-size: 14px;">Subtotal:</td>
                        <td style="padding: 10px 0; color: #333; font-size: 14px; font-weight: 600; text-align: right;">KES {{ number_format($order->subtotal, 2) }}</td>
                    </tr>
                    <tr>
                        <td style="padding: 10px 0; color: #666; font-size: 14px;">Delivery Fee:</td>
                        <td style="padding: 10px 0; color: #333; font-size: 14px; font-weight: 600; text-align: right;">KES {{ number_format($order->delivery_fee, 2) }}</td>
                    </tr>
                    <tr>
                        <td style="padding: 10px 0; color: #666; font-size: 14px;">Tax:</td>
                        <td style="padding: 10px 0; color: #333; font-size: 14px; font-weight: 600; text-align: right;">KES {{ number_format($order->tax, 2) }}</td>
                    </tr>
                    <tr style="border-top: 2px solid #f97316;">
                        <td style="padding: 15px 0 10px 0; color: #f97316; font-size: 18px; font-weight: 700;">Total:</td>
                        <td style="padding: 15px 0 10px 0; color: #f97316; font-size: 18px; font-weight: 700; text-align: right;">KES {{ number_format($order->total, 2) }}</td>
                    </tr>
                </table>
            </div>

            <!-- CTA Button -->
            <div style="text-align: center; margin: 30px 0;">
                <a href="{{ url('/orders/' . $order->id) }}" style="display: inline-block; background: linear-gradient(135deg, #f97316 0%, #ea580c 100%); color: white; padding: 15px 40px; text-decoration: none; border-radius: 50px; font-size: 16px; font-weight: 600; box-shadow: 0 4px 15px rgba(249, 115, 22, 0.4);">
                    View Order Details
                </a>
            </div>

            <!-- Info Box -->
            <div style="background: #ecfdf5; border-left: 4px solid #10b981; padding: 15px; margin: 30px 0; border-radius: 8px;">
                <p style="margin: 0; color: #065f46; font-size: 14px; line-height: 1.5;">
                    <strong>🚚 Delivery Info:</strong> We'll notify you when your order is on the way! Estimated delivery time is approximately 45 minutes.
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
                Thank you for choosing Cafe Delight!
            </p>
        </div>
    </div>
</body>
</html>
