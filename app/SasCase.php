<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use DB;

class SasCase extends Model
{
    use EAduanOld;
    public $table = 'case_info';
    
    public $primaryKey = 'CA_CASEID';
    
    public $incrementing = false;
    
    const CREATED_AT = 'CA_CREDT';
    const UPDATED_AT = 'CA_MODDT';
    const CREATED_BY = 'CA_CREBY';
    const UPDATED_BY = 'CA_MODBY';
    
    protected $fillable = ['CA_CASEID','CA_RCVBY','CA_INVSTS','CA_SUMMARY','CA_RCVTYP','CA_BRNCD','CA_INVBY','CA_RCVDT','CA_COMPLETEDT',
        'CA_NAME','CA_DOCNO','CA_SEXCD','CA_AGE','CA_ADDR','CA_STATECD','CA_DISTCD','CA_POSCD','CA_TELNO','CA_FAXNO','CA_EMAIL','CA_MOBILENO','CA_NATCD','CA_COUNTRYCD','CA_RACECD',
        'CA_CMPLCAT','CA_CMPLCD','CA_AGAINST_PREMISE','CA_AGAINST_EMAIL','CA_AGAINST_FAXNO','CA_AGAINSTADD','CA_AGAINSTNM','CA_AGAINST_TELNO','CA_AGAINST_MOBILENO','CA_AGAINST_POSTCD','CA_AGAINST_STATECD','CA_AGAINST_DISTCD','CA_RESULT','CA_ANSWER',
        'CA_CMPLKEYWORD','CA_ONLINECMPL_IND','CA_ONLINEADD_IND','CA_ONLINECMPL_URL','CA_ONLINECMPL_PROVIDER',
        'CA_ONLINECMPL_CASENO','CA_ONLINECMPL_ACCNO','CA_ONLINECMPL_AMOUNT',
        'CA_BPANO','CA_SERVICENO','CA_SSP'
    ];
    
    public static function getNoAduan() {
        $mSasCase = DB::table('case_info')
                    ->select('CA_CASEID')
                    ->whereYear('CA_RCVDT', date('Y'))
                    ->orderByRaw('RIGHT(CA_CASEID, 5) DESC')
//                    ->orderBy('CA_RCVDT', 'DESC')
//                    ->limit(1)
                    ->first();
        
        $mRef = DB::table('sys_ref')
            ->select('code')
            ->where('cat', '251')
            ->where('descr', 'like', '%High%')
            ->first()
        ;
        $HeadCaseNum = $mRef->code;
        $LatestNoAduan = $mSasCase->CA_CASEID;
        if($LatestNoAduan == '')
        { 
            $GenNoAduan = "00001";
            $NoAduan = $GenNoAduan;
        }
        else if($LatestNoAduan != '')
        { 
//            $GenNoAduan = $LatestNoAduan + 1;
            $GenNoAduan = substr($LatestNoAduan, -7) + 1;
            $NoAduan = substr("$GenNoAduan",2);
        }
        
        $Year = substr(date('Y'),2,4);
//        $NewNoAduan = "SAS".$Year.$NoAduan;
        $NewNoAduan = $HeadCaseNum.$Year.$NoAduan;
        
        return $NewNoAduan;
    }

    public static function getSelectedDist($STATECD)
    {
        $mRef = DB::table('sys_ref')
                ->where('cat', "18")
                ->where('code', 'LIKE',  "$STATECD%")
                ->orderBy('descr')
                ->pluck('descr','code')
//                ->prepend('0','-- SILA PILIH --');
                ->prepend('-- SILA PILIH --', '');
        return $mRef;
    }

    public function StatusAduan() {
        return $this->hasOne('App\Ref', 'code', 'CA_INVSTS')->where('cat','292');
    }
    public function StatusPerkembangan() {
        return $this->hasOne('App\Ref', 'code', 'CA_CASESTS')->where('cat','306');
    }
    
    public function invby() {
        return $this->hasOne('App\User', 'id', 'CA_INVBY');
    }
    
    public function RcvBy() {
        return $this->hasOne('App\User', 'id', 'CA_RCVBY');
    }
    
    public function againststate() {
        return $this->hasOne('App\Ref', 'code', 'CA_AGAINST_STATECD')->where('cat', '17');
    }
       public function namaCawangan() {
        return $this->hasOne('App\Branch', 'BR_BRNCD', 'CA_BRNCD');
    }
    public static function getcmplcdlist($cmplcat){
        $cmplcdlist = DB::table('sys_ref')
            ->where(['cat' => '634', 'status' => '1'])
            ->where('code', 'LIKE', "$cmplcat%")
            ->orderBy('sort', 'asc')
//            ->orderBy('descr', 'asc')
            ->pluck('descr', 'code')
            ->prepend('-- SILA PILIH --', '');
        return $cmplcdlist;
    }
    public static function getRefList($Cat, $PlsSlct) {
        $mRef = DB::table('sys_ref')
            ->where(['cat' => $Cat, 'status' => '1'])
            ->orderBy('descr', 'asc')
//            ->orderBy('sort', 'asc')
            ->pluck('descr', 'code')
        ;
        if($PlsSlct == true) {
            $mRef->prepend('-- SILA PILIH --', '');
            return $mRef;
        }else{
            return $mRef;
        }
    }
    public static function getrcvtyplist($Cat, $PlsSlct = true, $sort = 'sort') {
        $mRef = DB::table('sys_ref')
            ->where(['cat' => $Cat, 'status' => '1'])
            ->whereIn('code', ['S01','S02','S04','S05','S34','S14','S13','S35'])
            ->orderBy($sort, 'asc')
            ->pluck('descr', 'code')
        ;
        if($PlsSlct == true) {
            $mRef->prepend('-- SILA PILIH --', '');
            return $mRef;
        }else{
            return $mRef;
        }
    }
}
