<?php
namespace App\Traits;

Trait GeneralFileService{

    public function SaveFile($image,$path){
        $file_extention = $image->getClientOriginalExtension();
        $file_name = date('Y-m-d').time().'.'.$file_extention;
        $image->move($path,$file_name);
        return $file_name;
    }

    public function removeFile($pathImage){
        if(file_exists($pathImage))
            unlink($pathImage);
    }

}
