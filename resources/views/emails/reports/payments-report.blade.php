@component('mail::message')
# Payments Report

Hello {{ $generatedBy }},

Your requested Payments Report, generated on {{ $generatedAt }}, is attached to this email.

**Report Summary:**
- **Total Payments:** {{ $totalPaymentsCount }}
- **Total Amount:** {{ number_format($grandTotalAmount, 2) }}

**Filters Applied:**
- Search Term: {{ $search ?: 'N/A' }}
- Property: {{ $propertyName ?: 'N/A' }}
- Payment Type: {{ $paymentTypeName ?: 'N/A' }}
- Paid Date From: {{ $startDate ?: 'N/A' }}
- Paid Date To: {{ $endDate ?: 'N/A' }}

Please find the detailed report in the PDF attachment ({{ $pdfFilename }}).

@component('mail::button', ['url' => $url])
View Payments Online
@endcomponent

Thanks,<br>
{{ config('app.name') }}
@endcomponent
