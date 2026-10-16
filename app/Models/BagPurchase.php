<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class BagPurchase extends Model
{
    use HasFactory;

    protected $fillable = [
        'challan_no',
        'supplier_name',
        'quality',
        'total_bags',
        'bags_to_sizing',
        'bags_to_factory',
        'rate_per_bag',
        'total_amount',
        'purchase_date',
        'notes',
    ];

    protected $casts = [
        'purchase_date' => 'date',
        'rate_per_bag' => 'decimal:2',
        'total_amount' => 'decimal:2',
        'total_bags' => 'integer',
        'bags_to_sizing' => 'integer',
        'bags_to_factory' => 'integer',
    ];
}
