<?php

namespace App\Http\Controllers\Api\SiteSurvey;

use App\Http\Controllers\Controller;
use App\Services\SiteSurvey\SiteSurveyService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Response;
use Illuminate\Support\Facades\Validator;

class SiteSurveyController extends Controller
{
    protected $service;

    public function __construct(SiteSurveyService $service)
    {
        $this->service = $service;
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        $validator = Validator::make($request->all(),[
            'return_all' => 'required',
        ]);

        if ($validator->fails()){
            return Response::errorResponse($validator->errors(),[]);
        }

        return $this->service->index($request);
    }

    public function upload_file(Request $request){
        $validator = Validator::make($request->all(),[
            'file_path' => 'required|mimes:pdf,jpg,png,jpeg,docx|max:2048',
        ]);

        if ($validator->fails()){
            return Response::errorResponse($validator->errors(),[]);
        }

        return $this->service->upload_file($request);


    }

    public function sendToCRM($id){
        return $this->service->sendToCrm($id);
    }
}
