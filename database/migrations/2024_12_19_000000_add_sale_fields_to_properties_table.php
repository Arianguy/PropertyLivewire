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
        Schema::table('properties', function (Blueprint $table) {
            $table->date('sale_date')->nullable()->after('status');
            $table->decimal('sale_price', 15, 2)->nullable()->after('sale_date');
            $table->string('buyer_name')->nullable()->after('sale_price');
            $table->text('sale_notes')->nullable()->after('buyer_name');
            $table->boolean('is_archived')->default(false)->after('is_visible');
            $table->timestamp('archived_at')->nullable()->after('is_archived');
            $table->unsignedBigInteger('archived_by')->nullable()->after('archived_at');
            
            $table->foreign('archived_by')->references('id')->on('users')->nullOnDelete();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('properties', function (Blueprint $table) {
            $table->dropForeign(['archived_by']);
            $table->dropColumn([
                'sale_date',
                'sale_price',
                'buyer_name', 
                'sale_notes',
                'is_archived',
                'archived_at',
                'archived_by'
            ]);
        });
    }
};