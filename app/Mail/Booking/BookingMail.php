<?php

namespace App\Mail\Booking;

use App\Mail\Booking\Action\Ticket;
use App\Models\Transaction;
use Illuminate\Mail\SentMessage;
use Illuminate\Support\Facades\Mail;

class BookingMail
{
    static function ticket(Transaction $transaction): ?SentMessage
    {
        return Mail::send(new Ticket($transaction));
    }
}
