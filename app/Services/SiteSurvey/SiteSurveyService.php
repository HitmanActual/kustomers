<?php

namespace App\Services\SiteSurvey;


use App\Models\SiteSurvey;
use App\Traits\ConsumesExternalService;
use App\Traits\GeneralFileService;
use GuzzleHttp\Client;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Response;
use Illuminate\Support\Facades\Storage;
use function MongoDB\BSON\toJSON;

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

        $mediaFile = $request->file('file_path');
        $mimeType = $mediaFile->getClientMimeType();
        $size = $mediaFile->getSize(); //size in bytes

        $mediaFileName = uniqid().'.'.$mediaFile->extension();
        $mediaType = explode('/' , $mimeType)[0];

        $type = '';

        switch ($mediaType){
            case 'image':
                $type = 'image';
                break;
            case 'video':
                $type = 'video';
                break;
            case 'application':
                $type = 'pdf';
                break;
            default:
                //
        }

        $SiteSurvey = SiteSurvey::create([
            'user_id' => auth()->user()->id,
            'file_path' => $fileName,
            'mime_type' => $mimeType,
            'size' => $size,
            'filename' => $mediaFileName,
            'type' => $type
        ]);

        return Response::successResponse($SiteSurvey,"File Is Uploaded");
    }

    public function sendToCrm($id){
        $SiteSurvey = SiteSurvey::where('user_id',auth()->user()->id)->find($id);
        if (!$SiteSurvey){
            return Response::errorResponse("You Don't Have SiteSurvey");
        }

        $lead_id = auth()->user()->lead_id;

        $fileContent = $SiteSurvey->file;


        $media = [];

        $media = [
            'file' => $fileContent,
            'mime_type' => $SiteSurvey->mime_type,
            'size' => $SiteSurvey->size,
            'filename' => $SiteSurvey->file_path,
            'type' => $SiteSurvey->type,
        ];



        try {
            $Response = json_decode($this->performRequest('post','leads/customer_upload/'.$lead_id.'/media/property',$media));
        }catch (\Exception $e){
            return Response::errorResponse($e->getMessage());
        }

        $SiteSurvey->update([
            'status' => "send"
        ]);

        return Response::successResponse($SiteSurvey,"File Is Send");
    }

}
