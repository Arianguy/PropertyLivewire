<!DOCTYPE html>
<html>
<head>
    <title>Security Deposit Report</title>
    <style>
        body { font-family: sans-serif; font-size: 12px; }
        p { margin-bottom: 15px; }
    </style>
</head>
<body>
    <p>Hello,</p>

    <p>Please find the attached Security Deposit Report ({{ ucfirst($reportType) }}).</p>

    @if($startDate || $endDate)
        <p>Date Range: {{ $startDate ? \Carbon\Carbon::parse($startDate)->format('d-M-Y') : 'N/A' }} to {{ $endDate ? \Carbon\Carbon::parse($endDate)->format('d-M-Y') : 'N/A' }}</p>
    @endif

    <p>Thank you.</p>
</body>
</html>
