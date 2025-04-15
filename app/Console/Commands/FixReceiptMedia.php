<?php

namespace App\Console\Commands;

use App\Models\Receipt;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;

class FixReceiptMedia extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'receipts:fix-media';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Fix any missing media relations for receipts';

    /**
     * Execute the console command.
     *
     * @return int
     */
    public function handle()
    {
        $this->info('Checking for receipts with missing media...');

        // Get all receipts requiring attachments
        $receipts = Receipt::where('payment_type', 'CHEQUE')
            ->orWhere('payment_type', 'ONLINE_TRANSFER')
            ->get();

        $this->info("Found {$receipts->count()} receipts to check.");

        $fixedCount = 0;

        foreach ($receipts as $receipt) {
            $hasMedia = $receipt->media()->exists();

            if (!$hasMedia) {
                // This receipt should have media but doesn't
                $this->warn("Receipt #{$receipt->id} ({$receipt->payment_type}) has no media.");

                // Check if there are orphaned media records in the database
                $mediaRecords = DB::table('media')
                    ->where('model_type', Receipt::class)
                    ->where('model_id', $receipt->id)
                    ->get();

                if ($mediaRecords->count() > 0) {
                    $this->info("Found {$mediaRecords->count()} orphaned media records to reconnect.");

                    foreach ($mediaRecords as $mediaRecord) {
                        // Ensure the file exists
                        $path = $mediaRecord->disk . '/' . $mediaRecord->id . '/' . $mediaRecord->file_name;

                        if (Storage::exists($path)) {
                            $this->info("Media file exists at path: {$path}");
                        } else {
                            $this->error("Media file missing at path: {$path}");
                            continue;
                        }

                        // Update the model_type if it's wrong
                        if ($mediaRecord->model_type !== Receipt::class) {
                            DB::table('media')
                                ->where('id', $mediaRecord->id)
                                ->update(['model_type' => Receipt::class]);

                            $this->info("Updated model_type for media #{$mediaRecord->id}");
                        }

                        $fixedCount++;
                    }
                }
            } else {
                // Has media but check if it's in the right collection
                $correctCollection = $receipt->payment_type === 'CHEQUE'
                    ? 'cheque_images'
                    : 'transfer_receipts';

                $hasCorrectCollection = $receipt->media()
                    ->where('collection_name', $correctCollection)
                    ->exists();

                if (!$hasCorrectCollection) {
                    $this->warn("Receipt #{$receipt->id} has media but in wrong collection.");

                    // Fix the collection name
                    DB::table('media')
                        ->where('model_type', Receipt::class)
                        ->where('model_id', $receipt->id)
                        ->update(['collection_name' => $correctCollection]);

                    $this->info("Updated collection name to {$correctCollection}");
                    $fixedCount++;
                }
            }
        }

        $this->info("Fixed {$fixedCount} media relation issues.");

        return Command::SUCCESS;
    }
}
