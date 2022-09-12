<?php

namespace App\Services\SiteSurvey;


use App\Models\SiteSurvey;
use App\Traits\GeneralFileService;
use Illuminate\Support\Facades\Response;

class SiteSurveyService{
    use GeneralFileService;


    public function index($request){

        if($request->return_all == 1){
            $SiteSurvey = SiteSurvey::where('user_id',auth()->user()->id)->get();
            return Response::successResponse($SiteSurvey,"Site Survey Fetched All");
        }

        $SiteSurvey = SiteSurvey::where('user_id',auth()->user()->id)->paginate(5);
        return Response::successResponse($SiteSurvey,"Site Survey Fetched Paginate");

    }

    public function upload_file($request){
        $path = 'SiteSurvey/';
        $fileName = $this->SaveFile($request->file_path,$path);

        $SiteSurvey = SiteSurvey::create([
            'user_id' => auth()->user()->id,
            'file_path' => $fileName
        ]);

        return Response::successResponse($SiteSurvey,"File Is Uploaded");
    }

}
