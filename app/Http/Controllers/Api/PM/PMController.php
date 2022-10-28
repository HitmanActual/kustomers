<?php

namespace App\Http\Controllers\Api\PM;

use App\Http\Controllers\Controller;
use App\Services\PM\PMService;
use Illuminate\Http\Request;

class PMController extends Controller
{
    protected $service;

    public function __construct(PMService $service)
    {
        $this->service = $service;
    }

    public function getPMUserById(){
        return $this->service->GetPMUserById();
    }

    public function GetPMUserByTicketId($ticket_id){
        return $this->service->GetPMUserByTicketId($ticket_id);
    }

    public function getPMStatus(){
        return $this->service->GetPMStatus();
    }

    public function GetPMStatusByTicketID($ticket_id)
    {
        return $this->service->GetPMStatusByTicketID($ticket_id);
    }
}
