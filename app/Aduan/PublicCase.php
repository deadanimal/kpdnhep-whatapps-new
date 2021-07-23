<?php

namespace App\Aduan;

use Illuminate\Database\Eloquent\Model;
use DB;
use App\EAduanOld;
use Carbon\Carbon;
use App\Wd;
use App\Holiday;
use App\Ref;

class PublicCase extends Model {

    use EAduanOld;

    public $table = "case_info";
    public $primaryKey = 'id';

//    public $incrementing = false;

    const CREATED_AT = 'CA_CREDT';
    const UPDATED_AT = 'CA_MODDT';
    const CREATED_BY = 'CA_CREBY';
    const UPDATED_BY = 'CA_MODBY';

    protected $fillable = ['CA_TTPMNO', 'CA_TTPMTYP', 'CA_CASEID', 'CA_RCVBY', 'CA_INVSTS', 'CA_SUMMARY', 'CA_RCVTYP', 'CA_ONLINECMPL_CASENO',
        'CA_NAME', 'CA_DOCNO', 'CA_SEXCD', 'CA_AGE', 'CA_ADDR', 'CA_STATECD', 'CA_DISTCD', 'CA_POSCD', 'CA_TELNO', 'CA_FAXNO', 'CA_EMAIL', 'CA_MOBILENO', 'CA_NATCD', 'CA_COUNTRYCD', 'CA_RACECD',
        'CA_CMPLCAT', 'CA_CMPLCD', 'CA_AGAINST_PREMISE', 'CA_ONLINECMPL_PROVIDER', 'CA_ONLINECMPL_BANKCD', 'CA_ONLINECMPL_AMOUNT', 'CA_ONLINECMPL_ACCNO', 'CA_AGAINST_EMAIL', 'CA_AGAINST_FAXNO', 'CA_AGAINSTADD', 'CA_AGAINSTNM', 'CA_AGAINST_TELNO', 'CA_AGAINST_MOBILENO', 'CA_AGAINST_POSTCD', 'CA_AGAINST_STATECD', 'CA_AGAINST_DISTCD', 'CA_RESULT', 'CA_ANSWER'
    ];

    public function CaseStatus() {
        return $this->hasOne('App\Ref', 'code', 'CA_INVSTS')->where('cat', '292');
    }

    public static function getNoAduan() {
        $mSasCase = DB::table('case_info')
                ->select('CA_CASEID')
                ->whereYear('CA_RCVDT', date('Y'))
                ->orderByRaw('RIGHT(CA_CASEID, 5) DESC')
//                    ->orderBy('CA_RCVDT', 'DESC')
//                    ->limit(1);
                ->first();

        $mRef = DB::table('sys_ref')
                ->select('code')
                ->where('descr', 'Web')
                ->first();
        $HeadCaseNum = $mRef->code;

        if ($mSasCase) {
            $LatestNoAduan = $mSasCase->CA_CASEID;
        } else {
            $LatestNoAduan = '';
        }
        if ($LatestNoAduan == '') {
            $GenNoAduan = "00001";
            $NoAduan = $GenNoAduan;
        } else if ($LatestNoAduan != '') {
//            $GenNoAduan = $LatestNoAduan + 1;
            $GenNoAduan = substr($LatestNoAduan, -7) + 1;
            $NoAduan = substr("$GenNoAduan", 2);
        }

        $Year = substr(date('Y'), 2, 4);
        $NewNoAduan = $HeadCaseNum . $Year . $NoAduan;

        return $NewNoAduan;
    }

    public static function getNoAduanMobile() {
        $mPublicCase = DB::table('case_info')
                ->select('CA_CASEID')
                ->whereYear('CA_RCVDT', date('Y'))
                ->orderByRaw('RIGHT(CA_CASEID, 5) DESC')
                ->first();

        $mRef = DB::table('sys_ref')
                ->select('code')
                ->where('descr', 'like', '%ezAdu%')
                ->first();
        $HeadCaseNum = $mRef->code;

        if ($mPublicCase) {
            $LatestNoAduan = $mPublicCase->CA_CASEID;
        } else {
            $LatestNoAduan = '';
        }

        if ($LatestNoAduan == '') {
            $GenNoAduan = "00001";
            $NoAduan = $GenNoAduan;
        } else if ($LatestNoAduan != '') {
            $GenNoAduan = substr($LatestNoAduan, -7) + 1;
            $NoAduan = substr("$GenNoAduan", 2);
        }

        $Year = substr(date('Y'), 2, 4);
        $NewNoAduan = $HeadCaseNum . $Year . $NoAduan;

        return $NewNoAduan;
    }

