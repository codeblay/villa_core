<?php

namespace App\Models;

use Carbon\Carbon;
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

    function getFullDateAttribute() : string {
        $start  = Carbon::parse($this->start_date)->translatedFormat('j F Y');
        $end    = Carbon::parse($this->end_date)->translatedFormat('j F Y');

        return "$start - $end";
    }
}
