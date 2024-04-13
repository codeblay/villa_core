<?php

namespace App\Repositories;

use Google_Client;
use Illuminate\Http\Client\Response;
use Illuminate\Support\Facades\Http;

final class FirebaseRepository
{
    public string $url;
    public string $project_id;

    public function __construct()
    {
        $this->url          = config('firebase.url');
        $this->project_id   = config('firebase.project_id');
    }

    private static function getToken() : string {
        $config = json_decode(file_get_contents("../firebase.json"), true);
        $c = new Google_Client();
        $c->setAuthConfig($config);
        $c->setScopes("https://www.googleapis.com/auth/firebase.messaging");

        return $c->fetchAccessTokenWithAssertion()["access_token"];
    }

    function send(string $token, string $title, string $body): Response
    {
        $request = Http::acceptJson()
            ->withToken(self::getToken())
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
