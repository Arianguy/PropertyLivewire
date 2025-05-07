<x-mail::message>
# Tenant Ledger Report

Please find the requested Tenant Ledger report attached.

Report generated on: {{ $reportDate ?? now()->format('d-M-Y') }}

{{-- Optionally add filter summary here if needed from $filterInfo --}}
{{--
Filters Applied:
Status: {{ ucfirst($filterInfo['filterStatus'] ?? 'N/A') }}
Date Range: {{ $filterInfo['filterStartDate'] ? \Carbon\Carbon::parse($filterInfo['filterStartDate'])->format('d-M-Y') : 'N/A' }} - {{ $filterInfo['filterEndDate'] ? \Carbon\Carbon::parse($filterInfo['filterEndDate'])->format('d-M-Y') : 'N/A' }}
Search: {{ !empty($filterInfo['filterSearchTerm']) ? $filterInfo['filterSearchTerm'] : 'N/A' }}
--}}

Thanks,<br>
{{ config('app.name') }}
</x-mail::message>
