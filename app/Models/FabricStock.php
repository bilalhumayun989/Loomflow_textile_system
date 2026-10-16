<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class FabricStock extends Model
{
    use HasFactory;

    protected $fillable = [
        'quality_name',
        'construction',
        'width_inches',
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
        'total_meters_produced' => 'decimal:2',
        'total_rolls_in_stock' => 'integer',
        'current_stock_meters' => 'decimal:2',
        'committed_contract_meters' => 'decimal:2',
        'available_open_meters' => 'decimal:2',
        'unit_cost_meter' => 'decimal:2',
    ];
}
