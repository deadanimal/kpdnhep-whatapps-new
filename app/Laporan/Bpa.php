<?php

namespace App\Laporan;

use Illuminate\Database\Eloquent\Model;
use DB;

class Bpa extends Model
{
    protected $table = 'case_info';
    public $primaryKey = 'CA_CASEID';
    public $incrementing = false;
    
    public static function GetYearList() {
        $mYear = DB::table('case_info')
            ->select(DB::raw('DISTINCT YEAR(CA_RCVDT) AS year'))
            ->whereNotNull('CA_RCVDT')
            ->orderBy('year', 'asc')
            ->pluck('year', 'year')
        ;
        return $mYear;
    }
    
    public static function GetRefList($cat, $prepend) {
        $mRef = DB::table('sys_ref')
            ->where(['cat' => $cat, 'status' => '1'])
            ->orderBy('sort', 'asc')
            ->orderBy('descr', 'asc')
            ->pluck('descr', 'code')
        ;
        if($prepend == 'sp') {
            $mRef->prepend('-- SILA PILIH --', '');
            return $mRef;
        } else if ($prepend == 'semua') {
            $mRef->prepend('SEMUA', '');
            return $mRef;
        } else {
            return $mRef;
        }
    }
    
    public static function GetRefDeptList($cat, $prepend) {
        $mRef = DB::table('sys_ref')
            ->where(['cat' => $cat, 'status' => '1'])
            ->whereIn('code', ['BPDN', 'BPGK', 'BPP'])
            ->orderBy('sort', 'asc')
            ->orderBy('descr', 'asc')
            ->pluck('descr', 'code')
        ;
        if($prepend == 'sp') {
            $mRef->prepend('-- SILA PILIH --', '');
            return $mRef;
        } else if ($prepend == 'semua') {
            $mRef->prepend('SEMUA', '');
            return $mRef;
        } else {
            return $mRef;
        }
    }
    
