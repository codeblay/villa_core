<?php

namespace App\Repositories;

use Illuminate\Http\Client\Response;
use Illuminate\Support\Facades\Http;

final class FirebaseRepository
{
    public string $url;
    public string $server_key;
    public string $project_id;

    public function __construct()
    {
        $this->url          = config('firebase.url');
        $this->server_key   = config('firebase.server_key');
        $this->project_id   = config('firebase.project_id');
    }

    function send(string $token, string $title, string $body): Response
    {
        $request = Http::acceptJson()
            ->withToken($this->server_key)
            ->contentType('application/json')
            ->post("{$this->url}/v1/projects/{$this->project_id}/messages:send", [
                "message" => [
                    "token"         => $token,
                    "notification"  => [
                        "title"     => $title,
                        "body"      => $body,
                    ],
                ],
            ]);

        return $request;
    }
}
