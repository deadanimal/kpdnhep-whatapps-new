<?php

namespace App\Aduan;

use Illuminate\Database\Eloquent\Model;
use App\EAduanOld;
use App\Wd;
use App\Holiday;
use Carbon\Carbon;
use DB;

class AdminCase extends Model
{
    use EAduanOld;
    public $table = 'case_info';
    
    public $primaryKey = 'id';
//    public $primaryKey = 'CA_CASEID';
    
//    public $incrementing = false;
    
    const CREATED_AT = 'CA_CREDT';
    const UPDATED_AT = 'CA_MODDT';
    const CREATED_BY = 'CA_CREBY';
    const UPDATED_BY = 'CA_MODBY';
    
    protected $fillable = [
        'CA_CASEID','CA_INVSTS','CA_RCVTYP','CA_RCVBY','CA_ANSWER','CA_RESULT','CA_CMPLCAT','CA_CMPLCD',
        'CA_SUMMARY','CA_SEXCD','CA_NAME','CA_DOCNO','CA_AGE','CA_ADDR','CA_DISTCD','CA_POSCD','CA_STATECD',
        'CA_NATCD','CA_COUNTRYCD','CA_TELNO','CA_FAXNO','CA_EMAIL','CA_MOBILENO','CA_RACECD',
        'CA_AGAINSTNM','CA_AGAINSTADD','CA_AGAINST_POSTCD','CA_AGAINST_TELNO','CA_AGAINST_FAXNO',
        'CA_AGAINST_MOBILENO','CA_AGAINST_PREMISE','CA_AGAINST_DISTCD','CA_AGAINST_STATECD','CA_AGAINST_EMAIL',
        'CA_SERVICENO','CA_TTPMTYP','CA_TTPMNO',
        'CA_ONLINECMPL_PROVIDER','CA_ONLINECMPL_URL','CA_ONLINECMPL_AMOUNT','CA_ONLINECMPL_ACCNO',
        'CA_ONLINECMPL_BANKCD','CA_ONLINECMPL_IND','CA_ONLINECMPL_CASENO','CA_ONLINEADD_IND',
        'CA_MYIDENTITY_ADDR','CA_MYIDENTITY_POSCD','CA_MYIDENTITY_STATECD','CA_MYIDENTITY_DISTCD',
        'CA_STATUSPENGADU','CA_ROUTETOHQIND', 'feedback_module_id',
    ];
    
    public function statusAduan() {
        return $this->hasOne('App\Ref', 'code', 'CA_INVSTS')->where('cat','292');
    }
    
    public function statusPerkembangan() {
        return $this->hasOne('App\Ref', 'code', 'CA_CASESTS')->where('cat','306');
    }
    
    public function namapenerima() {
        return $this->hasOne('App\User', 'id', 'CA_RCVBY');
    }
    
    public function casedoc() {
        return $this->hasOne('App\AdminCaseDoc', 'CC_CASEID', 'CA_CASEID');
    }
    
    public function daerahpengadu() {
        return $this->hasOne('App\Ref', 'code', 'CA_DISTCD')->where('cat', '18');
    }
    
    public function negeripengadu() {
        return $this->hasOne('App\Ref', 'code', 'CA_STATECD')->where('cat', '17');
    }

    public function receivedType() {
        return $this->hasOne('App\Ref', 'code', 'CA_RCVTYP')->where('cat', '259');
    }

    public function sex() {
        return $this->hasOne('App\Ref', 'code', 'CA_SEXCD')->where('cat', '202');
    }

    public function race() {
        return $this->hasOne('App\Ref', 'code', 'CA_RACECD')->where('cat', '580');
    }

    public function nationality() {
        return $this->hasOne('App\Ref', 'code', 'CA_NATCD')->where('cat', '947');
    }

    public function country() {
        return $this->hasOne('App\Ref', 'code', 'CA_COUNTRYCD')->where('cat', '334');
    }

    public function category() {
        return $this->hasOne('App\Ref', 'code', 'CA_CMPLCAT')->where('cat', '244');
    }

    public function subCategory() {
        return $this->hasOne('App\Ref', 'code', 'CA_CMPLCD')->where('cat', '634');
    }

    public function ttpmType() {
        return $this->hasOne('App\Ref', 'code', 'CA_TTPMTYP')->where('cat', '1108');
    }

    // jenis barangan
    public function productType() {
        return $this->hasOne('App\Ref', 'code', 'CA_CMPLKEYWORD')->where('cat', '1051');
    }

    // jenis premis
    public function premiseType() {
        return $this->hasOne('App\Ref', 'code', 'CA_AGAINST_PREMISE')->where('cat', '221');
    }

    // pembekal perkhidmatan
    public function serviceProvider() {
        return $this->hasOne('App\Ref', 'code', 'CA_ONLINECMPL_PROVIDER')->where('cat', '1091');
    }

    // cara pembayaran
    public function paymentType() {
        return $this->hasOne('App\Ref', 'code', 'CA_ONLINECMPL_PYMNTTYP')->where('cat', '1207');
    }

