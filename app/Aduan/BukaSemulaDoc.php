<?php

namespace App\Aduan;

use Illuminate\Database\Eloquent\Model;

class BukaSemulaDoc extends Model
{
    public $table = 'case_doc';
    public $timestamps = false;
    
    public function attachment() {
        return $this->hasOne('App\Attachment', 'id', 'CC_DOCATTCHID');
    }
}
