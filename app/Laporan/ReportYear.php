<?php

namespace App\Laporan;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\View;
use DB;
use Carbon\Carbon;

class ReportYear extends Model {

    protected $table = 'case_info';
    public $primaryKey = 'CA_CASEID';
    protected $fillable = [
        'CA_RCVTYP', 'CA_RCVDT',
    ];

    public static function GetByYear($PlsSlct = true) {
        $mYear = DB::table('case_info')
                ->select(DB::raw('DISTINCT YEAR(CA_RCVDT) AS year '))
                ->whereNotNull('CA_RCVDT')
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

//        SELECT EXTRACT(MONTH FROM CA_RCVDT) AS OrderMonth,
//CA_RCVTYP FROM case_info
//INNER JOIN sys_brn ON case_info.CA_BRNCD=sys_brn.BR_BRNCD
//WHERE  YEAR(CA_RCVDT)
    public static function GetByMonth($month, $type, $thun, $state, $depart) {
//    
        $tahun = date('Y', strtotime(Carbon::now()));
        $bhgian = DB::table('case_info')
                ->select('CA_DEPTCD')
                ->join('sys_brn', 'case_info.CA_BRNCD', '=', 'sys_brn.BR_BRNCD')
                ->get();
        $ngri = DB::table('case_info')
                ->select('BR_STATECD')
                ->join('sys_brn', 'case_info.CA_BRNCD', '=', 'sys_brn.BR_BRNCD')
                ->get();
        if (!empty($depart)) {
//           
            $bymonth = DB::table('case_info')
                    ->select(DB::raw('EXTRACT(MONTH FROM CA_RCVD) AS OrderMonth,CA_RCVTYP'))
                    ->join('sys_brn', 'case_info.CA_BRNCD', '=', 'sys_brn.BR_BRNCD')
                    ->whereMonth('CA_RCVDT', $month)
//                 ->where(['CA_RCVTYP' => $type])
                    ->where(['CA_RCVTYP' => $type, 'CA_DEPTCD' => $depart])
                    ->whereYear('CA_RCVDT', $thun)
                    ->get();
        } else {
            $bymonth = DB::table('case_info')
                    ->select(DB::raw('EXTRACT(MONTH FROM CA_RCVD) AS OrderMonth,CA_RCVTYP'))
                    ->join('sys_brn', 'case_info.CA_BRNCD', '=', 'sys_brn.BR_BRNCD')
                    ->whereMonth('CA_RCVDT', $month)
                    ->where(['CA_RCVTYP' => $type])
//                    ->whereIn(['CA_DEPTCD' => $bhgian])
                    ->whereYear('CA_RCVDT', $thun)
                    ->get();
        }

        if (!empty($state)) {
//           
            $bymonth = DB::table('case_info')
                    ->select(DB::raw('EXTRACT(MONTH FROM CA_RCVDT) AS OrderMonth,CA_RCVTYP,BR_STATECD'))
                    ->join('sys_brn', 'case_info.CA_BRNCD', '=', 'sys_brn.BR_BRNCD')
                    ->whereMonth('CA_RCVDT', $month)
                    ->where(['CA_RCVTYP' => $type, 'BR_STATECD' => $state])
                    ->whereYear('CA_RCVDT', $thun)
                    ->get();
        } else {
            $bymonth = DB::table('case_info')
                    ->select(DB::raw('EXTRACT(MONTH FROM CA_RCVDT) AS OrderMonth,CA_RCVTYP'))
                    ->join('sys_brn', 'case_info.CA_BRNCD', '=', 'sys_brn.BR_BRNCD')
                    ->whereMonth('CA_RCVDT', $month)
                    ->where(['CA_RCVTYP' => $type])
                    ->whereYear('CA_RCVDT', $thun)
                    ->get();
//               dd($bymonth);
        }
//         $bymonth = DB::table('case_info')
//        ->join('sys_brn', 'case_info.CA_BRNCD', '=', 'sys_brn.BR_BRNCD')
//        ->select(DB::raw('EXTRACT(MONTH FROM CA_RCVDT) AS OrderMonth,CA_RCVTYP'))
//        ->whereMonth('CA_RCVDT', $month)
//        ->where(['CA_RCVTYP' => $type, 'BR_STATECD' => $state, 'CA_DEPTCD' => $depart])
//        ->whereYear('CA_RCVDT', $thun)
//        ->get();

        $countmonth = count($bymonth);
        return $countmonth;
    }

    public static function GetByState($type, $state, $year, $depart) {
        $tahun = date('Y', strtotime(Carbon::now()));
        $dprtmnt = DB::table('case_info')
                ->select('CA_DEPTCD')
                ->join('sys_brn', 'case_info.CA_BRNCD', '=', 'sys_brn.BR_BRNCD')
                ->get();

        if (!empty($depart)) {
            $bystate = DB::table('case_info')
                    ->join('sys_brn', 'case_info.CA_BRNCD', '=', 'sys_brn.BR_BRNCD')
                    ->select(DB::raw('CA_DEPTCD,CA_RCVTYP,BR_STATECD'))
                    ->where(['CA_RCVTYP' => $type, 'CA_DEPTCD' => $depart, 'BR_STATECD' => $state])
                    ->whereYear('CA_RCVDT', $year)
                    ->get();
        } else {
            $bystate = DB::table('case_info')
                    ->join('sys_brn', 'case_info.CA_BRNCD', '=', 'sys_brn.BR_BRNCD')
                    ->select(DB::raw('BR_STATECD,CA_RCVTYP'))
                    ->where(['BR_STATECD' => $state, 'CA_RCVTYP' => $type])
                    ->whereYear('CA_RCVDT', $year)
                    ->get();
        }
//              dd($bystate);
        $countstate = count($bystate);
        return $countstate;
    }

    public static function Jumlahbwh($month, $thun, $state, $depart) {
        if (!empty($depart)) {
            $total = DB::table('case_info')
                    ->select(DB::raw('EXTRACT(MONTH FROM CA_RCVDT) AS OrderMonth,CA_RCVTYP,CA_DEPTCD'))
                    ->join('sys_brn', 'case_info.CA_BRNCD', '=', 'sys_brn.BR_BRNCD')
//                        ->where(['CA_DEPTCD' => $depart])
                    ->whereMonth('CA_RCVDT', $month)
                    ->whereYear('CA_RCVDT', $thun)
                    ->get();
        } else {
            $total = DB::table('case_info')
                    ->select(DB::raw('EXTRACT(MONTH FROM CA_RCVDT) AS OrderMonth,CA_RCVTYP,BR_STATECD,CA_DEPTCD'))
                    ->join('sys_brn', 'case_info.CA_BRNCD', '=', 'sys_brn.BR_BRNCD')
//                        ->whereIn(['BR_STATECD' => $state, 'CA_DEPTCD' => $depart])
                    ->whereMonth('CA_RCVDT', $month)
                    ->whereYear('CA_RCVDT', $thun)
                    ->get();
        }
        if (!empty($state)) {
            $total = DB::table('case_info')
                    ->select(DB::raw('EXTRACT(MONTH FROM CA_RCVDT) AS OrderMonth,CA_RCVTYP,BR_STATECD'))
                    ->join('sys_brn', 'case_info.CA_BRNCD', '=', 'sys_brn.BR_BRNCD')
                    ->where(['BR_STATECD' => $state])
                    ->whereMonth('CA_RCVDT', $month)
                    ->whereYear('CA_RCVDT', $thun)
                    ->get();
        } else {
            $total = DB::table('case_info')
                    ->select(DB::raw('EXTRACT(MONTH FROM CA_RCVDT) AS OrderMonth'))
                    ->join('sys_brn', 'case_info.CA_BRNCD', '=', 'sys_brn.BR_BRNCD')
//                        ->whereIn(['BR_STATECD' => $state, 'CA_DEPTCD' => $depart])
                    ->whereMonth('CA_RCVDT', $month)
                    ->whereYear('CA_RCVDT', $thun)
                    ->get();
        }
//        dd($total);
        $countjum = count($total);
        return $countjum;
    }

    public static function JumlahTepi($type, $thun, $state, $depart) {
        if (!empty($depart)) {
            $total = DB::table('case_info')
                    ->select(DB::raw('EXTRACT(MONTH FROM CA_RCVDT) AS OrderMonth,CA_RCVTYP'))
                    ->join('sys_brn', 'case_info.CA_BRNCD', '=', 'sys_brn.BR_BRNCD')
                    ->whereYear('CA_RCVDT', $thun)
                    ->where(['BR_STATECD' => $state, 'CA_DEPTCD' => $depart, 'CA_RCVTYP' => $type])
                    ->get();
        } else {
            $total = DB::table('case_info')
                    ->select(DB::raw('EXTRACT(MONTH FROM CA_RCVDT) AS OrderMonth,CA_RCVTYP'))
                    ->join('sys_brn', 'case_info.CA_BRNCD', '=', 'sys_brn.BR_BRNCD')
                    ->whereYear('CA_RCVDT', $thun)
                    ->where(['CA_RCVTYP' => $type])
                    ->get();
        }
        if (!empty($state)) {
            $total = DB::table('case_info')
                    ->select(DB::raw('EXTRACT(MONTH FROM CA_RCVDT) AS OrderMonth,CA_RCVTYP'))
                    ->join('sys_brn', 'case_info.CA_BRNCD', '=', 'sys_brn.BR_BRNCD')
                    ->whereYear('CA_RCVDT', $thun)
                    ->where(['BR_STATECD' => $state, 'CA_RCVTYP' => $type])
                    ->get();
        } else {
            $total = DB::table('case_info')
                    ->select(DB::raw('EXTRACT(MONTH FROM CA_RCVDT) AS OrderMonth,CA_RCVTYP'))
                    ->join('sys_brn', 'case_info.CA_BRNCD', '=', 'sys_brn.BR_BRNCD')
                    ->whereYear('CA_RCVDT', $thun)
                    ->where(['CA_RCVTYP' => $type])
                    ->get();
        }
//        dd($total);
        $countjum = count($total);
        return $countjum;
    }

    public static function JumAll($thun, $state, $depart) {
        if (!empty($depart)) {
            $total = DB::table('case_info')
//              ->select('CA_RCVTYP')
                    ->join('sys_brn', 'case_info.CA_BRNCD', '=', 'sys_brn.BR_BRNCD')
                    ->where(['CA_DEPTCD' => $depart])
                    ->whereYear('CA_RCVDT', $thun)
                    ->get();
        } else {
            $total = DB::table('case_info')
//              ->select('CA_RCVTYP')
                    ->join('sys_brn', 'case_info.CA_BRNCD', '=', 'sys_brn.BR_BRNCD')
//                 ->where(['BR_STATECD' => $state, 'CA_DEPTCD' => $depart])
                    ->whereYear('CA_RCVDT', $thun)
                    ->get();
        }
        if (!empty($state)) {
            $total = DB::table('case_info')
//              ->select('CA_RCVTYP')
                    ->join('sys_brn', 'case_info.CA_BRNCD', '=', 'sys_brn.BR_BRNCD')
                    ->where(['BR_STATECD' => $state])
                    ->whereYear('CA_RCVDT', $thun)
                    ->get();
        } else {
            $total = DB::table('case_info')
//              ->select('CA_RCVTYP')
                    ->join('sys_brn', 'case_info.CA_BRNCD', '=', 'sys_brn.BR_BRNCD')
//                 ->where(['BR_STATECD' => $state, 'CA_DEPTCD' => $depart])
                    ->whereYear('CA_RCVDT', $thun)
                    ->get();
        }
        $countjum = count($total);
        return $countjum;
    }

    public static function JumlahTepiSa($type, $state, $thun, $depart) {
        if (!empty($depart)) {
            $total = DB::table('case_info')
                    ->select('CA_RCVTYP', 'BR_STATECD')
                    ->join('sys_brn', 'case_info.CA_BRNCD', '=', 'sys_brn.BR_BRNCD')
                    ->where(['CA_RCVTYP' => $type, 'CA_DEPTCD' => $depart])
                    ->whereYear('CA_RCVDT', $thun)
                    ->get();
        } else {
            $total = DB::table('case_info')
                    ->select('CA_RCVTYP', 'BR_STATECD')
                    ->join('sys_brn', 'case_info.CA_BRNCD', '=', 'sys_brn.BR_BRNCD')
                    ->where(['CA_RCVTYP' => $type])
                    ->whereYear('CA_RCVDT', $thun)
                    ->get();
        }
//          dd ($total);
        $countjum = count($total);
        return $countjum;
    }

    public static function JumlahbwhNgri($thun, $state, $depart) {
        if (!empty($depart)) {
            $total = DB::table('case_info')
                    ->select('CA_RCVTYP', 'BR_STATECD')
                    ->join('sys_brn', 'case_info.CA_BRNCD', '=', 'sys_brn.BR_BRNCD')
                    ->where(['CA_DEPTCD' => $depart, 'BR_STATECD' => $state])
                    ->whereYear('CA_RCVDT', $thun)
                    ->get();
        } else {
            $total = DB::table('case_info')
                    ->select('CA_RCVTYP', 'BR_STATECD')
                    ->join('sys_brn', 'case_info.CA_BRNCD', '=', 'sys_brn.BR_BRNCD')
                    ->where([['BR_STATECD', '=', $state], ['CA_RCVTYP', '!=', '']])
                    ->whereYear('CA_RCVDT', $thun)
                    ->get();
        }

//        dd($total);
        $countjum = count($total);
        return $countjum;
    }

    public static function JumSmua($thun, $state, $depart) {
        if (!empty($depart)) {
            $total = DB::table('case_info')
                    ->join('sys_brn', 'case_info.CA_BRNCD', '=', 'sys_brn.BR_BRNCD')
                    ->where(['CA_DEPTCD' => $depart])
                    ->whereYear('CA_RCVDT', $thun)
                    ->get();
        } else {
            $total = DB::table('case_info')
//              ->select('CA_RCVTYP')
                    ->join('sys_brn', 'case_info.CA_BRNCD', '=', 'sys_brn.BR_BRNCD')
                    ->whereYear('CA_RCVDT', $thun)
                    ->get();
        }

        $countjum = count($total);
        return $countjum;
    }

    public static function listkes($sYear, $month, $type, $state, $sdepart) {
        $tahun = date('Y', strtotime(Carbon::now()));
        $bhgian = DB::table('case_info')
                ->select('CA_DEPTCD')
                ->join('sys_brn', 'case_info.CA_BRNCD', '=', 'sys_brn.BR_BRNCD')
                ->get();
        $ngri = DB::table('case_info')
                ->select('BR_STATECD')
                ->join('sys_brn', 'case_info.CA_BRNCD', '=', 'sys_brn.BR_BRNCD')
                ->get();


//           $listcse = DB::table('case_info')
//                    ->whereYear('CA_RCVDT',$year)
//                    ->where(['CA_DEPTCD' => $sdepart,'BR_STATECD' => $sbrn])
//                 ->get();
//         
//        return $listcse;


        if (!empty($state)) {
//           
            $listcse = DB::table('case_info')
                    ->join('sys_brn', 'case_info.CA_BRNCD', '=', 'sys_brn.BR_BRNCD')
                    ->whereMonth('CA_RCVDT', $month)
                    ->where(['CA_RCVTYP' => $type, 'BR_STATECD' => $state])
                    ->whereYear('CA_RCVDT', $sYear)
                    ->get();
        } else {
            $listcse = DB::table('case_info')
                    ->join('sys_brn', 'case_info.CA_BRNCD', '=', 'sys_brn.BR_BRNCD')
                    ->whereMonth('CA_RCVDT', $month)
                    ->where(['CA_RCVTYP' => $type])
                    ->whereYear('CA_RCVDT', $sYear)
                    ->get();
//               dd($listcse);
        }


        return $listcse;
    }

    public static function GetRef($cat, $prepend = true) {
        $mRef = DB::table('sys_ref')
                ->where(['cat' => $cat, 'status' => '1'])
                ->orderBy('sort', 'asc')
                ->orderBy('descr', 'asc')
                ->pluck('descr', 'code');
        
        if ($prepend) {
            $mRef->prepend('SEMUA', '0');
        }
        
        return $mRef;
        
    }

}
