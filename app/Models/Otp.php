<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Otp extends Model
{
    use HasFactory;

    protected $guarded = [];

    const STATUS_ACTIVE     = 'Active';
    const STATUS_VALID      = 'Valid';
    const STATUS_EXPIRED    = 'Expired';
    const STATUS        = [
        self::STATUS_ACTIVE,
        self::STATUS_VALID,
        self::STATUS_EXPIRED,
    ];

    function getIsExpiredAttribute() : bool {
        return $this->expired_at <= now();
    }

    static function generateOtp() : int {
        return rand(10000 , 99999);
    }
}
