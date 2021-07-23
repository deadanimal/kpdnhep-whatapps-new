<?php

namespace App\Aduan;

use Illuminate\Database\Eloquent\Model;
use App\EAduan;

class PublicCaseDoc extends Model
{
    public $table = 'case_doc';
    
    use EAduan;
    
    public function Attachment() {
        return $this->hasOne('App\Attachment', 'id', 'CC_DOCATTCHID');
    }
    public function PublicCase() {
        return $this->hasOne('App\Aduan\PublicCase', 'id', 'CC_CASEID');
    }
}
