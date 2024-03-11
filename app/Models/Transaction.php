<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasOne;

class Transaction extends Model
{
    use HasFactory;

    protected $guarded = [];

    protected $casts = [
        'external_response' => 'object'
    ];

    const STATUS_PENDING    = 1;
    const STATUS_SUCCESS    = 2;
    const STATUS_FAILED     = 3;

    // Relation
    
    function buyer() : BelongsTo {
        return $this->belongsTo(Buyer::class);
    }
    
    function transactionDetail() : HasOne {
        return $this->hasOne(TransactionDetail::class);
    }
    
    function villa() : BelongsTo {
        return $this->belongsTo(Villa::class);
    }

    // End Relation
}
