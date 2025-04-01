<!DOCTYPE html>
<html>
<head>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8"/>
    <title>Properties Report</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            font-size: 12px;
        }
        .header {
            text-align: center;
            margin-bottom: 20px;
        }
        h1 {
            font-size: 18px;
            font-weight: bold;
            margin-bottom: 0;
        }
        .date {
            font-size: 12px;
            margin-top: 5px;
        }
        table {
            width: 100%;
            border-collapse: collapse;
        }
        th {
            background-color: #f2f2f2;
            text-align: left;
            font-weight: bold;
            padding: 8px;
            border: 1px solid #ddd;
        }
        td {
            padding: 8px;
            border: 1px solid #ddd;
        }
        tr:nth-child(even) {
            background-color: #f9f9f9;
        }
        .status-vacant {
            color: #e53e3e;
            font-weight: bold;
        }
        .status-leased {
            color: #38a169;
            font-weight: bold;
        }
        .status-maintenance {
            color: #d69e2e;
            font-weight: bold;
        }
        .footer {
            text-align: center;
            margin-top: 20px;
            font-size: 10px;
            color: #666;
        }
        .page-break {
            page-break-after: always;
        }
    </style>
</head>
<body>
    <div class="header">
        <h1>Properties Report</h1>
        <div class="date">Generated on: {{ $date }}</div>
    </div>

    <table>
        <thead>
            <tr>
                <th>Name</th>
                <th>Type</th>
                <th>Community</th>
                <th>Owner</th>
                <th>Purchase Date</th>
                <th>Purchase Value</th>
                <th>Status</th>
            </tr>
        </thead>
        <tbody>
            @forelse($properties as $property)
                <tr>
                    <td>{{ $property->name }}</td>
                    <td>{{ $property->type }}</td>
                    <td>{{ $property->community }}</td>
                    <td>{{ $property->owner ? $property->owner->name : 'N/A' }}</td>
                    <td>{{ $property->purchase_date ? $property->purchase_date->format('Y-m-d') : 'N/A' }}</td>
                    <td>{{ number_format($property->purchase_value, 2) }}</td>
                    <td class="status-{{ strtolower($property->status) }}">{{ $property->status }}</td>
                </tr>
            @empty
                <tr>
                    <td colspan="7" style="text-align: center;">No properties found</td>
                </tr>
            @endforelse
        </tbody>
    </table>

    <div class="footer">
        &copy; {{ date('Y') }} Property Management System. All rights reserved.
    </div>
</body>
</html>
