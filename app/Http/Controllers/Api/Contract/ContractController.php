<?php

namespace App\Http\Controllers\Api\Contract;

use App\Http\Controllers\Controller;
use App\Services\Contract\ContarctService;
use Illuminate\Http\Request;

class ContractController extends Controller
{
    protected $service;
    public function __construct(ContarctService $service)
    {
        $this->service = $service;
    }

    public function index(){
        return $this->service->index();
    }
}
