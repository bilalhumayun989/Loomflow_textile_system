<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class SalesContract extends Model
{
    use HasFactory;

    protected $fillable = [
        'contract_no',
        'buyer_name',
        'buyer_phone',
        'fabric_quality',
        'total_meters',
        'rate_per_meter',
        'total_amount',
        'delivered_meters',
        'remaining_meters',
        'start_date',
        'end_date',
        'status',
        'notes',
    ];

    protected $casts = [
        'total_meters' => 'decimal:2',
        'rate_per_meter' => 'decimal:2',
        'total_amount' => 'decimal:2',
        'delivered_meters' => 'decimal:2',
        'remaining_meters' => 'decimal:2',
        'start_date' => 'date',
        'end_date' => 'date',
    ];

    public function sales(): HasMany
    {
        return $this->hasMany(Sale::class);
    }
}
