<?php

namespace Authmagic\AuthmagicLaravel;

use \Illuminate\Http\Request;
use GuzzleHttp\Client;
use Illuminate\Support\Facades\Cache;
use Symfony\Component\HttpFoundation\Response;
use GuzzleHttp\Exception\ClientException;

class Authmagic
{
    protected $url;

    protected $cacheDuration;

    public function __construct($url, $cacheDuration)
    {
        $this->url = $url;
        $this->cacheDuration = $cacheDuration;
    }

    /**
     * Token status verification.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return mixed
     */
    public function auth(Request $request)
    {
        $authorization = $request->header('Authorization');

        if ($authorization) {
            $tokenArray = explode(' ', $authorization);
            $token = array_pop($tokenArray);

            if (!Cache::get($token)) {
                $client = new Client();

                try {
                    $response = $client->post($this->url, [
                        'headers' => ['Content-Type' => 'application/json'],
                        'body' => json_encode(['token' => $token]),
                    ]);
                } catch (ClientException $exception) {
                    $response = $exception->getResponse();
                }

                if ($response->getStatusCode() === Response::HTTP_OK) {
                    Cache::put($token, true, $this->cacheDuration);
                }
            }

            if (Cache::get($token)) {
                return $token;
            }
        }

        return null;
    }
}