<?php

namespace App\Services\UtilityBill;


use App\Models\SiteSurvey;
use App\Models\UtilityBill;
use App\Traits\ConsumesExternalService;
use App\Traits\GeneralFileService;
use Illuminate\Support\Facades\Response;

class UtilityBillService{
    use GeneralFileService;
    use ConsumesExternalService;

    public $baseUri;
    public $api_key;

    public function __construct()
    {
        $env = "local";
        $this->baseUri = config("gateway_services.$env.base_uri");
        $this->api_key = config("gateway_services.$env.api_key");
    }

    public function index($request){

        if($request->return_all == 1){
            $UtilityBill = UtilityBill::where('user_id',auth()->user()->id)->get();
            return Response::successResponse($UtilityBill,"Utility Bill Fetched All");
        }

        $UtilityBill = UtilityBill::where('user_id',auth()->user()->id)->paginate(20);
        return Response::successResponse($UtilityBill,"Utility Bill Fetched Paginate");

    }

    public function upload_file($request){
        $path = 'UtilityBill/';
        $fileName = $this->SaveFile($request->file('file_path'),$path);

        $UtilityBill = UtilityBill::create([
            'user_id' => auth()->user()->id,
            'file_path' => $fileName
        ]);

        return Response::successResponse($UtilityBill,"File Is Uploaded");
    }

    public function sendToCrm($id){
        $UtilityBill = UtilityBill::where('user_id',auth()->user()->id)->find($id);
        if (!$UtilityBill){
            return Response::errorResponse("You Don't Have Utility Bill");
        }

        $lead_id = auth()->user()->lead_id;

        $fileContent = fopen($UtilityBill->file,'r');

        $media = [];

        $media [] = [
            'name' => 'media[]',
            'contents' => $fileContent
        ];

        try {
            $Response = json_decode($this->performRequestFile('post','leads/customer_upload/'.$lead_id.'/media/utility_bill',$media));

        }catch (\Exception $e){
            return Response::errorResponse($e->getMessage());
        }

        $UtilityBill->update([
            'status' => "send"
        ]);

        return Response::successResponse($UtilityBill,"File Is Send");
    }

}
