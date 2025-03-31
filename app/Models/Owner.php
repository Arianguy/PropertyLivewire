<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Spatie\MediaLibrary\HasMedia;
use Spatie\MediaLibrary\InteractsWithMedia;
use Spatie\MediaLibrary\MediaCollections\Models\Media;

class Owner extends Model implements HasMedia
{
    use HasFactory, InteractsWithMedia;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'name',
        'eid',
        'eidexp',
        'nationality',
        'email',
        'mobile',
        'nakheelno',
        'address',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'eidexp' => 'date',
    ];

    /**
     * Get the properties that belong to the owner.
     */
    public function properties(): HasMany
    {
        return $this->hasMany(Property::class);
    }

    public function registerMediaCollections(): void
    {
        $this->addMediaCollection('passports')
            ->useDisk('public');

        $this->addMediaCollection('eids')
            ->useDisk('public');
    }

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
