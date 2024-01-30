<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Villa extends Model
{
    use HasFactory;

    // Relation
    
    function seller() : BelongsTo {
        return $this->belongsTo(Seller::class);
    }
    
    function city() : BelongsTo {
        return $this->belongsTo(City::class);
    }

    function facilities() : BelongsToMany {
        return $this->belongsToMany(Facility::class);
    }

    function ratings() : HasMany {
        return $this->hasMany(VillaRating::class);
    }

    // End Relation
}
