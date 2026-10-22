<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class FabricStock extends Model
{
    use HasFactory;

    protected $fillable = [
        'quality_name',
        'construction',
        'width_inches',
        'previous_stock_meters',
        'daily_received_meters',
        'adjustment_meters',
        'dispatch_meters',
        'area',
        'total_meters_produced',
        'total_rolls_in_stock',
        'current_stock_meters',
        'committed_contract_meters',
        'available_open_meters',
        'unit_cost_meter',
        'notes',
    ];

    protected $casts = [
        'width_inches' => 'decimal:2',
        'previous_stock_meters' => 'decimal:2',
        'daily_received_meters' => 'decimal:2',
        'adjustment_meters' => 'decimal:2',
        'dispatch_meters' => 'decimal:2',
        'total_meters_produced' => 'decimal:2',
        'total_rolls_in_stock' => 'integer',
        'current_stock_meters' => 'decimal:2',
        'committed_contract_meters' => 'decimal:2',
        'available_open_meters' => 'decimal:2',
        'unit_cost_meter' => 'decimal:2',
    ];

    public function movements(): HasMany
    {
        return $this->hasMany(FabricStockMovement::class);
    }
}
