<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Services\TimelineService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Response;

class TimelineController extends Controller
{
    protected $service;

    public function __construct(TimelineService $service)
    {
        $this->service = $service;
        $this->middleware(['auth:api']);
    }

    public function show(Request $request)
    {
        return $this->service->show($request);
    }
}
