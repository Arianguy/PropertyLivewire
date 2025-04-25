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
        Schema::create('receipts', function (Blueprint $table) {
            $table->id();
            $table->foreignId('contract_id')->constrained()->cascadeOnDelete();
            $table->enum('receipt_category', ['SECURITY_DEPOSIT', 'RENT', 'RETURN_CHEQUE', 'CANCELLED'])->default('RENT');
            $table->enum('payment_type', ['CASH', 'CHEQUE', 'ONLINE_TRANSFER'])->default('CASH');
            $table->decimal('amount', 10, 2);
            $table->date('receipt_date');
            $table->string('narration')->nullable();

            // Cheque specific fields
            $table->string('cheque_no')->nullable();
            $table->string('cheque_bank')->nullable();
            $table->date('cheque_date')->nullable();

            // Online transfer specific field
            $table->string('transaction_reference')->nullable();

            // Status tracking
            $table->enum('status', ['PENDING', 'CLEARED', 'BOUNCED'])->default('PENDING');
            $table->date('deposit_date')->nullable();
            $table->string('deposit_account')->nullable();
            $table->text('remarks')->nullable();

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('receipts');
    }
};
