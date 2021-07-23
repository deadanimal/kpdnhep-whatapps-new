<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use App\EAduanOld;

class PenugasanCaseDetail extends Model
{
    use EAduanOld;
    
    public $table = 'case_dtl';
    
    public $primaryKey = 'CD_DTLID';
    
    const CREATED_AT = 'CD_CREDT';
    const UPDATED_AT = 'CD_MODDT';
    const CREATED_BY = 'CD_CREBY';
    const UPDATED_BY = 'CD_MODBY';
    
    public $fillable = ['CD_CASEID','CD_TYPE','CD_DESC','CD_INVSTS','CD_CASESTS','CD_CURSTS','CD_DOCATTCHID_PUBLIC','CD_DOCATTCHID_ADMIN'];
    
    public function StatusAduan() {
        return $this->hasOne('App\Ref', 'code', 'CD_INVSTS')->where('cat','292');
    }
    
    public function UserDaripada() {
        return $this->hasOne('App\User', 'id', 'CD_ACTFROM');
    }
    
    public function UserCreate() {
        return $this->hasOne('App\User', 'id', 'CD_CREBY');
    }
    
    public function UserInv() {
        return $this->hasOne('App\User', 'id', 'CD_INVBY');
    }
    
    public function UserKepada() {
        return $this->hasOne('App\User', 'id', 'CD_ACTTO');
    }
    
    public function SuratPublic() {
        return $this->hasOne('App\Attachment', 'id', 'CD_DOCATTCHID_PUBLIC');
    }
    
    public function SuratAdmin() {
        return $this->hasOne('App\Attachment', 'id', 'CD_DOCATTCHID_ADMIN');
    }

    public function brncdfrom() {
        return $this->hasOne('App\Branch', 'BR_BRNCD', 'CD_BRNCD_FROM');
    }

    public function brncdto() {
        return $this->hasOne('App\Branch', 'BR_BRNCD', 'CD_BRNCD_TO');
    }
}
