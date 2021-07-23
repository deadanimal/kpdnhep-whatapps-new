<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use App\EAduan;

class FailKes extends Model
{
    use EAduan;
    
    protected $table = 'sys_data_upload';
    
    public $primaryKey = 'id';
    
    public function Attachment() {
        return $this->hasOne('App\Attachment', 'id', 'doc_attach_id');
    }
    
    public function Status() {
        return $this->hasOne('App\Ref', 'code', 'status')->where('cat','1130');
    }
}
