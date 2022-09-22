<?php

namespace App\Http\Controllers\Api\Solution;

use App\Http\Controllers\Controller;
use App\Services\Solution\SolutionService;
use Illuminate\Http\Request;

class SolutionController extends Controller
{
    protected $service;
    public function __construct(SolutionService $service)
    {
        $this->service = $service;
    }

    public function index(){
        return $this->service->index();
    }
}
