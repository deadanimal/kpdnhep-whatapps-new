<?php

namespace App\Laporan;

use Illuminate\Database\Eloquent\Model;
use DB;
use Carbon\Carbon;

class ReportLainlain extends Model {

    protected $table = 'case_info';
    public $primaryKey = 'CA_CASEID';
    protected $fillable = [
        'CA_RCVTYP', 'CA_RCVDT',
    ];

    public static function GetByYear($PlsSlct = true) {
        $mYear = DB::table('case_info')
                ->select(DB::raw('DISTINCT YEAR(CA_RCVDT) AS year '))
                ->orderBy('year', 'desc')
                ->pluck('year', 'year');


//       SELECT DISTINCT(YEAR(CA_RCVDT)) FROM pct_case ORDER BY YEAR(CA_RCVDT) DESC
        if ($PlsSlct == true) {
            $mYear->prepend('-- SILA PILIH --', '');
            return $mYear;
        } else {
            return $mYear;
        }
    }

    public static function StateKat($kat, $sState, $from, $to, $depart) {
        if (($from != '') && ($to != '') && ($depart != '')) {
            $listcataduan = DB::table('case_info')
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
                    ->where(['CA_CMPLCAT' => $kat, 'BR_STATECD' => $sState, 'CA_DEPTCD' => $depart])
                    ->whereBetween('CA_RCVDT', [date('Y-m-d', strtotime($from)), date('Y-m-d', strtotime($to))])
                    ->groupBy('CA_CMPLCAT')
                    ->orderBy(DB::raw('COUNT(CA_CASEID)'), 'desc')
                    ->count();
        } else if (($from != '') && ($to != '') && ($depart == '')) {
            $listcataduan = DB::table('case_info')
                    ->join('sys_brn', 'case_info.CA_BRNCD', '=', 'sys_brn.BR_BRNCD')
                    ->where(['CA_CMPLCAT' => $kat, 'BR_STATECD' => $sState])
                    ->whereBetween('CA_RCVDT', [date('Y-m-d', strtotime($from)), date('Y-m-d', strtotime($to))])
                    ->count();
        } else if (($from != '') && ($to == '') && ($depart != '')) {
            $listcataduan = DB::table('case_info')
                    ->join('sys_brn', 'case_info.CA_BRNCD', '=', 'sys_brn.BR_BRNCD')
                    ->where(['CA_CMPLCAT' => $kat, 'BR_STATECD' => $sState, 'CA_DEPTCD' => $depart])
                    ->whereDate('CA_RCVDT', '>=', date('Y-m-d', strtotime($from)))
                    ->count()
            ;
        } else if (($from == '') && ($to != '') && ($depart != '')) {
            $listcataduan = DB::table('case_info')
                    ->join('sys_brn', 'case_info.CA_BRNCD', '=', 'sys_brn.BR_BRNCD')
                    ->where(['CA_CMPLCAT' => $kat, 'BR_STATECD' => $sState, 'CA_DEPTCD' => $depart])
                    ->whereDate('CA_RCVDT', '<=', date('Y-m-d', strtotime($to)))
                    ->count()
            ;
        } else if (($from == '') && ($to == '') && ($depart == '')) {
            $listcataduan = DB::table('case_info')
                    ->join('sys_brn', 'case_info.CA_BRNCD', '=', 'sys_brn.BR_BRNCD')
                    ->where(['CA_CMPLCAT' => $kat, 'BR_STATECD' => $sState])
                    ->count()
            ;
        }
//        dd($listcataduan);
        return $listcataduan;
    }

