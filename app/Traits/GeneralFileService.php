<?php
namespace App\Traits;

use Illuminate\Support\Facades\Storage;

Trait GeneralFileService{

    public function SaveFile($image,$path){
        $file_extention = $image->getClientOriginalName();
        $extintion = explode(".",$file_extention);
        $file_name = date('Y-m-d').time().'.'.$extintion[count($extintion)-1];
        Storage::disk('public')->put($path.'/'.$file_name,file_get_contents($image));
        return $file_name;
    }

    public function removeFile($pathImage){
        if(file_exists($pathImage))
            unlink($pathImage);
    }

}
