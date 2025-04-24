<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8"/>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Contract Report - {{ $contract->name }}</title>
    <style>
        @page { margin: 25px; }
        body {
            font-family: 'Helvetica', 'Arial', sans-serif; /* Common sans-serif */
            font-size: 10px;
            line-height: 1.5;
            color: #333; /* Dark gray for text */
        }
        table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 20px;
        }
        th, td {
            border: 1px solid #ccc; /* Lighter gray border */
            padding: 8px; /* Slightly more padding */
            text-align: left;
            vertical-align: top;
        }
        th {
            background-color: #e9ecef; /* Light gray background for headers */
            color: #495057; /* Darker gray text for headers */
            font-weight: bold;
            text-transform: uppercase;
            font-size: 9px;
        }
        tbody tr:nth-child(even) {
            background-color: #f8f9fa; /* Very light gray for alternating rows */
        }
        h1, h2, h3 {
            margin-top: 25px;
            margin-bottom: 15px;
            color: #444; /* Slightly darker heading color */
        }
        h1 {
            font-size: 20px;
            text-align: center;
            color: #222; /* Darkest heading */
            border-bottom: 2px solid #6c757d; /* Medium gray border */
            padding-bottom: 10px;
            margin-bottom: 30px;
        }
        h2 {
            font-size: 15px;
            border-bottom: 1px solid #dee2e6; /* Light border under section titles */
            padding-bottom: 8px;
            color: #007bff; /* Using a blue - check grayscale */
            /* Or use a safe gray: color: #5a6268; */
        }
        h3 {
            font-size: 12px;
            font-weight: bold;
            margin-bottom: 10px;
        }
        .details-grid {
            margin-bottom: 25px;
            background-color: #f0f8ff; /* Light AliceBlue background (like amount summary) */
            padding: 15px;
            border: 1px solid #b0e0e6; /* PowderBlue border (like amount summary) */
            border-radius: 4px;
        }
        .details-grid dt {
            font-weight: bold;
            float: left;
            width: 160px; /* Adjusted width */
            clear: left;
            color: #555;
        }
        .details-grid dd {
            margin-left: 170px; /* Adjusted margin */
            margin-bottom: 8px;
            color: #333;
        }
        /* Clearfix for the definition list float */
        .details-grid dl::after {
            content: "";
            display: table;
            clear: both;
        }
        .amount-summary {
            margin-bottom: 25px;
            padding: 15px;
            background-color: #f0f8ff; /* Light AliceBlue background */
            border: 1px solid #b0e0e6; /* PowderBlue border */
            border-radius: 4px;
        }
        .amount-summary h3 {
            margin-top: 0;
            color: #4682b4; /* SteelBlue */
        }
        .amount-summary span {
            display: block;
            margin-bottom: 5px;
            font-size: 11px;
        }
        .page-break {
            page-break-after: always;
        }
        /* Footer Styles */
        @page { margin: 25px 25px 50px 25px; } /* Increased bottom margin */
        #footer {
            position: fixed;
            bottom: -35px; /* Adjust position slightly below margin */
            left: 0px;
            right: 0px;
            height: 40px;
            font-size: 9px;
            text-align: center;
            border-top: 1px solid #ccc;
            padding-top: 5px;
            color: #666;
        }
        /* Specific style for heading within the details grid */
        .details-grid h2 {
             color: #4682b4; /* SteelBlue like amount summary heading */
             border-bottom: 1px solid #b0e0e6; /* Match border color */
        }
        /* Status Text Colors */
        .status-cleared {
            color: #155724; /* Dark green */
            background-color: #d4edda; /* Light green */
            padding: 3px 6px;
            border-radius: 4px;
            font-weight: bold;
            font-size: 9px;
        }
        .status-bounced {
            color: #721c24; /* Dark red */
            background-color: #f8d7da; /* Light red */
            padding: 3px 6px;
            border-radius: 4px;
            font-weight: bold;
            font-size: 9px;
        }
        .status-pending {
            color: #856404; /* Dark yellow/brown */
            background-color: #fff3cd; /* Light yellow */
            padding: 3px 6px;
            border-radius: 4px;
            font-weight: bold;
            font-size: 9px;
        }
        .status-other {
            /* Default style for any other status */
        }
    </style>
