<?php

namespace App\Laporan;

use Illuminate\Database\Eloquent\Model;
use DB;

class BandingAduan extends Model {
    protected $table = 'case_info';
    public $primaryKey = 'CA_CASEID';
    public $incrementing = false;
    
    public function statusAduan() {
        return $this->hasOne('App\Ref', 'code', 'CA_INVSTS')->where('cat','292');
    }
    
    public function statusPerkembangan() {
        return $this->hasOne('App\Ref', 'code', 'CA_CASESTS')->where('cat','306');
    }
                                            
    public static function kategorinegeri($CA_RCVDT_FROM, $CA_RCVDT_TO, $CA_DEPTCD, $CA_CMPLCAT)
    {
        if($CA_DEPTCD != ''){
            $kategorinegeri = DB::table('case_info')
                ->join('sys_brn', 'case_info.CA_BRNCD', '=', 'sys_brn.BR_BRNCD')
                ->select('CA_CMPLCAT')
                ->select(DB::raw('SUM(CASE WHEN BR_STATECD="01" THEN 1 ELSE 0 END) AS countstate1,
                    SUM(CASE WHEN BR_STATECD="02" THEN 1 ELSE 0 END) AS countstate2,
                    SUM(CASE WHEN BR_STATECD="03" THEN 1 ELSE 0 END) AS countstate3,
                    SUM(CASE WHEN BR_STATECD="04" THEN 1 ELSE 0 END) AS countstate4,
                    SUM(CASE WHEN BR_STATECD="05" THEN 1 ELSE 0 END) AS countstate5,
                    SUM(CASE WHEN BR_STATECD="06" THEN 1 ELSE 0 END) AS countstate6,
                    SUM(CASE WHEN BR_STATECD="07" THEN 1 ELSE 0 END) AS countstate7,
                    SUM(CASE WHEN BR_STATECD="08" THEN 1 ELSE 0 END) AS countstate8,
                    SUM(CASE WHEN BR_STATECD="09" THEN 1 ELSE 0 END) AS countstate9,
                    SUM(CASE WHEN BR_STATECD="10" THEN 1 ELSE 0 END) AS countstate10,
                    SUM(CASE WHEN BR_STATECD="11" THEN 1 ELSE 0 END) AS countstate11,
                    SUM(CASE WHEN BR_STATECD="12" THEN 1 ELSE 0 END) AS countstate12,
                    SUM(CASE WHEN BR_STATECD="13" THEN 1 ELSE 0 END) AS countstate13,
                    SUM(CASE WHEN BR_STATECD="14" THEN 1 ELSE 0 END) AS countstate14,
                    SUM(CASE WHEN BR_STATECD="15" THEN 1 ELSE 0 END) AS countstate15,
                    SUM(CASE WHEN BR_STATECD="16" THEN 1 ELSE 0 END) AS countstate16,
                    COUNT(CA_CASEID) as countcaseid'))
                ->where([['CA_DEPTCD', 'like', "%$CA_DEPTCD%"],['CA_CMPLCAT', $CA_CMPLCAT]])
                ->whereBetween('CA_RCVDT', array(date('Y-m-d', strtotime($CA_RCVDT_FROM)), date('Y-m-d', strtotime($CA_RCVDT_TO))))
                ->groupBy('CA_CMPLCAT')
                ->orderBy(DB::raw('COUNT(CA_CASEID)'), 'desc')
                ->get();
        }
        else {
            $kategorinegeri = DB::table('case_info')
                ->join('sys_brn', 'case_info.CA_BRNCD', '=', 'sys_brn.BR_BRNCD')
                ->select(DB::raw('SUM(CASE WHEN BR_STATECD="01" THEN 1 ELSE 0 END) AS countstate1,
                    SUM(CASE WHEN BR_STATECD="02" THEN 1 ELSE 0 END) AS countstate2,
                    SUM(CASE WHEN BR_STATECD="03" THEN 1 ELSE 0 END) AS countstate3,
                    SUM(CASE WHEN BR_STATECD="04" THEN 1 ELSE 0 END) AS countstate4,
                    SUM(CASE WHEN BR_STATECD="05" THEN 1 ELSE 0 END) AS countstate5,
                    SUM(CASE WHEN BR_STATECD="06" THEN 1 ELSE 0 END) AS countstate6,
                    SUM(CASE WHEN BR_STATECD="07" THEN 1 ELSE 0 END) AS countstate7,
                    SUM(CASE WHEN BR_STATECD="08" THEN 1 ELSE 0 END) AS countstate8,
                    SUM(CASE WHEN BR_STATECD="09" THEN 1 ELSE 0 END) AS countstate9,
                    SUM(CASE WHEN BR_STATECD="10" THEN 1 ELSE 0 END) AS countstate10,
                    SUM(CASE WHEN BR_STATECD="11" THEN 1 ELSE 0 END) AS countstate11,
                    SUM(CASE WHEN BR_STATECD="12" THEN 1 ELSE 0 END) AS countstate12,
                    SUM(CASE WHEN BR_STATECD="13" THEN 1 ELSE 0 END) AS countstate13,
                    SUM(CASE WHEN BR_STATECD="14" THEN 1 ELSE 0 END) AS countstate14,
                    SUM(CASE WHEN BR_STATECD="15" THEN 1 ELSE 0 END) AS countstate15,
                    SUM(CASE WHEN BR_STATECD="16" THEN 1 ELSE 0 END) AS countstate16,
                    COUNT(CA_CASEID) as countcaseid'))
                ->where('CA_CMPLCAT', $CA_CMPLCAT)
//                ->where('CA_BRNCD', '!=', '')
                ->whereBetween('CA_RCVDT', array(date('Y-m-d', strtotime($CA_RCVDT_FROM)), date('Y-m-d', strtotime($CA_RCVDT_TO))))
                ->groupBy('CA_CMPLCAT')
                ->orderBy(DB::raw('COUNT(CA_CASEID)'), 'desc')
                ->get()
            ;
        }
        return $kategorinegeri;
    }
    
    public static function senaraiaduankategori($CA_CMPLCAT, $CA_RCVDT_FROM, $CA_RCVDT_TO, $CA_DEPTCD)
    {
        if(($CA_RCVDT_FROM != '') && ($CA_RCVDT_TO != '') && ($CA_DEPTCD != '')){
            $senaraiaduankategori = DB::table('case_info')
                ->join('sys_brn', 'case_info.CA_BRNCD', '=', 'sys_brn.BR_BRNCD')
                ->where(['CA_CMPLCAT' => $CA_CMPLCAT, 'CA_DEPTCD' => $CA_DEPTCD])
                ->whereBetween('CA_RCVDT', [date('Y-m-d', strtotime($CA_RCVDT_FROM)), date('Y-m-d', strtotime($CA_RCVDT_TO))])
                ->count()
            ;
        }
        else if(($CA_RCVDT_FROM != '') && ($CA_RCVDT_TO == '') && ($CA_DEPTCD == '')){
            $senaraiaduankategori = DB::table('case_info')
                ->join('sys_brn', 'case_info.CA_BRNCD', '=', 'sys_brn.BR_BRNCD')
                ->where(['CA_CMPLCAT' => $CA_CMPLCAT])
                ->whereDate('CA_RCVDT', '>=', date('Y-m-d', strtotime($CA_RCVDT_FROM)))
                ->count()
            ;
        }
        else if(($CA_RCVDT_FROM == '') && ($CA_RCVDT_TO != '') && ($CA_DEPTCD == '')){
            $senaraiaduankategori = DB::table('case_info')
                ->join('sys_brn', 'case_info.CA_BRNCD', '=', 'sys_brn.BR_BRNCD')
                ->where(['CA_CMPLCAT' => $CA_CMPLCAT])
                ->whereDate('CA_RCVDT', '<=', date('Y-m-d', strtotime($CA_RCVDT_TO)))
                ->count()
            ;
        }
        else if(($CA_RCVDT_FROM == '') && ($CA_RCVDT_TO == '') && ($CA_DEPTCD != '')){
            $senaraiaduankategori = DB::table('case_info')
                ->join('sys_brn', 'case_info.CA_BRNCD', '=', 'sys_brn.BR_BRNCD')
                ->where(['CA_CMPLCAT' => $CA_CMPLCAT])
                ->count()
            ;
        }
        else if(($CA_RCVDT_FROM == '') && ($CA_RCVDT_TO == '') && ($CA_DEPTCD == '')){
            $senaraiaduankategori = DB::table('case_info')
                ->join('sys_brn', 'case_info.CA_BRNCD', '=', 'sys_brn.BR_BRNCD')
                ->where(['CA_CMPLCAT' => $CA_CMPLCAT])
                ->count()
            ;
        }
        return $senaraiaduankategori;
    }
    
    public static function kategorinegerijumlah($BR_STATECD, $CA_RCVDT_FROM, $CA_RCVDT_TO, $CA_DEPTCD)
    {
        if(($CA_RCVDT_FROM != '') && ($CA_RCVDT_TO != '') && ($CA_DEPTCD != '')){
            $kategorinegerijumlah = DB::table('case_info')
                ->join('sys_brn', 'case_info.CA_BRNCD', '=', 'sys_brn.BR_BRNCD')
                ->where(['BR_STATECD' => $BR_STATECD])
                ->where('CA_DEPTCD', 'like', '%'.$CA_DEPTCD.'%')
//                ->where('CA_DEPTCD', 'like', "%$CA_DEPTCD%")
                ->whereBetween('CA_RCVDT', [date('Y-m-d', strtotime($CA_RCVDT_FROM)), date('Y-m-d', strtotime($CA_RCVDT_TO))])
                ->count()
            ;
        }
        else if(($CA_RCVDT_FROM != '') && ($CA_RCVDT_TO != '') && ($CA_DEPTCD == '')){
            $kategorinegerijumlah = DB::table('case_info')
                ->join('sys_brn', 'case_info.CA_BRNCD', '=', 'sys_brn.BR_BRNCD')
                ->where(['BR_STATECD' => $BR_STATECD])
                ->whereBetween('CA_RCVDT', [date('Y-m-d', strtotime($CA_RCVDT_FROM)), date('Y-m-d', strtotime($CA_RCVDT_TO))])
                ->count()
            ;
        }
        else if(($CA_RCVDT_FROM != '') && ($CA_RCVDT_TO == '') && ($CA_DEPTCD != '')){
            $kategorinegerijumlah = DB::table('case_info')
                ->join('sys_brn', 'case_info.CA_BRNCD', '=', 'sys_brn.BR_BRNCD')
                ->where(['BR_STATECD' => $BR_STATECD])
                ->where('CA_DEPTCD', 'like', '%'.$CA_DEPTCD.'%')
                ->whereDate('CA_RCVDT', '>=', date('Y-m-d', strtotime($CA_RCVDT_FROM)))
                ->count()
            ;
        }
        else if(($CA_RCVDT_FROM == '') && ($CA_RCVDT_TO != '') && ($CA_DEPTCD != '')){
            $kategorinegerijumlah = DB::table('case_info')
                ->join('sys_brn', 'case_info.CA_BRNCD', '=', 'sys_brn.BR_BRNCD')
                ->where(['BR_STATECD' => $BR_STATECD])
                ->where('CA_DEPTCD', 'like', '%'.$CA_DEPTCD.'%')
                ->whereDate('CA_RCVDT', '<=', date('Y-m-d', strtotime($CA_RCVDT_TO)))
                ->count()
            ;
        }
        else if(($CA_RCVDT_FROM == '') && ($CA_RCVDT_TO == '') && ($CA_DEPTCD != '')){
            $kategorinegerijumlah = DB::table('case_info')
                ->join('sys_brn', 'case_info.CA_BRNCD', '=', 'sys_brn.BR_BRNCD')
                ->where(['BR_STATECD' => $BR_STATECD])
                ->where('CA_DEPTCD', 'like', '%'.$CA_DEPTCD.'%')
                ->whereDate('CA_RCVDT', '<=', date('Y-m-d', strtotime($CA_RCVDT_TO)))
                ->count()
            ;
        }
        else if(($CA_RCVDT_FROM != '') && ($CA_RCVDT_TO == '') && ($CA_DEPTCD == '')){
            $kategorinegerijumlah = DB::table('case_info')
                ->join('sys_brn', 'case_info.CA_BRNCD', '=', 'sys_brn.BR_BRNCD')
                ->where(['BR_STATECD' => $BR_STATECD])
                ->whereDate('CA_RCVDT', '>=', date('Y-m-d', strtotime($CA_RCVDT_FROM)))
                ->count()
            ;
        }
        else if(($CA_RCVDT_FROM == '') && ($CA_RCVDT_TO != '') && ($CA_DEPTCD == '')){
            $kategorinegerijumlah = DB::table('case_info')
                ->join('sys_brn', 'case_info.CA_BRNCD', '=', 'sys_brn.BR_BRNCD')
                ->where(['BR_STATECD' => $BR_STATECD])
                ->whereDate('CA_RCVDT', '<=', date('Y-m-d', strtotime($CA_RCVDT_TO)))
                ->count()
            ;
        }
        else if(($CA_RCVDT_FROM == '') && ($CA_RCVDT_TO == '') && ($CA_DEPTCD != '')){
            $kategorinegerijumlah = DB::table('case_info')
                ->join('sys_brn', 'case_info.CA_BRNCD', '=', 'sys_brn.BR_BRNCD')
                ->where(['BR_STATECD' => $BR_STATECD, 'CA_DEPTCD' => $CA_DEPTCD])
                ->count()
            ;
        }
        else if(($CA_RCVDT_FROM == '') && ($CA_RCVDT_TO == '') && ($CA_DEPTCD == '')){
            $kategorinegerijumlah = DB::table('case_info')
                ->join('sys_brn', 'case_info.CA_BRNCD', '=', 'sys_brn.BR_BRNCD')
                ->where(['BR_STATECD' => $BR_STATECD])
                ->count()
            ;
        }
        return $kategorinegerijumlah;
    }
    
    public static function kategorinegerijumlahsemua($CA_RCVDT_FROM, $CA_RCVDT_TO, $CA_DEPTCD)
    {
        if(($CA_RCVDT_FROM != '') && ($CA_RCVDT_TO != '') && ($CA_DEPTCD != '')){
            $kategorinegerijumlahsemua = DB::table('case_info')
                ->join('sys_brn', 'case_info.CA_BRNCD', '=', 'sys_brn.BR_BRNCD')
                ->where(['CA_DEPTCD' => $CA_DEPTCD])
                ->whereDate('CA_RCVDT', '>=', date('Y-m-d', strtotime($CA_RCVDT_FROM)))
                ->whereDate('CA_RCVDT', '<=', date('Y-m-d', strtotime($CA_RCVDT_TO)))
                ->count()
            ;
            return $kategorinegerijumlahsemua;
        }
        else if(($CA_RCVDT_FROM != '') && ($CA_RCVDT_TO == '') && ($CA_DEPTCD == '')){
            $kategorinegerijumlahsemua = DB::table('case_info')
                ->join('sys_brn', 'case_info.CA_BRNCD', '=', 'sys_brn.BR_BRNCD')
                ->whereDate('CA_RCVDT', '>=', date('Y-m-d', strtotime($CA_RCVDT_FROM)))
                ->count()
            ;
            return $kategorinegerijumlahsemua;
        }
        else if(($CA_RCVDT_FROM == '') && ($CA_RCVDT_TO != '') && ($CA_DEPTCD == '')){
            $kategorinegerijumlahsemua = DB::table('case_info')
                ->join('sys_brn', 'case_info.CA_BRNCD', '=', 'sys_brn.BR_BRNCD')
                ->whereDate('CA_RCVDT', '<=', date('Y-m-d', strtotime($CA_RCVDT_TO)))
                ->count()
            ;
            return $kategorinegerijumlahsemua;
        }
        else if(($CA_RCVDT_FROM == '') && ($CA_RCVDT_TO == '') && ($CA_DEPTCD != '')){
            $kategorinegerijumlahsemua = DB::table('case_info')
                ->join('sys_brn', 'case_info.CA_BRNCD', '=', 'sys_brn.BR_BRNCD')
                ->where(['CA_DEPTCD' => $CA_DEPTCD])
                ->count()
            ;
            return $kategorinegerijumlahsemua;
        }
        else if(($CA_RCVDT_FROM == '') && ($CA_RCVDT_TO == '') && ($CA_DEPTCD == '')){
            $kategorinegerijumlahsemua = DB::table('case_info')
                ->join('sys_brn', 'case_info.CA_BRNCD', '=', 'sys_brn.BR_BRNCD')
                ->count()
            ;
            return $kategorinegerijumlahsemua;
        }
    }
    
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
        } else if ($prepend == 'semua0'){
            $mRef->prepend('SEMUA', '0');
            return $mRef;
        } else {
            return $mRef;
        }
    }
    
    public static function GetRefDeptList($cat, $statecd, $prepend) {
        if($statecd == 16 || $statecd == 0){
            $mRef = DB::table('sys_ref')
                ->where(['cat' => $cat, 'status' => '1'])
                ->whereIn('code', ['BPDN', 'BPGK', 'BPP'])
                ->orderBy('sort', 'asc')
                ->orderBy('descr', 'asc')
                ->pluck('descr', 'code')
            ;
        } else {
            $mRef = DB::table('sys_ref')
                ->where(['cat' => $cat, 'status' => '1'])
                ->whereIn('code', ['BPGK'])
                ->orderBy('sort', 'asc')
                ->orderBy('descr', 'asc')
                ->pluck('descr', 'code')
            ;
        }
        if($prepend == 'sp') {
            $mRef->prepend('-- SILA PILIH --', '');
            return $mRef;
        } else if ($prepend == 'semua') {
            $mRef->prepend('SEMUA', '0');
            return $mRef;
        } else {
            return $mRef;
        }
    }
    
    public static function GetMonthList(){
        $mRef = DB::table('sys_ref')
            ->where(['cat' => '206', 'status' => '1'])
            ->orderBy('sort', 'asc')
            ->pluck('descr', 'code')
            ->prepend('-- SILA PILIH --', '')
        ;
        return $mRef;
    }
    
    public static function GetStateList(){
        $mRef = DB::table('sys_ref')
            ->where(['cat' => '17', 'status' => '1'])
            ->orderBy('sort', 'asc')
            ->pluck('descr', 'code')
        ;
        return $mRef;
    }
    
//    public static function laporannegeribulan($BR_STATECD, $CA_RCVDT_MONTH)
    public static function laporannegeribulan($CA_RCVDT_YEAR, $CA_RCVDT_MONTH_FROM, $CA_RCVDT_MONTH_TO, $CA_DEPTCD, $BR_STATECD)
    {
//        SELECT *, MONTH(CA_RCVDT) FROM pct_case
//        INNER JOIN sys_brn
//        ON pct_case.CA_BRNCD=sys_brn.BR_BRNCD
//        WHERE BR_STATECD > '0' && MONTH(CA_RCVDT)>='$from' && 
//        YEAR(CA_RCVDT) BETWEEN $fromy AND $toy && CA_DEPTCD like '%$bahagaian%'
//        select * from pct_case WHERE MONTH(CA_RCVDT) BETWEEN $from AND $to GROUP BY MONTH(CA_RCVDT)
        $laporannegeribulan = DB::table('case_info')
            ->join('sys_brn', 'case_info.CA_BRNCD', '=', 'sys_brn.BR_BRNCD')
            ->select('BR_STATECD', DB::raw('
                SUM(CASE WHEN MONTH(CA_RCVDT)="1" THEN 1 ELSE 0 END) AS countmonth1,
                SUM(CASE WHEN MONTH(CA_RCVDT)="2" THEN 1 ELSE 0 END) AS countmonth2,
                SUM(CASE WHEN MONTH(CA_RCVDT)="3" THEN 1 ELSE 0 END) AS countmonth3,
                SUM(CASE WHEN MONTH(CA_RCVDT)="4" THEN 1 ELSE 0 END) AS countmonth4,
                SUM(CASE WHEN MONTH(CA_RCVDT)="5" THEN 1 ELSE 0 END) AS countmonth5,
                SUM(CASE WHEN MONTH(CA_RCVDT)="6" THEN 1 ELSE 0 END) AS countmonth6,
                SUM(CASE WHEN MONTH(CA_RCVDT)="7" THEN 1 ELSE 0 END) AS countmonth7,
                SUM(CASE WHEN MONTH(CA_RCVDT)="8" THEN 1 ELSE 0 END) AS countmonth8,
                SUM(CASE WHEN MONTH(CA_RCVDT)="9" THEN 1 ELSE 0 END) AS countmonth9, 
                SUM(CASE WHEN MONTH(CA_RCVDT)="10" THEN 1 ELSE 0 END) AS countmonth10,
                SUM(CASE WHEN MONTH(CA_RCVDT)="11" THEN 1 ELSE 0 END) AS countmonth11,
                SUM(CASE WHEN MONTH(CA_RCVDT)="12" THEN 1 ELSE 0 END) AS countmonth12,
                COUNT(CA_CASEID) as countcaseid'))
            ->whereYear('CA_RCVDT', $CA_RCVDT_YEAR)
            ->whereMonth('CA_RCVDT', '>=', $CA_RCVDT_MONTH_FROM)
            ->whereMonth('CA_RCVDT', '<=', $CA_RCVDT_MONTH_TO)
            ->where('CA_DEPTCD', 'like', "%$CA_DEPTCD%")
            ->whereIn('BR_STATECD', $BR_STATECD)
            ->groupBy('BR_STATECD')
            ->get()
        ;
        return $laporannegeribulan;
    }
    
    public static function laporannegerijumlah($BR_STATECD)
    {
//        SELECT *, MONTH(CA_RCVDT) FROM pct_case
//        INNER JOIN sys_brn
//        ON pct_case.CA_BRNCD=sys_brn.BR_BRNCD
//        WHERE BR_STATECD > '0' && MONTH(CA_RCVDT)>='$from' && YEAR(CA_RCVDT) BETWEEN $fromy AND $toy && CA_DEPTCD like '%$bahagaian%'
//        select * from pct_case WHERE MONTH(CA_RCVDT) BETWEEN $from AND $to GROUP BY MONTH(CA_RCVDT)
        $laporannegeribulan = DB::table('case_info')
            ->join('sys_brn', 'case_info.CA_BRNCD', '=', 'sys_brn.BR_BRNCD')
            ->where(['BR_STATECD' => $BR_STATECD])
            ->count()
        ;
        return $laporannegeribulan;
    }
    
    public static function jumlahaduanlaporannegeribulan($CA_RCVDT_MONTH)
    {
        $jumlahaduanlaporannegeribulan = DB::table('case_info')
            ->join('sys_brn', 'case_info.CA_BRNCD', '=', 'sys_brn.BR_BRNCD')
            ->whereMonth('CA_RCVDT', $CA_RCVDT_MONTH)
            ->count()
        ;
        return $jumlahaduanlaporannegeribulan;
    }
    
    public static function laporannegerijumlahsemua()
    {
        $laporannegerijumlahsemua = DB::table('case_info')
            ->join('sys_brn', 'case_info.CA_BRNCD', '=', 'sys_brn.BR_BRNCD')
            ->count()
        ;
        return $laporannegerijumlahsemua;
    }
    
    public static function jumlahaduanstatustahun($CA_INVSTS, $CA_RCVDT_YEAR, $BR_STATECD, $CA_DEPTCD)
    {
        $jumlahaduanstatustahun = DB::table('case_info')
            ->join('sys_brn', 'case_info.CA_BRNCD', '=', 'sys_brn.BR_BRNCD')
            ->select('CA_INVSTS', DB::raw('
                COUNT(CA_CASEID) as countcaseid'))
            ->when($CA_RCVDT_YEAR, function ($query) use ($CA_RCVDT_YEAR) {
                return $query->whereYear('CA_RCVDT', $CA_RCVDT_YEAR);
            })
//            ->when($CA_RCVDT_YEAR_FROM, function ($query) use ($CA_RCVDT_YEAR_FROM) {
//                return $query->whereYear('CA_RCVDT', '>=', $CA_RCVDT_YEAR_FROM);
//            })
//            ->when($CA_RCVDT_YEAR_TO, function ($query) use ($CA_RCVDT_YEAR_TO) {
//                return $query->whereYear('CA_RCVDT', '<=', $CA_RCVDT_YEAR_TO);
//            })
            ->when($CA_INVSTS, function ($query) use ($CA_INVSTS) {
                return $query->where('CA_INVSTS', $CA_INVSTS);
            })
            ->when($BR_STATECD, function ($query) use ($BR_STATECD) {
                return $query->where('BR_STATECD', $BR_STATECD);
            })
            ->when($CA_DEPTCD, function ($query) use ($CA_DEPTCD) {
                return $query->where('CA_DEPTCD', $CA_DEPTCD);
            })
            ->where(['CA_INVSTS' => $CA_INVSTS],['BR_STATECD' => $BR_STATECD])
            ->whereYear('CA_RCVDT', $CA_RCVDT_YEAR)
            ->count()
        ;
        return $jumlahaduanstatustahun;
    }
    
    public static function jumlahaduanstatus($CA_INVSTS = '')
    {
        $jumlahaduanstatustahun = DB::table('case_info')
            ->join('sys_brn', 'case_info.CA_BRNCD', '=', 'sys_brn.BR_BRNCD')
            ->where(['CA_INVSTS' => $CA_INVSTS])
            ->count()
        ;
        return $jumlahaduanstatustahun;
    }
    
    public static function jumlahaduantahun($CA_RCVDT_YEAR, $BR_STATECD, $CA_DEPTCD)
    {
        $jumlahaduantahun = DB::table('case_info')
            ->join('sys_brn', 'case_info.CA_BRNCD', '=', 'sys_brn.BR_BRNCD')
            ->whereYear('CA_RCVDT', $CA_RCVDT_YEAR)
            ->whereNotIn('CA_INVSTS', ['10'])
            ->when($BR_STATECD, function ($query) use ($BR_STATECD) {
                return $query->where('BR_STATECD', $BR_STATECD);
            })
            ->when($CA_DEPTCD, function ($query) use ($CA_DEPTCD) {
                return $query->where('CA_DEPTCD', $CA_DEPTCD);
            })
            ->count()
        ;
        return $jumlahaduantahun;
    }
    
    public static function jumlahaduanmengikutstatustahunan()
    {
        $jumlahaduanstatustahun = DB::table('case_info')
            ->join('sys_brn', 'case_info.CA_BRNCD', '=', 'sys_brn.BR_BRNCD')
            ->count()
        ;
        return $jumlahaduanstatustahun;
    }
    
    public static function laporannegeritarikhkategori($CA_CMPLCAT = '', $CA_RCVDT_DAY = '')
    {
        $laporannegeritarikhkategori = DB::table('case_info')
            ->where(['CA_CMPLCAT' => $CA_CMPLCAT])
            ->whereDay('CA_RCVDT', $CA_RCVDT_DAY)
            ->count()
        ;
        return $laporannegeritarikhkategori;
    }
    
    public static function jumlahlaporannegeritarikh($CA_RCVDT_DAY = '')
    {
        $jumlahlaporannegeritarikh = DB::table('case_info')
            ->whereDay('CA_RCVDT', $CA_RCVDT_DAY)
            ->count()
        ;
        return $jumlahlaporannegeritarikh;
    }
    
    public static function jumlahkategoritahun($CA_CMPLCAT = '', $CA_RCVDT_YEAR = '', $CA_STATECD = '', $CA_DEPTCD = '')
    {
        $jumlahkategoritahun = DB::table('case_info')
            ->join('sys_brn', 'case_info.CA_BRNCD', '=', 'sys_brn.BR_BRNCD')
//            ->where(['CA_CMPLCAT' => $CA_CMPLCAT, 'CA_STATECD' => $CA_STATECD, 'CA_DEPTCD' => $CA_DEPTCD])
//            ->whereYear('CA_RCVDT', $CA_RCVDT_YEAR)
            ->when($CA_CMPLCAT, function ($query) use ($CA_CMPLCAT) {
                return $query->where('CA_CMPLCAT', $CA_CMPLCAT);
            })
            ->when($CA_RCVDT_YEAR, function ($query) use ($CA_RCVDT_YEAR) {
                return $query->whereYear('CA_RCVDT', $CA_RCVDT_YEAR);
            })
            ->when($CA_STATECD, function ($query) use ($CA_STATECD) {
                return $query->where('BR_STATECD', $CA_STATECD);
            })
            ->when($CA_DEPTCD, function ($query) use ($CA_DEPTCD) {
                return $query->where('CA_DEPTCD', $CA_DEPTCD);
            })
            ->count()
        ;
        return $jumlahkategoritahun;
    }
    
    public static function jumlahkategoritahunsemuatahun($CA_RCVDT_YEAR_FROM, $CA_RCVDT_YEAR_TO, $CA_CMPLCAT = '', $CA_STATECD = '', $CA_DEPTCD = '')
    {
        $jumlahkategoritahun = DB::table('case_info')
            ->join('sys_brn', 'case_info.CA_BRNCD', '=', 'sys_brn.BR_BRNCD')
//            ->where(['CA_CMPLCAT' => $CA_CMPLCAT, 'CA_STATECD' => $CA_STATECD, 'CA_DEPTCD' => $CA_DEPTCD])
            ->when($CA_CMPLCAT, function ($query) use ($CA_CMPLCAT) {
                return $query->where('CA_CMPLCAT', $CA_CMPLCAT);
            })
            ->when($CA_RCVDT_YEAR_FROM, function ($query) use ($CA_RCVDT_YEAR_FROM) {
                return $query->whereYear('CA_RCVDT', '>=', $CA_RCVDT_YEAR_FROM);
            })
            ->when($CA_RCVDT_YEAR_TO, function ($query) use ($CA_RCVDT_YEAR_TO) {
                return $query->whereYear('CA_RCVDT', '<=', $CA_RCVDT_YEAR_TO);
            })
            ->when($CA_STATECD, function ($query) use ($CA_STATECD) {
                return $query->where('BR_STATECD', $CA_STATECD);
            })
            ->when($CA_DEPTCD, function ($query) use ($CA_DEPTCD) {
                return $query->where('CA_DEPTCD', $CA_DEPTCD);
            })
            ->count()
        ;
        return $jumlahkategoritahun;
    }
    
    public static function jumlahkategoritahunsemuakategori($CA_RCVDT_YEAR = '', $CA_STATECD = '', $CA_DEPTCD = '')
    {
        $jumlahkategoritahun = DB::table('case_info')
            ->join('sys_brn', 'case_info.CA_BRNCD', '=', 'sys_brn.BR_BRNCD')
            ->join('sys_ref', 'case_info.CA_CMPLCAT', '=', 'sys_ref.code')
//            ->where(['CA_STATECD' => $CA_STATECD, 'CA_DEPTCD' => $CA_DEPTCD])
//            ->whereYear('CA_RCVDT', $CA_RCVDT_YEAR)
            ->when($CA_RCVDT_YEAR, function ($query) use ($CA_RCVDT_YEAR) {
                return $query->whereYear('CA_RCVDT', $CA_RCVDT_YEAR);
            })
            ->when($CA_STATECD, function ($query) use ($CA_STATECD) {
                return $query->where('BR_STATECD', $CA_STATECD);
            })
            ->when($CA_DEPTCD, function ($query) use ($CA_DEPTCD) {
                return $query->where('CA_DEPTCD', $CA_DEPTCD);
            })
            ->where('sys_ref.cat', '244')
            ->where('sys_ref.status', '1')
            ->count()
        ;
        return $jumlahkategoritahun;
    }
    
    public static function jumlahkategoritahunsemua($CA_RCVDT_YEAR_FROM, $CA_RCVDT_YEAR_TO, $CA_STATECD = '', $CA_DEPTCD = '')
    {
        $jumlahkategoritahun = DB::table('case_info')
//            ->where(['CA_STATECD' => $CA_STATECD, 'CA_DEPTCD' => $CA_DEPTCD])
            ->join('sys_brn', 'case_info.CA_BRNCD', '=', 'sys_brn.BR_BRNCD')
            ->join('sys_ref', 'case_info.CA_CMPLCAT', '=', 'sys_ref.code')
            ->when($CA_RCVDT_YEAR_FROM, function ($query) use ($CA_RCVDT_YEAR_FROM) {
                return $query->whereYear('CA_RCVDT', '>=', $CA_RCVDT_YEAR_FROM);
            })
            ->when($CA_RCVDT_YEAR_TO, function ($query) use ($CA_RCVDT_YEAR_TO) {
                return $query->whereYear('CA_RCVDT', '<=', $CA_RCVDT_YEAR_TO);
            })
            ->when($CA_STATECD, function ($query) use ($CA_STATECD) {
                return $query->where('BR_STATECD', $CA_STATECD);
            })
            ->when($CA_DEPTCD, function ($query) use ($CA_DEPTCD) {
                return $query->where('CA_DEPTCD', $CA_DEPTCD);
            })
            ->where('sys_ref.cat', '244')
            ->where('sys_ref.status', '1')
            ->count()
        ;
        return $jumlahkategoritahun;
    }
    
    public static function getUserName($id) {
        $mUserName = DB::table('sys_users')
            ->select('name')
            ->where(['id' => $id])
            ->first()
        ;
        return $mUserName->name;
    }
}
