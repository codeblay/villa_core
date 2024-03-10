<?php

namespace App\Repositories;

use App\Models\DTO\Sendtalk\Message;
use App\Models\DTO\Sendtalk\Verification;
use Illuminate\Http\Client\Response;
use Illuminate\Support\Facades\Http;

final class SendtalkRepository
{

    public string $url;
    public string $key;
    public string $server_key;
    
    public function __construct() {
        $this->url  = config('sendtalk.url');
        $this->key  = config('sendtalk.key');
    }

    function sendMessage(Message $body) : Response {
        $request = Http::acceptJson()
            ->contentType('application/json')
            ->withHeader('API-Key', $this->key)
            ->post("{$this->url}/api/v1/message/send_whatsapp", (array)$body);

        return $request;
    }

    function sendVerification(Verification $body) : Response {
        $request = Http::acceptJson()
            ->contentType('application/json')
            ->withHeader('API-Key', $this->key)
            ->post("{$this->url}/api/v1/verification/create_whatsapp_verification", (array)$body);

        return $request;
    }
}
