<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class SecurityDepositSettlement extends Model
{
    use HasFactory;

    protected $fillable = [
        'contract_id',
        'original_deposit_amount',
        'deduction_amount',
        'deduction_reason',
        'return_amount',
        'return_date',
        'return_payment_type',
        'return_reference',
        'notes',
        'created_by',
    ];

    protected $casts = [
        'return_date' => 'date',
        'original_deposit_amount' => 'decimal:2',
        'deduction_amount' => 'decimal:2',
        'return_amount' => 'decimal:2',
    ];

    public function contract(): BelongsTo
    {
        return $this->belongsTo(Contract::class);
    }

    public function creator(): BelongsTo
    {
        return $this->belongsTo(User::class, 'created_by');
    }
}
