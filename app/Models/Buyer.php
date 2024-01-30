<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Foundation\Auth\User as Authenticatable;

class Buyer extends Authenticatable
{
    use HasFactory;

    // Relation
    
    function transactions() : HasMany {
        return $this->hasMany(Transaction::class);
    }
    
    function ratings() : HasMany {
        return $this->hasMany(VillaRating::class);
    }

    // End Relation
}
