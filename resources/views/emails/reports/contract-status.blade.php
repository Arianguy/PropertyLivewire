<!DOCTYPE html>
<html>
<head>
    <title>Contracts Report</title>
    <style>
        body { font-family: sans-serif; font-size: 12px; }
        p { margin-bottom: 15px; }
    </style>
</head>
<body>
    <p>Hello,</p>

    <p>Please find the attached Contracts Report ({{ ucfirst($reportType ?? 'N/A') }}).</p>

    @if($startDate || $endDate)
        <p>Date Range Filter (Start Date): {{ $startDate ? \Carbon\Carbon::parse($startDate)->format('d-M-Y') : 'N/A' }} to {{ $endDate ? \Carbon\Carbon::parse($endDate)->format('d-M-Y') : 'N/A' }}</p>
    @endif

    <p>Thank you.</p>
</body>
</html>
