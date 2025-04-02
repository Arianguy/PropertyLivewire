<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Spatie\MediaLibrary\HasMedia;
use Spatie\MediaLibrary\InteractsWithMedia;

class Contract extends Model implements HasMedia
{
    use HasFactory, InteractsWithMedia;

    protected $fillable = [
        'name',
        'tenant_id',
        'property_id',
        'cstart',
        'cend',
        'amount',
        'sec_amt',
        'ejari',
        'validity',
        'type',
        'previous_contract_id'
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
    public function previousContract()
    {
        return $this->belongsTo(Contract::class, 'previous_contract_id');
    }

    /**
     * Get the renewals for the contract.
     */
    public function renewals()
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

        if ($this->previousContract) {
            $allAncestors->push($this->previousContract);
            $allAncestors = $allAncestors->merge($this->previousContract->allAncestors());
        }

        return $allAncestors;
    }

    /**
     * Register the media collections.
     */
    public function registerMediaCollections(): void
    {
        $this->addMediaCollection('contracts_copy')
            ->useDisk('private')
            ->acceptsFile(function ($file) {
                return in_array($file->mimeType, ['application/pdf', 'image/jpeg', 'image/png']);
            });
    }
}
