<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Property extends Model
{
    use HasFactory;

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
    ];

    /**
     * Get the owner that owns the property.
     */
    public function owner(): BelongsTo
    {
        return $this->belongsTo(Owner::class);
    }
}
