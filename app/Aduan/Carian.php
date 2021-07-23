<?php

namespace App\Aduan;

use App\EAduanOld;
use App\Holiday;
use App\Wd;
use Carbon\Carbon;
use DB;
use Illuminate\Database\Eloquent\Model;

class Carian extends Model
{
    use EAduanOld;
    
    public $table = 'case_info';
    public $primaryKey = 'CA_CASEID';
    public $incrementing = false;
    
    const CREATED_AT = 'CA_CREDT';
    const UPDATED_AT = 'CA_MODDT';
    const CREATED_BY = 'CA_CREBY';
    const UPDATED_BY = 'CA_MODBY';
    
    public function StatusAduan() {
        return $this->hasOne('App\Ref', 'code', 'CA_INVSTS')->where('cat','292');
    }
    
    public function InvBy() {
        return $this->hasOne('App\User', 'id', 'CA_INVBY');
    }
    
    public function SexCd() {
        return $this->hasOne('App\Ref', 'code', 'CA_SEXCD')->where('cat','202');
    }
    
    public function CountryCd() {
        return $this->hasOne('App\Ref', 'code', 'CA_COUNTRYCD')->where('cat','334');
    }
    
    public function StatusPengadu() {
        return $this->hasOne('App\Ref', 'code', 'CA_STATUSPENGADU')->where('cat','1233');
    }
    
    public function CmplCat() {
        return $this->hasOne('App\Ref', 'code', 'CA_CMPLCAT')->where('cat','244');
    }
    
    public function CmplCd() {
        return $this->hasOne('App\Ref', 'code', 'CA_CMPLCD')->where('cat','634');
    }
    
    public function RcvTyp() {
        return $this->hasOne('App\Ref', 'code', 'CA_RCVTYP')->where('cat','259');
    }
    
    public function NatCd() {
        return $this->hasOne('App\Ref', 'code', 'CA_NATCD')->where('cat','947');
    }
    
    public function BrnCd() {
        return $this->hasOne('App\Branch', 'BR_BRNCD', 'CA_BRNCD');
    }
    
    public function onlinecmplprovider() {
        return $this->hasOne('App\Ref', 'code', 'CA_ONLINECMPL_PROVIDER')->where('cat','1091');
    }

    public function onlinecmplpymnttyp() {
        return $this->hasOne('App\Ref', 'code', 'CA_ONLINECMPL_PYMNTTYP')->where('cat','1207');
    }

    public function Bankcd() {
        return $this->hasOne('App\Ref', 'code', 'CA_ONLINECMPL_BANKCD')->where('cat','1106');
    }

    public function againstdistcd() {
        return $this->hasOne('App\Ref', 'code', 'CA_AGAINST_DISTCD')->where('cat', '18');
    }
    
    public function againststatecd() {
        return $this->hasOne('App\Ref', 'code', 'CA_AGAINST_STATECD')->where('cat', '17');
    }

    public function cmplkeyword() {
        return $this->hasOne('App\Ref', 'code', 'CA_CMPLKEYWORD')->where('cat', '1051');
    }

    public function againstpremise() {
        return $this->hasOne('App\Ref', 'code', 'CA_AGAINST_PREMISE')->where('cat', '221');
    }
    
    public function getduration($CA_RCVDT, $CA_CASEID)
    {
        $mCarian = Carian::where('CA_CASEID', $CA_CASEID)->first();
        $workingDay = new Wd;
        $holiday = new Holiday;
//        $stateCode = $mCarian->CA_AGAINST_STATECD;
        if ($mCarian->CA_AGAINST_STATECD != null) {
            $stateCode = $mCarian->CA_AGAINST_STATECD;
        }
        else if ($mCarian->CA_STATECD != null) {
            $stateCode = $mCarian->CA_STATECD;
        }
        else {
            $stateCode = 16;
        }
        $RCVDT = Carbon::parse($CA_RCVDT);
        $start = date('Y-m-d', strtotime($CA_RCVDT));
        if($mCarian->CA_COMPLETEDT){
            $end = date('Y-m-d', strtotime($mCarian->CA_COMPLETEDT));
            $CA_COMPLETEDT = $mCarian->CA_COMPLETEDT;
        } else {
            $mCarianCaseDetail = DB::table('case_dtl')->where('CD_CASEID', $CA_CASEID)
                ->whereIn('CD_INVSTS', ['4','5','6','8','11','12'])
                ->orderBy('CD_CREDT', 'DESC')
                ->first();
            if (count($mCarianCaseDetail) > 0) {
                $mCarianCaseDetailIncomplete = DB::table('case_dtl')->where('CD_CASEID', $CA_CASEID)
                    ->whereIn('CD_INVSTS', ['7'])
                    ->orderBy('CD_CREDT', 'DESC')
                    ->first();
                $mCarianCaseDetailIncompleteByCron = DB::table('case_dtl')->where('CD_CASEID', $CA_CASEID)
                    ->whereIn('CD_INVSTS', ['12'])
                    ->orderBy('CD_CREDT', 'DESC')
                    ->first();
                if (!empty($mCarianCaseDetailIncompleteByCron)) {
                    if (!empty($mCarianCaseDetailIncomplete)) {
                        $end = date('Y-m-d', strtotime($mCarianCaseDetailIncomplete->CD_CREDT));
                        $CA_COMPLETEDT = $mCarianCaseDetailIncomplete->CD_CREDT;
                    } else {
                        $end = date('Y-m-d', strtotime($mCarianCaseDetailIncompleteByCron->CD_CREDT));
                        $CA_COMPLETEDT = $mCarianCaseDetailIncompleteByCron->CD_CREDT;
                    }
                } else {
                    $end = date('Y-m-d', strtotime($mCarianCaseDetail->CD_CREDT));
                    $CA_COMPLETEDT = $mCarianCaseDetail->CD_CREDT;
                }
            } else {
                $end = date('Y-m-d', strtotime(Carbon::now()));
                $CA_COMPLETEDT = Carbon::now();
            }
        }
        $data['durationIncomplete'] = 0;
        $mCaseDetailIncomplete = DB::table('case_dtl')->where('CD_CASEID', $CA_CASEID)
            ->whereIn('CD_INVSTS', ['7'])
            ->orderBy('CD_CREDT', 'desc')
            ->first();
        if($mCaseDetailIncomplete) {
            $mCaseDetailUpdateByUser = DB::table('case_dtl')->where('CD_CASEID', $CA_CASEID)
                // ->whereNotIn('CD_INVSTS', ['12'])
                ->whereDate('CD_CREDT', '>=', $mCaseDetailIncomplete->CD_CREDT)
                ->orderBy('CD_CREDT')
                ->first();
            if($mCaseDetailUpdateByUser && !in_array($mCaseDetailUpdateByUser->CD_INVSTS, ['12'])) {
                $data['startDateIncomplete'] = Carbon::parse($mCaseDetailIncomplete->CD_CREDT);
                $data['mCaseDetailUpdateByUser'] = Carbon::parse($mCaseDetailUpdateByUser->CD_CREDT);
                $data['durationIncomplete'] = $data['startDateIncomplete']->diffInDays($data['mCaseDetailUpdateByUser']);
            }
        }
        $offDay = $workingDay->offDay($stateCode); // DAPATKAN HARI CUTI MINGGUAN MENGIKUT NEGERI
        $holidayDay = $holiday->off($start, $end, $stateCode); // KIRAAN CUTI MENGIKUT NEGERI
        $repeatHoliday = $holiday->repeatedOffday($start, $end, $stateCode); // KIRAAN CUTI BERULANG MENGIKUT NEGERI
        $duration = $workingDay->getWorkingDay($RCVDT, $CA_COMPLETEDT, $offDay); // KIRAAN CUTI MINGGUAN DALAM MENGIKUT NEGERI
        $totalDuration = $duration - ($holidayDay + $repeatHoliday + $data['durationIncomplete']); // CUTI DALAM TEMPOH ADUAN
        return $totalDuration;
    }
    
