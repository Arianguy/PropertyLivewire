<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Spatie\MediaLibrary\HasMedia;
use Spatie\MediaLibrary\InteractsWithMedia;
use Spatie\MediaLibrary\MediaCollections\Models\Media;

class Tenant extends Model implements HasMedia
{
    use HasFactory, InteractsWithMedia;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'name',
        'email',
        'mobile',
        'nationality',
        'passport_no',
        'passport_expiry',
        'visa_expiry',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'passport_expiry' => 'date',
        'visa_expiry' => 'date',
    ];

    /**
     * Register media collections
     */
    public function registerMediaCollections(): void
    {
        $this->addMediaCollection('passport_files')
            ->useDisk('public')
            ->registerMediaConversions(function () {
                $this->addMediaConversion('thumb')
                    ->width(200)
                    ->height(200);
            });

        $this->addMediaCollection('visa_files')
            ->useDisk('public')
            ->registerMediaConversions(function () {
                $this->addMediaConversion('thumb')
                    ->width(200)
                    ->height(200);
            });
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
     * Get the path to store media files
     */
    public function getMediaPath(): string
    {
        return 'tenants/' . $this->id;
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
