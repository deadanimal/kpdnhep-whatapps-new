<?php

namespace App\Pertanyaan;

use Illuminate\Database\Eloquent\Model;
use App\EAduanOld;

class PertanyaanDetailAdmin extends Model
{
    use EAduanOld;
    protected $table = 'ask_dtl';
    
    const CREATED_AT = 'AD_CREDT';
    const UPDATED_AT = 'AD_MODDT';
    const CREATED_BY = 'AD_CREBY';
    const UPDATED_BY = 'AD_MODBY';
    
    public function ShowStatus() {
        return $this->hasOne('App\Ref', 'code', 'AD_ASKSTS')->where('cat','1061');
    }
    
    public function ShowCurrentStatus() {
        return $this->hasOne('App\Ref', 'code', 'AD_CURSTS')->where('cat','1065');
    }
}
