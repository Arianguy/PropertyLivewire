            {{-- Transactions Menu Section --}}
            <x-flux::sidebar.section label="Transactions">
                {{-- ... other transaction links like Receipts, Cheques ... --}}
                <x-flux::sidebar.link :href="route('receipts.index')" label="Receipts" :active="request()->routeIs('receipts.index')" icon="receipt"/>
                <x-flux::sidebar.link :href="route('cheques.index')" label="Cheque Management" :active="request()->routeIs('cheques.index')" icon="cash"/>

                {{-- ... other transaction links ... --}}
            </x-flux::sidebar.section>

            {{-- ... other sections ... --}}
