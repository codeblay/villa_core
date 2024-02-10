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

    protected $guarded = [];

    const STATUS_DRAFT      = 0;
    const STATUS_PUBLISH    = 1;

    // Relation
    
    function seller() : BelongsTo {
        return $this->belongsTo(Seller::class);
    }
    
    function city() : BelongsTo {
        return $this->belongsTo(City::class);
    }

    function facilities() : BelongsToMany {
        return $this->belongsToMany(Facility::class, 'villa_facilities');
    }

    function ratings() : HasMany {
        return $this->hasMany(VillaRating::class);
    }

    // End Relation

    function getAddressAttribute() : string {
        $address = [
            $this->city->name,
            $this->city->province->name,
        ];
        return join(', ', $address);
    }

    function getPublishLabelAttribute() : string {
        return match ($this->is_publish) {
            0 => 'Draft',
            1 => 'Publish',
        };
    }
}
