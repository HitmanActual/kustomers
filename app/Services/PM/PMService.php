<?php

namespace App\Services\PM;

use App\Traits\ConsumesExternalService;
use Illuminate\Support\Facades\Response;

class PMService{
    use ConsumesExternalService;
    public $baseUri;
    public $api_key;
    public function __construct()
    {
        $env           = "pm";
        $this->baseUri = config("gateway_services.$env.base_uri");
        $this->api_key = config("gateway_services.$env.api_key");
    }

    public function GetPMUserById(){
        $user_id = auth()->user()->lead_id;
        try {
            $tickets = json_decode($this->performRequest('get', "tickets/getTickets/".$user_id));
        }catch (\Exception $e){
            return Response::errorResponse('Error Fetch Tickets');
        }

        $data = [];
        foreach ($tickets->data as $ticket){


            $id = $ticket->ticket->user_id;
            try {
                $PmUser = json_decode($this->performRequest('get', "user_by_id/".$id));
            }catch (\Exception $e){
                return Response::errorResponse('Error Fetch User From PM');
            }

            array_push($data,$PmUser->data);
        }

        return Response::successResponse($data,"Pm Users Fetched");

    }

    public function GetPMStatus(){
        $user_id = auth()->user()->lead_id;
        try {
            $tickets = json_decode($this->performRequest('get', "tickets/getTickets/".$user_id));
        }catch (\Exception $e){
            return Response::errorResponse('Error Fetch Tickets');
        }

        $data = [];
        foreach ($tickets->data as $ticket){


            $ticket_id = $ticket->ticket->id;
            try {
                $PMStatus = json_decode($this->performRequest('get', "tickets/pm-statuses/get_api_status/".$ticket_id));
            }catch (\Exception $e){
                return Response::errorResponse('Error Fetch Status From PM');
            }

            array_push($data,$PMStatus->data);
        }

        return Response::successResponse($data,"Pm Status Fetched");

    }
}