    public static function JumlahbwhKat($sState, $from, $to, $depart) {

        if (($from != '') && ($to != '') && ($depart != '')) {
            $totalcat = DB::table('case_info')
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
                    ->where(['BR_STATECD' => $sState, 'CA_DEPTCD' => $depart])
                    ->whereBetween('CA_RCVDT', [date('Y-m-d', strtotime($from)), date('Y-m-d', strtotime($to))])
                    ->groupBy('CA_CMPLCAT')
                    ->orderBy(DB::raw('COUNT(CA_CASEID)'), 'desc')
                    ->count();
        } else if (($from != '') && ($to != '') && ($depart == '')) {
            $totalcat = DB::table('case_info')
                    ->join('sys_brn', 'case_info.CA_BRNCD', '=', 'sys_brn.BR_BRNCD')
                    ->where(['BR_STATECD' => $sState])
                    ->whereBetween('CA_RCVDT', [date('Y-m-d', strtotime($from)), date('Y-m-d', strtotime($to))])
                    ->count();
//            dd($totalcat);
        } else if (($from != '') && ($to == '') && ($depart != '')) {
            $totalcat = DB::table('case_info')
                    ->join('sys_brn', 'case_info.CA_BRNCD', '=', 'sys_brn.BR_BRNCD')
                    ->where(['BR_STATECD' => $sState, 'CA_DEPTCD' => $depart])
                    ->whereDate('CA_RCVDT', '>=', date('Y-m-d', strtotime($from)))
                    ->count()
            ;
        } else if (($from == '') && ($to != '') && ($depart != '')) {
            $totalcat = DB::table('case_info')
                    ->join('sys_brn', 'case_info.CA_BRNCD', '=', 'sys_brn.BR_BRNCD')
                    ->where(['BR_STATECD' => $sState, 'CA_DEPTCD' => $depart])
                    ->whereDate('CA_RCVDT', '<=', date('Y-m-d', strtotime($to)))
                    ->count()
            ;
        } else if (($from == '') && ($to == '') && ($depart == '')) {
            $totalcat = DB::table('case_info')
                    ->join('sys_brn', 'case_info.CA_BRNCD', '=', 'sys_brn.BR_BRNCD')
                    ->where(['BR_STATECD' => $sState])
                    ->count()
            ;
        }
//  
//        dd($totalcat);
//        $countjum = count($totalcat);
        return $totalcat;
    }

    public static function Jumlahkat($kat, $from, $to, $depart) {

        if (($from != '') && ($to != '') && ($depart != '')) {
            $totalcat = DB::table('case_info')
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
                    ->where(['CA_CMPLCAT' => $kat, 'CA_DEPTCD' => $depart])
                    ->whereBetween('CA_RCVDT', [date('Y-m-d', strtotime($from)), date('Y-m-d', strtotime($to))])
                    ->groupBy('CA_CMPLCAT')
                    ->orderBy(DB::raw('COUNT(CA_CASEID)'), 'desc')
                    ->count();
        } else if (($from != '') && ($to != '') && ($depart == '')) {
            $totalcat = DB::table('case_info')
                    ->join('sys_brn', 'case_info.CA_BRNCD', '=', 'sys_brn.BR_BRNCD')
                    ->where(['CA_CMPLCAT' => $kat])
                    ->whereBetween('CA_RCVDT', [date('Y-m-d', strtotime($from)), date('Y-m-d', strtotime($to))])
                    ->count();
//            dd($totalcat);
        } else if (($from != '') && ($to == '') && ($depart != '')) {
            $totalcat = DB::table('case_info')
                    ->join('sys_brn', 'case_info.CA_BRNCD', '=', 'sys_brn.BR_BRNCD')
                    ->where(['CA_CMPLCAT' => $kat, 'CA_DEPTCD' => $depart])
                    ->whereDate('CA_RCVDT', '>=', date('Y-m-d', strtotime($from)))
                    ->count()
            ;
        } else if (($from == '') && ($to != '') && ($depart != '')) {
            $totalcat = DB::table('case_info')
                    ->join('sys_brn', 'case_info.CA_BRNCD', '=', 'sys_brn.BR_BRNCD')
                    ->where(['CA_CMPLCAT' => $kat, 'CA_DEPTCD' => $depart])
                    ->whereDate('CA_RCVDT', '<=', date('Y-m-d', strtotime($to)))
                    ->count()
            ;
        } else if (($from == '') && ($to == '') && ($depart == '')) {
            $totalcat = DB::table('case_info')
                    ->join('sys_brn', 'case_info.CA_BRNCD', '=', 'sys_brn.BR_BRNCD')
                    ->where(['CA_CMPLCAT' => $kat])
                    ->count();

            dd($totalcat);
        }
//  
//        dd($totalcat);
//        $countjum = count($totalcat);
        return $totalcat;
    }

