<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Spatie\MediaLibrary\HasMedia;
use Spatie\MediaLibrary\InteractsWithMedia;
use Spatie\MediaLibrary\MediaCollections\Models\Media;

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

        $this->addMediaCollection('transfer_receipts')
            ->useDisk('public')
            ->singleFile();
    }

    public function registerMediaConversions(\Spatie\MediaLibrary\MediaCollections\Models\Media $media = null): void
    {
        $this->addMediaConversion('thumb')
            ->width(200)
            ->height(200)
            ->sharpen(10);
    }

    public function getChequeImageAttribute()
    {
        return $this->getFirstMediaUrl('cheque_images');
    }

    public function getTransferReceiptAttribute()
    {
        return $this->getFirstMediaUrl('transfer_receipts');
    }

    public function getStatusAttribute($value)
    {
        // If payment type is CASH, always return CLEARED
        if ($this->payment_type === 'CASH') {
            return 'CLEARED';
        }
        return $value ?? 'PENDING';
    }

    public function setStatusAttribute($value)
    {
        // If payment type is CASH, always set status to CLEARED
        if ($this->payment_type === 'CASH') {
            $this->attributes['status'] = 'CLEARED';
        } else {
            $this->attributes['status'] = $value;
        }
    }

    public function getRawStatus()
    {
        // For cash payments, return CLEARED
        if ($this->payment_type === 'CASH') {
            return 'CLEARED';
        }
        return $this->getAttributes()['status'] ?? 'PENDING';
    }

    public function hasAttachment()
    {
        if ($this->payment_type === 'CHEQUE') {
            return count($this->getMedia('cheque_images')) > 0;
        } elseif ($this->payment_type === 'ONLINE_TRANSFER') {
            return count($this->getMedia('transfer_receipts')) > 0;
        }
        return false;
    }

    /**
     * Get all media for debugging purposes
     */
    public function getAllMedia()
    {
        return $this->media()->get();
    }

    /**
     * Get cheque image media
     */
    public function getChequeMedia()
    {
        return $this->getMedia('cheque_images');
    }

    /**
     * Get transfer receipt media
     */
    public function getTransferMedia()
    {
        return $this->getMedia('transfer_receipts');
    }

    public function getAttachmentUrl()
    {
        if ($this->payment_type === 'CHEQUE') {
            return $this->getFirstMediaUrl('cheque_images');
        } elseif ($this->payment_type === 'ONLINE_TRANSFER') {
            return $this->getFirstMediaUrl('transfer_receipts');
        }
        return null;
    }

    public function getAttachmentName()
    {
        if ($this->payment_type === 'CHEQUE') {
            $media = $this->getFirstMedia('cheque_images');
            return $media ? $media->name : 'Cheque Image';
        } elseif ($this->payment_type === 'ONLINE_TRANSFER') {
            $media = $this->getFirstMedia('transfer_receipts');
            return $media ? $media->name : 'Transfer Receipt';
        }
        return null;
    }

    /**
     * Check if the receipt has a cheque image
     */
    public function hasChequeImage()
    {
        return count($this->getMedia('cheque_images')) > 0;
    }

    /**
     * Check if the receipt has a transfer receipt image
     */
    public function hasTransferReceiptImage()
    {
        return count($this->getMedia('transfer_receipts')) > 0;
    }

    /**
     * Get the URL for the cheque image
     */
    public function getChequeImageUrl()
    {
        return $this->getFirstMediaUrl('cheque_images');
    }

    /**
     * Get the URL for the transfer receipt image
     */
    public function getTransferReceiptImageUrl()
    {
        return $this->getFirstMediaUrl('transfer_receipts');
    }

    /**
     * Get the file path for the cheque image for download
     */
    public function getChequeImagePath()
    {
        $media = $this->getFirstMedia('cheque_images');
        return $media ? $media->getPath() : null;
    }

    /**
     * Get the file path for the transfer receipt image for download
     */
    public function getTransferReceiptImagePath()
    {
        $media = $this->getFirstMedia('transfer_receipts');
        return $media ? $media->getPath() : null;
    }
}
