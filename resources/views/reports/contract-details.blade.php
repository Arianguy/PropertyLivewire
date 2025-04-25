<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8"/>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Contract Report - {{ $contract->name }}</title>
    <style>
        @page { margin: 20px; } /* Slightly reduced margin */
        body {
            font-family: 'Helvetica', 'Arial', sans-serif;
            font-size: 9px; /* Slightly smaller base font */
            line-height: 1.4; /* Adjusted line height */
            color: #333;
        }
        table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 15px; /* Adjusted */
        }
        th, td {
            border: 1px solid #ccc;
            padding: 6px; /* Adjusted */
            text-align: left;
            vertical-align: top;
            font-size: 9px; /* Consistent font size */
        }
        th {
            background-color: #e9ecef;
            color: #495057;
            font-weight: bold;
            text-transform: uppercase;
        }
        tbody tr:nth-child(even) {
            background-color: #f8f9fa;
        }
        h1, h2, h3 {
            margin-top: 25px;
            margin-bottom: 15px;
            color: #444; /* Slightly darker heading color */
        }
        h1 {
            font-size: 18px; /* Adjusted */
            text-align: center;
            color: #222;
            border-bottom: 2px solid #6c757d;
            padding-bottom: 8px; /* Adjusted */
            margin-bottom: 20px; /* Adjusted */
            margin-top: 0; /* Remove top margin */
        }
        h2 {
            font-size: 14px; /* Adjusted */
            border-bottom: 1px solid #dee2e6;
            padding-bottom: 6px; /* Adjusted */
            color: #4682b4;
            margin-bottom: 10px; /* Adjusted */
            margin-top: 20px; /* Add top margin */
        }
        h3 { /* Used inside Rent Summary */
            font-size: 14px; /* Match h2 */
            border-bottom: 1px solid #dee2e6;
            padding-bottom: 6px;
            color: #4682b4;
            margin-bottom: 10px;
            margin-top: 0; /* Keep no top margin here */
            font-weight: bold;
        }
        .details-grid {
            margin-bottom: 0;
            padding: 0; /* Remove padding */
        }
        .details-grid dt {
            font-weight: bold;
            float: left;
            width: 140px; /* Adjusted width */
            clear: left;
            color: #555;
            padding-bottom: 5px; /* Add spacing */
        }
        .details-grid dd {
            margin-left: 150px; /* Adjusted margin */
            margin-bottom: 5px; /* Adjusted */
            color: #333;
            padding-bottom: 5px; /* Add spacing */
        }
        .details-grid dl::after {
            content: "";
            display: table;
            clear: both;
        }
        .amount-summary {
            margin-bottom: 0;
            margin-top: 0;
            padding: 0; /* Remove padding */
        }
        .amount-summary span {
            display: block;
            margin-bottom: 4px; /* Adjusted */
            font-size: 10px; /* Adjusted */
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
             /* Styles now handled by general h2 */
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
        .status-cancelled {
            color: #383d41; /* Dark gray */
            background-color: #e2e3e5; /* Light gray */
            padding: 3px 6px;
            border-border-radius: 4px;
            font-weight: bold;
            font-size: 9px;
        }
        .status-other {
            /* Default style for any other status */
        }
        /* New styles for top table layout */
        .top-layout-table { width: 100%; border-collapse: separate; border-spacing: 0 15px; /* Spacing between rows */ margin-bottom: 20px; }
        .top-layout-table > tbody > tr > td {
            vertical-align: top;
            border: 1px solid #b0e0e6; /* Border from info-box */
            background-color: #f0f8ff; /* Background from info-box */
            padding: 10px 15px;
            border-radius: 4px;
        }
        .top-layout-table > tbody > tr > td.contract-info-cell { width: 65%; padding-right: 10px; } /* Adjust width */
        .top-layout-table > tbody > tr > td.rent-summary-cell { width: 35%; padding-left: 10px; } /* Adjust width */

        /* Ensure h2/h3 inside cells have no top margin */
        .top-layout-table h2, .top-layout-table h3 {
            margin-top: 0;
        }

        /* Remove background/border from info-box as it's on the table cells now */
        .info-box {
            /* background-color: #f0f8ff; */
            padding: 0; /* Remove padding */
            /* border: 1px solid #b0e0e6; */
            border-radius: 0; /* Remove radius */
            margin-bottom: 0; /* Remove margin */
         }
    </style>
</head>
<body>
    <div id="footer">
        {{ config('app.name') }} | Printed by: {{ $userName ?? 'N/A' }} | Print Date: {{ now()->format('Y-m-d H:i:s') }}
    </div>

    <h1>Contract Report - #{{ $contract->name }}</h1>

    {{-- Top Section Table Layout --}}
    <table class="top-layout-table">
        <tr>
            <td class="contract-info-cell">
                 <h2>Contract Information</h2>
                 <div class="details-grid">
                     <dl>
                         <dt>Tenant:</dt><dd>{{ $contract->tenant->name }}</dd>
                         <dt>Property:</dt><dd>{{ $contract->property->name }}</dd>
                         <dt>Contract Period:</dt><dd>{{ $contract->cstart->format('M d, Y') }} - {{ $contract->cend->format('M d, Y') }}</dd>
                         <dt>Rental Amount:</dt><dd>${{ number_format($contract->amount, 2) }}</dd>
                         <dt>Security Deposit:</dt><dd>${{ number_format($contract->sec_amt, 2) }}</dd>
                         <dt>Ejari:</dt><dd>{{ $contract->ejari }}</dd>
                         <dt>Contract Type:</dt><dd>{{ $contract->type === 'terminated' ? 'Terminated' : ucfirst($contract->type) }}</dd>
                         <dt>Status:</dt><dd>{{ $contract->validity === 'YES' ? 'Active' : 'Inactive' }}</dd>
                         @if($contract->termination_reason)<dt>Termination Reason:</dt><dd>{{ $contract->termination_reason }}</dd>@endif
                         @if($settlement)<dt>Settlement Status:</dt><dd style="color: #155724; font-weight: bold;">Settled on {{ $settlement->created_at->format('M d, Y') }}</dd>@endif
                     </dl>
                 </div>
            </td>
            <td class="rent-summary-cell">
                <div class="amount-summary">
                    <h3>Rent Summary</h3>
                    <span>Collection Scheduled: ${{ number_format($totalRentScheduled, 2) }}</span>
                    @if($balanceDue > 0)<span>Unscheduled: ${{ number_format($balanceDue, 2) }}</span>@endif
                    <span>Realized Amount: ${{ number_format($totalRentCleared, 2) }}</span>
                    @if($totalRentPendingClearance > 0)<span>Balance Pending Realization: ${{ number_format($totalRentPendingClearance, 2) }}</span>@endif
                </div>
            </td>
        </tr>
    </table>
    {{-- End Top Section --}}

    {{-- Settlement Details Section (Only show if settled) --}}
    @if($settlement)
        <h2>Settlement Details</h2>
        <table style="width: 100%; border: none;">
            <tr style="border: none;">
                <td style="width: 50%; vertical-align: top; border: none; padding-right: 10px;">
                    <table class="settlement-details-table">
                        <tbody>
                            <tr>
                                <td>Original Deposit Amount</td>
                                <td>${{ number_format($settlement->original_deposit_amount, 2) }}</td>
                            </tr>
                            <tr>
                                <td>Deduction Amount</td>
                                <td>${{ number_format($settlement->deduction_amount, 2) }}</td>
                            </tr>
                            <tr>
                                <td>Return Amount</td>
                                <td style="font-weight: bold;">${{ number_format($settlement->return_amount, 2) }}</td>
                            </tr>
                        </tbody>
                    </table>
                </td>
                <td style="width: 50%; vertical-align: top; border: none; padding-left: 10px;">
                    <table class="settlement-details-table">
                        <tbody>
                            <tr>
                                <td>Return Date</td>
                                <td>{{ $settlement->return_date->format('M d, Y') }}</td>
                            </tr>
                            <tr>
                                <td>Return Payment Type</td>
                                <td>{{ $settlement->return_payment_type }}</td>
                            </tr>
                            @if(in_array($settlement->return_payment_type, ['CHEQUE', 'ONLINE_TRANSFER']))
                            <tr>
                                <td>Return Reference</td>
                                <td>{{ $settlement->return_reference ?: 'N/A' }}</td>
                            </tr>
                            @endif
                            @if($settlement->notes)
                            <tr>
                                <td>Settlement Notes</td>
                                <td>{{ $settlement->notes }}</td>
                            </tr>
                            @endif
                        </tbody>
                    </table>
                </td>
            </tr>
        </table>
    @endif

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
                                'CANCELLED' => 'status-cancelled',
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
