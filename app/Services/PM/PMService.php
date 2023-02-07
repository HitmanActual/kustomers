<?php

namespace App\Services\PM;

use App\Traits\ConsumesExternalService;
use Illuminate\Support\Facades\Auth;
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


            $ticket_id = $ticket->ticket->id;
            $PmUser = $ticket->ticket->user;
            $SalesRapeData = [
                "name" => $ticket->opportunity->user->name,
                "email" => $ticket->opportunity->user->email,
                "phone" => $ticket->opportunity->user->phone
            ];

            if ($ticket->opportunity->service_type_id == 1){
                $Solution_type = "Solar";
            }elseif ($ticket->opportunity->service_type_id == 2){
                $Solution_type = "Roofing";
            }else{
                $Solution_type = "HVAC";
            }

            $result = [
                "ticket_id" => $ticket_id,
                "solution_type" => $Solution_type,
                "pm_user" => $PmUser,
                "sales" => $SalesRapeData,
            ];


            array_push($data,$result);
        }

        return Response::successResponse($data,"Pm Users Fetched");

    }

    public function GetPMUserByTicketId($ticket_id){
        try {
            $ticket = json_decode($this->performRequest('get', "tickets/show-single-ticket/".$ticket_id));
        }catch (\Exception $e){
            return Response::errorResponse('Error Fetch Tickets');
        }

        $ticket = $ticket->data;

        if ($ticket->ticket->lead_id != Auth::user()->lead_id){
            return Response::errorResponse("You Can't Get This Ticket");
        }

        $PmUser = $ticket->ticket->user;
        if ($PmUser != null){
            $PmUser->role = [
                "id" => 4,
                "title" => "project_manager"
            ];
            $PmUser->site = "pm";
        }
        $SalesRapeData = [
            "id" => $ticket->opportunity->user->id,
            "name" => $ticket->opportunity->user->name,
            "email" => $ticket->opportunity->user->email,
            "phone" => $ticket->opportunity->user->phone,
            "role" => [
                "id" => 1,
                "title" => "Sales Rep"
            ],
            "site" => 'crm'
        ];

        $data = [
          "pm_user" => $PmUser,
          "sales" => $SalesRapeData
        ];

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

    public function GetPMStatusByTicketID($ticket_Id){
        try {
            $PMStatus = json_decode($this->performRequest('get', "tickets/pm-statuses/get_api_status/".$ticket_Id));
        }catch (\Exception $e){
            return Response::errorResponse('Error Fetch Status From PM');
        }

        return Response::successResponse($PMStatus,"Pm Status Fetched");

    }
}
