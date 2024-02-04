<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

class Facility extends Model
{
    use HasFactory;

    protected $guarded = [];

    // Relation
    
    function villas() : BelongsToMany {
        return $this->belongsToMany(Villa::class, 'villa_facilities');
    }

    // End Relation
}
