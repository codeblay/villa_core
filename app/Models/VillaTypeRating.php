<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class VillaTypeRating extends Model
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

    // End Relation
}
