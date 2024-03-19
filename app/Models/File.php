<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\MorphTo;

class File extends Model
{
    use HasFactory;

    protected $guarded = [];

    const TYPE_IMAGE    = 0;
    const TYPE_DOCUMENT = 1;

    public function fileable() : MorphTo{
        return $this->morphTo();
    }
}
