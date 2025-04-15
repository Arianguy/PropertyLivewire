<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;
use App\Models\Receipt;

class FixCashReceipts extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'receipts:fix-cash';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Fix all CASH receipts to have CLEARED status and no cheque/transfer data';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $this->info('Starting to fix CASH receipts...');

        // Use Query Builder to bypass model accessors/mutators
        $count = DB::table('receipts')
            ->where('payment_type', 'CASH')
            ->update([
                'status' => 'CLEARED',
                'cheque_no' => null,
                'cheque_bank' => null,
                'cheque_date' => null,
                'transaction_reference' => null,
            ]);

        $this->info("Updated {$count} CASH receipts with correct values.");

        // Also clean up the media files for cash receipts
        $cashReceipts = Receipt::where('payment_type', 'CASH')->get();

        $mediaCount = 0;
        foreach ($cashReceipts as $receipt) {
            $mediaCount += count($receipt->media);
            $receipt->clearMediaCollection('cheque_images');
            $receipt->clearMediaCollection('transfer_receipts');
        }

        $this->info("Cleared media for {$cashReceipts->count()} CASH receipts (removed {$mediaCount} media files).");

        $this->info('✓ Done fixing CASH receipts.');

        return 0;
    }
}
