<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Bank extends Model
{
    use HasFactory;
    
    protected $guarded = [];

    const QR    = "qr";
    const BCA   = "bca";
    const BRI   = "bri";
    const BNI   = "bni";

    function getLogoAttribute() : string {
        return asset("image/bank/{$this->code}.png");
    }

    function getIsActiveLabelAttribute() : string {
        return $this->is_active ? "aktif" : "tidak aktif";
    }
}
