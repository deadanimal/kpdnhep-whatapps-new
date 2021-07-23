<?php

namespace App\Aduan;

use Illuminate\Database\Eloquent\Model;
use App\EAduanOld;
use DB;
use App\Wd;
use App\Holiday;
use Carbon\Carbon;

class PenugasanSemula extends Model
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
        'CA_CASEID','CA_RCVBY','CA_INVSTS','CA_SUMMARY','CA_RCVTYP','CA_NAME',
        'CA_DOCNO','CA_SEXCD','CA_AGE','CA_ADDR','CA_STATECD','CA_DISTCD',
        'CA_POSCD','CA_TELNO','CA_FAXNO','CA_EMAIL','CA_MOBILENO','CA_NATCD',
        'CA_COUNTRYCD','CA_RACECD','CA_CMPLCAT','CA_CMPLCD',
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
    
    public function penugasansemulaoleh() {
        return $this->hasOne('App\User', 'id', 'CA_ASGBY');
    }
    
    public function namapenyiasat() {
        return $this->hasOne('App\User', 'id', 'CA_INVBY');
    }
    
    public function InvBy() {
        return $this->hasOne('App\User', 'id', 'CA_INVBY');
    }
    
    public function ditutupoleh() {
        return $this->hasOne('App\User', 'id', 'CA_CLOSEBY');
    }
    
    public static function getcmplcdlist($CMPLCAT, $placeholder = true) {
        $mCmplcdlist = DB::table('sys_ref')
            ->where('code', 'LIKE', $CMPLCAT.'%')
            ->where('code', '!=', $CMPLCAT)
            ->where('cat', '634')
            ->pluck('descr', 'code');
        if($placeholder == true) {
            $mCmplcdlist->prepend('-- SILA PILIH --', '');
            return $mCmplcdlist;
        } else{
            return $mCmplcdlist;
        }
    }
    
    public function getduration($CA_RCVDT, $CA_CASEID)
    {
        $mPenugasanSemula = PenugasanSemula::where('CA_CASEID', $CA_CASEID)->first();
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
        $RCVDT = Carbon::parse($CA_RCVDT);
        $start = date('Y-m-d', strtotime($CA_RCVDT));
        if($mPenugasanSemula->CA_COMPLETEDT){
            $end = date('Y-m-d', strtotime($mPenugasanSemula->CA_COMPLETEDT));
            $CA_COMPLETEDT = $mPenugasanSemula->CA_COMPLETEDT;
        } else {
            $mPenugasanSemulaCaseDetail = DB::table('case_dtl')->where('CD_CASEID', $CA_CASEID)
                ->whereIn('CD_INVSTS', ['4','5','6','8','11','12'])
                ->orderBy('CD_CREDT', 'DESC')
                ->first();
            if (count($mPenugasanSemulaCaseDetail) > 0) {
                $end = date('Y-m-d', strtotime($mPenugasanSemulaCaseDetail->CD_CREDT));
                $CA_COMPLETEDT = $mPenugasanSemulaCaseDetail->CD_CREDT;
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
    
    public static function GetStatusList()
    {
        $mRef = DB::table('sys_ref')
            ->where(['cat' => '292', 'status' => '1'])
            ->whereIn('code', [0, 2])
            ->orderBy('sort', 'asc')
            ->orderBy('descr', 'asc')
            ->pluck('descr', 'code')
            ->prepend('-- SILA PILIH --','');
        return $mRef;
    }
}
