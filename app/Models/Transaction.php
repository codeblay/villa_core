<?php

namespace App\Models;

use App\Models\DTO\Midtrans\Charge;
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

    // Relation

    function buyer(): BelongsTo
    {
        return $this->belongsTo(Buyer::class);
    }

    function transactionDetail(): HasOne
    {
        return $this->hasOne(TransactionDetail::class);
    }

    function villa(): BelongsTo
    {
        return $this->belongsTo(Villa::class);
    }

    function bank(): BelongsTo
    {
        return $this->belongsTo(Bank::class);
    }

    // End Relation

    function getStatusLabelAttribute(): string
    {
        return match ($this->status) {
            self::STATUS_NEW        => 'baru',
            self::STATUS_PENDING    => 'pending',
            self::STATUS_SUCCESS    => 'sukses',
            self::STATUS_FAILED     => 'gagal',
            self::STATUS_REJECT     => 'ditolak',
        };
    }

    function getCanAcceptAttribute(): string
    {
        return $this->status == self::STATUS_NEW;
    }

    function getCanDenyAttribute(): string
    {
        return $this->status == self::STATUS_NEW;
    }

    function getExternalResponseParseAttribute(): array
    {
        $result = [];

        if ($this->status != self::STATUS_PENDING) return $result;

        switch ($this->bank->midtrans_payment_type) {
            case Charge::PAYMENT_TYPE_QRIS:
                $result = $this->external_response->actions;
                break;

            case Charge::PAYMENT_TYPE_BANK_TRANSFER:
                $result = $this->external_response->va_numbers;
                break;

            default:
                $result = [];
                break;
        }

        return $result;
    }
}
