<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Contract Report - {{ $contractName }}</title>
    <style>
        /* Basic styles for email clients */
        body { font-family: Arial, sans-serif; font-size: 12px; line-height: 1.6; color: #333333; margin: 0; padding: 0; background-color: #f4f4f4; }
        .container { max-width: 800px; margin: 20px auto; background-color: #ffffff; padding: 20px; border: 1px solid #dddddd; }
        h1, h2, h3 { color: #333333; margin-top: 0; }
        h1 { font-size: 22px; text-align: center; margin-bottom: 20px; border-bottom: 2px solid #cccccc; padding-bottom: 10px; }
        h2 { font-size: 16px; margin-bottom: 10px; border-bottom: 1px solid #eeeeee; padding-bottom: 5px; color: #0056b3; }
        h3 { font-size: 14px; margin-bottom: 8px; color: #0056b3; }
        table { width: 100%; border-collapse: collapse; margin-bottom: 15px; }
        th, td { border: 1px solid #dddddd; padding: 8px; text-align: left; font-size: 11px; vertical-align: top; }
        th { background-color: #f2f2f2; font-weight: bold; text-transform: uppercase; }
        .details-table td:first-child { font-weight: bold; width: 160px; background-color: #f9f9f9; }
        .footer { margin-top: 20px; font-size: 10px; text-align: center; color: #777777; border-top: 1px solid #eeeeee; padding-top: 10px; }
        .info-box { background-color: #eaf6ff; border: 1px solid #bce8f1; padding: 15px; border-radius: 4px; margin-bottom: 15px; }
        .status-cleared { color: #155724; background-color: #d4edda; padding: 3px 6px; border-radius: 4px; font-weight: bold; font-size: 9px; display: inline-block; }
        .status-bounced { color: #721c24; background-color: #f8d7da; padding: 3px 6px; border-radius: 4px; font-weight: bold; font-size: 9px; display: inline-block; }
        .status-pending { color: #856404; background-color: #fff3cd; padding: 3px 6px; border-radius: 4px; font-weight: bold; font-size: 9px; display: inline-block; }
        .status-cancelled { color: #383d41; background-color: #e2e3e5; padding: 3px 6px; border-radius: 4px; font-weight: bold; font-size: 9px; display: inline-block; }
    </style>
</head>
<body>
    <div class="container">
        <h1>Contract Report - #{{ $contractName }}</h1>

        {{-- Top Section Table --}}
        <table style="width: 100%; border: none; border-spacing: 0; margin-bottom: 20px;">
            <tr>
                <td style="width: 65%; vertical-align: top; padding-right: 10px; border: none;">
                    <div class="info-box">
                        <h2>Contract Information</h2>
                        <table class="details-table">
                            <tr><td>Tenant:</td><td>{{ $tenantName }}</td></tr>
                            <tr><td>Property:</td><td>{{ $propertyName }}</td></tr>
                            {{-- Add other necessary fields passed from mailable --}}
                            {{-- Example: Need to pass contract start/end etc. if needed --}}
                             @if(isset($contractType))<tr><td>Contract Type:</td><td>{{ $contractType === 'terminated' ? 'Terminated' : ucfirst($contractType) }}</td></tr>@endif
                             @if(isset($contractStatus))<tr><td>Status:</td><td>{{ $contractStatus }}</td></tr>@endif
                             @if(isset($terminationReason) && $terminationReason)<tr><td>Termination Reason:</td><td>{{ $terminationReason }}</td></tr>@endif
                             @if($settlement)<tr><td>Settlement Status:</td><td style="color: #155724; font-weight: bold;">Settled on {{ $settlement->created_at->format('M d, Y') }}</td></tr>@endif
                        </table>
                    </div>
                </td>
                <td style="width: 35%; vertical-align: top; padding-left: 10px; border: none;">
                     <div class="info-box">
                        <h3>Rent Summary</h3>
                        <p style="margin: 0 0 5px 0; font-size: 11px;">Collection Scheduled: ${{ number_format($totalRentScheduled, 2) }}</p>
                        @if($balanceDue > 0)<p style="margin: 0 0 5px 0; font-size: 11px;">Unscheduled: ${{ number_format($balanceDue, 2) }}</p>@endif
                        <p style="margin: 0 0 5px 0; font-size: 11px;">Realized Amount: ${{ number_format($totalRentCleared, 2) }}</p>
                        @if($totalRentPendingClearance > 0)<p style="margin: 0 0 5px 0; font-size: 11px;">Balance Pending Realization: ${{ number_format($totalRentPendingClearance, 2) }}</p>@endif
                    </div>
                </td>
            </tr>
        </table>

        {{-- Settlement Details --}}
        @if($settlement)
            <h2>Settlement Details</h2>
             <table style="width: 100%; border: none; border-spacing: 0; margin-bottom: 20px;">
                <tr>
                     <td style="width: 50%; vertical-align: top; padding-right: 10px; border: none;">
                        <table class="details-table">
                             <tr><td>Original Deposit Amount</td><td>${{ number_format($settlement->original_deposit_amount, 2) }}</td></tr>
                             <tr><td>Deduction Amount</td><td>${{ number_format($settlement->deduction_amount, 2) }}</td></tr>
                             <tr><td>Return Amount</td><td style="font-weight: bold;">${{ number_format($settlement->return_amount, 2) }}</td></tr>
                        </table>
                     </td>
                     <td style="width: 50%; vertical-align: top; padding-left: 10px; border: none;">
                         <table class="details-table">
                            <tr><td>Return Date</td><td>{{ $settlement->return_date->format('M d, Y') }}</td></tr>
                            <tr><td>Return Payment Type</td><td>{{ $settlement->return_payment_type }}</td></tr>
                            @if(in_array($settlement->return_payment_type, ['CHEQUE', 'ONLINE_TRANSFER']))<tr><td>Return Reference</td><td>{{ $settlement->return_reference ?: 'N/A' }}</td></tr>@endif
                            @if($settlement->notes)<tr><td>Settlement Notes</td><td>{{ $settlement->notes }}</td></tr>@endif
                         </table>
                     </td>
                </tr>
            </table>
        @endif

        {{-- Receipt History --}}
        @if($receipts && $receipts->isNotEmpty())
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
                    @foreach ($receipts as $receipt)
                        <tr>
                            <td>{{ $receipt->receipt_category }}</td>
                            <td>{{ $receipt->cheque_no ?: '-' }}</td>
                            <td>${{ number_format($receipt->amount, 2) }}</td>
                            <td>{{ $receipt->payment_type }}</td>
                            <td>{{ $receipt->receipt_date ? $receipt->receipt_date->format('d M Y') : 'N/A' }}</td>
                            <td>
                                @php
                                    $statusClass = match(strtoupper($receipt->status ?? '')) {
                                        'CLEARED' => 'status-cleared',
                                        'BOUNCED' => 'status-bounced',
                                        'PENDING' => 'status-pending',
                                        'CANCELLED' => 'status-cancelled',
                                        default => '',
                                    };
                                @endphp
                                <span class="{{ $statusClass }}">{{ $receipt->status }}</span>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        @endif

        <div class="footer">
            {{ config('app.name') }} | Sent by: {{ $userName ?? 'N/A' }} | Sent Date: {{ $timestamp ?? 'N/A' }}
        </div>
    </div>
</body>
</html>
