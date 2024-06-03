<?php

namespace App\Models;

use App\Models\DTO\Midtrans\Charge;
use App\MyConst;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasOne;

class Transaction extends Model
{
    use HasFactory;

    protected $guarded = [];

    protected $casts = [
        'external_response' => 'object'
    ];

    const STATUS_NEW        = 0;
    const STATUS_PENDING    = 1;
    const STATUS_SUCCESS    = 2;
    const STATUS_FAILED     = 3;
    const STATUS_REJECT     = 4;
    const STATUS_CANCEL     = 5;

    const STATUS_LABEL = [
        self::STATUS_NEW        => 'baru',
        self::STATUS_PENDING    => 'pending',
        self::STATUS_SUCCESS    => 'sukses',
        self::STATUS_FAILED     => 'gagal',
        self::STATUS_REJECT     => 'ditolak',
        self::STATUS_CANCEL     => 'dibatalkan',
    ];

    const STATUS_CLASS = [
        self::STATUS_NEW        => 'secondary',
        self::STATUS_PENDING    => 'info',
        self::STATUS_SUCCESS    => 'success',
        self::STATUS_FAILED     => 'danger',
        self::STATUS_REJECT     => 'warning',
        self::STATUS_CANCEL     => 'dark',
    ];

    // Relation

    function buyer(): BelongsTo
    {
        return $this->belongsTo(Buyer::class);
    }

    function transactionDetail(): HasOne
    {
        return $this->hasOne(TransactionDetail::class);
    }

    function villaType(): BelongsTo
    {
        return $this->belongsTo(VillaType::class);
    }

    function bank(): BelongsTo
    {
        return $this->belongsTo(Bank::class);
    }

    function villaRating(): HasOne
    {
        return $this->hasOne(VillaRating::class);
    }

    // End Relation

    function getStatusLabelAttribute(): string
    {
        return self::STATUS_LABEL[$this->status];
    }

    function getStatusClassAttribute(): string
    {
        return self::STATUS_CLASS[$this->status];
    }

    function getCanAcceptAttribute(): bool
    {
        return $this->status == self::STATUS_NEW;
    }

    function getCanDenyAttribute(): bool
    {
        return $this->status == self::STATUS_NEW;
    }

    function getCanCancelAttribute(): bool
    {
        return in_array($this->status, [
            self::STATUS_NEW,
            self::STATUS_PENDING,
        ]);
    }

    function getCanSyncAttribute(): bool
    {
        return $this->status == self::STATUS_PENDING;
    }

    function getExternalResponseParseAttribute(): ?array
    {
        $result = null;

        if ($this->status != self::STATUS_PENDING) return $result;

        switch ($this->bank->midtrans_payment_type) {
            case Charge::PAYMENT_TYPE_QRIS:
                $result = [
                    'payment'   => $this->bank->name,
                    'value'     => $this->external_response->actions[0]->url,
                ];
                break;

            case Charge::PAYMENT_TYPE_ECHANNEL:
                $result = [
                    'payment'   => $this->bank->name,
                    'value'     => $this->external_response->bill_key,
                ];
                break;

            case Charge::PAYMENT_TYPE_BANK_TRANSFER:
                $result = [
                    'payment'   => $this->bank->name,
                    'value'     => $this->external_response->va_numbers[0]->va_number,
                ];
                break;

            default:
                $result = null;
                break;
        }

        if ($this->is_manual == !$result) {
            if ($this->bank->code == Bank::QR) {
                $result['value'] = asset($this->bank->va_number);
            } else {
                $result['value'] = $this->bank->va_number;
            }
        }

        return $result;
    }
}