    public static function penerimaanpenyelesaianbulan($CA_RCVDT_YEAR, $CA_RCVDT_MONTH, $BR_STATECD, $CA_DEPTCD){
        /* if($CA_DEPTCD == ''){
            $penerimaanpenyelesaianbulan = DB::table('case_info')
                ->join('sys_brn', 'case_info.CA_BRNCD', '=', 'sys_brn.BR_BRNCD')
                ->select(DB::raw('COUNT(CA_CASEID) as countcaseid,'
//                    MONTHNAME(CA_RCVDT) AS MONTH,
                    .'YEAR(CA_RCVDT) as year,
                    SUM(CASE WHEN CA_INVSTS="1" THEN 1 ELSE 0 END) AS Count1,
                    SUM(CASE WHEN CA_INVSTS="2" THEN 1 ELSE 0 END) AS Count2,
                    SUM(CASE WHEN CA_INVSTS="3" THEN 1 ELSE 0 END) AS Count3,
                    SUM(CASE WHEN CA_INVSTS="4" THEN 1 ELSE 0 END) AS Count4,
                    SUM(CASE WHEN CA_INVSTS="5" THEN 1 ELSE 0 END) AS Count5,
                    SUM(CASE WHEN CA_INVSTS="6" THEN 1 ELSE 0 END) AS Count6,
                    SUM(CASE WHEN CA_INVSTS="7" THEN 1 ELSE 0 END) AS Count7,
                    SUM(CASE WHEN CA_INVSTS="8" THEN 1 ELSE 0 END) AS Count8,
                    SUM(CASE WHEN CA_INVSTS="9" THEN 1 ELSE 0 END) AS Count9
                '))
                ->whereYear('CA_RCVDT', $CA_RCVDT_YEAR)
                ->whereMonth('CA_RCVDT',  $CA_RCVDT_MONTH)
                ->where([['BR_STATECD', 'like', "%$BR_STATECD%"]])
                ->groupBy(DB::raw('year'))
                ->get();
        } else {
            $penerimaanpenyelesaianbulan = DB::table('case_info')
                ->join('sys_brn', 'case_info.CA_BRNCD', '=', 'sys_brn.BR_BRNCD')
                ->select(DB::raw('COUNT(CA_CASEID) as countcaseid,'
//                    MONTHNAME(CA_RCVDT) AS MONTH,
                    .'YEAR(CA_RCVDT) as year,
                    SUM(CASE WHEN CA_INVSTS="1" THEN 1 ELSE 0 END) AS Count1,
                    SUM(CASE WHEN CA_INVSTS="2" THEN 1 ELSE 0 END) AS Count2,
                    SUM(CASE WHEN CA_INVSTS="3" THEN 1 ELSE 0 END) AS Count3,
                    SUM(CASE WHEN CA_INVSTS="4" THEN 1 ELSE 0 END) AS Count4,
                    SUM(CASE WHEN CA_INVSTS="5" THEN 1 ELSE 0 END) AS Count5,
                    SUM(CASE WHEN CA_INVSTS="6" THEN 1 ELSE 0 END) AS Count6,
                    SUM(CASE WHEN CA_INVSTS="7" THEN 1 ELSE 0 END) AS Count7,
                    SUM(CASE WHEN CA_INVSTS="8" THEN 1 ELSE 0 END) AS Count8,
                    SUM(CASE WHEN CA_INVSTS="9" THEN 1 ELSE 0 END) AS Count9
                '))
                ->whereYear('CA_RCVDT', $CA_RCVDT_YEAR)
                ->whereMonth('CA_RCVDT',  $CA_RCVDT_MONTH)
                ->where([['BR_STATECD', 'like', "%$BR_STATECD%"],['CA_DEPTCD', 'like', "%$CA_DEPTCD%"]])
                ->groupBy(DB::raw('year'))
                ->get();
        } */
        $penerimaanpenyelesaianbulan = DB::table('case_info')
                ->join('sys_brn', 'case_info.CA_BRNCD', '=', 'sys_brn.BR_BRNCD')
                ->select(DB::raw('COUNT(CA_CASEID) as countcaseid,'
//                    MONTHNAME(CA_RCVDT) AS MONTH,
                    .'YEAR(CA_RCVDT) as year,
                    SUM(CASE WHEN CA_INVSTS="1" THEN 1 ELSE 0 END) AS Count1,
                    SUM(CASE WHEN CA_INVSTS="2" THEN 1 ELSE 0 END) AS Count2,
                    SUM(CASE WHEN CA_INVSTS="3" THEN 1 ELSE 0 END) AS Count3,
                    SUM(CASE WHEN CA_INVSTS="4" THEN 1 ELSE 0 END) AS Count4,
                    SUM(CASE WHEN CA_INVSTS="5" THEN 1 ELSE 0 END) AS Count5,
                    SUM(CASE WHEN CA_INVSTS="6" THEN 1 ELSE 0 END) AS Count6,
                    SUM(CASE WHEN CA_INVSTS="7" THEN 1 ELSE 0 END) AS Count7,
                    SUM(CASE WHEN CA_INVSTS="8" THEN 1 ELSE 0 END) AS Count8,
                    SUM(CASE WHEN CA_INVSTS="9" THEN 1 ELSE 0 END) AS Count9
                '))
                ->whereYear('CA_RCVDT', $CA_RCVDT_YEAR)
                ->whereMonth('CA_RCVDT',  $CA_RCVDT_MONTH);
        if ($BR_STATECD != '') {
            $penerimaanpenyelesaianbulan->where('BR_STATECD', 'like', "%$BR_STATECD%");
        }
        if ($CA_DEPTCD != '') {
            $penerimaanpenyelesaianbulan->where('CA_DEPTCD', 'like', "%$CA_DEPTCD%");
        }
        $penerimaanpenyelesaianbulan->groupBy(DB::raw('year'))->get();
        return $penerimaanpenyelesaianbulan;
    }
    
    public static function sumberpenerimaanbulan($CA_RCVDT_YEAR, $CA_RCVDT_MONTH, $CA_DEPTCD, $BR_STATECD, $CA_RCVTYP){
        /* if($CA_RCVTYP != ''){
        $sumberpenerimaanbulan = DB::table('case_info')
            ->join('sys_brn', 'case_info.CA_BRNCD', '=', 'sys_brn.BR_BRNCD')
            ->select('CA_AGAINST_STATECD', 'CA_RCVTYP')
            ->select(DB::raw('COUNT(CA_CASEID) as countcaseid'))
            ->whereYear('CA_RCVDT', $CA_RCVDT_YEAR)
            ->whereMonth('CA_RCVDT',  $CA_RCVDT_MONTH)
            ->where([['CA_DEPTCD', 'like', "%$CA_DEPTCD%"],['BR_STATECD', 'like', "%$BR_STATECD%"],['CA_RCVTYP', $CA_RCVTYP]])
            ->groupBy('CA_RCVTYP')
            ->get();
        } else if ($CA_RCVTYP == ''){
        $sumberpenerimaanbulan = DB::table('case_info')
            ->join('sys_brn', 'case_info.CA_BRNCD', '=', 'sys_brn.BR_BRNCD')
            ->select('CA_AGAINST_STATECD', 'CA_RCVTYP')
            ->select(DB::raw('COUNT(CA_CASEID) as countcaseid'))
            ->whereYear('CA_RCVDT', $CA_RCVDT_YEAR)
            ->whereMonth('CA_RCVDT',  $CA_RCVDT_MONTH)
            ->where([['CA_DEPTCD', 'like', "%$CA_DEPTCD%"],['BR_STATECD', 'like', "%$BR_STATECD%"],['CA_RCVTYP', '']])
            ->groupBy('CA_RCVTYP')
            ->get();
        } */
        $sumberpenerimaanbulan = DB::table('case_info')
            ->join('sys_brn', 'case_info.CA_BRNCD', '=', 'sys_brn.BR_BRNCD')
            ->select('CA_AGAINST_STATECD', 'CA_RCVTYP')
            ->select(DB::raw('COUNT(CA_CASEID) as countcaseid'))
            ->whereYear('CA_RCVDT', $CA_RCVDT_YEAR)
            ->whereMonth('CA_RCVDT',  $CA_RCVDT_MONTH);
        if($CA_RCVTYP != ''){
            $sumberpenerimaanbulan->where('CA_RCVTYP', $CA_RCVTYP);
        }
        if ($CA_DEPTCD != '') {
            $sumberpenerimaanbulan->where('CA_DEPTCD', 'like', "%$CA_DEPTCD%");
        }
        if ($BR_STATECD != '') {
            $sumberpenerimaanbulan->where('BR_STATECD', 'like', "%$BR_STATECD%");
        }
        $sumberpenerimaanbulan->groupBy('CA_RCVTYP')->get();
        return $sumberpenerimaanbulan;
    }
    
    public static function sumberpenerimaanbulankumulatif($CA_RCVDT_YEAR, $CA_RCVDT_MONTH_FROM, $CA_RCVDT_MONTH_TO, $CA_DEPTCD, $BR_STATECD, $CA_RCVTYP){
        if($CA_RCVTYP != ''){
        $sumberpenerimaanbulan = DB::table('case_info')
            ->join('sys_brn', 'case_info.CA_BRNCD', '=', 'sys_brn.BR_BRNCD')
            ->select('CA_AGAINST_STATECD', 'CA_RCVTYP')
            ->select(DB::raw('COUNT(CA_CASEID) as countcaseid'))
            ->whereYear('CA_RCVDT', $CA_RCVDT_YEAR)
            ->whereMonth('CA_RCVDT', '>=', $CA_RCVDT_MONTH_FROM)
            ->whereMonth('CA_RCVDT', '<=', $CA_RCVDT_MONTH_TO)
            ->where([['CA_DEPTCD', 'like', "%$CA_DEPTCD%"],['BR_STATECD', 'like', "%$BR_STATECD%"],['CA_RCVTYP', $CA_RCVTYP]])
            ->groupBy('CA_RCVTYP')
            ->get();
        } else if ($CA_RCVTYP == ''){
        $sumberpenerimaanbulan = DB::table('case_info')
            ->join('sys_brn', 'case_info.CA_BRNCD', '=', 'sys_brn.BR_BRNCD')
            ->select('CA_AGAINST_STATECD', 'CA_RCVTYP')
            ->select(DB::raw('COUNT(CA_CASEID) as countcaseid'))
            ->whereYear('CA_RCVDT', $CA_RCVDT_YEAR)
            ->whereMonth('CA_RCVDT', '>=', $CA_RCVDT_MONTH_FROM)
            ->whereMonth('CA_RCVDT', '<=', $CA_RCVDT_MONTH_TO)
            ->where([['CA_DEPTCD', 'like', "%$CA_DEPTCD%"],['BR_STATECD', 'like', "%$BR_STATECD%"],['CA_RCVTYP', '']])
            ->groupBy('CA_RCVTYP')
            ->get();
        }
        return $sumberpenerimaanbulan;
    }
}
