<?php

namespace App\Models\External;

final class Midtrans
{
    const STATUS_SETTLEMENT = 'settlement';
    const STATUS_CANCEL     = 'cancel';
    const STATUS_EXPIRE     = 'expire';
    const STATUS_FAILURE    = 'failure';
    const STATUS_PENDING    = 'pending';
}
