<?php

use App\MyConst;

return [
    'method' => env('PAYMENT_METHOD', MyConst::PAYMENT_MANUAL),
];