    public static function GetCmplCd($CmplCat, $Lang) {
        if ($Lang == 'ms') {
            $CmplCdList = DB::table('sys_ref')
                    ->where(['cat' => '634', 'status' => '1'])
                    ->where('code', 'LIKE', "$CmplCat%")
//                ->orderBy('descr', 'asc')
                    ->orderBy('sort', 'asc')
                    ->pluck('descr', 'code');
            $CmplCdList->prepend('-- SILA PILIH --', '');
        } elseif ($Lang == 'en') {
            $CmplCdList = DB::table('sys_ref')
                    ->where(['cat' => '634', 'status' => '1'])
                    ->where('code', 'LIKE', "$CmplCat%")
//                ->orderBy('descr_en', 'asc')
                    ->orderBy('sort', 'asc')
                    ->pluck('descr_en', 'code');
            $CmplCdList->prepend('-- PLEASE SELECT --', '');
        } else {
            $CmplCdList = DB::table('sys_ref')
                    ->where(['cat' => '634', 'status' => '1'])
                    ->where('code', 'LIKE', "$CmplCat%")
//                ->orderBy('descr_en', 'asc')
                    ->orderBy('sort', 'asc')
                    ->pluck('descr_en', 'code');
        }

        return $CmplCdList;
    }

//    public static function GetDstrtList($state_cd) {
//        $mDstrtList = DB::table('sys_ref')
//                ->where('code', 'LIKE', $state_cd . '%')->where('code', '!=', $state_cd)->where('cat', '18')
//                ->pluck('descr', 'code');
//        $mDstrtList->prepend('-- SILA PILIH --', '');
//        return json_encode($mDstrtList);
//    }
//    public static function GetDstrtList($state_cd, $placeholder = true) {
//        $mDstrtList = DB::table('sys_ref')
//                ->where('code','LIKE', $state_cd.'%')->where('code', '!=', $state_cd)->where('cat', '18')
//                ->pluck('descr','code');
//        if($placeholder == true) {
//            $mDstrtList->prepend('-- SILA PILIH --', '');
//            return $mDstrtList;
//        } else{
//            return $mDstrtList;
//        }
//    }

    public static function GetDstrtList($state_cd, $PlsSlct = true, $Lang = 'ms') {
        if ($Lang == 'ms') {
            $mDstrtList = DB::table('sys_ref')
                    ->where('code', 'LIKE', $state_cd . '%')->where('code', '!=', $state_cd)->where('cat', '18')
                    ->pluck('descr', 'code');
            if ($PlsSlct == true) {
                $mDstrtList->prepend('-- SILA PILIH --', '');
                return $mDstrtList;
            } else {
                return $mDstrtList;
            }
        } else {
            $mDstrtList = DB::table('sys_ref')
                            ->where('code', 'LIKE', $state_cd . '%')->where('code', '!=', $state_cd)->where('cat', '18')
                            ->orderBy('sort', 'asc')->pluck('descr_en', 'code');

            if ($PlsSlct == true) {
                $mDstrtList->prepend('-- PLEASE SELECT --', '');
                return $mDstrtList;
            } else {
                return $mDstrtList;
            }
        }
    }

    public function GetDistList($state_cd) {
        if (Auth::user()->lang == 'en') {
            $mDistList = DB::table('sys_ref')
                    ->where('cat', '18')
                    ->where('code', 'like', "$state_cd%")
                    ->orderBy('sort')
                    ->pluck('code', 'descr')
                    ->prepend('', '--  PLEASE SELECT --');
        } else {
            $mDistList = DB::table('sys_ref')
                    ->where('cat', '18')
                    ->where('code', 'like', "$state_cd%")
                    ->orderBy('sort')
                    ->pluck('code', 'descr_en')
                    ->prepend('', '-- SILA PILIH --');
        }

        return json_encode($mDistList);
    }

