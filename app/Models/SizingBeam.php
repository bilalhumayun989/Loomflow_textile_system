<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class SizingBeam extends Model
{
    use HasFactory;

    protected $fillable = [
        'sizing_slip_id',
        'beam_no',
        'yarn_quality',
        'total_length_meters',
        'remaining_length_meters',
        'assigned_loom_no',
        'status',
        'mounted_date',
        'notes',
    ];

    protected $casts = [
        'total_length_meters' => 'decimal:2',
        'remaining_length_meters' => 'decimal:2',
        'mounted_date' => 'date',
    ];

    public function sizingSlip(): BelongsTo
    {
        return $this->belongsTo(SizingSlip::class);
    }

    public function productions(): HasMany
    {
        return $this->hasMany(Production::class);
    }
}
