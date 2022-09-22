<?php

namespace App\Traits;

use GuzzleHttp\Client;

trait ConsumesExternalService
{
    public function performRequest($method, $requestUrl, $formParams = [], $headers = [])
    {

        $client = new Client([
            'base_uri' => $this->baseUri,
        ]);
        $headers['token'] = $this->api_key;
        $response = $client->request($method, "api/" . $requestUrl, ['form_params' => $formParams, 'headers' => $headers]);
        return $response->getBody()->getContents();
    }
}
