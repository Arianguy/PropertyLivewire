<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Spatie\MediaLibrary\HasMedia;
use Spatie\MediaLibrary\InteractsWithMedia;
use Spatie\MediaLibrary\MediaCollections\Models\Media;

class Contract extends Model implements HasMedia
{
    use HasFactory, InteractsWithMedia;

    protected $fillable = [
        'name',
        'tenant_id',
        'property_id',
        'cstart',
        'cend',
        'close_date',
        'amount',
        'vat_rate',
        'vat_amount',
        'vat_applicable',
        'sec_amt',
        'ejari',
        'validity',
        'type',
        'previous_contract_id',
        'termination_reason'
    ];

    protected $casts = [
        'cstart' => 'date',
        'cend' => 'date',
        'close_date' => 'date',
        'amount' => 'decimal:2',
        'vat_rate' => 'decimal:2',
        'vat_amount' => 'decimal:2',
        'vat_applicable' => 'boolean',
        'sec_amt' => 'decimal:2',
    ];

    /**
     * Get the tenant that owns the contract.
     */
    public function tenant(): BelongsTo
    {
        return $this->belongsTo(Tenant::class);
    }

    /**
     * Get the property that owns the contract.
     */
    public function property(): BelongsTo
    {
        return $this->belongsTo(Property::class);
    }

    /**
     * Get the previous contract for the renewal.
     */
    public function previousContract(): BelongsTo
    {
        return $this->belongsTo(Contract::class, 'previous_contract_id');
    }

    /**
     * Get the previous contracts for the tenant.
     */
    public function previousContracts()
    {
        return $this->belongsTo(Contract::class, 'tenant_id', 'tenant_id')
            ->where('id', '<', $this->id)
            ->orderBy('id', 'desc');
    }

    /**
     * Get the renewals for the contract.
     */
    public function renewals(): HasMany
    {
        return $this->hasMany(Contract::class, 'previous_contract_id');
    }

    /**
     * Recursively retrieve all renewals associated with the contract.
     *
     * @return \Illuminate\Support\Collection
     */
    public function allRenewals()
    {
        $allRenewals = collect();

        foreach ($this->renewals as $renewal) {
            $allRenewals->push($renewal);
            $allRenewals = $allRenewals->merge($renewal->allRenewals());
        }

        return $allRenewals;
    }

    /**
     * Recursively retrieve all previous contracts (ancestors).
     *
     * @return \Illuminate\Support\Collection
     */
    public function allAncestors()
    {
        $allAncestors = collect();

        $current = $this->previousContract;
        while ($current) {
            $allAncestors->push($current);
            $current = $current->previousContract;
        }

        return $allAncestors;
    }

    /**
     * Check if VAT is applicable based on property type.
     *
     * @return bool
     */
    public function isVatApplicable()
    {
        return $this->property && strtolower($this->property->type) === 'commercial';
    }

    /**
     * Get the VAT rate for this contract.
     *
     * @return float
     */
    public function getVatRate()
    {
        return $this->isVatApplicable() ? 5.00 : 0.00;
    }

    /**
     * Calculate VAT amount based on the contract amount.
     *
     * @return float
     */
    public function calculateVatAmount()
    {
        return $this->amount * ($this->getVatRate() / 100);
    }

    /**
     * Get total amount including VAT.
     *
     * @return float
     */
    public function getTotalAmountWithVat()
    {
        return $this->amount + $this->calculateVatAmount();
    }

    /**
     * Register media collections
     */
    public function registerMediaCollections(): void
    {
        $this->addMediaCollection('contracts_copy')
            ->useDisk('public')
            ->acceptsMimeTypes(['application/pdf', 'image/jpeg', 'image/png'])
            ->withResponsiveImages();
    }

    /**
     * Register media conversions
     */
    public function registerMediaConversions(Media $media = null): void
    {
        $this->addMediaConversion('thumb')
            ->width(200)
            ->height(200)
            ->nonQueued();

        $this->addMediaConversion('preview')
            ->width(800)
            ->height(800)
            ->nonQueued();
    }

    /**
     * Get secure URL for media
     */
    public function getSecureMediaUrl($media, ?string $conversion = null): string
    {
        if ($conversion && $media->hasGeneratedConversion($conversion)) {
            return route('media.thumbnail', ['id' => $media->id, 'conversion' => $conversion]);
        }

        return route('media.show', ['id' => $media->id]);
    }

    /**
     * Get secure download URL for media
     */
    public function getSecureDownloadUrl($media): string
    {
        return route('media.download', ['id' => $media->id]);
    }

    public function getContractCopyUrl(): string
    {
        $media = $this->getFirstMedia('contracts_copy');
        return $media ? route('media.show', $media->id) : '';
    }

    public function getContractCopyDownloadUrl(): string
    {
        $media = $this->getFirstMedia('contracts_copy');
        return $media ? route('media.download', $media->id) : '';
    }

    /**
     * Get the receipts for the contract.
     */
    public function receipts(): HasMany
    {
        return $this->hasMany(Receipt::class);
    }

    // Relationship to Parent Contract (for renewals)
    public function parent(): BelongsTo
    {
        return $this->belongsTo(Contract::class, 'previous_contract_id');
    }

    // Add the settlement relationship
    public function settlement(): HasOne
    {
        return $this->hasOne(SecurityDepositSettlement::class);
    }
}
