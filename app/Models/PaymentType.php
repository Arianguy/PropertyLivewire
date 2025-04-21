<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Support\Str;

class PaymentType extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'slug',
        'description',
    ];

    // Automatically generate slug from name if not provided
    protected static function boot(): void
    {
        parent::boot();

        static::creating(function ($paymentType) {
            if (empty($paymentType->slug)) {
                $paymentType->slug = Str::slug($paymentType->name);
            }
        });

        static::updating(function ($paymentType) {
            if ($paymentType->isDirty('name') && empty($paymentType->slug)) {
                $paymentType->slug = Str::slug($paymentType->name);
            }
        });
    }

    public function payments(): HasMany
    {
        return $this->hasMany(Payment::class);
    }
}
