<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Otp extends Model
{
    use HasFactory;

    protected $guarded = [];

    protected $casts = [
        'external_response' => 'object'
    ];

    const STATUS_ACTIVE     = 'Active';
    const STATUS_VALID      = 'Valid';
    const STATUS        = [
        self::STATUS_ACTIVE,
        self::STATUS_VALID,
    ];

    function getIsExpiredAttribute() : bool {
        return $this->expired_at <= now();
    }

    static function generateOtp() : int {
        return rand(10000 , 99999);
    }
}