    public static function Jumlahsmua($from, $to, $depart) {

        if (($from != '') && ($to != '') && ($depart != '')) {
            $totalcat = DB::table('case_info')
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
                    ->where(['CA_DEPTCD' => $depart])
                    ->whereBetween('CA_RCVDT', [date('Y-m-d', strtotime($from)), date('Y-m-d', strtotime($to))])
//                ->groupBy('CA_CMPLCAT')
                    ->orderBy(DB::raw('COUNT(CA_CASEID)'), 'desc')
                    ->count();
        } else if (($from != '') && ($to != '') && ($depart == '')) {
            $totalcat = DB::table('case_info')
                    ->join('sys_brn', 'case_info.CA_BRNCD', '=', 'sys_brn.BR_BRNCD')
//                ->where(['CA_CMPLCAT' => $kat])
                    ->whereBetween('CA_RCVDT', [date('Y-m-d', strtotime($from)), date('Y-m-d', strtotime($to))])
                    ->count();
//            dd($totalcat);
        } else if (($from != '') && ($to == '') && ($depart != '')) {
            $totalcat = DB::table('case_info')
                    ->join('sys_brn', 'case_info.CA_BRNCD', '=', 'sys_brn.BR_BRNCD')
                    ->where(['CA_DEPTCD' => $depart])
                    ->whereDate('CA_RCVDT', '>=', date('Y-m-d', strtotime($from)))
                    ->count()
            ;
        } else if (($from == '') && ($to != '') && ($depart != '')) {
            $totalcat = DB::table('case_info')
                    ->join('sys_brn', 'case_info.CA_BRNCD', '=', 'sys_brn.BR_BRNCD')
                    ->where(['CA_DEPTCD' => $depart])
                    ->whereDate('CA_RCVDT', '<=', date('Y-m-d', strtotime($to)))
                    ->count()
            ;
        } else if (($from == '') && ($to == '') && ($depart == '')) {
            $totalcat = DB::table('case_info')
                    ->join('sys_brn', 'case_info.CA_BRNCD', '=', 'sys_brn.BR_BRNCD')
//                ->where(['CA_CMPLCAT' => $kat])
                    ->count();

            dd($totalcat);
        }
//  
//        dd($totalcat);
//        $countjum = count($totalcat);
        return $totalcat;
    }

    public static function negeristatus($from, $to, $state, $CA_INVSTS) {
        $ngristatus = DB::table('case_info')
                ->select(DB::raw('case_info.CA_CASESTS AS CA_CASESTS, '
                                . 'SUM(CASE WHEN CA_INVSTS = 3 THEN 1 ELSE 0 END) AS SELESAI,'
                                . 'SUM(CASE WHEN CA_CASESTS = 1 THEN 1 ELSE 0 END) AS TERIMA, '
                                . 'SUM(CASE WHEN CA_CASESTS = 1 AND CA_INVSTS = 2 THEN 1 ELSE 0 END) AS DALAMSIASATAN, '
                                . 'SUM(CASE WHEN CA_CASESTS = 2 AND CA_INVSTS = 9 THEN 1 ELSE 0 END) AS TUTUP, '
                                . 'SUM(CASE WHEN CA_CASESTS = 2 AND CA_INVSTS = 4 THEN 1 ELSE 0 END) AS AGENSILAIN, '
                                . 'SUM(CASE WHEN CA_CASESTS = 2 AND CA_INVSTS = 5 THEN 1 ELSE 0 END) AS TRIBUNAL, '
                                . 'SUM(CASE WHEN CA_CASESTS = 2 AND CA_INVSTS = 6 THEN 1 ELSE 0 END) AS PERTANYAAN, '
                                . 'SUM(CASE WHEN CA_CASESTS = 2 AND CA_INVSTS = 7 THEN 1 ELSE 0 END) AS MKLUMATXLENGKAP, '
                                . 'SUM(CASE WHEN CA_CASESTS = 2 AND CA_INVSTS = 8 THEN 1 ELSE 0 END) AS LUARBIDANG, '
                                . 'COUNT(CA_CASEID)AS COUNT_CA_CASEID'))
                ->join('sys_brn', 'case_info.CA_BRNCD', '=', 'sys_brn.BR_BRNCD')
                ->where(['CA_CASESTS' => $CA_INVSTS])
                ->whereIn('BR_STATECD', $state)
                ->whereIn('BR_STATECD', $CA_INVSTS)
                ->whereBetween('CA_RCVDT', array(date('Y-m-d', strtotime($from)), date('Y-m-d', strtotime($to))))
                ->groupBy('BR_STATECD')
                ->get();
//                    dd($ngristatus);
        return $ngristatus;
    }

