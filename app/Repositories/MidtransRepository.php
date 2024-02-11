<?php

namespace App\Repositories;

use App\Models\DTO\Midtrans\Charge;
use Illuminate\Http\Client\Response;
use Illuminate\Support\Facades\Http;

final class MidtransRepository
{

    public string $url;
    public string $client_key;
    public string $server_key;
    
    public function __construct() {
        $this->url          = config('midtrans.url');
        $this->client_key   = config('midtrans.client_key');
        $this->server_key   = config('midtrans.server_key');
    }

    function charge(Charge $body) : Response {
        $request = Http::acceptJson()
            ->contentType('application/json')
            ->withBasicAuth($this->server_key, '')
            ->post("{$this->url}/v2/charge", (array)$body);

        return $request;
    }
}
