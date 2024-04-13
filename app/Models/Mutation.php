<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Mutation extends Model
{
    use HasFactory;
    
    protected $guarded = [];

    const TYPE_RENT         = 0;
    const TYPE_WITHDRAW     = 1;
    const TYPE_COMMISION    = 2;

    const TYPE_LIST = [
        self::TYPE_RENT,
        self::TYPE_WITHDRAW,
        self::TYPE_COMMISION,
    ];

    function seller() : BelongsTo {
        return $this->belongsTo(Seller::class);
    }

    function transaction() : BelongsTo {
        return $this->belongsTo(Transaction::class);
    }

    function getTypeLabelAttribute() : string {
        return match ($this->type) {
            self::TYPE_RENT         => "Transaksi",
            self::TYPE_WITHDRAW     => "Pencairan",
            self::TYPE_COMMISION    => "Komisi",
        };
    }
}