    public function GetDuration($CA_RCVDT, $CA_CASEID) {
        $mPublicCase = PublicCase::where(['CA_CASEID' => $CA_CASEID])->first();
        $Working_day = new Wd();
        $Holidays = new Holiday();
//        $state_code = $mPublicCase->CA_AGAINST_STATECD;
        if ($mPublicCase->CA_AGAINST_STATECD != null) {
            $state_code = $mPublicCase->CA_AGAINST_STATECD;
        }
        else if ($mPublicCase->CA_STATECD != null) {
            $state_code = $mPublicCase->CA_STATECD;
        }
        else {
            $state_code = 16;
        }
        $now = \Carbon\Carbon::now();
        $RCVDT = Carbon::parse($CA_RCVDT);
        $start = date('Y-m-d', strtotime($CA_RCVDT));
        $end = date('Y-m-d', strtotime($now));
        $offDay = $Working_day->offDay($state_code);  // DAPATKAN HARI CUTI MINGGUAN MENGIKUT NEGERI
        $holidayDay = $Holidays->off($start, $end, $state_code); //   KIRAAN CUTI MENGIKUT NEGERI
        $repeatHoliday = $Holidays->repeatedOffday($start, $end, $state_code); //   KIRAAN CUTI BERULANG MENGIKUT NEGERI
        $Duration = $Working_day->getWorkingDay($RCVDT, $now, $offDay); // KIRAAN CUTI MINGGUAN DALAM MENGIKUT NEGERI
        $TotalDuration = $Duration - ($holidayDay + $repeatHoliday); // CUTI DALAM TEMPOH ADUAN

        return $TotalDuration;
    }
    
    public function GetTempohMaklumatTidakLengkap() {
        $mTempoh = Ref::where('cat', 1247)->first();
        return $mTempoh->code;
    }

    public function Casedoc() {
        return $this->hasOne('App\Aduan\PublicCaseDoc', 'CC_CASEID', 'CA_CASEID');
    }

    public function BrnCd() {
        return $this->hasOne('App\Branch', 'BR_BRNCD', 'CA_BRNCD');
    }

    public static function getRefList($Cat, $PlsSlct, $Lang = 'ms', $sort = 'sort') {
        if ($Lang == 'ms') {
            $mRef = DB::table('sys_ref')
                    ->where(['cat' => $Cat, 'status' => '1'])
                    ->whereNotIn('code', ['4', '5', '6', '7', '8', '9', '0', '11', '12'])
                    ->orderBy('descr', 'asc')
//            ->orderBy('sort', 'asc')
                    ->pluck('descr', 'code')
            ;
            if ($PlsSlct == true) {
                $mRef->prepend('-- SILA PILIH --', '');
                return $mRef;
            } else {
                return $mRef;
            }
        }else{
            $mRef = DB::table('sys_ref')
                    ->where(['cat' => $Cat, 'status' => '1'])
                    ->whereNotIn('code', ['4', '5', '6', '7', '8', '9', '0', '11', '12'])
                    ->orderBy('descr_en', 'asc')
//            ->orderBy('sort', 'asc')
                    ->pluck('descr_en', 'code')
            ;
            if ($PlsSlct == true) {
                $mRef->prepend('-- PLEASE SELECT --', '');
                return $mRef;
            } else {
                return $mRef;
            }
        }
    }
    
    public function daerahpengadu() {
        return $this->hasOne('App\Ref', 'code', 'CA_DISTCD')->where('cat', '18');
    }
    
    public function negeripengadu() {
        return $this->hasOne('App\Ref', 'code', 'CA_STATECD')->where('cat', '17');
    }
    
    public function InvBy() {
        return $this->hasOne('App\User', 'id', 'CA_INVBY');
    }
    
}