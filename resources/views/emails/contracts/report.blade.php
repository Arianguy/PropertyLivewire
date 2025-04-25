<x-mail::message>
# Contract Report

Please find the contract report for **{{ $contractName }}** attached.

Tenant: **{{ $tenantName }}**
Property: **{{ $propertyName }}**

@if($hasSettlement)
Security Deposit Status: Settled on {{ $settlementDate->format('M d, Y') }}
@endif

Thanks,<br>
<hr>
<small>
{{ config('app.name') }} | Sent by: {{ $userName ?? 'N/A' }} | Sent Date: {{ $timestamp ?? 'N/A' }}
</small>
</x-mail::message>
