<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class VillaSchedule extends Model
{
    use HasFactory;

    protected $table = 'villa_type_schedules';
    protected $guarded = [];

    
    // Relation
    
    function villaType() : BelongsTo {
        return $this->belongsTo(VillaType::class);
    }

    // End Relation
}
