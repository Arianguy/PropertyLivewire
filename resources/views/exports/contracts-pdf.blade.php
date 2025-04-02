<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>Contracts Report</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            font-size: 12px;
            line-height: 1.5;
            color: #333;
            margin: 0;
            padding: 0;
        }
        .container {
            width: 100%;
            margin: 0 auto;
            padding: 20px;
        }
        .header {
            text-align: center;
            margin-bottom: 20px;
            border-bottom: 1px solid #ddd;
            padding-bottom: 10px;
        }
        .header h1 {
            font-size: 24px;
            margin: 0;
            color: #333;
        }
        .header p {
            font-size: 14px;
            margin: 5px 0;
            color: #666;
        }
        table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 20px;
        }
        th, td {
            border: 1px solid #ddd;
            padding: 8px;
            text-align: left;
        }
        th {
            background-color: #f2f2f2;
            font-weight: bold;
        }
        tr:nth-child(even) {
            background-color: #f9f9f9;
        }
        .footer {
            margin-top: 20px;
            text-align: center;
            font-size: 10px;
            color: #666;
            border-top: 1px solid #ddd;
            padding-top: 10px;
        }
        .active {
            color: #22c55e;
        }
        .inactive {
            color: #ef4444;
        }
    </style>
</head>
<body>
    <div class="container">
        <div class="header">
            <h1>Contracts Report</h1>
            <p>Generated on {{ $date }}</p>
        </div>

        <table>
            <thead>
                <tr>
                    <th>Contract #</th>
                    <th>Tenant</th>
                    <th>Property</th>
                    <th>Start Date</th>
                    <th>End Date</th>
                    <th>Amount</th>
                    <th>Security</th>
                    <th>Status</th>
                    <th>Type</th>
                </tr>
            </thead>
            <tbody>
                @foreach($contracts as $contract)
                    <tr>
                        <td>{{ $contract->name }}</td>
                        <td>{{ $contract->tenant->name ?? 'N/A' }}</td>
                        <td>{{ $contract->property->name ?? 'N/A' }}</td>
                        <td>{{ \Carbon\Carbon::parse($contract->cstart)->format('M d, Y') }}</td>
                        <td>{{ \Carbon\Carbon::parse($contract->cend)->format('M d, Y') }}</td>
                        <td>${{ number_format($contract->amount, 2) }}</td>
                        <td>${{ number_format($contract->sec_amt, 2) }}</td>
                        <td class="{{ $contract->validity === 'YES' ? 'active' : 'inactive' }}">
                            {{ $contract->validity === 'YES' ? 'Active' : 'Inactive' }}
                        </td>
                        <td>{{ $contract->type ?? 'Original' }}</td>
                    </tr>
                @endforeach
            </tbody>
        </table>

        <div class="footer">
            <p>PropertyLivewire - Contracts Management System</p>
            <p>© {{ date('Y') }} All Rights Reserved</p>
        </div>
    </div>
</body>
</html>
