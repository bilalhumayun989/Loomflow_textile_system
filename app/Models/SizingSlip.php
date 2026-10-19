<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
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
        'set_length_value',
        'set_length_unit',
        'total_ends',
        'reed_count',
        'fabric_width_inches',
        'selvedge_ends',
        'sizing_weight_pounds',
        'bag_transaction_id',
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
        'set_length_value' => 'decimal:2',
        'total_ends' => 'integer',
        'reed_count' => 'integer',
        'fabric_width_inches' => 'decimal:2',
        'selvedge_ends' => 'integer',
        'sizing_weight_pounds' => 'decimal:2',
    ];

    public function beams(): HasMany
    {
        return $this->hasMany(SizingBeam::class);
    }

    public function bagTransaction(): BelongsTo
    {
        return $this->belongsTo(BagTransaction::class);
    }
}
