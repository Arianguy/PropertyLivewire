<!DOCTYPE html>
<html>
<head>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8"/>
    <title>Properties Report</title>
    <style>
        @page {
            size: landscape;
            margin: 1cm;
        }
        body {
            font-family: Arial, sans-serif;
            font-size: 10px;
            margin: 0;
            padding: 0;
        }
        .header {
            text-align: center;
            margin-bottom: 10px;
            padding: 10px;
            background-color: #f8f9fa;
            border-bottom: 1px solid #dee2e6;
        }
        h1 {
            font-size: 16px;
            font-weight: bold;
            margin: 0;
            padding: 0;
        }
        .date {
            font-size: 10px;
            margin-top: 5px;
        }
        table {
            width: 100%;
            border-collapse: collapse;
            font-size: 8px;
        }
        th {
            background-color: #f2f2f2;
            text-align: left;
            font-weight: bold;
            padding: 6px 4px;
            border: 1px solid #ddd;
            font-size: 8px;
            white-space: nowrap;
        }
        td {
            padding: 6px 4px;
            border: 1px solid #ddd;
            vertical-align: top;
        }
        tr:nth-child(even) {
            background-color: #f9f9f9;
        }
        .property-name {
            font-weight: bold;
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
            margin-top: 10px;
            font-size: 8px;
            color: #666;
            position: absolute;
            bottom: 10px;
            width: 100%;
        }
        .page-number {
            position: absolute;
            bottom: 10px;
            right: 10px;
            font-size: 8px;
        }
        .section-title {
            background-color: #edf2f7;
            font-weight: bold;
        }
    </style>
</head>
<body>
    <div class="header">
        <h1>Properties Detailed Report</h1>
        <div class="date">Generated on: {{ $date }}</div>
    </div>

    <table>
        <thead>
            <tr>
                <!-- Basic Info -->
                <th>Name</th>
                <th>Type</th>
                <th>Class</th>
                <th>Status</th>

                <!-- Property Details -->
                <th>Purchase Date</th>
                <th>Title Deed No</th>
                <th>Mortgage</th>

                <!-- Location -->
                <th>Community</th>
                <th>Plot No</th>
                <th>Bldg No</th>
                <th>Bldg Name</th>
                <th>Property No</th>
                <th>Floor</th>

                <!-- Area -->
                <th>Suite (m²)</th>
                <th>Balcony (m²)</th>
                <th>Area (m²)</th>
                <th>Common (m²)</th>
                <th>Area (ft²)</th>

                <!-- Financial -->
                <th>Owner</th>
                <th>Purchase Value</th>
                <th>DEWA Premise</th>
                <th>DEWA Account</th>
            </tr>
        </thead>
        <tbody>
            @forelse($properties as $property)
                <tr>
                    <!-- Basic Info -->
                    <td class="property-name">{{ $property->name }}</td>
                    <td>{{ $property->type }}</td>
                    <td>{{ $property->class }}</td>
                    <td class="status-{{ strtolower($property->status) }}">{{ $property->status }}</td>

                    <!-- Property Details -->
                    <td>{{ $property->purchase_date ? $property->purchase_date->format('Y-m-d') : 'N/A' }}</td>
                    <td>{{ $property->title_deed_no }}</td>
                    <td>{{ $property->mortgage_status }}</td>

                    <!-- Location -->
                    <td>{{ $property->community }}</td>
                    <td>{{ $property->plot_no }}</td>
                    <td>{{ $property->bldg_no }}</td>
                    <td>{{ $property->bldg_name }}</td>
                    <td>{{ $property->property_no }}</td>
                    <td>{{ $property->floor_detail }}</td>

                    <!-- Area -->
                    <td>{{ number_format($property->suite_area, 2) }}</td>
                    <td>{{ number_format($property->balcony_area, 2) }}</td>
                    <td>{{ number_format($property->area_sq_mter, 2) }}</td>
                    <td>{{ number_format($property->common_area, 2) }}</td>
                    <td>{{ number_format($property->area_sq_feet, 2) }}</td>

                    <!-- Financial -->
                    <td>{{ $property->owner ? $property->owner->name : 'N/A' }}</td>
                    <td>{{ number_format($property->purchase_value, 2) }}</td>
                    <td>{{ $property->dewa_premise_no }}</td>
                    <td>{{ $property->dewa_account_no }}</td>
                </tr>
            @empty
                <tr>
                    <td colspan="22" style="text-align: center;">No properties found</td>
                </tr>
            @endforelse
        </tbody>
    </table>

    <div class="footer">
        &copy; {{ date('Y') }} Property Management System. All rights reserved.
    </div>

    <script type="text/php">
        if (isset($pdf)) {
            $pdf->page_text(765, 550, "Page {PAGE_NUM} of {PAGE_COUNT}", null, 8, array(0, 0, 0));
        }
    </script>
</body>
</html>
