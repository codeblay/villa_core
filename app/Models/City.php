<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class City extends Model
{
    use HasFactory;

    protected $guarded = [];

    // Relation
    
    function province() : BelongsTo {
        return $this->belongsTo(Province::class);
    }
    
    function destinations() : HasMany {
        return $this->hasMany(Destination::class);
    }
    
    function villas() : HasMany {
        return $this->hasMany(Villa::class);
    }

    function getAddressAttribute() : string {
        $address = [
            $this->name,
            $this->province->name,
        ];
        return join(', ', $address);
    }

    // End Relation
}
