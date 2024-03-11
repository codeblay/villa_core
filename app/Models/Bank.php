<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Bank extends Model
{
    use HasFactory;
    
    protected $guarded = [];

    const STATUS_NONACTIVE  = false;
    const STATUS_ACTIVE     = true;

    function getIsActiveLabelAttribute() : string {
        return $this->is_active ? "aktif" : "tidak aktif";
    }
}
