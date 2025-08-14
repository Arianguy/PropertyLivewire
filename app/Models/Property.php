<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Spatie\MediaLibrary\HasMedia;
use Spatie\MediaLibrary\InteractsWithMedia;
use Spatie\MediaLibrary\MediaCollections\Models\Media;
use App\Models\User;

class Property extends Model implements HasMedia
{
    use HasFactory, InteractsWithMedia;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'name',
        'class',
        'type',
        'purchase_date',
        'title_deed_no',
        'mortgage_status',
        'community',
        'plot_no',
        'bldg_no',
        'bldg_name',
        'property_no',
        'floor_detail',
        'suite_area',
        'balcony_area',
        'area_sq_mter',
        'common_area',
        'area_sq_feet',
        'owner_id',
        'purchase_value',
        'dewa_premise_no',
        'dewa_account_no',
        'status',
        'salesdeed',
        'is_visible',
        'sale_date',
        'sale_price',
        'buyer_name',
        'sale_notes',
        'is_archived',
        'archived_at',
        'archived_by',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'purchase_date' => 'date',
        'suite_area' => 'decimal:2',
        'balcony_area' => 'decimal:2',
        'area_sq_mter' => 'decimal:2',
        'common_area' => 'decimal:2',
        'area_sq_feet' => 'decimal:2',
        'purchase_value' => 'integer',
        'dewa_premise_no' => 'integer',
        'dewa_account_no' => 'integer',
        'is_visible' => 'boolean',
        'sale_date' => 'date',
        'sale_price' => 'decimal:2',
        'is_archived' => 'boolean',
        'archived_at' => 'datetime',
    ];

    /**
     * Get the owner that owns the property.
     */
    public function owner(): BelongsTo
    {
        return $this->belongsTo(Owner::class);
    }

    /**
     * Get the contracts for the property.
     */
    public function contracts(): HasMany
    {
        return $this->hasMany(Contract::class);
    }

    /**
     * Get the user who archived the property.
     */
    public function archivedBy(): BelongsTo
    {
        return $this->belongsTo(User::class, 'archived_by');
    }

    /**
     * Scope for active (non-archived) properties.
     */
    public function scopeActive($query)
    {
        return $query->where('is_archived', false);
    }

    /**
     * Scope for archived properties.
     */
    public function scopeArchived($query)
    {
        return $query->where('is_archived', true);
    }

    /**
     * Register media collections
     */
    public function registerMediaCollections(): void
    {
        $this->addMediaCollection('deeds')
            ->useDisk('public');
    }

    /**
     * Register media conversions
     */
    public function registerMediaConversions(Media $media = null): void
    {
        $this->addMediaConversion('thumb')
            ->width(200)
            ->height(200)
            ->sharpen(10);

        $this->addMediaConversion('preview')
            ->width(400)
            ->height(400);
    }

    /**
     * Get secure URL for media
     *
     * @param Media $media
     * @param string|null $conversion
     * @return string
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
     *
     * @param Media $media
     * @return string
     */
    public function getSecureDownloadUrl($media): string
    {
        return route('media.download', ['id' => $media->id]);
    }
}
