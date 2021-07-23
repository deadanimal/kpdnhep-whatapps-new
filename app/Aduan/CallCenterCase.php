<?php

namespace App\Aduan;

use Illuminate\Database\Eloquent\Model;
use DB;
use App\Wd;
use App\Holiday;
use Carbon\Carbon;
use App\EAduanOld;

class CallCenterCase extends Model
{
    use EAduanOld;
    
    public $table = 'case_info';
//    public $primaryKey = 'CA_CASEID';
    public $primaryKey = 'id';
//    public $incrementing = false;
    
    const CREATED_AT = 'CA_CREDT';
    const UPDATED_AT = 'CA_MODDT';
    const CREATED_BY = 'CA_CREBY';
    const UPDATED_BY = 'CA_MODBY';
    
    protected $fillable = ['CA_ROUTETOHQIND','CA_RCVTYP','CA_CASEID',
                'CA_NAME','CA_DOCNO','CA_SEXCD','CA_AGE','CA_ADDR','CA_STATECD','CA_DISTCD','CA_POSCD','CA_TELNO','CA_FAXNO','CA_EMAIL','CA_MOBILENO','CA_NATCD','CA_COUNTRYCD','CA_RACECD','CA_SUMMARY',
                'CA_CMPLCAT','CA_CMPLCD','CA_AGAINST_PREMISE','CA_AGAINST_EMAIL','CA_AGAINST_FAXNO','CA_AGAINSTADD','CA_AGAINSTNM','CA_AGAINST_TELNO','CA_AGAINST_MOBILENO','CA_AGAINST_POSTCD','CA_AGAINST_STATECD','CA_AGAINST_DISTCD',
                'CA_ONLINECMPL_AMOUNT',
                'CA_MYIDENTITY_ADDR','CA_MYIDENTITY_POSCD','CA_MYIDENTITY_STATECD','CA_MYIDENTITY_DISTCD','CA_STATUSPENGADU'
        ];
    
    public function daerahpengadu() {
        return $this->hasOne('App\Ref', 'code', 'CA_DISTCD')->where('cat', '18');
    }
    
    public function negeripengadu() {
        return $this->hasOne('App\Ref', 'code', 'CA_STATECD')->where('cat', '17');
    }
    
    public static function getNoAduan() {
        $mSasCase = DB::table('case_info')
                    ->select('CA_CASEID')
                    ->whereYear('CA_RCVDT', date('Y'))
                    ->orderByRaw('RIGHT(CA_CASEID, 5) DESC')
//                    ->orderBy('CA_RCVDT', 'DESC')
//                    ->limit(1)
                    ->first();
        if($mSasCase){
            $LatestNoAduan = $mSasCase->CA_CASEID;
        } else {
            $LatestNoAduan = '';
        }
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
        $NewNoAduan = "00".$Year.$NoAduan;
        
        return $NewNoAduan;
    }
    
    public function statusAduan() {
        return $this->hasOne('App\Ref', 'code', 'CA_INVSTS')->where('cat','292');
    }
    
    public function getduration($CA_RCVDT, $CA_CASEID)
    {
        $mPenugasanSemula = CallCenterCase::where('CA_CASEID', $CA_CASEID)->first();
        $workingDay = new Wd;
        $holiday = new Holiday;
//        $stateCode = $mPenugasanSemula->CA_AGAINST_STATECD;
        if ($mPenugasanSemula->CA_AGAINST_STATECD != null) {
            $stateCode = $mPenugasanSemula->CA_AGAINST_STATECD;
        }
        else if ($mPenugasanSemula->CA_STATECD != null) {
            $stateCode = $mPenugasanSemula->CA_STATECD;
        }
        else {
            $stateCode = 16;
        }
        $now = Carbon::now();
        $RCVDT = Carbon::parse($CA_RCVDT);
        $startDate = date('Y-m-d', strtotime($CA_RCVDT));
//        $todayDate = date('Y-m-d', strtotime($now));
        if($mPenugasanSemula->CA_COMPLETEDT){
            $todayDate = date('Y-m-d', strtotime($mPenugasanSemula->CA_COMPLETEDT));
            $CA_COMPLETEDT = $mPenugasanSemula->CA_COMPLETEDT;
        } else {
            $todayDate = date('Y-m-d', strtotime(Carbon::now()));
            $CA_COMPLETEDT = Carbon::now();
        }
        $offDay = $workingDay->offDay($stateCode); // DAPATKAN HARI CUTI MINGGUAN MENGIKUT NEGERI
        $holidayDay = $holiday->off($todayDate, $startDate, $stateCode); // KIRAAN CUTI MENGIKUT NEGERI
        $duration = $workingDay->getWorkingDay($RCVDT, $CA_COMPLETEDT, $offDay); // KIRAAN CUTI MINGGUAN DALAM MENGIKUT NEGERI
        $totalDuration = $duration - $holidayDay; // CUTI DALAM TEMPOH ADUAN
        return $totalDuration;
    }
    
        public static function getonlinecdlist($cmplcat) {
        $mCmplcdlist = DB::table('sys_ref')
            ->where('cat', '634')
            ->where('code', 'like', $cmplcat.'%')
            ->where('code', '!=', $cmplcat)
            ->pluck('descr', 'code')
            ->prepend('-- SILA PILIH --', '');
        return $mCmplcdlist;
    }
    
    public static function GetCmplCd($CmplCat)
    {
        $CmplCdList = DB::table('sys_ref')
            ->where(['cat' => '634', 'status' => '1'])
            ->where('code', 'LIKE', "$CmplCat%")
            ->orderBy('sort', 'asc')
            ->pluck('descr', 'code');
        $CmplCdList->prepend('-- SILA PILIH --', '');
            return $CmplCdList;
            
    }
    public static function GetDstrtList($state_cd) {
        $mDstrtList = DB::table('sys_ref')
            ->where('code','LIKE', $state_cd.'%')->where('code', '!=', $state_cd)->where('cat', '18')
            ->pluck('descr','code');
        return $mDstrtList;
    }
}
