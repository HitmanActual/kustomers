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
        $headers['Accept'] = 'application/json';
        $response = $client->request($method, "api/" . $requestUrl, ['form_params' => $formParams, 'headers' => $headers]);
        return $response->getBody()->getContents();
    }

    public function performRequestFile($method, $requestUrl, $formParams = [], $headers = [])
    {

        $client = new Client([
            'base_uri' => $this->baseUri,
        ]);
        $headers['token'] = $this->api_key;
        $headers['Accept'] = 'application/json';
        $headers['Content-Type'] = 'multipart/form-data; boundary=-----44cf242ea3173cfa0b97f80c68608c4c';
        $response = $client->request($method, "api/" . $requestUrl, ['multipart' => $formParams, 'headers' => $headers]);
        return $response->getBody()->getContents();
    }
}
