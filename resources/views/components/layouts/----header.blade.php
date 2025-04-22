                        {{-- Transactions Dropdown Items --}}
                        <x-dropdown.item :href="route('receipts.index')" label="Receipts" />
                        <x-dropdown.item :href="route('cheques.index')" label="Cheque Management" />

                        {{-- ... other transaction dropdown items ... --}}
