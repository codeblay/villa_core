<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class VillaInvestor extends Model
{
    use HasFactory;

    protected $guarded = [];

    // Relation

    function villa() : BelongsTo {
        return $this->belongsTo(Villa::class);
    }

    function investor() : BelongsTo {
        return $this->belongsTo(Seller::class);
    }

    // End Relation
}
