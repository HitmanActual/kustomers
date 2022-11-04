<?php

namespace App\Services\SiteSurvey;


use App\Models\SiteSurvey;
use App\Traits\ConsumesExternalService;
use App\Traits\GeneralFileService;
use GuzzleHttp\Client;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Response;
use Illuminate\Support\Facades\Storage;

class SiteSurveyService{
    use GeneralFileService;
    use ConsumesExternalService;

    public $baseUri;
    public $api_key;

    public function __construct()
    {
        $env = "crm";
        $this->baseUri = config("gateway_services.$env.base_uri");
        $this->api_key = config("gateway_services.$env.api_key");
    }


    public function index($request){

        if($request->return_all == 1){
            $SiteSurvey = SiteSurvey::where('user_id',auth()->user()->id)->get();
            return Response::successResponse($SiteSurvey,"Site Survey Fetched All");
        }

        $SiteSurvey = SiteSurvey::where('user_id',auth()->user()->id)->paginate(20);
        return Response::successResponse($SiteSurvey,"Site Survey Fetched Paginate");

    }

    public function upload_file($request){


        $path = 'SiteSurvey/';
        $fileName = $this->SaveFile($request->file('file_path'),$path);


        $SiteSurvey = SiteSurvey::create([
            'user_id' => auth()->user()->id,
            'file_path' => $fileName
        ]);

        return Response::successResponse($SiteSurvey,"File Is Uploaded");
    }

    public function sendToCrm($id){
        $SiteSurvey = SiteSurvey::where('user_id',auth()->user()->id)->find($id);
        if (!$SiteSurvey){
            return Response::errorResponse("You Don't Have SiteSurvey");
        }

        $lead_id = auth()->user()->lead_id;

        $fileContent = fopen($SiteSurvey->file,'r');

        $media = [];

        $media [] = [
            'name' => 'media[]',
            'contents' => $fileContent
        ];

        try {
            $Response = json_decode($this->performRequestFile('post','leads/customer_upload/'.$lead_id.'/media/property',$media));

        }catch (\Exception $e){
            return Response::errorResponse($e->getMessage());
        }

        $SiteSurvey->update([
            'status' => "send"
        ]);

        return Response::successResponse($SiteSurvey,"File Is Send");
    }

}
