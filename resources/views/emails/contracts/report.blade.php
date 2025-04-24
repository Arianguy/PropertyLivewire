<x-mail::message>
# Contract Report

Please find the contract report for **{{ $contractName }}** attached.

Tenant: **{{ $tenantName }}**
Property: **{{ $propertyName }}**

Thanks,<br>
<hr>
<small>
{{ config('app.name') }} | Sent by: {{ $userName ?? 'N/A' }} | Sent Date: {{ $timestamp ?? 'N/A' }}
</small>
</x-mail::message>
