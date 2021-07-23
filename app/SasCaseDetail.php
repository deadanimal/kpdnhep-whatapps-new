<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class SasCaseDetail extends Model
{
    use EAduanOld;
    public $table = 'case_dtl';
    
    const CREATED_AT = 'CD_CREDT';
    const UPDATED_AT = 'CD_MODDT';
    const CREATED_BY = 'CD_CREBY';
    const UPDATED_BY = 'CD_MODBY';
    
    public function statusaduan() {
        return $this->hasOne('App\Ref', 'code', 'CD_INVSTS')->where('cat','292');
    }
    
    public function creby() {
        return $this->hasOne('App\User', 'id', 'CD_CREBY');
    }
}
