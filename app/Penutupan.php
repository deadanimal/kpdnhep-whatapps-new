<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use DB;
use App\Wd;
use App\Holiday;
use Carbon\Carbon;

class Penutupan extends Model
{
    use EAduanOld;
    public $table = 'case_info';
    
    public $primaryKey = 'CA_CASEID';
    
    public $incrementing = false;
    
    const CREATED_AT = 'CA_CREDT';
    const UPDATED_AT = 'CA_MODDT';
    const CREATED_BY = 'CA_CREBY';
    const UPDATED_BY = 'CA_MODBY';
    
    protected $fillable = ['CA_CASEID','CA_RCVBY','CA_INVSTS','CA_SUMMARY','CA_RCVTYP',
                'CA_NAME','CA_DOCNO','CA_SEXCD','CA_AGE','CA_ADDR','CA_STATECD','CA_DISTCD','CA_POSCD','CA_TELNO','CA_FAXNO','CA_EMAIL','CA_MOBILENO','CA_NATCD','CA_COUNTRYCD','CA_RACECD',
                'CA_CMPLCAT','CA_CMPLCD','CA_AGAINST_PREMISE','CA_AGAINST_EMAIL','CA_AGAINST_FAXNO','CA_AGAINSTADD','CA_AGAINSTNM','CA_AGAINST_TELNO','CA_AGAINST_MOBILENO','CA_AGAINST_POSTCD','CA_AGAINST_STATECD','CA_AGAINST_DISTCD','CA_RESULT','CA_ANSWER'
        ];
    
    public function StatusAduan() {
        return $this->hasOne('App\Ref', 'code', 'CA_INVSTS')->where('cat','292');
    }
    
    public function namapenyiasat() {
        return $this->hasOne('App\User', 'id', 'CA_INVBY');
    }
    
    public function InvBy() {
        return $this->hasOne('App\User', 'id', 'CA_INVBY');
    }
    
    public function namaPemberiTugas() {
        return $this->hasOne('App\User', 'id', 'CA_ASGBY');
    }
    
    public static function GetStatusList($PlsSlct = true) {
        $mRef = DB::table('sys_ref')->where(['cat' => '292', 'status' => '1'])->whereIn('code', [3, 12])->orderBy('sort', 'asc')->orderBy('descr', 'asc')->pluck('descr', 'code');

        if($PlsSlct == true) {
            $mRef->prepend('-- SILA PILIH --', '');
            return $mRef;
        }else{
            return $mRef;
        }
    }
    
    public function getduration($CA_RCVDT, $CA_CASEID)
    {
        $mPenutupan = Penutupan::where('CA_CASEID', $CA_CASEID)->first();
        $workingDay = new Wd;
        $holiday = new Holiday;
//        $stateCode = $mPenugasanSemula->CA_AGAINST_STATECD;
        if ($mPenutupan->CA_AGAINST_STATECD != null) {
            $stateCode = $mPenutupan->CA_AGAINST_STATECD;
        }
        else if ($mPenutupan->CA_STATECD != null) {
            $stateCode = $mPenutupan->CA_STATECD;
        }
        else {
            $stateCode = 16;
        }
        $RCVDT = Carbon::parse($CA_RCVDT);
        $startDate = date('Y-m-d', strtotime($CA_RCVDT));
//        $todayDate = date('Y-m-d', strtotime($now));
        if($mPenutupan->CA_COMPLETEDT){
            $endDate = date('Y-m-d', strtotime($mPenutupan->CA_COMPLETEDT));
            $CA_COMPLETEDT = $mPenutupan->CA_COMPLETEDT;
        } else {
            $mCarianCaseDetail = DB::table('case_dtl')->where('CD_CASEID', $CA_CASEID)
                ->whereIn('CD_INVSTS', ['4','5','6','8','11','12'])
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
}
