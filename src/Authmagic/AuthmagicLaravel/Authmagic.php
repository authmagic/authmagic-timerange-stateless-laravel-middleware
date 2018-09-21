<?php

namespace AuthMagic\AuthmagicLaravel;

use \Illuminate\Http\Request;
use GuzzleHttp\Client;
use Illuminate\Support\Facades\Cache;
use Symfony\Component\HttpFoundation\Response;

class Authmagic
{
    protected $url;

    protected $cacheDuration;

    public function __construct($url, $cacheDuration)
    {
        $this->url = $url;
        $this->cacheDuration = $cacheDuration;
    }

    public function auth(Request $request)
    {
        $authorization = $request->header('Authorization');

        if ($authorization) {
            $token = array_pop(explode(' ', $authorization));

            if (!Cache::get($token)) {
                $client = new Client();
                $response = $client->post($this->url, [
                    'headers' => ['Content-Type' => 'application/json'],
                    'form_params' => [
                        'token' => $token,
                    ],
                ]);

                if ($response->getStatusCode() === Response::HTTP_OK) {
                    Cache::put($token, $this->cacheDuration);
                }
            }

            if (Cache::get($token)) {
                return true;
            }
        }

        return false;
    }
}