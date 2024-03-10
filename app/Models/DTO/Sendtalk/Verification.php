<?php

namespace App\Models\DTO\Sendtalk;

final class Verification
{
    public string   $userPhone;
    public string   $languageCode   = 'id';
    public int      $expiryMinutes  = 10;
    public string   $appURL;
}