<?php

namespace App\Models\DTO\Sendtalk;

final class Message
{
    const TYPE_OTP      = 'otp';
    const TYPE_TEXT     = 'text';
    const TYPE_IMAGE    = 'image';

    public string $phone;
    public string $messageType;
    public string $body;
}