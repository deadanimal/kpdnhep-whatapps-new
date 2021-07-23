<?php

namespace App\Laporan;

use Illuminate\Database\Eloquent\Model;
use DB;
use App\Wd;
use App\Holiday;
use App\Ref;
use Carbon\Carbon;

class TerimaSelesaiAduan extends Model
{
    protected $table = 'case_info';
    public $primaryKey = 'CA_CASEID';

    protected $fillable = [
        'CA_RCVTYP', 'CA_RCVDT',
    ];

    public static function GetCaseByBGK()
    {
        $CaseByBGK = DB::table('case_info')
            ->select('CA_DEPTCD')
            ->join('sys_brn', 'case_info.CA_BRNCD', '=', 'sys_brn.BR_BRNCD')
            ->get();
        return $CaseByBGK;
    }

//    public static function SenaraiAduanByKat($dateFrom, $dateTo, $StateCd, $BranchCd, $CA_CMPLCAT, $Status)
    public static function SenaraiAduanByKat($dateFrom, $dateTo, $BranchCd, $CA_CMPLCAT)
    {
        $CaseByCategory = DB::table('case_info')
            ->select(DB::raw('case_info.CA_CMPLCAT AS CA_CMPLCAT, '
                . 'SUM(CASE WHEN CA_INVSTS = 3 THEN 1 ELSE 0 END) AS SELESAI,'
                . 'SUM(CASE WHEN CA_CASESTS = 1 THEN 1 ELSE 0 END) AS BELUMAGIH, '
                . 'SUM(CASE WHEN CA_CASESTS = 1 AND CA_INVSTS = 2 THEN 1 ELSE 0 END) AS DALAMSIASATAN, '
                . 'SUM(CASE WHEN CA_CASESTS = 2 AND CA_INVSTS = 9 THEN 1 ELSE 0 END) AS TUTUP, '
                . 'SUM(CASE WHEN CA_CASESTS = 2 AND CA_INVSTS = 4 THEN 1 ELSE 0 END) AS AGENSILAIN, '
                . 'SUM(CASE WHEN CA_CASESTS = 2 AND CA_INVSTS = 5 THEN 1 ELSE 0 END) AS TRIBUNAL, '
                . 'SUM(CASE WHEN CA_CASESTS = 2 AND CA_INVSTS = 6 THEN 1 ELSE 0 END) AS PERTANYAAN, '
                . 'SUM(CASE WHEN CA_CASESTS = 2 AND CA_INVSTS = 7 THEN 1 ELSE 0 END) AS MKLUMATXLENGKAP, '
                . 'SUM(CASE WHEN CA_CASESTS = 2 AND CA_INVSTS = 8 THEN 1 ELSE 0 END) AS LUARBIDANG, '
                . 'COUNT(CA_CASEID)AS COUNT_CA_CASEID'))
            ->join('sys_brn', 'case_info.CA_BRNCD', '=', 'sys_brn.BR_BRNCD')
            ->whereIn('CA_BRNCD', $BranchCd)
            ->where(['CA_CMPLCAT' => $CA_CMPLCAT])
            ->whereBetween('CA_RCVDT', array(date('Y-m-d', strtotime($dateFrom)), date('Y-m-d', strtotime($dateTo))))
            ->groupBy('CA_CMPLCAT')
            ->get();
//            dd($CaseByCategory);
        return $CaseByCategory;
    }

    public static function SenaraiAduanTerima($dateFrom, $dateTo, $StateCd, $BranchCd, $CA_CMPLCAT, $CaseCat)
    {
        $CaseRcvByCategory = DB::table('case_info')
            ->join('sys_brn', 'case_info.CA_BRNCD', '=', 'sys_brn.BR_BRNCD')
            ->where(['BR_STATECD' => $StateCd, 'CA_CMPLCAT' => $CaseCat])
            ->whereIn('CA_BRNCD', $BranchCd)
            ->whereIn('CA_CMPLCAT', $CA_CMPLCAT)
            ->whereBetween('CA_RCVDT', array(date('Y-m-d', strtotime($dateFrom)), date('Y-m-d', strtotime($dateTo))))
            ->get();
        $countCaseRcv = count($CaseRcvByCategory);
        return $countCaseRcv;
    }

