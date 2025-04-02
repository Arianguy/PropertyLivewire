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
        Schema::create('contracts', function (Blueprint $table) {
            $table->id();
            $table->foreignId('tenant_id')
                ->constrained('tenants')
                ->cascadeOnDelete();
            $table->foreignId('property_id')
                ->constrained('properties')
                ->cascadeOnDelete();
            $table->string('name');
            $table->date('cstart');
            $table->date('cend');
            $table->decimal('amount', 10, 2);
            $table->decimal('sec_amt', 10, 2);
            $table->string('ejari')->default('NO');
            $table->string('validity')->default('NO');
            $table->string('type')->default('fresh');
            $table->foreignId('previous_contract_id')->nullable()->constrained('contracts')->nullOnDelete();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('contracts');
    }
};
