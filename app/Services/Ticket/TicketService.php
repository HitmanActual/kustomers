<?php

namespace App\Services\Ticket;

use App\Traits\ConsumesExternalService;
use Illuminate\Support\Facades\Response;

class TicketService{
    use ConsumesExternalService;
    public $baseUri;
    public $api_key;

    public function index(){
        $user_id = auth()->user()->lead_id;
        try {
            $this->initial_api("pm");
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

    public function getTicketById($ticket_id){
        try {
            $this->initial_api("pm");
            $ticket = json_decode($this->performRequest('get', "tickets/show-single-ticket/".$ticket_id));
        }catch (\Exception $e){
            return Response::errorResponse($e->getMessage());
        }

        $ticket =  $ticket->data;

        //get Finance Status in Single Ticket

        $result = [];
        $result['opportunity_id'] = $ticket->ticket->opportunity_id;
        $result['lead_id'] = $ticket->ticket->lead_id;
        if($ticket->opportunity->is_finance == 1){
            $result['financial_name'] = $ticket->opportunity->financial_institution;
            $result['financial'] = "Financed-".$ticket->opportunity->financial_institution;
            if($ticket->opportunity->financial_institution == "sunlight"){

                try {
                    $this->initial_api("crm");
                    $project_ids = json_decode($this->performRequest('get', "v1/sunlight_customer/get-submitted/project_ids/".$result['lead_id']));
                }catch (\Exception $e){
                    return Response::errorResponse($e->getMessage());
                }
                $result['project_ids'] = $project_ids->data;

            }elseif ($ticket->opportunity->financial_institution == "goodleap"){

                try {
                    $this->initial_api("crm");
                    $finance_status = json_decode($this->performRequest('get', "v1/goodleap_customer/get-status/".$result['opportunity_id']));
                }catch (\Exception $e){
                    return Response::errorResponse($e->getMessage());
                }

                $result['finance_status'] = $finance_status;
            }
        }else{
            $result['financial_name'] = "Cache";
            $result['financial'] = "Cache";
            $result['finance_status'] = [
                "loan_amount" => $ticket->contract->cost
            ];
        }




        $data = [];
        $data['finance'] = $result;


        //get Docs in Single Ticket
        $data['utility_bill'] = [];
        $data['contract'] = [];

        foreach ($ticket->ticket->ticket_media as $media){
            if($media->type == "utility_bill"){
                array_push($data['utility_bill'],$media->url);
            }elseif ($media->type == "contract"){
                array_push($data['contract'],$media->url);
            }else{
                $data[$media->type] = $media->url;
            }
        }
        $data['solution_pdf'] = $ticket->opportunity->pdf_path;


        $data['plan'] = [];
        try {
            $plan = json_decode($this->performRequest('post', "client-plans/".$ticket_id));
        }catch (\Exception $e){
            return Response::successResponse($data,"Fetch Success");
        }


        foreach ($plan->data->media as $media){
            array_push($data['plan'],$media->path);
        }


        return Response::successResponse($data,"Fetch Success");
    }

    protected function initial_api($type){
        $env           = $type;
        $this->baseUri = config("gateway_services.$env.base_uri");
        $this->api_key = config("gateway_services.$env.api_key");
    }

}
