<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Audit Logs Report - Cafe Delight</title>
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }
        body {
            font-family: Arial, sans-serif;
            font-size: 12px;
            line-height: 1.4;
            color: #333;
        }
        .container {
            max-width: 210mm;
            margin: 0 auto;
            padding: 20px;
        }
        .header {
            text-align: center;
            margin-bottom: 30px;
            border-bottom: 2px solid #f97316;
            padding-bottom: 20px;
        }
        .header h1 {
            color: #f97316;
            font-size: 24px;
            margin-bottom: 5px;
        }
        .header p {
            color: #666;
            font-size: 14px;
        }
        .info {
            margin-bottom: 20px;
            padding: 10px;
            background: #f5f5f5;
            border-radius: 5px;
        }
        .info p {
            margin: 5px 0;
        }
        table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 20px;
        }
        th, td {
            padding: 10px;
            text-align: left;
            border-bottom: 1px solid #ddd;
        }
        th {
            background: #f97316;
            color: white;
            font-weight: bold;
        }
        tr:nth-child(even) {
            background: #f9f9f9;
        }
        .action-badge {
            display: inline-block;
            padding: 3px 8px;
            border-radius: 10px;
            font-size: 10px;
            font-weight: bold;
            background: #f97316;
            color: white;
        }
        .footer {
            text-align: center;
            margin-top: 30px;
            padding-top: 20px;
            border-top: 1px solid #ddd;
            color: #666;
            font-size: 10px;
        }
    </style>
</head>
<body>
    <div class="container">
        <div class="header">
            <h1>Audit Logs Report</h1>
            <p>Cafe Delight - System Activity Tracking</p>
            <p>Generated on: {{ now()->format('F d, Y H:i:s') }}</p>
        </div>

        <div class="info">
            <p><strong>Total Records:</strong> {{ $auditLogs->count() }}</p>
            <p><strong>Date Range:</strong> {{ $auditLogs->first()?->created_at->format('M d, Y') }} to {{ $auditLogs->last()?->created_at->format('M d, Y') }}</p>
        </div>

        <table>
            <thead>
                <tr>
                    <th>Date & Time</th>
                    <th>User</th>
                    <th>Action</th>
                    <th>Description</th>
                    <th>IP Address</th>
                </tr>
            </thead>
            <tbody>
                @foreach($auditLogs as $log)
                <tr>
                    <td>{{ $log->created_at->format('M d, Y H:i:s') }}</td>
                    <td>
                        @if($log->user)
                            {{ $log->user->name }}
                        @else
                            System
                        @endif
                    </td>
                    <td>
                        <span class="action-badge">{{ ucfirst(str_replace('_', ' ', $log->action)) }}</span>
                    </td>
                    <td>{{ $log->description }}</td>
                    <td>{{ $log->ip_address }}</td>
                </tr>
                @endforeach
            </tbody>
        </table>

        <div class="footer">
            <p>This report was generated automatically by Cafe Delight Admin System.</p>
            <p>Confidential - For internal use only.</p>
        </div>
    </div>
</body>
</html>
