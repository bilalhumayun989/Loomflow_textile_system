<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasOne;

class BagTransaction extends Model
{
    use HasFactory;

    protected $fillable = [
        'transaction_type',
        'reference_no',
        'quality',
        'quantity_bags',
        'source',
        'destination',
        'party_name',
        'transaction_date',
        'notes',
    ];

    protected $casts = [
        'transaction_date' => 'date',
        'quantity_bags' => 'integer',
    ];

    public function sizingSlip(): HasOne
    {
        return $this->hasOne(SizingSlip::class);
    }
}
