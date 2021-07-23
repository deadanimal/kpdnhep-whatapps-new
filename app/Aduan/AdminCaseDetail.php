<?php

namespace App\Aduan;

use Illuminate\Database\Eloquent\Model;
use App\EAduanOld;
    
class AdminCaseDetail extends Model
{
    use EAduanOld;
    
    public $table = 'case_dtl';
    
    public $primaryKey = 'CD_DTLID';
    
    const CREATED_AT = 'CD_CREDT';
    const UPDATED_AT = 'CD_MODDT';
    const CREATED_BY = 'CD_CREBY';
    const UPDATED_BY = 'CD_MODBY';
    
    public $fillable = ['CD_CASEID','CD_TYPE','CD_DESC','CD_INVSTS','CD_CASESTS','CD_CURSTS','CD_DOCATTCHID_PUBLIC'];
    
    public function statusaduan() {
        return $this->hasOne('App\Ref', 'code', 'CD_INVSTS')->where('cat','292');
    }
    
    public function actfrom() {
        return $this->hasOne('App\User', 'id', 'CD_ACTFROM');
    }
    
    public function actto() {
        return $this->hasOne('App\User', 'id', 'CD_ACTTO');
    }
    
    public function CmplCat() {
        return $this->hasOne('App\Ref', 'code', 'CA_CMPLCAT')->where('cat','244');
    }
    
    public function BrnCd() {
        return $this->hasOne('App\Branch', 'BR_BRNCD', 'CA_BRNCD');
    }

    public function InvBy() {
        return $this->hasOne('App\User', 'id', 'CA_INVBY');
    }

    public function CompleteBy() {
        return $this->hasOne('App\User', 'id', 'AS_COMPLETEBY');
    }
}
