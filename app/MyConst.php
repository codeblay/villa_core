<?php

namespace App;

class MyConst
{
    const DOCUMENT_VERIFICATION_NAME = 'verification.pdf';
    const BANNER_NAME = 'banner.jpg';

    const USER_SELLER   = 'investor';
    const USER_BUYER    = 'buyer';
    
    const GENDER_MALE   = 'M';
    const GENDER_FEMALE = 'F';
    const GENDER        = [
        self::GENDER_MALE,
        self::GENDER_FEMALE,
    ];
}
