<?php

namespace App\Aduan;

use App\Wd;
use App\Holiday;
use Carbon\Carbon;
use DB;
use Illuminate\Database\Eloquent\Model;

class MaklumatXLengkap extends Model
{
    public $table = 'case_info';
    
    public $primaryKey = 'id';
//    public $primaryKey = 'CA_CASEID';

    const CREATED_AT = 'CA_CREDT';
    const UPDATED_AT = 'CA_MODDT';
    const CREATED_BY = 'CA_CREBY';
    const UPDATED_BY = 'CA_MODBY';
    
    public static function getduration($CA_RCVDT, $CA_CASEID)
    {
        $mAdminCase = MaklumatXLengkap::where('CA_CASEID', $CA_CASEID)->first();
        $workingDay = new Wd;
        $holiday = new Holiday;
        // $stateCode = $mAdminCase->CA_AGAINST_STATECD;
        if ($mAdminCase->CA_AGAINST_STATECD != null) {
            $stateCode = $mAdminCase->CA_AGAINST_STATECD;
        }
        else if ($mAdminCase->CA_STATECD != null) {
            $stateCode = $mAdminCase->CA_STATECD;
        }
        else {
            $stateCode = 16;
        }
//        $now = Carbon::now();
        $RCVDT = Carbon::parse($CA_RCVDT);
        $start = date('Y-m-d', strtotime($CA_RCVDT));
        if($mAdminCase->CA_COMPLETEDT){
            $end = date('Y-m-d', strtotime($mAdminCase->CA_COMPLETEDT));
            $CA_COMPLETEDT = $mAdminCase->CA_COMPLETEDT;
        } else {
            $end = date('Y-m-d', strtotime(Carbon::now()));
            $CA_COMPLETEDT = Carbon::now();
        }
        $offDay = $workingDay->offDay($stateCode); // DAPATKAN HARI CUTI MINGGUAN MENGIKUT NEGERI
        $holidayDay = $holiday->off($start, $end, $stateCode); // KIRAAN CUTI MENGIKUT NEGERI
        $repeatHoliday = $holiday->repeatedOffday($start, $end, $stateCode); // KIRAAN CUTI BERULANG MENGIKUT NEGERI
        if ($mAdminCase->CA_INVSTS == '8') {
            $duration = $workingDay->getWorkingDay($RCVDT, $CA_COMPLETEDT, $offDay); // KIRAAN CUTI MINGGUAN DALAM MENGIKUT NEGERI
            $totalDuration = $duration - ($holidayDay + $repeatHoliday); // CUTI DALAM TEMPOH ADUAN
        }
        elseif ($mAdminCase->CA_INVSTS == '7') {
            $duration = $workingDay->getWorkingDay($RCVDT, $CA_COMPLETEDT, []); // KIRAAN CUTI MINGGUAN DALAM MENGIKUT NEGERI
            $totalDuration = $duration;
        }
        return $totalDuration;
    }
}
