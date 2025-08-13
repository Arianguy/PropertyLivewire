<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        // First, update any invalid receipt_category values to 'RENT'
        DB::statement("UPDATE receipts SET receipt_category = 'RENT' WHERE receipt_category NOT IN ('SECURITY_DEPOSIT', 'RENT', 'RETURN_CHEQUE', 'CANCELLED')");
        
        // Then modify the enum to include 'VAT'
        DB::statement("ALTER TABLE receipts MODIFY COLUMN receipt_category ENUM('SECURITY_DEPOSIT', 'RENT', 'RETURN_CHEQUE', 'CANCELLED', 'VAT') NOT NULL DEFAULT 'RENT'");
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        DB::statement("ALTER TABLE receipts MODIFY COLUMN receipt_category ENUM('SECURITY_DEPOSIT', 'RENT', 'RETURN_CHEQUE', 'CANCELLED') NOT NULL DEFAULT 'RENT'");
    }
};
