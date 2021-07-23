<?php

namespace App\Aduan;

use Illuminate\Database\Eloquent\Model;
use App\EAduanOld;

class PindahAduanDetail extends Model
{
    use EAduanOld;
    
    public $table = 'case_dtl';
    
    public $primaryKey = 'CD_DTLID';
    
    const CREATED_AT = 'CD_CREDT';
    const UPDATED_AT = 'CD_MODDT';
    const CREATED_BY = 'CD_CREBY';
    const UPDATED_BY = 'CD_MODBY';
    
    public function statusaduan() {
        return $this->hasOne('App\Ref', 'code', 'CD_INVSTS')->where('cat','292');
    }
    
    public function actfrom() {
        return $this->hasOne('App\User', 'id', 'CD_ACTFROM');
    }
    
    public function actto() {
        return $this->hasOne('App\User', 'id', 'CD_ACTTO');
    }
    
    public function suratadmin() {
        return $this->hasOne('App\Attachment', 'id', 'CD_DOCATTCHID_ADMIN');
    }
    public function suratpublic() {
        return $this->hasOne('App\Attachment', 'id', 'CD_DOCATTCHID_PUBLIC');
    }
}
