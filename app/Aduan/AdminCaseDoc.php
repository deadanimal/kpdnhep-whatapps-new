<?php

namespace App\Aduan;

use Illuminate\Database\Eloquent\Model;
use App\EAduan;

class AdminCaseDoc extends Model
{
    public $table = 'case_doc';
//    public $timestamps = false;
    use EAduan;
    
    public function attachment() {
        return $this->hasOne('App\Attachment', 'id', 'CC_DOCATTCHID');
    }
    
}
