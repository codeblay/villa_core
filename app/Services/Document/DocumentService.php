<?php

namespace App\Services\Document;

use App\Models\DTO\ServiceResponse;
use App\Services\Document\Action\Upload;
use Illuminate\Http\Request;

class DocumentService
{
    static function upload(Request $request): ServiceResponse
    {
        return (new Upload($request))->call();
    }
}