    public static function gettempoh($tempoh_aduan)
    {
        $TempohPertama = \App\Ref::find(1244);
        $TempohKedua = \App\Ref::find(1245);
        $TempohKetiga = \App\Ref::find(1246);

        $mCarian = Carian::join('sys_brn as b', 'case_info.CA_BRNCD', '=', 'b.BR_BRNCD')
            ->whereYear('CA_RCVDT', date('Y'))
            ->whereIn('CA_INVSTS', [1, 2, 3])
            ->orderBy('CA_RCVDT', 'DESC')
            ->get();
        $array = [];
        foreach ($mCarian as $carian) {
            $workingDay = new Wd;
            $holiday = new Holiday;
            $stateCode = $carian->CA_AGAINST_STATECD;
            if ($carian->CA_AGAINST_STATECD != null) {
                $stateCode = $carian->CA_AGAINST_STATECD;
            } else if ($carian->CA_STATECD != null) {
                $stateCode = $carian->CA_STATECD;
            } else {
                $stateCode = 16;
            }
            $RCVDT = Carbon::parse($carian->CA_RCVDT);
            $start = date('Y-m-d', strtotime($carian->CA_RCVDT));
            if (!empty($carian->CA_COMPLETEDT)) {
                $end = date('Y-m-d', strtotime($carian->CA_COMPLETEDT));
                $CA_COMPLETEDT = $carian->CA_COMPLETEDT;
            } else {
                $end = date('Y-m-d', strtotime(Carbon::now()));
                $CA_COMPLETEDT = Carbon::now();
            }
            $offDay = $workingDay->offDay($stateCode); // DAPATKAN HARI CUTI MINGGUAN MENGIKUT NEGERI
            $holidayDay = $holiday->off($start, $end, $stateCode); // KIRAAN CUTI MENGIKUT NEGERI
            $repeatHoliday = $holiday->repeatedOffday($start, $end, $stateCode); // KIRAAN CUTI BERULANG MENGIKUT NEGERI
            $duration = $workingDay->getWorkingDay($RCVDT, $CA_COMPLETEDT, $offDay); // KIRAAN CUTI MINGGUAN DALAM MENGIKUT NEGERI
            $totalDuration = $duration - ($holidayDay + $repeatHoliday); // CUTI DALAM TEMPOH ADUAN
            if ($tempoh_aduan == 0) {
                if ($totalDuration >= 0 && $totalDuration <= $TempohPertama->code)
                    array_push($array, $carian->CA_CASEID);
            }
            else if ($tempoh_aduan == 1) {
                // if ($totalDuration > $TempohPertama->code && $totalDuration <= $TempohKedua->code)
                if ($totalDuration > $TempohPertama->code && $totalDuration <= 16)
                    array_push($array, $carian->CA_CASEID);
            }
            else if ($tempoh_aduan == 2) {
                // if ($totalDuration > $TempohKedua->code && $totalDuration <= $TempohKetiga->code)
                if ($totalDuration > 16 && $totalDuration <= $TempohKetiga->code)
                    array_push($array, $carian->CA_CASEID);
            }
            else if ($tempoh_aduan == 3) {
                if ($totalDuration > $TempohKetiga->code)
                    array_push($array, $carian->CA_CASEID);
            }
        }
        return $array;
    }
}