    public static function SenaraiAduanByKat($dateFrom, $dateTo, $CA_DEPTCD, $BR_STATECD) {
//      "CA_RCVDT_dri" => "10-01-2017"
//      "CA_RCVDT_lst" => "05-06-2017"
//      "CA_RCVTYP" => "BPGK"
//      "CA_CASESTS" => array:4 [▶]
//      "BR_STATECD" => array:4 [▶]
//      "cari" => "Carian"

        $CaseByCategory = DB::table('case_info')
                ->select(DB::raw('sys_brn.BR_STATECD AS BR_STATECD, '
                                . 'SUM(CASE WHEN CA_INVSTS = 3 THEN 1 ELSE 0 END) AS SELESAI,'
                                . 'SUM(CASE WHEN CA_CASESTS = 1 THEN 1 ELSE 0 END) AS TERIMA, '
                                . 'SUM(CASE WHEN CA_CASESTS = 1 AND CA_INVSTS = 2 THEN 1 ELSE 0 END) AS DALAMSIASATAN, '
                                . 'SUM(CASE WHEN CA_CASESTS = 2 AND CA_INVSTS = 9 THEN 1 ELSE 0 END) AS TUTUP, '
                                . 'SUM(CASE WHEN CA_CASESTS = 2 AND CA_INVSTS = 4 THEN 1 ELSE 0 END) AS AGENSILAIN, '
                                . 'SUM(CASE WHEN CA_CASESTS = 2 AND CA_INVSTS = 5 THEN 1 ELSE 0 END) AS TRIBUNAL, '
                                . 'SUM(CASE WHEN CA_CASESTS = 2 AND CA_INVSTS = 6 THEN 1 ELSE 0 END) AS PERTANYAAN, '
                                . 'SUM(CASE WHEN CA_CASESTS = 2 AND CA_INVSTS = 7 THEN 1 ELSE 0 END) AS MKLUMATXLENGKAP, '
                                . 'SUM(CASE WHEN CA_CASESTS = 2 AND CA_INVSTS = 8 THEN 1 ELSE 0 END) AS LUARBIDANG, '
                                . 'COUNT(CA_CASEID)AS COUNT_CA_CASEID'))
                ->join('sys_brn', 'case_info.CA_BRNCD', '=', 'sys_brn.BR_BRNCD')
//                            ->where(['CA_DEPTCD' => $CA_DEPTCD, 'BR_STATECD' => $BR_STATECD])
//                ->where(['CA_DEPTCD' => $CA_DEPTCD])
//                ->whereBetween('CA_RCVDT', array(date('Y-m-d', strtotime($dateFrom)), date('Y-m-d', strtotime($dateTo))))
                ->groupBy('BR_STATECD')
                ->get();
//        dd($CaseByCategory);
        return $CaseByCategory;
    }

