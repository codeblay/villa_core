<?php

namespace App\Services\Profile;

use App\Models\DTO\ServiceResponse;
use App\Services\Profile\Action\ProfileBuyer;
use App\Services\Profile\Action\ProfileSeller;

final class ProfileService
{
    static function profileSeller(): ServiceResponse
    {
        return (new ProfileSeller())->call();
    }

    static function profileBuyer(): ServiceResponse
    {
        return (new ProfileBuyer())->call();
    }
}
