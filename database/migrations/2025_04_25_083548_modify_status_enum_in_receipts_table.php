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
        Schema::table('receipts', function (Blueprint $table) {
            DB::statement("ALTER TABLE receipts MODIFY COLUMN status ENUM('PENDING', 'CLEARED', 'BOUNCED', 'CANCELLED') NOT NULL DEFAULT 'PENDING'");
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('receipts', function (Blueprint $table) {
            DB::statement("ALTER TABLE receipts MODIFY COLUMN status ENUM('PENDING', 'CLEARED', 'BOUNCED') NOT NULL DEFAULT 'PENDING'");
        });
    }
};
