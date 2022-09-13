<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class UtilityBill extends Model
{
    use HasFactory;
    use SoftDeletes;

    protected $appends = ['file'];
    protected $table = "utility_bills";
    protected $fillable = [
        'id',
        'user_id',
        'file_path',
        'status',
        'created_at'
    ];

    protected $hidden = [
        'updated_at'
    ];

    public function getFileAttribute(){
        return ($this->file_path !==null)?asset('files/UtilityBill/'.$this->file_path):"";
    }


}
