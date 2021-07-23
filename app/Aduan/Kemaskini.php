<?php

namespace App\Aduan;

use App\EAduanOld;
use App\Holiday;
use App\Wd;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;

class Kemaskini extends Model
{
    use EAduanOld;
    
    public $table = 'case_info';
    
    const CREATED_AT = 'CA_CREDT';
    const UPDATED_AT = 'CA_MODDT';
    const CREATED_BY = 'CA_CREBY';
    const UPDATED_BY = 'CA_MODBY';
    
    protected $fillable = [
//        'CA_CASEID','CA_INVSTS','CA_RCVTYP','CA_RCVBY','CA_ANSWER','CA_RESULT','CA_CMPLCAT','CA_CMPLCD',
        // 'CA_SEXCD','CA_NAME','CA_DOCNO','CA_AGE','CA_ADDR','CA_DISTCD','CA_POSCD','CA_STATECD',
//        'CA_NATCD','CA_COUNTRYCD','CA_TELNO','CA_FAXNO','CA_EMAIL','CA_MOBILENO','CA_RACECD',
//        'CA_AGAINST_DISTCD','CA_AGAINST_STATECD',
        'CA_SUMMARY','CA_AGAINSTNM','CA_AGAINSTADD','CA_AGAINST_POSTCD','CA_AGAINST_TELNO',
        'CA_AGAINST_FAXNO','CA_AGAINST_MOBILENO','CA_AGAINST_PREMISE','CA_AGAINST_EMAIL',
        'CA_ONLINECMPL_PROVIDER','CA_ONLINECMPL_URL','CA_ONLINECMPL_AMOUNT','CA_ONLINECMPL_ACCNO',
        'CA_ONLINECMPL_IND','CA_ONLINECMPL_CASENO','CA_ONLINEADD_IND','CA_ROUTETOHQIND',
//        'CA_MYIDENTITY_ADDR','CA_MYIDENTITY_POSCD','CA_MYIDENTITY_STATECD','CA_MYIDENTITY_DISTCD',
//        'CA_STATUSPENGADU',
    ];
    
    public function getduration($CA_RCVDT, $CA_CASEID)
    {
        $mAdminCase = Kemaskini::where('CA_CASEID', $CA_CASEID)->first();
        $workingDay = new Wd;
        $holiday = new Holiday;
//        $stateCode = $mAdminCase->CA_AGAINST_STATECD;
        if ($mAdminCase->CA_AGAINST_STATECD != null) {
            $stateCode = $mAdminCase->CA_AGAINST_STATECD;
        }
        else if ($mAdminCase->CA_STATECD != null) {
            $stateCode = $mAdminCase->CA_STATECD;
        }
        else {
            $stateCode = 16;
        }
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
        $duration = $workingDay->getWorkingDay($RCVDT, $CA_COMPLETEDT, $offDay); // KIRAAN CUTI MINGGUAN DALAM MENGIKUT NEGERI
        $totalDuration = $duration - ($holidayDay + $repeatHoliday); // CUTI DALAM TEMPOH ADUAN
        return $totalDuration;
    }
}
