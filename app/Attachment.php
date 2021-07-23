<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Attachment extends Model
{
    use EAduan;
    public $table = 'doc_attach';
    
    public function CaseDoc()
    {
        return $this->belongsTo('App\PublicCaseDoc');
    }
}
