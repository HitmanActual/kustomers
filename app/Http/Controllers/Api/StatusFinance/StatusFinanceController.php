<?php

namespace App\Http\Controllers\Api\StatusFinance;

use App\Http\Controllers\Controller;
use App\Services\StatusFinance\StatusFinanceService;
use Illuminate\Http\Request;

class StatusFinanceController extends Controller
{
    protected $service;
    public function __construct(StatusFinanceService $service)
    {
        $this->service = $service;
    }

    public function getFinanced(){
        return $this->service->getFinanced();
    }

    public function getStatusForSunlight($project_id){
        return $this->service->getStatusForSunlight($project_id);
    }
}
