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
        $env = "crm";
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

        $UtilityBill = UtilityBill::create([
            'user_id' => auth()->user()->id,
            'file_path' => $fileName,
            'mime_type' => $mimeType,
            'size' => $size,
            'filename' => $mediaFileName,
            'type' => $type
        ]);

        return Response::successResponse($UtilityBill,"File Is Uploaded");
    }

    public function sendToCrm($id){
        $UtilityBill = UtilityBill::where('user_id',auth()->user()->id)->find($id);
        if (!$UtilityBill){
            return Response::errorResponse("You Don't Have Utility Bill");
        }

        $lead_id = auth()->user()->lead_id;

        $fileContent = $UtilityBill->file;


        $media = [];

        $media = [
            'file' => $fileContent,
            'mime_type' => $UtilityBill->mime_type,
            'size' => $UtilityBill->size,
            'filename' => $UtilityBill->file_path,
            'type' => $UtilityBill->type,
        ];



        try {
            $Response = json_decode($this->performRequest('post','leads/customer_upload/'.$lead_id.'/media/utility_bill',$media));
        }catch (\Exception $e){
            return Response::errorResponse($e->getMessage());
        }

        //utility_bill
        $UtilityBill->update([
            'status' => "send"
        ]);

        return Response::successResponse($UtilityBill,"File Is Send");
    }

}
