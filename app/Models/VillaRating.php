<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class VillaRating extends Model
{
    use HasFactory;

    protected $guarded = [];

    // Relation
    
    function villa() : BelongsTo {
        return $this->belongsTo(Villa::class);
    }
    
    function buyer() : BelongsTo {
        return $this->belongsTo(Buyer::class);
    }
    
    function transaction() : BelongsTo {
        return $this->belongsTo(Transaction::class);
    }

    // End Relation
}
