<x-layouts.app>
    @if(isset($payment))
        <livewire:payments.payment-form :payment="$payment" />
    @else
        <livewire:payments.payment-form />
    @endif
</x-layouts.app>