    // nama bank
    public function bankName() {
        return $this->hasOne('App\Ref', 'code', 'CA_ONLINECMPL_BANKCD')->where('cat', '1106');
    }

    // daerah diadu
    public function againstDistrict() {
        return $this->hasOne('App\Ref', 'code', 'CA_AGAINST_DISTCD')->where('cat', '18');
    }

    // negeri diadu
    public function againstState() {
        return $this->hasOne('App\Ref', 'code', 'CA_AGAINST_STATECD')->where('cat', '17');
    }

    public static function getcmplcdlist($cmplcat) {
        $mCmplcdlist = DB::table('sys_ref')
            ->where('cat', '634')
            ->where('code', 'like', $cmplcat.'%')
            ->where('status', '=', '1')
            ->where('code', '!=', $cmplcat)
            ->orderBy('sort', 'asc')
//            ->orderBy('descr', 'asc')
            ->pluck('descr', 'code')
            ->prepend('-- SILA PILIH --', '');
        return $mCmplcdlist;
    }
    
    public function getduration($CA_RCVDT, $CA_CASEID)
    {
        $mAdminCase = AdminCase::where('CA_CASEID', $CA_CASEID)->first();
        $workingDay = new Wd;
        $holiday = new Holiday;
        $stateCode = $mAdminCase->CA_AGAINST_STATECD ?? $mAdminCase->CA_STATECD ?? 16;
        $RCVDT = Carbon::parse($CA_RCVDT);
        $start = date('Y-m-d', strtotime($CA_RCVDT));
        if($mAdminCase->CA_COMPLETEDT){
            $end = date('Y-m-d', strtotime($mAdminCase->CA_COMPLETEDT));
            $CA_COMPLETEDT = $mAdminCase->CA_COMPLETEDT;
        } else {
            $mCarianCaseDetail = DB::table('case_dtl')->where('CD_CASEID', $CA_CASEID)
                ->whereIn('CD_INVSTS', ['4','5','6','8','11','12'])
                ->orderBy('CD_CREDT', 'DESC')
                ->first();
            if ($mCarianCaseDetail) {
                $end = date('Y-m-d', strtotime($mCarianCaseDetail->CD_CREDT));
                $CA_COMPLETEDT = $mCarianCaseDetail->CD_CREDT;
            } else {
                $end = date('Y-m-d', strtotime(Carbon::now()));
                $CA_COMPLETEDT = Carbon::now();
            }
        }
        $offDay = $workingDay->offDay($stateCode); // DAPATKAN HARI CUTI MINGGUAN MENGIKUT NEGERI
        $holidayDay = $holiday->off($start, $end, $stateCode); // KIRAAN CUTI MENGIKUT NEGERI
        $repeatHoliday = $holiday->repeatedOffday($start, $end, $stateCode); // KIRAAN CUTI BERULANG MENGIKUT NEGERI
        $duration = $workingDay->getWorkingDay($RCVDT, $CA_COMPLETEDT, $offDay); // KIRAAN CUTI MINGGUAN DALAM MENGIKUT NEGERI
        $totalDuration = $duration - ($holidayDay + $repeatHoliday); // CUTI DALAM TEMPOH ADUAN
        return $totalDuration;
    }
    
    public static function getinvstslist($Cat, $PlsSlct) {
        $mRef = DB::table('sys_ref')
            ->where(['cat' => $Cat, 'status' => '1'])
            ->whereNotIn('code', [10])
            ->orderBy('sort', 'asc')
            ->orderBy('descr', 'asc')
            ->pluck('descr', 'code');
        if($PlsSlct == true) {
            $mRef->prepend('-- SILA PILIH --', '');
            return $mRef;
        }else{
            return $mRef;
        }
    }
    
    public static function getRefList($Cat, $PlsSlct) {
        $mRef = DB::table('sys_ref')
            ->where(['cat' => $Cat, 'status' => '1'])
            ->whereNotIn('code',['S01','S02','S03','S04','S05','S06','S13','S14','S28','S29','S34','S35'])
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
    
    public static function getcaseid(){
        $mAdminCase = DB::table('case_info')
            ->select('CA_CASEID')
            ->whereYear('CA_RCVDT', date('Y'))
            ->orderByRaw('RIGHT(CA_CASEID, 5) DESC')
//            ->orderBy('CA_RCVDT', 'DESC')
//            ->limit(1)
            ->first()
        ;
        $mRef = DB::table('sys_ref')
            ->select('code')
            ->where('descr', 'Web')
            ->first()
        ;
        $HeadCaseNum = $mRef->code;
        $LatestNoAduan = $mAdminCase->CA_CASEID;
        if($LatestNoAduan == '')
        {
            $GenNoAduan = "00001";
            $NoAduan = $GenNoAduan;
        }
        else if($LatestNoAduan != '')
        {
            $GenNoAduan = substr($LatestNoAduan, -7) + 1;
            $NoAduan = substr("$GenNoAduan",2);
        }
        $Year = substr(date('Y'),2,4);
        $NewNoAduan = $HeadCaseNum.$Year.$NoAduan;
        return $NewNoAduan;
    }
}