</head>
<body>
    <div id="footer">
        {{ config('app.name') }} | Printed by: {{ $userName ?? 'N/A' }} | Print Date: {{ now()->format('Y-m-d H:i:s') }}
    </div>

    <h1>Contract Report - #{{ $contract->name }}</h1>

    <h2>Contract Information</h2>
    <div class="details-grid">
        <dl>
            <dt>Tenant:</dt>
            <dd>{{ $contract->tenant->name }}</dd>
            <dt>Property:</dt>
            <dd>{{ $contract->property->name }}</dd>
            <dt>Contract Period:</dt>
            <dd>{{ $contract->cstart->format('M d, Y') }} - {{ $contract->cend->format('M d, Y') }}</dd>
            <dt>Rental Amount:</dt>
            <dd>${{ number_format($contract->amount, 2) }}</dd>
            <dt>Security Deposit:</dt>
            <dd>${{ number_format($contract->sec_amt, 2) }}</dd>
            <dt>Ejari:</dt>
            <dd>{{ $contract->ejari }}</dd>
            <dt>Contract Type:</dt>
            <dd>{{ ucfirst($contract->type) }}</dd>
            <dt>Status:</dt>
            <dd>{{ $contract->validity === 'YES' ? 'Active' : 'Inactive' }}</dd>
            @if($contract->termination_reason)
                <dt>Termination Reason:</dt>
                <dd>{{ $contract->termination_reason }}</dd>
            @endif
        </dl>
    </div>

    <div class="amount-summary">
        <h3>Rent Summary</h3>
        <span>Collection Scheduled: ${{ number_format($totalRentScheduled, 2) }}</span>
        @if($balanceDue > 0)
            <span>Unscheduled: ${{ number_format($balanceDue, 2) }}</span>
        @endif
        <span>Realized Amount: ${{ number_format($totalRentCleared, 2) }}</span>
        @if($totalRentPendingClearance > 0)
            <span>Balance Pending Realization: ${{ number_format($totalRentPendingClearance, 2) }}</span>
        @endif
    </div>

    <h2>Receipt History</h2>
    <table>
        <thead>
            <tr>
                <th>Category</th>
                <th>Cheque No</th>
                <th>Amount</th>
                <th>Payment Type</th>
                <th>Date</th>
                <th>Status</th>
            </tr>
        </thead>
        <tbody>
            @forelse ($contract->receipts->sortBy('receipt_date') as $receipt)
                <tr>
                    <td>{{ str_replace('_', ' ', $receipt->receipt_category) }}</td>
                    <td>{{ $receipt->cheque_no ?: '-' }}</td>
                    <td>${{ number_format($receipt->amount, 2) }}</td>
                    <td>{{ $receipt->payment_type }}</td>
                    <td>{{ $receipt->receipt_date->format('d M Y') }}</td>
                    <td>
                        @php
                            $statusClass = match(strtoupper($receipt->status ?? '')) {
                                'CLEARED' => 'status-cleared',
                                'BOUNCED' => 'status-bounced',
                                'PENDING' => 'status-pending',
                                default => 'status-other',
                            };
                        @endphp
                        <span class="{{ $statusClass }}">{{ $receipt->status }}</span>
                    </td>
                </tr>
            @empty
                <tr>
                    <td colspan="6">No receipts found for this contract.</td>
                </tr>
            @endforelse
        </tbody>
    </table>

    {{-- Add other sections if needed, e.g., Documents, Previous Contracts --}}
    {{-- Use <div class="page-break"></div> to force page breaks if necessary --}}

</body>
</html>