    public static function lanjutan($from, $to, $branch, $kat,$depart) {
    $listlanjutan = DB::table('case_info')
                    ->join('sys_brn', 'case_info.CA_BRNCD', '=', 'sys_brn.BR_BRNCD')
//                    ->select('CA_CMPLCAT')
                    ->select(DB::raw('CA_CMPLCAT,SUM(CASE WHEN CA_DEPTCD="BDP" THEN 1 ELSE 0 END) AS BDP,
                    SUM(CASE WHEN CA_DEPTCD="BPGK" THEN 1 ELSE 0 END) AS BPGK,
                    SUM(CASE WHEN CA_DEPTCD="BGK" THEN 1 ELSE 0 END) AS BGK,
                    SUM(CASE WHEN CA_DEPTCD="BIP" THEN 1 ELSE 0 END) AS BIP,
                    SUM(CASE WHEN CA_DEPTCD="BKPK" THEN 1 ELSE 0 END) AS BKPK,
                    SUM(CASE WHEN CA_DEPTCD="BPDN" THEN 1 ELSE 0 END) AS BPDN,
                    SUM(CASE WHEN CA_DEPTCD="BPP" THEN 1 ELSE 0 END) AS BPP,
                    SUM(CASE WHEN CA_DEPTCD="BPSM" THEN 1 ELSE 0 END) AS BPSM,
                    SUM(CASE WHEN CA_DEPTCD="BTM" THEN 1 ELSE 0 END) AS BTM,
                    SUM(CASE WHEN CA_DEPTCD="KSU" THEN 1 ELSE 0 END) AS KSU,
                    SUM(CASE WHEN CA_DEPTCD="NGO" THEN 1 ELSE 0 END) AS NGO,
                    SUM(CASE WHEN CA_DEPTCD="PPEN" THEN 1 ELSE 0 END) AS PPEN,
                    SUM(CASE WHEN CA_DEPTCD="RS" THEN 1 ELSE 0 END) AS RS,
                    SUM(CASE WHEN CA_DEPTCD="UKK" THEN 1 ELSE 0 END) AS UKK,
                    COUNT(CA_CASEID) as countcaseid'))
                    ->where('CA_CMPLCAT','!=','')
//                    ->whereBetween('CA_RCVDT', array (date('Y-m-d', strtotime($from)), date('Y-m-d', strtotime($to))))
//                    ->whereBetween('CA_RCVDT', [date('Y-m-d', strtotime($from)), date('Y-m-d', strtotime($to))])
                    ->groupBy('CA_CMPLCAT')
//                     ->orderBy(DB::raw('COUNT(CA_CASEID)'), 'desc')
//                    ->count();
                    ->get();
//        dd($listlanjutan);
        return $listlanjutan;
    }

    public static function GetRef($cat, $prepend) {
        $mRef = DB::table('sys_ref')
                ->where(['cat' => $cat, 'status' => '1'])
                ->orderBy('sort', 'asc')
                ->orderBy('descr', 'asc')
                ->pluck('descr', 'code')
        ;
        if ($prepend == 'sp') {
            $mRef->prepend('-- SILA PILIH --', '');
            return $mRef;
        } else if ($prepend == 'semua') {
            $mRef->prepend('SEMUA', '0');
            return $mRef;
        } else {
            return $mRef;
        }
    }

    public static function GetMonth() {
        $mRef = DB::table('sys_ref')
                ->where(['cat' => '206', 'status' => '1'])
                ->orderBy('sort', 'asc')
                ->pluck('descr', 'code')
                ->prepend('-- SILA PILIH --', '')
        ;
        return $mRef;
    }
    
    public static function EzStarGetStatus() {
        $mRef = DB::table('sys_ref')
                ->where(['cat' => '292', 'status' => '1'])
                ->whereNotIn('code',[10,1,6,12])
                ->orderBy('id')
                ->get();
        return $mRef;
    }
    
    public static function GetList($cat) {
        $mRef = DB::table('sys_ref')
                ->where(['cat' => $cat, 'status' => '1'])
                ->orderBy('sort', 'asc')
                ->pluck('descr', 'code')
                ->prepend('SEMUA', '0');
        return $mRef;
    }
    
}
