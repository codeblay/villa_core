<?php

namespace App\Interface;

use App\Models\DTO\ServiceResponse;

interface Service
{
    function call(): ServiceResponse;
}
