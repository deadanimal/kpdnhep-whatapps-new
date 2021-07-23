<?php

namespace App\Aduan;

use Illuminate\Database\Eloquent\Model;
use App\EAduanOld;
use DB;
use App\Wd;
use App\Holiday;
use Carbon\Carbon;

class BukaSemula extends Model
{
    use EAduanOld;
    public $table = 'case_info';
    
    public $primaryKey = 'CA_CASEID';
    
    public $incrementing = false;
    
    const CREATED_AT = 'CA_CREDT';
    const UPDATED_AT = 'CA_MODDT';
    const CREATED_BY = 'CA_CREBY';
    const UPDATED_BY = 'CA_MODBY';
    
    protected $fillable = [
        'CA_CASEID','CA_RCVBY','CA_INVSTS','CA_SUMMARY','CA_RCVTYP','CA_NAME','CA_BRNCD','CA_DEPTCD','CA_STATUSPENGADU','CA_RESIDENTIALSTATUS',
        'CA_DOCNO','CA_SEXCD','CA_AGE','CA_ADDR','CA_STATECD','CA_DISTCD',
        'CA_POSCD','CA_TELNO','CA_FAXNO','CA_EMAIL','CA_MOBILENO','CA_NATCD',
        'CA_COUNTRYCD','CA_RACECD','CA_CMPLCAT','CA_CMPLCD',
        'CA_INVBY','CA_SSP','CA_IPNO','CA_AKTA','CA_SUBAKTA',
        'CA_AGAINST_PREMISE','CA_AGAINST_EMAIL','CA_AGAINST_FAXNO','CA_AGAINSTADD',
        'CA_AGAINSTNM','CA_AGAINST_TELNO','CA_AGAINST_MOBILENO','CA_AGAINST_POSTCD',
        'CA_AGAINST_STATECD','CA_AGAINST_DISTCD','CA_RESULT','CA_ANSWER'
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
    
    public function AsgBy() {
        return $this->hasOne('App\User', 'id', 'CA_ASGBY');
    }
    
    public function InvBy() {
        return $this->hasOne('App\User', 'id', 'CA_INVBY');
    }
    
    public function CompleteBy() {
        return $this->hasOne('App\User', 'id', 'CA_COMPLETEBY');
    }
    
    public function CloseBy() {
        return $this->hasOne('App\User', 'id', 'CA_CLOSEBY');
    }
    
    public function casedetail() {
        return $this->hasOne('App\Aduan\BukaSemulaDetail', 'CD_CASEID', 'CA_CASEID')->latest('CD_CREDT');
    }
    
    public static function getbrnlist($PleaseSelect = true) {
        $mBranch = DB::table('sys_brn')
            ->orderBy('BR_BRNCD', 'asc')
            ->pluck('BR_BRNNM', 'BR_BRNCD');
        if($PleaseSelect == true) {
            $mBranch->prepend('-- SILA PILIH --', '');
            return $mBranch;
        }else{
            return $mBranch;
        }
    }
    
    public function getduration($CA_RCVDT, $CA_CASEID)
    {
        $mBukaSemula = BukaSemula::where('CA_CASEID', $CA_CASEID)->first();
        $workingDay = new Wd;
        $holiday = new Holiday;
//        $stateCode = $mPenugasanSemula->CA_AGAINST_STATECD;
        if ($mBukaSemula->CA_AGAINST_STATECD != null) {
            $stateCode = $mBukaSemula->CA_AGAINST_STATECD;
        }
        else if ($mBukaSemula->CA_STATECD != null) {
            $stateCode = $mBukaSemula->CA_STATECD;
        }
        else {
            $stateCode = 16;
        }
        $RCVDT = Carbon::parse($CA_RCVDT);
        $startDate = date('Y-m-d', strtotime($CA_RCVDT));
//        $todayDate = date('Y-m-d', strtotime($now));
        if($mBukaSemula->CA_COMPLETEDT){
            $endDate = date('Y-m-d', strtotime($mBukaSemula->CA_COMPLETEDT));
            $CA_COMPLETEDT = $mBukaSemula->CA_COMPLETEDT;
        } else {
            $mCarianCaseDetail = DB::table('case_dtl')->where('CD_CASEID', $CA_CASEID)
                ->whereIn('CD_INVSTS', ['4','5','6','8','9','11'])
                ->orderBy('CD_CREDT', 'DESC')
                ->first();
            if ($mCarianCaseDetail) {
                $endDate = date('Y-m-d', strtotime($mCarianCaseDetail->CD_CREDT));
                $CA_COMPLETEDT = $mCarianCaseDetail->CD_CREDT;
            } else {
                $endDate = date('Y-m-d', strtotime(Carbon::now()));
                $CA_COMPLETEDT = Carbon::now();
            }
        }
        $offDay = $workingDay->offDay($stateCode); // DAPATKAN HARI CUTI MINGGUAN MENGIKUT NEGERI
        $holidayDay = $holiday->off($startDate, $endDate, $stateCode); // KIRAAN CUTI MENGIKUT NEGERI
        $repeatHoliday = $holiday->repeatedOffday($startDate, $endDate, $stateCode); // KIRAAN CUTI BERULANG MENGIKUT NEGERI
        $duration = $workingDay->getWorkingDay($RCVDT, $CA_COMPLETEDT, $offDay); // KIRAAN CUTI MINGGUAN DALAM MENGIKUT NEGERI
        $totalDuration = $duration - ($holidayDay + $repeatHoliday); // CUTI DALAM TEMPOH ADUAN
        return $totalDuration;
    }
    
    public static function getNoAduan($RCVTYP) {
        $mBukaSemula = DB::table('case_info')
            ->select('CA_CASEID')
            ->where('CA_CASEID','<>',NULL)
            ->whereYear('CA_RCVDT', date('Y'))
            ->orderBy('id', 'DESC')
//                    ->orderBy('CA_RCVDT', 'DESC')
            ->limit(1)
            ->first();
        
        if ($RCVTYP == 'S28'){
            $descr = 'Call Center';
        } else if ($RCVTYP == 'S29'){
            $descr = 'ezAdu';
        } else {
            $descr = 'Web';
        }
        
        $mRef = DB::table('sys_ref')
            ->select('code')
            ->where('cat', '251')
            ->where('descr', $descr)
            ->first();
        $headNoAduan = $mRef->code;
        
        $latestNoAduan = $mBukaSemula->CA_CASEID;
        if($latestNoAduan == '') {
            $genNoAduan = "00001";
            $noAduan = $genNoAduan;
        } else if($latestNoAduan != '') {
            $genNoAduan = substr($latestNoAduan, -7) + 1;
            $noAduan = substr("$genNoAduan", 2);
        }
        
        $year = substr(date('Y'), 2, 4);
        $newNoAduan = $headNoAduan.$year.$noAduan;
        return $newNoAduan;
    }
    
    public static function GetStatusList($PlsSlct = true) {
        
        $mRef = DB::table('sys_ref')->where(['cat' => '292', 'status' => '1'])->whereIn('code',[4,5,6,8,9,11])->orderBy('sort', 'asc')->orderBy('descr', 'asc')->pluck('descr', 'code');

        if($PlsSlct == true) {
            $mRef->prepend('-- SILA PILIH --', '');
            return $mRef;
        }else{
            return $mRef;
        }
        
    }
}
