<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class SizingSlip extends Model
{
    use HasFactory;

    protected $fillable = [
        'slip_no',
        'sizing_party',
        'yarn_quality',
        'bags_allocated',
        'bags_used',
        'bags_returned_to_factory',
        'bags_held_with_sizing',
        'total_beams',
        'total_length_meters',
        'slip_date',
        'status',
        'notes',
    ];

    protected $casts = [
        'slip_date' => 'date',
        'bags_allocated' => 'integer',
        'bags_used' => 'integer',
        'bags_returned_to_factory' => 'integer',
        'bags_held_with_sizing' => 'integer',
        'total_beams' => 'integer',
        'total_length_meters' => 'decimal:2',
    ];

    public function beams(): HasMany
    {
        return $this->hasMany(SizingBeam::class);
    }
}
