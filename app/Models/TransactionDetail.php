<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class TransactionDetail extends Model
{
    use HasFactory;

    protected $guarded = [];

    // Relation

    function transaction() : BelongsTo {
        return $this->belongsTo(Transaction::class);
    }

    // End Relation
}
