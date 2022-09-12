<?php

namespace App\Http\Controllers\Api\CustomersController;

use App\Http\Controllers\Controller;
use App\Services\CustomerService;
use Illuminate\Http\Request;

class CustomerController extends Controller
{
    public $service;

    public function __construct(CustomerService $serv)
    {
        $this->service = $serv;
    }

    public function show(){
        return $this->service->show();
    }
}
