<?php

namespace App\Models;

use App\Models\DTO\Midtrans\Charge;
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

    const BANK_CODE = [
        self::QR,
        self::BCA,
        self::BRI,
        self::BNI,
    ];

    function getLogoAttribute() : string {
        return asset("image/bank/{$this->code}.png");
    }

    function getIsActiveLabelAttribute() : string {
        return $this->is_active ? "aktif" : "tidak aktif";
    }

    function getMidtransPaymentTypeAttribute() : string {
        return match ($this->code) {
            self::QR    => Charge::PAYMENT_TYPE_QRIS,
            default     => Charge::PAYMENT_TYPE_BANK_TRANSFER,
        };
    }
}
