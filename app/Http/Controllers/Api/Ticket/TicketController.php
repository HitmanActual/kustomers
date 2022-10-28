<?php

namespace App\Http\Controllers\Api\Ticket;

use App\Http\Controllers\Controller;
use App\Services\Ticket\TicketService;
use Illuminate\Http\Request;

class TicketController extends Controller
{
    public $service;

    public function __construct(TicketService $service)
    {
        $this->service = $service;
    }

    public function index(){
        return $this->service->index();
    }
}
