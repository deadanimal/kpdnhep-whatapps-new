<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use App\EAduan;

class SasCaseDoc extends Model
{
//    public $timestamps = false;
    
    public $table = 'case_doc';
    use EAduan;
    
    public function Attachment() {
        return $this->hasOne('App\Attachment', 'id', 'CC_DOCATTCHID');
    }
}
