<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Analytics Report — Cafe Delight</title>
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }
        body {
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            line-height: 1.6;
            color: #333;
            background: #f5f5f5;
            padding: 20px;
        }
        .container {
            max-width: 1200px;
            margin: 0 auto;
            background: white;
            padding: 40px;
            box-shadow: 0 2px 10px rgba(0,0,0,0.1);
        }
        .header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            border-bottom: 3px solid #f97316;
            padding-bottom: 20px;
            margin-bottom: 30px;
        }
        .logo {
            display: flex;
            align-items: center;
            gap: 15px;
        }
        .logo-icon {
            width: 60px;
            height: 60px;
            background: linear-gradient(135deg, #f97316, #ea580c);
            border-radius: 12px;
            display: flex;
            align-items: center;
            justify-content: center;
            color: white;
            font-size: 30px;
        }
        .logo-text h1 {
            font-size: 28px;
            font-weight: 800;
            color: #0b0a09;
        }
        .logo-text span {
            background: linear-gradient(120deg, #fb923c, #ea580c 40%, #e6b450);
            -webkit-background-clip: text;
            background-clip: text;
            color: transparent;
        }
        .report-info {
            text-align: right;
        }
        .report-info h2 {
            font-size: 24px;
            color: #0b0a09;
            margin-bottom: 5px;
        }
        .report-info p {
            color: #666;
            font-size: 14px;
        }
        .summary-cards {
            display: grid;
            grid-template-columns: repeat(4, 1fr);
            gap: 20px;
            margin-bottom: 30px;
        }
        .card {
            background: linear-gradient(135deg, #fff7ed, #ffedd5);
            padding: 20px;
            border-radius: 12px;
            border: 1px solid #fed7aa;
        }
        .card h3 {
            font-size: 12px;
            text-transform: uppercase;
            letter-spacing: 1px;
            color: #ea580c;
            margin-bottom: 10px;
        }
        .card .value {
            font-size: 28px;
            font-weight: 800;
            color: #0b0a09;
        }
        .section {
            margin-bottom: 30px;
        }
        .section h3 {
            font-size: 20px;
            color: #0b0a09;
            margin-bottom: 15px;
            padding-bottom: 10px;
            border-bottom: 2px solid #f97316;
        }
        table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 20px;
        }
        th, td {
            padding: 12px;
            text-align: left;
            border-bottom: 1px solid #e5e5e5;
        }
        th {
            background: #f97316;
            color: white;
            font-weight: 600;
            text-transform: uppercase;
            font-size: 12px;
            letter-spacing: 0.5px;
        }
        tr:hover {
            background: #fff7ed;
        }
        .footer {
            margin-top: 40px;
            padding-top: 20px;
            border-top: 2px solid #f97316;
            text-align: center;
            color: #666;
            font-size: 14px;
        }
        .print-btn {
            position: fixed;
            top: 20px;
            right: 20px;
            background: #f97316;
            color: white;
            padding: 12px 24px;
            border: none;
            border-radius: 8px;
            cursor: pointer;
            font-size: 16px;
            font-weight: 600;
            box-shadow: 0 4px 10px rgba(249, 115, 22, 0.3);
            transition: all 0.3s;
        }
        .print-btn:hover {
            background: #ea580c;
            transform: translateY(-2px);
        }
        @media print {
            .print-btn {
                display: none;
            }
            body {
                background: white;
                padding: 0;
            }
            .container {
                box-shadow: none;
                padding: 0;
            }
        }
    </style>
</head>
<body>
    <button class="print-btn" onclick="window.print()">🖨️ Print to PDF</button>
    
    <div class="container">
        <div class="header">
            <div class="logo">
                <div class="logo-icon">🍽️</div>
                <div class="logo-text">
                    <h1>Cafe <span>Delight</span></h1>
                    <p style="color: #666; font-size: 14px; margin-top: 5px;">Analytics Report</p>
                </div>
            </div>
            <div class="report-info">
                <h2>Business Performance</h2>
                <p>Period: {{ \Carbon\Carbon::parse($startDate)->format('M d, Y') }} - {{ \Carbon\Carbon::parse($endDate)->format('M d, Y') }}</p>
                <p>Generated: {{ \Carbon\Carbon::now()->format('M d, Y - H:i') }}</p>
            </div>
        </div>

        <div class="summary-cards">
            <div class="card">
                <h3>Total Revenue</h3>
                <div class="value">KSh {{ number_format($totalRevenue, 0) }}</div>
            </div>
            <div class="card">
                <h3>Total Orders</h3>
                <div class="value">{{ $totalOrders }}</div>
            </div>
            <div class="card">
                <h3>Unique Customers</h3>
                <div class="value">{{ $totalCustomers }}</div>
            </div>
            <div class="card">
                <h3>Avg Order Value</h3>
                <div class="value">KSh {{ number_format($avgOrderValue, 0) }}</div>
            </div>
        </div>

        <div class="section">
            <h3>Payment Methods Breakdown</h3>
            <table>
                <thead>
                    <tr>
                        <th>Payment Method</th>
                        <th>Revenue</th>
                        <th>Orders</th>
                        <th>Percentage</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($salesByPayment as $payment)
                    <tr>
                        <td>{{ $payment['method'] }}</td>
                        <td>KSh {{ number_format($payment['revenue'], 0) }}</td>
                        <td>{{ $payment['orders'] }}</td>
                        <td>{{ $payment['percentage'] }}%</td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>

        <div class="section">
            <h3>Top Selling Dishes</h3>
            <table>
                <thead>
                    <tr>
                        <th>Rank</th>
                        <th>Dish Name</th>
                        <th>Quantity Sold</th>
                        <th>Revenue</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($topDishes as $i => $dish)
                    <tr>
                        <td>{{ $i + 1 }}</td>
                        <td>{{ $dish['name'] }}</td>
                        <td>{{ $dish['quantity'] }}</td>
                        <td>KSh {{ number_format($dish['revenue'], 0) }}</td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>

        <div class="section">
            <h3>Recent Orders</h3>
            <table>
                <thead>
                    <tr>
                        <th>Order ID</th>
                        <th>Customer</th>
                        <th>Date</th>
                        <th>Total</th>
                        <th>Status</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($orders->take(20) as $order)
                    <tr>
                        <td>#{{ str_pad($order->id, 6, '0', STR_PAD_LEFT) }}</td>
                        <td>{{ $order->user->name }}</td>
                        <td>{{ $order->created_at->format('M d, Y - H:i') }}</td>
                        <td>KSh {{ number_format($order->total, 0) }}</td>
                        <td>{{ ucfirst(str_replace('_', ' ', $order->status)) }}</td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>

        <div class="footer">
            <p>© {{ date('Y') }} Cafe Delight. All rights reserved.</p>
            <p>This report was generated automatically by the Cafe Delight Analytics System.</p>
        </div>
    </div>

    <script>
        // Auto-print when page loads (optional - comment out if not desired)
        // window.onload = function() {
        //     window.print();
        // };
    </script>
</body>
</html>
