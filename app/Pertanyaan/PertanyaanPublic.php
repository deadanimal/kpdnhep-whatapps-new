<?php

namespace App\Pertanyaan;

use Illuminate\Database\Eloquent\Model;
use App\EAduanOld;
use DB;

class PertanyaanPublic extends Model
{
    use EAduanOld;
    
    protected $table = 'ask_info';
    
//    public $primaryKey = 'AS_ASKID';
//    public $incrementing = false;
    
    const CREATED_AT = 'AS_CREDT';
    const UPDATED_AT = 'AS_MODDT';
    const CREATED_BY = 'AS_CREBY';
    const UPDATED_BY = 'AS_MODBY';
    
    protected $fillable = [
        'AS_ASKID','AS_DEPTCD','AS_ASKSTS','AS_RCVBY','AS_RCVDT','AS_SUMMARY','AS_BRNCD','AS_USERID'
    ];
    
    public static function getNoPertanyaan() {
        $mPertanyaan = DB::table('ask_info')
                    ->select('AS_ASKID')
                    ->where('AS_ASKID', '<>', NULL)
                    ->whereYear('AS_CREDT', date('Y'))
                    ->orderBy('AS_CREDT', 'DESC')
                    ->limit(1)
                    ->first();
        
        if(!empty($mPertanyaan)) {
            $LatestNoPertanyaan = $mPertanyaan->AS_ASKID;
            $GenNoAduan = substr($LatestNoPertanyaan, -7) + 1;
            $NoAduan = substr("$GenNoAduan",2);
        }else{
            $NoAduan = "00001";
        }
        $Year = substr(date('Y'),2,4);
//        $NextNoPertanyaan = "A".$Year.$NoAduan;
        $NextNoPertanyaan = "P".$Year.$NoAduan;
        
        return $NextNoPertanyaan;
    }
    
    public function ShowStatus() {
        return $this->hasOne('App\Ref', 'code', 'AS_ASKSTS')->where('cat','1061');
    }
    
    public function User() {
        return $this->hasOne('App\User', 'id', 'AS_USERID')->where('user_cat','2');
    }
    
    public function CompleteBy() {
        return $this->hasOne('App\User', 'id', 'AS_COMPLETEBY');
    }
    
    public function daerahpengadu() {
        return $this->hasOne('App\Ref', 'code', 'AS_DISTCD')->where('cat', '18');
    }
    
    public function negeripengadu() {
        return $this->hasOne('App\Ref', 'code', 'AS_STATECD')->where('cat', '17');
    }
}
