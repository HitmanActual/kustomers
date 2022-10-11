<?php
namespace App\Services\StatusFinance;

use App\Traits\ConsumesExternalService;
use Illuminate\Support\Facades\Response;

class StatusFinanceService{

use ConsumesExternalService;

    protected $baseUri;
    protected $api_key;

    public function getFinanced(){
        $user_id = auth()->user()->lead_id;
        try {
            $this->initial_api("pm");
            $tickets = json_decode($this->performRequest('get', "tickets/getTickets/631"));
        }catch (\Exception $e){
            return Response::errorResponse($e->getMessage());
        }

        $data = [];
        foreach ($tickets->data as $ticket){
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
            }

            array_push($data,$result);
        }

        return Response::successResponse($data,"Fetch Success");
    }

    public function getStatusForSunlight($project_id){
        try {
            $this->initial_api("crm");
            return Response::successResponse($this->api_key,"Fetch Success");
            $finance_status = json_decode($this->performRequest('get', "v1/sunlight_customer/pull-status/".$project_id));
        }catch (\Exception $e){
            return Response::errorResponse($e->getMessage());
        }

        return Response::successResponse($finance_status,"Fetch Success");
    }


    protected function initial_api($type){
        $env           = $type;
        $this->baseUri = config("gateway_services.$env.base_uri");
        $this->api_key = config("gateway_services.$env.api_key");
    }

}
