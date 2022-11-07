<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class SiteSurvey extends Model
{
    use HasFactory;
    use SoftDeletes;

    protected $appends = ['file'];
    protected $table = "site_surveys";

    protected $fillable = [
        'id',
        'user_id',
        'file_path',
        'mime_type',
        'size',
        'filename',
        'type',
        'status',
        'created_at'
    ];

    protected $hidden = [
        'updated_at',
    ];

    public function getFileAttribute(){
        return ($this->file_path !==null)?asset('files/SiteSurvey/'.$this->file_path):"";
    }
}