    public static function SenaraiAduanBelumAgih($dateFrom, $dateTo, $StateCd, $BranchCd, $CA_CMPLCAT, $CaseCat)
    {
        $CaseByCategory = DB::table('case_info')
            ->join('sys_brn', 'case_info.CA_BRNCD', '=', 'sys_brn.BR_BRNCD')
            ->where(['BR_STATECD' => $StateCd, 'CA_CMPLCAT' => $CaseCat, 'case_info.CA_CASESTS' => '1'])
            ->whereIn('CA_BRNCD', $BranchCd)
            ->whereIn('CA_CMPLCAT', $CA_CMPLCAT)
            ->whereBetween('CA_RCVDT', array(date('Y-m-d', strtotime($dateFrom)), date('Y-m-d', strtotime($dateTo))))
            ->get();
        $countCase = count($CaseByCategory);
        return $countCase;
    }

    public static function SenaraiAduanDalamSiasatan($dateFrom, $dateTo, $StateCd, $BranchCd, $CA_CMPLCAT, $CaseCat)
    {
        $CaseInInvByCategory = DB::table('case_info')
            ->join('sys_brn', 'case_info.CA_BRNCD', '=', 'sys_brn.BR_BRNCD')
            ->where(['BR_STATECD' => $StateCd, 'CA_CMPLCAT' => $CaseCat, 'case_info.CA_CASESTS' => '2', 'case_info.CA_INVSTS' => '2'])
            ->whereBetween('CA_RCVDT', array(date('Y-m-d', strtotime($dateFrom)), date('Y-m-d', strtotime($dateTo))))
            ->whereIn('CA_BRNCD', $BranchCd)
            ->whereIn('CA_CMPLCAT', $CA_CMPLCAT)
            ->get();
        $countCaseInInv = count($CaseInInvByCategory);
        return $countCaseInInv;
    }

    public static function SenaraiAduanDalamTindakan($dateFrom, $dateTo, $StateCd, $BranchCd, $CA_CMPLCAT, $CaseCat, $status)
    {
        $CaseRcvByCategory = DB::table('case_info')
            ->join('sys_brn', 'case_info.CA_BRNCD', '=', 'sys_brn.BR_BRNCD')
            ->where(['BR_STATECD' => $StateCd, 'CA_CMPLCAT' => $CaseCat, 'case_info.CA_CASESTS' => 2, 'case_info.CA_INVSTS' => $status])
            ->whereIn('CA_BRNCD', $BranchCd)
            ->whereIn('CA_CMPLCAT', $CA_CMPLCAT)
            ->whereBetween('CA_RCVDT', array(date('Y-m-d', strtotime($dateFrom)), date('Y-m-d', strtotime($dateTo))))
            ->get();
        $countCaseRcv = count($CaseRcvByCategory);
        return $countCaseRcv;
    }

