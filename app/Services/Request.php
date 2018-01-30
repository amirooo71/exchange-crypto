<?php

namespace App\Services;

use GuzzleHttp\Client;

class Request
{

    const BASE_URL = 'https://api.coinmarketcap.com/v1';

    /**
     * @param $url
     * @return mixed|\Psr\Http\Message\ResponseInterface
     */
    public function get($url)
    {
        $data = (new Client())->request('GET', self::BASE_URL . $url);
        return $this->decoders($data->getBody());
    }

    /**
     * @param $data
     * @return mixed
     */
    private function decoders($data)
    {
        return \GuzzleHttp\json_decode($data, true);
    }
}
