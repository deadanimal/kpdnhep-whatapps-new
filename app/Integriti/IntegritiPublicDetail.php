<?php

namespace App\Integriti;

use Illuminate\Database\Eloquent\Model;
use App\EAduanOld;

class IntegritiPublicDetail extends Model
{
    use EAduanOld;
    
    protected $table = 'integriti_case_dtl';
    
    const CREATED_AT = 'ID_CREATED_AT';
    const UPDATED_AT = 'ID_UPDATED_AT';
    const CREATED_BY = 'ID_CREATED_BY';
    const UPDATED_BY = 'ID_UPDATED_BY';

    public function createdby() {
        return $this->hasOne('App\User', 'id', 'ID_CREATED_BY');
        // dicipta oleh
    }

    public function actfrom() {
        return $this->hasOne('App\User', 'id', 'ID_ACTFROM');
        // tindakan daripada
    }
    
    public function actto() {
        return $this->hasOne('App\User', 'id', 'ID_ACTTO');
        // tindakan kepada
    }

    public function docattachidpublic() {
        return $this->hasOne('App\Attachment', 'id', 'ID_DOCATACHID_PUBLIC');
        // surat kepada pengadu
    }
    
    public function docattachidadmin() {
        return $this->hasOne('App\Attachment', 'id', 'ID_DOCATACHID_ADMIN');
        // surat kepada pegawai
    }
}
