<?php

namespace App\Aduan;

use Illuminate\Database\Eloquent\Model;
use App\EAduanOld;

class KemaskiniDetail extends Model
{
    use EAduanOld;
    public $table = 'case_dtl';
    public $primaryKey = 'CD_DTLID';
    
    const CREATED_AT = 'CD_CREDT';
    const UPDATED_AT = 'CD_MODDT';
    const CREATED_BY = 'CD_CREBY';
    const UPDATED_BY = 'CD_MODBY';
    
    public $fillable = ['CD_CASEID','CD_TYPE','CD_DESC','CD_INVSTS','CD_CASESTS','CD_CURSTS','CD_DOCATTCHID_PUBLIC'];
}