    public static function GetByYear($PlsSlct = true)
    {
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

    public static function GetMonth()
    {
        $mRef = DB::table('sys_ref')->where(['cat' => 206, 'status' => '1'])->orderBy('sort', 'asc')->orderBy('descr', 'asc')->pluck('descr', 'code');
        $mRef->prepend('-- SILA PILIH --', '');
        return $mRef;
    }

    public static function SenaraiAgihan($SelectYear, $MonthFrom, $MonthTo, $BR_STATECD, $CA_RCVTYP)
    {
//        SELECT sys_brn.BR_STATECD,
//        SUM(CASE WHEN DATEDIFF(case_info.CA_MODDT,case_info.CA_RCVDT) <2 THEN 1 ELSE 0 END) AS COUNT,
//        SUM(CASE WHEN DATEDIFF(case_info.CA_MODDT,case_info.CA_RCVDT) BETWEEN 2 AND 900 THEN 1 ELSE 0 END) AS Count2,
//        COUNT(CA_CASEID) 
//        FROM 
//        case_info
//        LEFT JOIN sys_brn
//        ON case_info.CA_BRNCD=sys_brn.BR_BRNCD
//        WHERE (CA_INVSTS BETWEEN 4 AND 5 || CA_INVSTS='0')  
//        -- && YEAR(CA_RCVDT)='$fromy'
//        -- && MONTH(CA_RCVDT) BETWEEN '$monthfrom' AND '$monthTo'
//        -- && BR_STATECD in ($g) 
//        -- && CA_DEPTCD like '%$bahagian%'
//        GROUP BY sys_brn.BR_STATECD
        if ($CA_RCVTYP != '') {
            $senarai = DB::table('case_info')
                ->join('sys_brn', 'case_info.CA_BRNCD', '=', 'sys_brn.BR_BRNCD')
                ->select(DB::raw('sys_brn.BR_STATECD as BR_STATECD, case_info.CA_DEPTCD, '
                    . 'SUM(CASE WHEN DATEDIFF(case_info.CA_MODDT,case_info.CA_RCVDT) <2 THEN 1 ELSE 0 END) AS COUNT,'
                    . 'SUM(CASE WHEN DATEDIFF(case_info.CA_MODDT,case_info.CA_RCVDT) BETWEEN 2 AND 900 THEN 1 ELSE 0 END) AS Count2, '
                    . 'COUNT(CA_CASEID)AS COUNT_CA_CASEID'))
                ->whereIn('CA_INVSTS', [0, 4, 5])
                ->whereYear('CA_RCVDT', $SelectYear)
                ->whereMonth('CA_RCVDT', '>=', $MonthFrom)
                ->whereMonth('CA_RCVDT', '<=', $MonthTo)
                ->whereIn('BR_STATECD', $BR_STATECD)
                ->where('CA_DEPTCD', $CA_RCVTYP)
                ->groupBy('BR_STATECD', 'CA_DEPTCD')
                ->get();
        } else {
            $senarai = DB::table('case_info')
                ->join('sys_brn', 'case_info.CA_BRNCD', '=', 'sys_brn.BR_BRNCD')
                ->select(DB::raw('sys_brn.BR_STATECD as BR_STATECD, '
                    . 'SUM(CASE WHEN DATEDIFF(case_info.CA_MODDT,case_info.CA_RCVDT) <2 THEN 1 ELSE 0 END) AS COUNT,'
                    . 'SUM(CASE WHEN DATEDIFF(case_info.CA_MODDT,case_info.CA_RCVDT) BETWEEN 2 AND 900 THEN 1 ELSE 0 END) AS Count2, '
                    . 'COUNT(CA_CASEID)AS COUNT_CA_CASEID'))
                ->whereIn('CA_INVSTS', [0, 4, 5])
                ->whereYear('CA_RCVDT', $SelectYear)
                ->whereMonth('CA_RCVDT', '>=', $MonthFrom)
                ->whereMonth('CA_RCVDT', '<=', $MonthTo)
                ->whereIn('BR_STATECD', $BR_STATECD)
                ->groupBy('BR_STATECD')
                ->get();
        }
        return $senarai;
    }

    /**
     * Senarai alternatif kepada SenaraiAgihan1hariByNegeri & SenaraiAgihanLebih1hariByNegeri.
     *
     * @desc Get all data with their datediff in single query
     * then populate to the data array for easier management in the future.
     *
     * @param integer $SelectYear tahun pilihan
     * @param integer $MonthFrom bulan mula
     * @param integer $MonthTo bulan akhir
     * @param mixed $BR_STATECD state
     * @param string $CA_RCVTYP department
     * @param integer $is_closed close or not
     * @return mixed
     */
    public static function SenaraiAgihanWithDateDiff($SelectYear, $MonthFrom, $MonthTo, $BR_STATECD, $CA_RCVTYP = '', $is_closed = 0, $CA_INVSTS)
    {
        $q = DB::table('case_info')
            ->select('BR_STATECD',
                'CA_DEPTCD',
                DB::Raw('DATEDIFF(case_info.CA_MODDT,case_info.CA_RCVDT) as date_diff'),
                DB::Raw('count(1) as total_cases')
            )
            ->join('sys_brn', 'case_info.CA_BRNCD', '=', 'sys_brn.BR_BRNCD')
            ->whereIn('CA_INVSTS', $CA_INVSTS)
            ->whereYear('CA_RCVDT', $SelectYear)
            ->whereMonth('CA_RCVDT', '>=', $MonthFrom)
            ->whereMonth('CA_RCVDT', '<=', $MonthTo);

        if (is_array($BR_STATECD) && count($BR_STATECD) > 0) {
            $q = $q->whereIn('BR_STATECD', $BR_STATECD);
        } else if (!is_array($BR_STATECD) && $BR_STATECD != '') {
            $q = $q->where('BR_STATECD', $BR_STATECD);
        } else { // when no data passed
            $q = $q->where('BR_STATECD', 'nodata');
        }

        if ($is_closed == 1) {
            $q = $q->whereNotNull('CA_CLOSEDT');
        }

        if ($CA_RCVTYP != '0') {
            $q = $q->where('CA_DEPTCD', $CA_RCVTYP);
        }

        return $lists = $q->groupBy('BR_STATECD', 'CA_DEPTCD', 'date_diff')->get();
    }

    public static function SenaraiSelesaiWithDateDiff($CA_RCVDT_FROM, $CA_RCVDT_TO, $BR_STATECD, $CA_RCVTYP = '', $is_closed = 0)
    {
        $q = DB::table('case_info')
            ->select('BR_STATECD', 'CA_DEPTCD', 'CA_AGAINST_STATECD', 'CA_STATECD', 'CA_COMPLETEDT', 'CA_RCVDT', 'CA_PRECLOSE_DURATION',
                DB::Raw('count(1) as total_cases')
            )
            ->join('sys_brn', 'case_info.CA_BRNCD', '=', 'sys_brn.BR_BRNCD')
            ->whereIn('CA_INVSTS', [3, 4, 5, 6, 8, 9, 11, 12])// close
            ->whereBetween('CA_RCVDT', [$CA_RCVDT_FROM, $CA_RCVDT_TO]);

        if (!empty($BR_STATECD)) {
            if (is_array($BR_STATECD) && count($BR_STATECD) > 0) {
                $q = $q->whereIn('BR_STATECD', $BR_STATECD);
            } else if (!is_array($BR_STATECD) && $BR_STATECD != '') {
                $q = $q->where('BR_STATECD', $BR_STATECD);
            }
        }

        if ($CA_RCVTYP != '0') {
            $q = $q->where('CA_DEPTCD', $CA_RCVTYP);
        }
        
        return $lists = $q->groupBy('BR_STATECD', 'CA_DEPTCD', 'CA_CASEID', 'CA_AGAINST_STATECD', 'CA_STATECD'
            , 'CA_COMPLETEDT', 'CA_RCVDT', 'CA_PRECLOSE_DURATION')->get();
    }

    /**
     * @deprecated use SenaraiAgihanWithDateDiff instead
     * @param $SelectYear
     * @param $MonthFrom
     * @param $MonthTo
     * @param $BR_STATECD
     * @param $CA_RCVTYP
     * @return int
     */
    public static function SenaraiAgihan1hariByNegeri($SelectYear, $MonthFrom, $MonthTo, $BR_STATECD, $CA_RCVTYP)
    {
        if ($CA_RCVTYP != '') {
            $senarai = DB::table('case_info')
                ->join('sys_brn', 'case_info.CA_BRNCD', '=', 'sys_brn.BR_BRNCD')
                ->select('BR_STATECD')
                ->whereIn('CA_INVSTS', [0, 4, 5])
                ->whereYear('CA_RCVDT', $SelectYear)
                ->whereMonth('CA_RCVDT', '>=', $MonthFrom)
                ->whereMonth('CA_RCVDT', '<=', $MonthTo)
                ->where(['BR_STATECD' => $BR_STATECD, 'CA_DEPTCD' => $CA_RCVTYP])
                ->whereRaw('DATEDIFF(case_info.CA_MODDT,case_info.CA_RCVDT) <2')
                ->get();
        } else {
            $senarai = DB::table('case_info')
                ->join('sys_brn', 'case_info.CA_BRNCD', '=', 'sys_brn.BR_BRNCD')
                ->select('BR_STATECD')
                ->whereIn('CA_INVSTS', [0, 4, 5])
                ->whereYear('CA_RCVDT', $SelectYear)
                ->whereMonth('CA_RCVDT', '>=', $MonthFrom)
                ->whereMonth('CA_RCVDT', '<=', $MonthTo)
                ->where('BR_STATECD', $BR_STATECD)
                ->whereRaw('DATEDIFF(case_info.CA_MODDT,case_info.CA_RCVDT) <2')
                ->get();
        }
        return count($senarai);
    }

    /**
     * @deprecated use SenaraiAgihanWithDateDiff instead
     * @param $SelectYear
     * @param $MonthFrom
     * @param $MonthTo
     * @param $BR_STATECD
     * @param $CA_RCVTYP
     * @return int
     */
    public static function SenaraiAgihanLebih1hariByNegeri($SelectYear, $MonthFrom, $MonthTo, $BR_STATECD, $CA_RCVTYP)
    {
        if ($CA_RCVTYP != '') {
            $senarai = DB::table('case_info')
                ->join('sys_brn', 'case_info.CA_BRNCD', '=', 'sys_brn.BR_BRNCD')
                ->select('BR_STATECD')
                ->whereIn('CA_INVSTS', [0, 4, 5])
                ->whereYear('CA_RCVDT', $SelectYear)
                ->whereMonth('CA_RCVDT', '>=', $MonthFrom)
                ->whereMonth('CA_RCVDT', '<=', $MonthTo)
                ->where(['BR_STATECD' => $BR_STATECD, 'CA_DEPTCD' => $CA_RCVTYP])
                ->whereRaw('DATEDIFF(case_info.CA_MODDT,case_info.CA_RCVDT) BETWEEN 2 AND 900')
                ->get();
        } else {
            $senarai = DB::table('case_info')
                ->join('sys_brn', 'case_info.CA_BRNCD', '=', 'sys_brn.BR_BRNCD')
                ->select('BR_STATECD')
                ->whereIn('CA_INVSTS', [0, 4, 5])
                ->whereYear('CA_RCVDT', $SelectYear)
                ->whereMonth('CA_RCVDT', '>=', $MonthFrom)
                ->whereMonth('CA_RCVDT', '<=', $MonthTo)
                ->where('BR_STATECD', $BR_STATECD)
                ->whereRaw('DATEDIFF(case_info.CA_MODDT,case_info.CA_RCVDT) BETWEEN 2 AND 900')
                ->get();
        }
        return count($senarai);
    }

    public static function SenaraiAduanSelesai($CA_RCVDT_FROM, $CA_RCVDT_TO, $BR_STATECD, $CA_DEPTCD)
    {
        $SenaraiSelasai = DB::table('case_info')
            ->select(DB::raw('sys_brn.BR_STATECD as BR_STATECD, COUNT(CA_CASEID) AS TOTAL_CASE,'
                . 'SUM(CASE WHEN DATEDIFF(case_info.CA_MODDT,case_info.CA_RCVDT) <2 THEN 1 ELSE 0 END) AS COUNT,'
                . 'SUM(CASE WHEN DATEDIFF(case_info.CA_MODDT,case_info.CA_RCVDT) BETWEEN 2 AND 5 THEN 1 ELSE 0 END) AS Count25, '
                . 'SUM(CASE WHEN DATEDIFF(case_info.CA_MODDT,case_info.CA_RCVDT) BETWEEN 6 AND 10 THEN 1 ELSE 0 END) AS Count610, '
                . 'SUM(CASE WHEN DATEDIFF(case_info.CA_MODDT,case_info.CA_RCVDT) BETWEEN 11 AND 15 THEN 1 ELSE 0 END) AS Count1115, '
                . 'SUM(CASE WHEN DATEDIFF(case_info.CA_MODDT,case_info.CA_RCVDT) BETWEEN 16 AND 21 THEN 1 ELSE 0 END) AS Count1621, '
                . 'SUM(CASE WHEN DATEDIFF(case_info.CA_MODDT,case_info.CA_RCVDT) BETWEEN 22 AND 30 THEN 1 ELSE 0 END) AS Count2230, '
                . 'SUM(CASE WHEN DATEDIFF(case_info.CA_MODDT,case_info.CA_RCVDT) BETWEEN 31 AND 60 THEN 1 ELSE 0 END) AS Count3160, '
                . 'SUM(CASE WHEN DATEDIFF(case_info.CA_MODDT,case_info.CA_RCVDT) > 60 THEN 1 ELSE 0 END) AS Count60, '
                . 'COUNT(CA_CASEID)AS COUNT_CA_CASEID'))
            ->join('sys_brn', 'case_info.CA_BRNCD', '=', 'sys_brn.BR_BRNCD')
            ->whereBetween('CA_RCVDT', [
                date('Y-m-d', strtotime($CA_RCVDT_FROM)),
                date('Y-m-d', strtotime($CA_RCVDT_TO))
            ])
            ->whereIn('CA_INVSTS', [3, 4, 5, 6, 7, 8, 9])// close
            ->groupBy('BR_STATECD');

        if ($CA_DEPTCD != '') {
            $SenaraiSelasai->whereIn('BR_STATECD', $BR_STATECD);
        }

        return $SenaraiSelasai->get();
    }

    /**
     * To get a list of tickets data that have been close by period of time
     * @param $CA_RCVDT_FROM
     * @param $CA_RCVDT_TO
     * @param $CA_DEPTCD
     * @return \Illuminate\Support\Collection
     */
    public static function SenaraiTempohAduan($CA_RCVDT_FROM, $CA_RCVDT_TO, $CA_DEPTCD)
    {
        $SenaraiTempohAduan = DB::table('case_info')
            ->select('CA_CASEID')
            ->whereBetween('CA_RCVDT', [
                date('Y-m-d', strtotime($CA_RCVDT_FROM)),
                date('Y-m-d', strtotime($CA_RCVDT_TO))
            ])
            ->whereIn('CA_INVSTS', [3, 4, 5, 6, 7, 8, 9]); // close

        if ($CA_DEPTCD != '') {
            $SenaraiTempohAduan->where(['CA_DEPTCD' => $CA_DEPTCD]);
        }

        return $SenaraiTempohAduan->get();
    }

    public static function GetDuration($CA_RCVDT, $CA_CASEID)
    {
        $mTerima = TerimaSelesaiAduan::where(['CA_CASEID' => $CA_CASEID])->first();
        $Working_day = new Wd();
        $Holidays = new Holiday();
//        $state_code = $mTerima->CA_AGAINST_STATECD;
        if ($mTerima->CA_AGAINST_STATECD != null) {
            $state_code = $mTerima->CA_AGAINST_STATECD;
        } else if ($mTerima->CA_STATECD != null) {
            $state_code = $mTerima->CA_STATECD;
        } else {
            $state_code = 16;
        }
        $now = \Carbon\Carbon::now();
        $RCVDT = Carbon::parse($CA_RCVDT);
        $start = date('Y-m-d', strtotime($CA_RCVDT));
        $end = date('Y-m-d', strtotime($now));
        $offDay = $Working_day->offDay($state_code);  // DAPATKAN HARI CUTI MINGGUAN MENGIKUT NEGERI
        $holidayDay = $Holidays->off($start, $end, $state_code); //   KIRAAN CUTI MENGIKUT NEGERI
        $repeatHoliday = $Holidays->repeatedOffday($start, $end, $state_code); //   KIRAAN CUTI BERULANG MENGIKUT NEGERI
        $Duration = $Working_day->getWorkingDay($RCVDT, $now, $offDay); // KIRAAN CUTI MINGGUAN DALAM MENGIKUT NEGERI
        $TotalDuration = $Duration - ($holidayDay + $repeatHoliday); // CUTI DALAM TEMPOH ADUAN

        return $TotalDuration;
    }

    public static function GetAduanByNegDept($CA_RCVDT_FROM, $CA_RCVDT_TO, $CA_DEPTCD, $BR_STATECD)
    {
        $SenaraiAduan = DB::table('case_info')
            ->select('CA_RCVDT', 'CA_MODDT', 'CA_CASEID')
            ->join('sys_brn', 'case_info.CA_BRNCD', '=', 'sys_brn.BR_BRNCD')
            ->whereBetween('CA_RCVDT', [date('Y-m-d', strtotime($CA_RCVDT_FROM)), date('Y-m-d', strtotime($CA_RCVDT_TO))])
            ->whereIn('CA_INVSTS', [3, 4, 5, 6, 7, 8, 9])
            ->where(['CA_DEPTCD' => $CA_DEPTCD, 'BR_STATECD' => $BR_STATECD])
            ->get();

        // UNTUK KIRA TEMPOH
//        dd($SenaraiAduan);
//        SELECT CA_RCVDT,CA_MODDT FROM pct_case INNER JOIN sys_brn ON pct_case.CA_BRNCD=sys_brn.BR_BRNCD WHERE BR_STATECD='$neg' 
//            && CA_INVSTS>'2' && DATE(CA_RCVDT) BETWEEN '$from' AND '$to'&& CA_DEPTCD like '%$bahagaian%'  ORDER BY CA_RCVDT ASC 
        return $SenaraiAduan;
    }

    public static function GetCountByNegeri($CA_RCVDT, $CA_CASEID)
    {
        $mTerima = TerimaSelesaiAduan::where(['CA_CASEID' => $CA_CASEID])->first();
        $Working_day = new Wd();
        $Holidays = new Holiday();
        $state_code = $mTerima->CA_AGAINST_STATECD;
        $now = \Carbon\Carbon::now();
        $RCVDT = Carbon::parse($CA_RCVDT);
        $start = date('Y-m-d', strtotime($CA_RCVDT));
        $end = date('Y-m-d', strtotime($now));
        $offDay = $Working_day->offDay($state_code);  // DAPATKAN HARI CUTI MINGGUAN MENGIKUT NEGERI
        $holidayDay = $Holidays->off($start, $end, $state_code); //   KIRAAN CUTI MENGIKUT NEGERI
        $repeatHoliday = $Holidays->repeatedOffday($start, $end, $state_code); //   KIRAAN CUTI BERULANG MENGIKUT NEGERI
        $Duration = $Working_day->getWorkingDay($RCVDT, $now, $offDay); // KIRAAN CUTI MINGGUAN DALAM MENGIKUT NEGERI
        $TotalDuration = $Duration - ($holidayDay + $repeatHoliday); // CUTI DALAM TEMPOH ADUAN

        return $TotalDuration;
    }

    public static function GetTempoh($start_date, $end_date, $holidays, $year, $weekend)
    { //DARI SISTEM LAMA
//        $holidays=array('01-01','01-23','02-05','04-11','05-01','05-05','06-02','08-19','08-20','08-31','09-16','10-26','11-13','11-15','12-25');
//        function get_working_days($start_date, $end_date, $holidays, $year, $weekend)
        $start_ts = strtotime($start_date);
        $end_ts = strtotime($end_date);
        foreach ($holidays as & $holiday) {
            $holiday = strtotime($year . '-' . $holiday);
        }
        $working_days = 0;
        $tmp_ts = $start_ts;
        while ($tmp_ts <= $end_ts) {
            $tmp_day = date('D', $tmp_ts);

            if ($weekend == 'A') {
                if (!($tmp_day == 'Fri') && !($tmp_day == 'Sat') && !in_array($tmp_ts, $holidays)) {
                    $working_days++;
                };
            } else
                if (!($tmp_day == 'Sun') && !($tmp_day == 'Sat') && !in_array($tmp_ts, $holidays)) {
                    $working_days++;
                }
            $tmp_ts = strtotime('+1 day', $tmp_ts);
        }
        return $working_days;
    }

    public static function GetRef($cat, $prepend)
    {
        $mRef = DB::table('sys_ref')
            ->where(['cat' => $cat, 'status' => '1'])
            ->orderBy('sort', 'asc')
            ->orderBy('descr', 'asc')
            ->pluck('descr', 'code');
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

    public static function GetRefAll($cat, $prepend)
    {
        $mRef = DB::table('sys_ref')
            ->where(['cat' => $cat])
            ->orderBy('sort', 'asc')
            ->orderBy('descr', 'asc')
            ->pluck('descr', 'code');
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

    public static function GetRefList($cat, $prepend)
    {
        $mRef = DB::table('sys_ref')
            ->where(['cat' => $cat, 'status' => '1'])
            ->whereIn('code', ['BPDN', 'BPGK', 'BPP'])
            ->orderBy('sort', 'asc')
            ->orderBy('descr', 'asc')
            ->pluck('descr', 'code');
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
}
