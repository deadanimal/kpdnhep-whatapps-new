<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use App\EAduanOld;

class Agensi extends Model
{
    use EAduanOld;
    public $table = 'sys_min';
    public $primaryKey = 'id';
    
    const CREATED_AT = 'MI_CREDT';
    const UPDATED_AT = 'MI_MODDT';
    const CREATED_BY = 'MI_CREBY';
    const UPDATED_BY = 'MI_MODBY';
    
    protected $fillable = ['MI_MINCD','MI_DESC','MI_STATECD','MI_ADDR','MI_POSCD','MI_DISTCD','MI_TELNO','MI_FAXNO','MI_EMAIL','MI_OFFNM','MI_STS','MI_MINTYP'];
    
    public static function ShowStatus($status) {
        if($status == '1') {
            return 'AKTIF';
        }else{
            return 'TIDAK AKTIF';
        }
    }
     public function daerahagensi() {
        return $this->hasOne('App\Ref', 'code', 'MI_DISTCD')->where('cat', '18');
    }
     public function Negeriagensi() {
        return $this->hasOne('App\Ref', 'code', 'MI_STATECD')->where('cat', '17');
    }
     public function Negaraagensi() {
        return $this->hasOne('App\Ref', 'code', 'MI_COUNTRY')->where('cat', '334');
    }
}
