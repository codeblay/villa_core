<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Database\Eloquent\Relations\MorphMany;
use Illuminate\Database\Eloquent\Relations\MorphOne;

class Villa extends Model
{
    use HasFactory;

    protected $guarded = [];

    const STATUS_DRAFT      = 0;
    const STATUS_PUBLISH    = 1;

    // Relation

    function city() : BelongsTo {
        return $this->belongsTo(City::class);
    }

    function facilities() : BelongsToMany {
        return $this->belongsToMany(Facility::class, 'villa_facilities')->withTimestamps();
    }

    function ratings() : HasMany {
        return $this->hasMany(VillaRating::class);
    }

    function files() : MorphMany {
        return $this->morphMany(File::class, 'fileable');
    }

    function file() : MorphOne {
        return $this->morphOne(File::class, 'fileable');
    }
    
    function primaryImage() : MorphOne {
        return $this->morphOne(File::class, 'fileable')->where('type', File::TYPE_IMAGE);
    }

    function villaTypes() : HasMany {
        return $this->hasMany(VillaType::class);
    }

    function investors() : BelongsToMany {
        return $this->belongsToMany(Seller::class, 'villa_investors', 'villa_id', 'investor_id');
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

    function getPriceAttribute() : int {
        return $this->villaTypes->sortBy('price')->first()->price;
    }

    function getRatingAttribute() : float {
        if ($this->bypass_rating > 0) return $this->bypass_rating;
        return $this->ratings->count() == 0 ? 0 : ceil($this->ratings->sum('rating') / $this->ratings->count());
    }
}
