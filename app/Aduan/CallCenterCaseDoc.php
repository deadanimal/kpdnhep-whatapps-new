<?php

namespace App\Aduan;

use Illuminate\Database\Eloquent\Model;
use App\EAduan;

class CallCenterCaseDoc extends Model
{
//    public $timestamps = false;
    
    public $table = 'case_doc';
    use EAduan;
    
    public function CallCenterCase() {
        return $this->hasOne('App\Aduan\CallCenterCase', 'CA_CASEID', 'CC_CASEID');
    }
    public function Attachment() {
        return $this->hasOne('App\Attachment', 'id', 'CC_DOCATTCHID');
    }
}