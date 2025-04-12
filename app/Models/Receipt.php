<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Spatie\MediaLibrary\HasMedia;
use Spatie\MediaLibrary\InteractsWithMedia;

class Receipt extends Model implements HasMedia
{
    use HasFactory, InteractsWithMedia;

    protected $fillable = [
        'contract_id',
        'receipt_category',
        'payment_type',
        'amount',
        'receipt_date',
        'narration',
        'cheque_no',
        'cheque_bank',
        'cheque_date',
        'transaction_reference',
        'status',
        'deposit_date',
        'deposit_account',
        'remarks',
    ];

    protected $casts = [
        'receipt_date' => 'date',
        'cheque_date' => 'date',
        'deposit_date' => 'date',
        'amount' => 'decimal:2',
    ];

    public function contract()
    {
        return $this->belongsTo(Contract::class);
    }

    public function registerMediaCollections(): void
    {
        $this->addMediaCollection('cheque_images')
            ->useDisk('public')
            ->singleFile();
    }

    public function getChequeImageAttribute()
    {
        return $this->getFirstMediaUrl('cheque_images');
    }
}
