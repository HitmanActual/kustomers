<?php

namespace App\Services\UtilityBill;


use App\Models\SiteSurvey;
use App\Models\UtilityBill;
use App\Traits\GeneralFileService;
use Illuminate\Support\Facades\Response;

class UtilityBillService{
    use GeneralFileService;


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

}
