<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\MorphOne;

class Destination extends Model
{
    use HasFactory;

    protected $guarded = [];
    
    // Relation
    
    function city() : BelongsTo {
        return $this->belongsTo(City::class);
    }

    function category() : BelongsTo {
        return $this->belongsTo(DestinationCategory::class, 'destination_category_id');
    }

    function file() : MorphOne {
        return $this->morphOne(File::class, 'fileable');
    }

    // End Relation

    function getImagePathAttribute() : string {
        return config('filesystems.disks.destination.public_path') . "/" . @$this->file->path;
    }
}
