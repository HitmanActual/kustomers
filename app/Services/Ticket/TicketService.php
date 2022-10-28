<?php

namespace App\Services\Ticket;

use App\Traits\ConsumesExternalService;
use Illuminate\Support\Facades\Response;

class TicketService{
    use ConsumesExternalService;
    public $baseUri;
    public $api_key;
    public function __construct()
    {
        $env           = "pm";
        $this->baseUri = config("gateway_services.$env.base_uri");
        $this->api_key = config("gateway_services.$env.api_key");
    }

    public function index(){
        $user_id = auth()->user()->lead_id;
        try {
            $tickets = json_decode($this->performRequest('get', "tickets/getTickets/".$user_id));
        }catch (\Exception $e){
            return Response::errorResponse('Error Fetch Tickets');
        }


        $data = [];
        foreach ($tickets->data as $ticket){
            $result['ticket_id'] = $ticket->ticket->id;
            $result['address'] = $ticket->ticket->client_address;

            if($ticket->opportunity->is_finance == 1){
                $result['financial'] = "Financed-".$ticket->opportunity->financial_institution;
            }else{
                $result['financial'] = "Cache";
            }

            $result['cost'] = $ticket->opportunity->estimated_cost;

            if ($ticket->opportunity->service_type_id == 1){
                $result['service'] = "Solar";
            }elseif ($ticket->opportunity->service_type_id == 2){
                $result['service'] = "Roofing";
            }else{
                $result['service'] = "HVAC";
            }


            array_push($data,$result);

        }

        return Response::successResponse($data,"Fetch Success");

    }

}
