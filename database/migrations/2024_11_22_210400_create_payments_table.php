<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('payments', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->onDelete('restrict');
            $table->foreignId('property_id')->constrained()->onDelete('restrict');
            $table->foreignId('contract_id')->nullable()->constrained()->onDelete('restrict');
            $table->foreignId('payment_type_id')->constrained()->onDelete('restrict');
            $table->decimal('amount', 8, 2);
            $table->timestamp('paid_at');
            $table->enum('payment_method', ['cash', 'cheque', 'bank_transfer', 'credit_card']);
            $table->string('reference_number')->nullable();
            $table->text('description')->nullable();
            $table->timestamps();
            $table->softDeletes();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('payments');
    }
};
