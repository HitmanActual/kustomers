<?php

namespace App\Http\Controllers\Api\UtilityBill;

use App\Http\Controllers\Controller;
use App\Services\UtilityBill\UtilityBillService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Response;
use Illuminate\Support\Facades\Validator;

class UtilityBillController extends Controller
{
    protected $service;

    public function __construct(UtilityBillService $service)
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
            'ticket_id' => 'required|numeric',
            'address' => 'required',
            'service' => 'required',
            'cost' => 'required|numeric'
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
