<?php

namespace App\Models;

use App\Models\DTO\Midtrans\Charge;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Bank extends Model
{
    use HasFactory;
    
    protected $guarded = [];

    const QR        = "qr";
    const BCA       = "bca";
    const BRI       = "bri";
    const BNI       = "bni";
    const MANDIRI   = "mandiri";
    const PERMATA   = "permata";
    const CIMB      = "cimb";

    const BANK_CODE = [
        self::QR,
        self::BCA,
        self::BRI,
        self::BNI,
        self::MANDIRI,
        self::PERMATA,
        self::CIMB,
    ];

    function getLogoAttribute() : string {
        if ($this->code == self::QR && !empty($this->va_number) && file_exists(public_path("{$this->va_number}"))) {
            return asset("{$this->va_number}");
        }

        return asset("image/bank/{$this->code}.png");
    }

    function getIsActiveLabelAttribute() : string {
        return $this->is_active ? "aktif" : "tidak aktif";
    }

    function getMidtransPaymentTypeAttribute() : string {
        return match ($this->code) {
            self::QR        => Charge::PAYMENT_TYPE_QRIS,
            self::MANDIRI   => Charge::PAYMENT_TYPE_ECHANNEL,
            default         => Charge::PAYMENT_TYPE_BANK_TRANSFER,
        };
    }
}
