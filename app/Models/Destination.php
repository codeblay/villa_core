<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Destination extends Model
{
    use HasFactory;
    
    // Relation
    
    function city() : BelongsTo {
        return $this->belongsTo(City::class);
    }

    function category() : BelongsTo {
        return $this->belongsTo(DestinationCategory::class);
    }

    // End Relation
}
