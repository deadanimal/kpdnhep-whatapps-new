<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class PenugasanDoc extends Model
{
    public $table = 'case_doc';
    public $timestamps = false;
    
    public function attachment() {
        return $this->hasOne('App\Attachment', 'id', 'CC_DOCATTCHID');
    }
    public function attachmentviewpub() {
        return $this->hasOne('App\Attachment', 'id', 'CD_DOCATTCHID_PUBLIC');
    }
}
