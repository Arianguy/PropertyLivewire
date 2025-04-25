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
        Schema::create('security_deposit_settlements', function (Blueprint $table) {
            $table->id();
            $table->foreignId('contract_id')->constrained()->cascadeOnDelete();
            $table->decimal('original_deposit_amount', 10, 2);
            $table->decimal('deduction_amount', 10, 2)->default(0.00);
            $table->text('deduction_reason')->nullable();
            $table->decimal('return_amount', 10, 2);
            $table->date('return_date');
            $table->enum('return_payment_type', ['CASH', 'CHEQUE', 'ONLINE_TRANSFER']);
            $table->string('return_reference')->nullable(); // Cheque no or transaction ID
            $table->text('notes')->nullable();
            $table->foreignId('created_by')->nullable()->constrained('users')->nullOnDelete();
            $table->timestamps();

            // Ensure a contract can only have one settlement record
            $table->unique('contract_id');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('security_deposit_settlements');
    }
};
