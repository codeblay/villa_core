<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Transaction extends Model
{
    use HasFactory;

    // Relation
    
    function buyer() : BelongsTo {
        return $this->belongsTo(Buyer::class);
    }
    
    function villa() : BelongsTo {
        return $this->belongsTo(Villa::class);
    }

    // End Relation
}
