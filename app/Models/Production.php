<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Production extends Model
{
    use HasFactory;

    protected $fillable = [
        'production_date',
        'shift',
        'loom_no',
        'operator_name',
        'sizing_beam_id',
        'fabric_quality',
        'start_meter',
        'end_meter',
        'net_meters_produced',
        'beam_meters_consumed',
        'leakage_waste_meters',
        'leakage_percentage',
        'picks_per_inch',
        'rpm',
        'efficiency_percentage',
        'remarks',
    ];

    protected $casts = [
        'production_date' => 'date',
        'start_meter' => 'decimal:2',
        'end_meter' => 'decimal:2',
        'net_meters_produced' => 'decimal:2',
        'beam_meters_consumed' => 'decimal:2',
        'leakage_waste_meters' => 'decimal:2',
        'leakage_percentage' => 'decimal:2',
        'picks_per_inch' => 'integer',
        'rpm' => 'integer',
        'efficiency_percentage' => 'decimal:2',
    ];

    public function beam(): BelongsTo
    {
        return $this->belongsTo(SizingBeam::class, 'sizing_beam_id');
    }
}
