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

class VillaType extends Model
{
    use HasFactory;

    protected $guarded = [];

    const STATUS_DRAFT      = 0;
    const STATUS_PUBLISH    = 1;

    // Relation

    function villa() : BelongsTo {
        return $this->belongsTo(Villa::class);
    }

    function facilities() : BelongsToMany {
        return $this->belongsToMany(Facility::class, 'villa_type_facilities')->withTimestamps();
    }

    function ratings() : HasMany {
        return $this->hasMany(VillaTypeRating::class);
    }

    function schedule() : HasOne {
        return $this->hasOne(VillaSchedule::class);
    }

    function files() : MorphMany {
        return $this->morphMany(File::class, 'fileable');
    }
    
    function primaryImage() : MorphOne {
        return $this->morphOne(File::class, 'fileable')->where('type', File::TYPE_IMAGE);
    }

    // End Relation

    function getRatingAttribute() : float {
        if ($this->bypass_rating > 0) return $this->bypass_rating;
        return $this->ratings->count() == 0 ? 0 : ceil($this->ratings->sum('rating') / $this->ratings->count());
    }

    function getFullNameAttribute() : string {
        return "{$this->villa->name} ({$this->name})";
    }
}
