<?php

namespace App\Http\Controllers\Api\CustomersController;

use App\Http\Controllers\Controller;
use App\Services\CustomerService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Response;

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

    public function getUserById($id){
        return $this->service->getUserById($id);
    }

    public function getAllUsers(){
        return $this->service->getAllUsers();
    }
}
