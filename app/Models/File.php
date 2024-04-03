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

    function getLocalPathAttribute() : string {
        return match ($this->fileable_type) {
            Villa::class        => config('filesystems.disks.villa.public_path') . "/" . $this->path,
            Destination::class  => config('filesystems.disks.destination.public_path') . "/" . $this->path,
            default             => config('filesystems.disks.public.public_path') . "/" . $this->path,
        };
    }
}
