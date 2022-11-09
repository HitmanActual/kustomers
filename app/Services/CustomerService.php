<?php

namespace App\Services;


use App\Http\Resources\CustomerResource;
use App\Models\User;
use App\Traits\ConsumesExternalService;
use Illuminate\Support\Facades\Response;

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

    public function getUserById($id){
        $user = User::find($id);
        if (!$user){
            return Response::successResponse(null,"No User By This Id");
        }

        return Response::successResponse(new CustomerResource($user),"User Fetch Success");
    }

    public function getAllUsers(){
        $users = User::get();
        return Response::successResponse(CustomerResource::collection($users),"User Fetch Success");
    }
}
