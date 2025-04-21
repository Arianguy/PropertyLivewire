<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Spatie\MediaLibrary\HasMedia;
use Spatie\MediaLibrary\InteractsWithMedia;
use Illuminate\Database\Eloquent\Builder;

class Payment extends Model implements HasMedia
{
    use HasFactory, InteractsWithMedia;

    protected $fillable = [
        'user_id',
        'property_id',
        'contract_id',
        'payment_type_id',
        'amount',
        'paid_at',
        'description',
    ];

    protected $casts = [
        'paid_at' => 'date',
        'amount' => 'decimal:2',
    ];

    public function registerMediaCollections(): void
    {
        $this->addMediaCollection('attachments')
            ->acceptsMimeTypes(['image/jpeg', 'image/png', 'application/pdf', 'application/msword', 'application/vnd.openxmlformats-officedocument.wordprocessingml.document'])
            ->singleFile(); // Assuming one attachment per payment for now, can be changed
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function property(): BelongsTo
    {
        return $this->belongsTo(Property::class);
    }

    public function contract(): BelongsTo
    {
        // Optional relationship
        return $this->belongsTo(Contract::class);
    }

    public function paymentType(): BelongsTo
    {
        return $this->belongsTo(PaymentType::class);
    }

    /**
     * Scope a query to search payments based on description or related data.
     */
    public function scopeSearch(Builder $query, string $term): Builder
    {
        if (empty($term)) {
            return $query;
        }

        return $query->where(function (Builder $query) use ($term) {
            $query->where('description', 'like', "%{$term}%")
                ->orWhere('amount', 'like', "%{$term}%")
                ->orWhereHas('property', function (Builder $subQuery) use ($term) {
                    $subQuery->where('name', 'like', "%{$term}%");
                })
                ->orWhereHas('paymentType', function (Builder $subQuery) use ($term) {
                    $subQuery->where('name', 'like', "%{$term}%");
                })
                ->orWhereHas('user', function (Builder $subQuery) use ($term) {
                    $subQuery->where('name', 'like', "%{$term}%");
                });
        });
    }
}
