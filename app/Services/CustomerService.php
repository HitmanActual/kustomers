<?php

namespace App\Services;


use App\Traits\ConsumesExternalService;

class CustomerService{
    use ConsumesExternalService;
    public $baseUri;
    public $api_key;


    public function __construct(){
        $env           = config('app.env');
        $this->baseUri = config("gateway_services.$env.base_uri");
        $this->api_key = config("gateway_services.$env.api_key");
    }


    public function show(){
        $user_id = auth()->user()->lead_id;
        return json_decode($this->performRequest('get', "customer/".$user_id));
    }
}
