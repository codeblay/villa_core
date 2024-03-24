<?php

namespace App;

class MyConst
{
    const DOCUMENT_VERIFICATION_NAME = 'verification.pdf';

    const USER_SELLER   = 'seller';
    const USER_BUYER    = 'buyer';
    
    const GENDER_MALE   = 'M';
    const GENDER_FEMALE = 'F';
    const GENDER        = [
        self::GENDER_MALE,
        self::GENDER_FEMALE,
    ];
}
