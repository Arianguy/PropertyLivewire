<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::table('receipts', function (Blueprint $table) {
            $table->foreignId('resolves_receipt_id')->nullable()->after('id')->constrained('receipts')->onDelete('set null');
            // Add an index for performance
            $table->index('resolves_receipt_id');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('receipts', function (Blueprint $table) {
            // Drop index first
            $table->dropIndex(['resolves_receipt_id']);
            // Drop foreign key constraint before dropping column
            $table->dropForeign(['resolves_receipt_id']);
            $table->dropColumn('resolves_receipt_id');
        });
    }
};
