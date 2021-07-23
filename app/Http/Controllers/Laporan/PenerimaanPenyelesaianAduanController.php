<?php

namespace App\Http\Controllers\Laporan;

use App\Aduan\Carian;
use App\Holiday;
use App\Http\Controllers\Controller;
use App\Laporan\TerimaSelesaiAduan;
use App\Ref;
use App\User;
use App\Wd;
use Box\Spout\Common\Type;
use Box\Spout\Writer\WriterFactory;
use Carbon\Carbon;
use DB;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Input;
use JavaScript;
use Maatwebsite\Excel\Facades\Excel;
use niklasravnsborg\LaravelPdf\Facades\Pdf;
use Yajra\DataTables\Facades\DataTables;

//use Illuminate\Support\Facades\Request;

class PenerimaanPenyelesaianAduanController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function __construct()
    {
        $this->middleware('auth');
    }

    /**
     * Penerimaan & Penyelesaian Aduan - Pengagihan Aduan (Tempoh Pindah Aduan)
     * @param Request $request
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     * @todo pecahkan kepada controller dia sendiri. SOLID purpose.
     * @todo pecahkan kepada post request dan get request. SOLID purpose.
     * @todo refactor function name to pengagihanAduan. CamelCase readability purpose.
     */
    public function pengagihanaduan(Request $request)
    {
        // parameter initializer
        $mNegeriList = DB::table('sys_ref')->select('descr', 'code')->where('cat', '17')->get();

        $data = $request->all();
        $generate = isset($data['gen']) ? $data['gen'] : 'web';
        $SelectYear = isset($data['CA_RCVDT_YEAR']) ? $data['CA_RCVDT_YEAR'] : date('Y');
        $MonthFrom = isset($data['CA_RCVDT_MONTH_FROM']) ? $data['CA_RCVDT_MONTH_FROM'] : date('m');
        $MonthTo = isset($data['CA_RCVDT_MONTH_TO']) ? $data['CA_RCVDT_MONTH_TO'] : 12;
        $BR_STATECD = isset($data['BR_STATECD']) ? $data['BR_STATECD'] : [];
        $CA_DEPTCD = isset($data['CA_DEPTCD']) ? $data['CA_DEPTCD'] : '';
        $senNegeri = isset($data['BR_STATECD']) ? implode(',', $BR_STATECD) : '';
        $monthFromDesc = Ref::GetDescr('206', $MonthFrom);
        $monthToDesc = Ref::GetDescr('206', $MonthTo);
        // $departmentDesc = $CA_DEPTCD != '' ? Ref::GetDescr('315', $CA_DEPTCD) : 'Semua Bahagian';
        $departmentDesc = !empty($CA_DEPTCD) ? Ref::GetDescr('315', $CA_DEPTCD) : 'SEMUA BAHAGIAN';
        $stateList = DB::table('sys_ref')->where('cat', 17)->pluck('descr', 'code');
        $countJumlah = 0;
        $count1hari = 0;
        $countLebih1hari = 0;
        $totalAll = 0;
        $senaraiNegeri = '';
        $dataCount['total'] = [
            '<1' => 0,
            '1' => 0,
            '2-3' => 0,
            '>3' => 0,
            'total' => 0,
        ];
        $dataCategories = [];
        $dataCountFinal = [
            '<1' => [],
            '1' => [],
            '2-3' => [],
            '>3' => [],
        ];

        // create prelim data
        foreach ($BR_STATECD as $state) {
            $dataCount[$state] = [
                '<1' => 0,
                '1' => 0,
                '2-3' => 0,
                '>3' => 0,
                'total' => 0,
            ];
        }
        
        /**
         * Status Aduan
         * 0 - PINDAH KE NEGERI/BAHAGIAN/CAWANGAN LAIN
         * 4 - PENUTUPAN (RUJUK KE AGENSI KPDNKK)
         * 5 - PENUTUPAN (RUJUK KE TRIBUNAL)
         */
        $CA_INVSTS = [0 ,4 ,5];

        // get all data
        $senaraiAgihanNegeriWithDateDiff = TerimaSelesaiAduan::SenaraiAgihanWithDateDiff($SelectYear, $MonthFrom, $MonthTo, $BR_STATECD, $CA_DEPTCD, 0, $CA_INVSTS);

        // poll it into an array
        foreach ($senaraiAgihanNegeriWithDateDiff as $v) {
            switch (true) {
                case $v->date_diff < 1:
                    $dataCount[$v->BR_STATECD]['<1'] += $v->total_cases;
                    $dataCount['total']['<1'] += $v->total_cases;
                    break;
                case $v->date_diff == 1:
                    $dataCount[$v->BR_STATECD]['1'] += $v->total_cases;
                    $dataCount['total']['1'] += $v->total_cases;
                    break;
                case ($v->date_diff > 1 && $v->date_diff < 4):
                    $dataCount[$v->BR_STATECD]['2-3'] += $v->total_cases;
                    $dataCount['total']['2-3'] += $v->total_cases;
                    break;
                case $v->date_diff > 3:
                    $dataCount[$v->BR_STATECD]['>3'] += $v->total_cases;
                    $dataCount['total']['>3'] += $v->total_cases;
                    break;
                default:
                    # code...
                    break;
            }
            $dataCount[$v->BR_STATECD]['total'] += $v->total_cases;
            $dataCount['total']['total'] += $v->total_cases;
        }

        /**
         * remapping to final data array.
         * not using array_map() to reduce probability wrongly mapped value.
         */
        foreach ($dataCount as $key => $datum) {
            if ($key != 'total') {
                $namaNegeri = Ref::GetDescr('17', $key);
                $senaraiNegeri .= ($senaraiNegeri != '' ? ',' : '') . "'$namaNegeri'";
                $dataCategories[] = $namaNegeri;

                $dataCountFinal['<1'][] = $dataCount[$key]['<1'];
                $dataCountFinal['1'][] = $dataCount[$key]['1'];
                $dataCountFinal['2-3'][] = $dataCount[$key]['2-3'];
                $dataCountFinal['>3'][] = $dataCount[$key]['>3'];
            }
        }

        $data = [
            [
                "name" => '< 1 hari',
                "data" => $dataCountFinal['<1']
            ],
            [
                "name" => '1 hari',
                "data" => $dataCountFinal['1']
            ],
            [
                "name" => '2 - 3 hari',
                "data" => $dataCountFinal['2-3']
            ],
            [
                "name" => '> 3 hari',
                "data" => $dataCountFinal['>3']
            ]
        ];

        /**
         * Excel generating purpose
         * @todo change to maatwebsite/excel
         */
        $i = 1;
        if ($request->btnExcel) {
            $this->PrintExcel($SelectYear, $MonthFrom, $MonthTo, $senNegeri, $CA_DEPTCD);
        }

        switch ($generate) {
            case 'pdf':
                $pdf = PDF::loadView('laporan.penerimaanpenyelesaianaduan.pengagihan_aduan.pdf',
                    compact('mNegeriList', 'stateList', 'monthFromDesc', 'monthToDesc', 'departmentDesc', 'SenaraiAgihan', 'i', 'SelectYear', 'MonthFrom', 'MonthTo', 'Parameter', 'BR_STATECD', 'CA_DEPTCD', 'countJumlah', 'count1hari', 'countLebih1hari', 'totalAll', 'agihanNegeri', 'dataCount'));
                return $pdf->stream('Tempoh Pindah Aduan' . date("_Ymd_His") . '.pdf');
                break;
            case 'excel':
                // return Excel::create('LAPORAN PINDAH ADUAN' . date("_Ymd_His"), function ($excel) use ($mNegeriList, $stateList, $monthFromDesc, $monthToDesc, $departmentDesc, $i, $SelectYear, $MonthFrom, $MonthTo, $BR_STATECD, $CA_DEPTCD, $countJumlah, $count1hari, $countLebih1hari, $totalAll, $dataCount) {
                //     $excel->sheet('Report', function ($sheet) use ($mNegeriList, $stateList, $monthFromDesc, $monthToDesc, $departmentDesc, $i, $SelectYear, $MonthFrom, $MonthTo, $BR_STATECD, $CA_DEPTCD, $countJumlah, $count1hari, $countLebih1hari, $totalAll, $dataCount) {
                //         $sheet->loadView('laporan.penerimaanpenyelesaianaduan.pengagihan_aduan.excel')
                //             ->with([
                //                 'mNegeriList' => $mNegeriList,
                //                 'stateList' => $stateList,
                //                 'monthFromDesc' => $monthFromDesc,
                //                 'monthToDesc' => $monthToDesc,
                //                 'departmentDesc' => $departmentDesc,
                //                 'i' => $i,
                //                 'SelectYear' => $SelectYear,
                //                 'MonthFrom' => $MonthFrom,
                //                 'MonthTo' => $MonthTo,
                //                 'BR_STATECD' => $BR_STATECD,
                //                 'CA_DEPTCD' => $CA_DEPTCD,
                //                 'countJumlah' => $countJumlah,
                //                 'count1hari' => $count1hari,
                //                 'countLebih1hari' => $countLebih1hari,
                //                 'totalAll' => $totalAll,
                //                 'dataCount' => $dataCount,
                //             ]);
                //     });
                // })->export('xlsx');
                return view(
                    'laporan.penerimaanpenyelesaianaduan.pengagihan_aduan.excel', 
                    compact(
                        'mNegeriList', 'stateList', 'monthFromDesc', 'monthToDesc', 'departmentDesc', 
                        'i', 'SelectYear', 'MonthFrom', 'MonthTo', 'BR_STATECD', 'CA_DEPTCD', 
                        'countJumlah', 'count1hari', 'countLebih1hari', 'totalAll', 'dataCount'
                    )
                );
                break;
            case 'web':
            default:
                // push data to javascript by binding it to layouts.bottom
                JavaScript::put([
                    'data' => $data,
                    'categories' => $dataCategories,
                ]);

                return view('laporan.penerimaanpenyelesaianaduan.pengagihan_aduan.index',
                    compact('mNegeriList', 'stateList', 'monthFromDesc', 'monthToDesc', 'departmentDesc', 'SenaraiAgihan', 'i', 'SelectYear', 'MonthFrom', 'MonthTo', 'Parameter', 'BR_STATECD', 'CA_DEPTCD', 'countJumlah', 'count1hari', 'countLebih1hari', 'totalAll', 'agihanNegeri', 'dataCount'));
                break;
        }
    }

//    public function senaraipindah(Request $request,$SelectYear,$MonthFrom,$MonthTo,$BR_STATECD,$CA_DEPTCD)
    public function senaraipindah(Request $request, $DATEDIFF, $BR_STATECD, $CA_RCVDT_YEAR, $CA_RCVDT_MONTH_FROM, $CA_RCVDT_MONTH_TO, $CA_DEPTCD)
    {
//        $SelectYear = $request->CA_RCVDT_YEAR;
//        $MonthFrom = $request->CA_RCVDT_MONTH_FROM;
//        $MonthTo = $request->CA_RCVDT_MONTH_TO;
//        $BR_STATECD = $request->BR_STATECD;
//        $CA_DEPTCD = $request->CA_DEPTCD;
        $senarai =
//                TerimaSelesaiAduan::
            DB::table('case_info')
                ->select('BR_STATECD', 'CA_DEPTCD', 'CA_CASEID', 'CA_SUMMARY', 'CA_NAME', 'CA_AGAINSTNM', 'CA_CMPLCAT', 'CA_RCVDT')->
                join('sys_brn', 'case_info.CA_BRNCD', '=', 'sys_brn.BR_BRNCD')
                ->whereIn('CA_INVSTS', [0, 4, 5])
//                ->whereYear('CA_RCVDT', $SelectYear)
//                ->whereMonth('CA_RCVDT', '>=', $MonthFrom)
//                ->whereMonth('CA_RCVDT', '<=', $MonthTo)
//                ->where(['BR_STATECD' => $BR_STATECD]);
                ->whereYear('CA_RCVDT', $CA_RCVDT_YEAR)
                ->whereMonth('CA_RCVDT', '>=', $CA_RCVDT_MONTH_FROM)
                ->whereMonth('CA_RCVDT', '<=', $CA_RCVDT_MONTH_TO)
                ->where(['BR_STATECD' => $BR_STATECD])
                ->whereNotNull('CA_MODDT');
        if ($CA_DEPTCD != '0') {
            $senarai->where('CA_DEPTCD', $CA_DEPTCD);
        }
        if ($DATEDIFF == 0) {
            $senarai->whereRaw("DATEDIFF(case_info.CA_MODDT,case_info.CA_RCVDT) < 1");
        }
        if ($DATEDIFF == 1) {
            $senarai->whereRaw("DATEDIFF(case_info.CA_MODDT,case_info.CA_RCVDT) = 1");
        }
        if ($DATEDIFF == 23) {
            $senarai->whereRaw("DATEDIFF(case_info.CA_MODDT,case_info.CA_RCVDT) > 1 AND DATEDIFF(case_info.CA_MODDT,case_info.CA_RCVDT) > 4");
        }
        if ($DATEDIFF == 3) {
            $senarai->whereRaw("DATEDIFF(case_info.CA_MODDT,case_info.CA_RCVDT) > 3");
        }
//        if ($request->DATEDIFF == 0) {
//            $senarai->whereRaw("DATEDIFF(case_info.CA_MODDT,case_info.CA_RCVDT) < 1");
//        }
//        if ($request->DATEDIFF == 1) {
//            $senarai->whereRaw("DATEDIFF(case_info.CA_MODDT,case_info.CA_RCVDT) = 1");
//        }
//        if ($request->DATEDIFF == 23) {
//            $senarai->whereRaw("DATEDIFF(case_info.CA_MODDT,case_info.CA_RCVDT) > 1 AND DATEDIFF(case_info.CA_MODDT,case_info.CA_RCVDT) > 4");
//        }
//        if ($request->DATEDIFF == 3) {
//            $senarai->whereRaw("DATEDIFF(case_info.CA_MODDT,case_info.CA_RCVDT) > 3");
//        }
        $i = 1;
        $lists = $senarai->get();

        if ($request->get('excel') == '1') {
//             dd('berjaya');
            return view('laporan.penerimaanpenyelesaianaduan.senaraipindah_excel',
//                  compact('senarai', 'i', 'SelectYear', 'MonthFrom', 'MonthTo', 'BR_STATECD', 'CA_DEPTCD', 'lists','request'));
                compact('senarai', 'i', 'DATEDIFF', 'CA_RCVDT_YEAR', 'CA_RCVDT_MONTH_FROM', 'CA_RCVDT_MONTH_TO', 'BR_STATECD', 'CA_DEPTCD', 'lists', 'request'));
        } elseif ($request->get('pdf') == '1') {
            $pdf = PDF::loadView('laporan.penerimaanpenyelesaianaduan.senaraipindah_pdf', compact('senarai', 'i', 'DATEDIFF', 'CA_RCVDT_YEAR', 'CA_RCVDT_MONTH_FROM', 'CA_RCVDT_MONTH_TO', 'BR_STATECD', 'CA_DEPTCD', 'lists', 'request'), [], ['default_font_size' => 7, 'title' => date('Ymd_His')]);
            return $pdf->stream('penerimaanpenyelesaianaduan' . date("Ymd_His") . '.pdf');
        } else {
            return view('laporan.penerimaanpenyelesaianaduan.senaraipindah',
//            compact('senarai', 'i', 'SelectYear', 'MonthFrom', 'MonthTo', 'BR_STATECD', 'CA_DEPTCD', 'lists','request'));
                compact('senarai', 'i', 'DATEDIFF', 'CA_RCVDT_YEAR', 'CA_RCVDT_MONTH_FROM', 'CA_RCVDT_MONTH_TO', 'BR_STATECD', 'CA_DEPTCD', 'lists', 'request'));
        }
    }

    /**
     * @deprecated
     * Report to calculate all closed tickets.
     *
     * @return \Illuminate\Http\Response
     */
    public function selesaiaduannegeritahun(Request $request)
    {
        $CA_RCVDT_FROM = Carbon::now();
        $CA_RCVDT_TO = Carbon::now();
        $CA_DEPTCD = '';
        $BR_STATECD = [];
        $count = 1;

        if ($request->CA_RCVDT_FROM) {
            $CA_RCVDT_FROM = $request->CA_RCVDT_FROM;
        }
        if ($request->CA_RCVDT_TO) {
            $CA_RCVDT_TO = $request->CA_RCVDT_TO;
        }
        if ($request->BR_STATECD) {
            $BR_STATECD = $request->BR_STATECD;
        }
        if ($request->CA_DEPTCD) {
            $CA_DEPTCD = $request->CA_DEPTCD;
        }
        $mNegeriList = DB::table('sys_ref')
            ->select('descr', 'code')
            ->where('cat', '17')
            ->get();
//        $SenaraiSelesai = TerimaSelesaiAduan::SenaraiAduanSelesai($CA_RCVDT_FROM, $CA_RCVDT_TO, $BR_STATECD, $CA_DEPTCD); // JUMLAH ADUAN/NEGERI
        $SenaraiTempohAduan = TerimaSelesaiAduan::SenaraiTempohAduan($CA_RCVDT_FROM, $CA_RCVDT_TO, $CA_DEPTCD);
        return view('laporan.penerimaanpenyelesaianaduan.selesaiaduannegeritahun',
            compact('mNegeriList', 'CA_RCVDT_FROM', 'CA_RCVDT_TO', 'CA_DEPTCD', 'count', 'SenaraiSelesai',
                'SenaraiTempohAduan', 'day1', 'day25', 'day610', 'day1115', 'day1620', 'day2130', 'day3160', 'day60'));
    }

    public function selesaiAduanTahunan(Request $request)
    {
        $mNegeriList = DB::table('sys_ref')->select('descr', 'code')->where('cat', '17')->get();
        $data = $request->all();
        $CA_RCVDT_FROM = isset($data['CA_RCVDT_FROM']) ? Carbon::createFromFormat('d-m-Y', $data['CA_RCVDT_FROM'])->startOfDay() : date('d-m-Y', strtotime(Carbon::now()->startOfDay()));
        $CA_RCVDT_TO = isset($data['CA_RCVDT_TO']) ? Carbon::createFromFormat('d-m-Y', $data['CA_RCVDT_TO'])->endOfDay() : date('d-m-Y', strtotime(Carbon::now()->endOfDay()));
        $BR_STATECD = isset($data['BR_STATECD']) ? $data['BR_STATECD'] : [];
        $CA_DEPTCD = isset($data['CA_DEPTCD']) ? $data['CA_DEPTCD'] : '';
        $senaraiNegeri = '';
        $i = 1;
        $data_template = ['<1' => 0, '1' => 0, '2-5' => 0, '6-10' => 0, '11-15' => 0, '16-21' => 0, '22-31' => 0, '32-60' => 0, '>60' => 0, 'total' => 0,];
        $dataCount['total'] = $data_template;
        $dataCategories = [];
        $dataCountFinal = ['<1' => [], '1' => [], '2-5' => [], '6-10' => [], '11-15' => [], '16-21' => [], '22-31' => [], '32-60' => [], '>60' => [],];
        if (count($BR_STATECD) == 0 && isset($data['CA_RCVDT_TO']) && $request->get('excel') != 1 && $request->get('pdf') != 1) {
            $BR_STATECD = Ref::where('cat', '17')->get()->pluck('code', 'id')->toArray();
        }
        foreach ($BR_STATECD as $state) {
            $dataCount[$state] = $data_template;
        }

        $SenaraiSelesaiWithDateDiff = TerimaSelesaiAduan::SenaraiSelesaiWithDateDiff($CA_RCVDT_FROM, $CA_RCVDT_TO, $BR_STATECD, $CA_DEPTCD, 1);
        // poll it into an array
        foreach ($SenaraiSelesaiWithDateDiff as $v) {
            switch (true) {
                case $v->CA_PRECLOSE_DURATION <= 1:
                    $dataCount[$v->BR_STATECD]['1'] += $v->total_cases;
                    $dataCount['total']['1'] += $v->total_cases;
                    break;
                case ($v->CA_PRECLOSE_DURATION > 1 && $v->CA_PRECLOSE_DURATION < 6):
                    $dataCount[$v->BR_STATECD]['2-5'] += $v->total_cases;
                    $dataCount['total']['2-5'] += $v->total_cases;
                    break;
                case ($v->CA_PRECLOSE_DURATION > 5 && $v->CA_PRECLOSE_DURATION < 11):
                    $dataCount[$v->BR_STATECD]['6-10'] += $v->total_cases;
                    $dataCount['total']['6-10'] += $v->total_cases;
                    break;
                case ($v->CA_PRECLOSE_DURATION > 10 && $v->CA_PRECLOSE_DURATION< 16):
                    $dataCount[$v->BR_STATECD]['11-15'] += $v->total_cases;
                    $dataCount['total']['11-15'] += $v->total_cases;
                    break;
                case ($v->CA_PRECLOSE_DURATION > 15 && $v->CA_PRECLOSE_DURATION < 22):
                    $dataCount[$v->BR_STATECD]['16-21'] += $v->total_cases;
                    $dataCount['total']['16-21'] += $v->total_cases;
                    break;
                case ($v->CA_PRECLOSE_DURATION > 21 && $v->CA_PRECLOSE_DURATION < 32):
                    $dataCount[$v->BR_STATECD]['22-31'] += $v->total_cases;
                    $dataCount['total']['22-31'] += $v->total_cases;
                    break;
                case ($v->CA_PRECLOSE_DURATION > 31 && $v->CA_PRECLOSE_DURATION < 61):
                    $dataCount[$v->BR_STATECD]['32-60'] += $v->total_cases;
                    $dataCount['total']['32-60'] += $v->total_cases;
                    break;
                case $v->CA_PRECLOSE_DURATION > 60:
                    $dataCount[$v->BR_STATECD]['>60'] += $v->total_cases;
                    $dataCount['total']['>60'] += $v->total_cases;
                    break;
                default:
                    # code...
                    break;
            }
            $dataCount[$v->BR_STATECD]['total'] += $v->total_cases;
            $dataCount['total']['total'] += $v->total_cases;
        }

        foreach ($dataCount as $key => $datum) {
            if ($key != 'total') {
                $namaNegeri = Ref::GetDescr('17', $key);
                $senaraiNegeri .= ($senaraiNegeri != '' ? ',' : '') . "'$namaNegeri'";
                $dataCategories[] = $namaNegeri;
                $dataCountFinal['1'][] = $dataCount[$key]['1'];
                $dataCountFinal['2-5'][] = $dataCount[$key]['2-5'];
                $dataCountFinal['6-10'][] = $dataCount[$key]['6-10'];
                $dataCountFinal['11-15'][] = $dataCount[$key]['11-15'];
                $dataCountFinal['16-21'][] = $dataCount[$key]['16-21'];
                $dataCountFinal['22-31'][] = $dataCount[$key]['22-31'];
                $dataCountFinal['32-60'][] = $dataCount[$key]['32-60'];
                $dataCountFinal['>60'][] = $dataCount[$key]['>60'];
            }
        }
        if ($request->get('excel') == '1') {
            return view('laporan.penerimaanpenyelesaianaduan.selesaiaduannegeritahun1_excel',
                compact('state', 'mNegeriList', 'i', 'CA_RCVDT_FROM', 'CA_RCVDT_TO', 'BR_STATECD', 'CA_DEPTCD', 'Parameter', 'dataCount'));
        } elseif ($request->get('pdf') == '1') {
            $pdf = PDF::loadView('laporan.penerimaanpenyelesaianaduan.selesaiaduannegeritahun_pdf',
                compact('senarai', 'i', 'CA_RCVDT_FROM', 'CA_RCVDT_TO', 'BR_STATECD', 'CA_DEPTCD', 'lists', 'request', 'dataCount'), [], ['default_font_size' => 7, 'title' => date('Ymd_His')]);
            return $pdf->stream('bpa' . date("Ymd_His") . '.pdf');
        } else {
            return view('laporan.penerimaanpenyelesaianaduan.selesaiaduannegeritahun1',
                compact('state', 'mNegeriList', 'i', 'CA_RCVDT_FROM', 'CA_RCVDT_TO', 'BR_STATECD', 'CA_DEPTCD', 'Parameter', 'dataCount'));
        }
    }

    public function senaraiSelesai(Request $request, $DATEDIFF, $BR_STATECD, $CA_RCVDT_FROM, $CA_RCVDT_TO, $CA_DEPTCD)
    {
        $senarai = DB::table('case_info')
            ->select('BR_STATECD', 'CA_DEPTCD', 'CA_CASEID', 'CA_SUMMARY', 'CA_NAME', 'CA_AGAINSTNM', 'CA_CMPLCAT', 'CA_RCVDT', 'CA_PRECLOSE_DURATION', 'sys_brn.BR_BRNNM')
            ->join('sys_brn', 'case_info.CA_BRNCD', '=', 'sys_brn.BR_BRNCD')
            ->whereIn('CA_INVSTS', [3, 4, 5, 6, 8, 9, 11, 12])
            ->whereBetween('CA_RCVDT', [$CA_RCVDT_FROM, $CA_RCVDT_TO])
            ->where(['BR_STATECD' => $BR_STATECD]);

        if ($CA_DEPTCD != '0') {
            $senarai = $senarai->where('CA_DEPTCD', $CA_DEPTCD);
        }

        if ($DATEDIFF == 0) {
            $senarai->whereRaw("CA_PRECLOSE_DURATION < 1");
        }
        if ($DATEDIFF == 1) {
            $senarai->whereRaw("CA_PRECLOSE_DURATION <= 1");
        }
        if ($DATEDIFF == 25) {
            $senarai->whereRaw("CA_PRECLOSE_DURATION > 1 AND CA_PRECLOSE_DURATION < 6");
        }
        if ($DATEDIFF == 610) {
            $senarai->whereRaw("CA_PRECLOSE_DURATION > 5 AND CA_PRECLOSE_DURATION < 11");
        }
        if ($DATEDIFF == 1115) {
            $senarai->whereRaw("CA_PRECLOSE_DURATION > 10 AND CA_PRECLOSE_DURATION < 16");
        }
        if ($DATEDIFF == 1621) {
            $senarai->whereRaw("CA_PRECLOSE_DURATION > 15 AND CA_PRECLOSE_DURATION < 22");
        }
        if ($DATEDIFF == 2231) {
            $senarai->whereRaw("CA_PRECLOSE_DURATION > 21 AND CA_PRECLOSE_DURATION < 32");
        }
        if ($DATEDIFF == 3260) {
            $senarai->whereRaw("CA_PRECLOSE_DURATION > 31 AND CA_PRECLOSE_DURATION < 61");
        }
        if ($DATEDIFF == 60) {
            $senarai->whereRaw("CA_PRECLOSE_DURATION > 60");
        }

        $i = 1;
        $lists = $senarai->get();

        if ($request->get('excel') == '1') {
            return view('laporan.penerimaanpenyelesaianaduan.senaraiselesai_excel',
                compact('senarai', 'i', 'DATEDIFF', 'CA_RCVDT_FROM', 'CA_RCVDT_TO', 'BR_STATECD', 'CA_DEPTCD', 'lists', 'request'));
        } elseif ($request->get('pdf') == '1') {
            $pdf = PDF::loadView('laporan.penerimaanpenyelesaianaduan.senaraiselesai_pdf', compact('senarai', 'i', 'CA_RCVDT_FROM', 'CA_RCVDT_TO', 'BR_STATECD', 'CA_DEPTCD', 'lists', 'request'), [], ['default_font_size' => 7, 'title' => date('Ymd_His')]);
            return $pdf->stream('bpa' . date("Ymd_His") . '.pdf');
        } else {
            return view('laporan.penerimaanpenyelesaianaduan.senaraiselesai',
//                compact('senarai', 'i', 'SelectYear', 'MonthFrom', 'MonthTo', 'BR_STATECD', 'CA_DEPTCD','lists'));
                compact('senarai', 'i', 'CA_RCVDT_FROM', 'CA_RCVDT_TO', 'BR_STATECD', 'CA_DEPTCD', 'lists', 'request'));
        }
    }

    public function senaraiSelesaiMengikutTempoh(Request $request, $DATEDIFF, $CA_RCVDT_FROM, $CA_RCVDT_TO, $CA_DEPTCD)
    {
//        $CA_RCVDT_FROM = $request->CA_RCVDT_FROM;
//        $CA_RCVDT_TO = $request->CA_RCVDT_TO;
//        $BR_STATECD = $request->BR_STATECD;
//        $CA_DEPTCD = $request->CA_DEPTCD;
        $senarai = DB::table('case_info')
            ->select('BR_STATECD', 'CA_DEPTCD', 'CA_CASEID', 'CA_SUMMARY', 'CA_NAME', 'CA_AGAINSTNM', 'CA_CMPLCAT', 'CA_RCVDT', 'sys_brn.BR_BRNNM')
            ->join('sys_brn', 'case_info.CA_BRNCD', '=', 'sys_brn.BR_BRNCD')
            ->whereIn('CA_INVSTS', [3, 4, 5, 6, 8, 9, 11, 12])
//            ->whereBetween('CA_RCVDT', [date('Y-m-d', strtotime($CA_RCVDT_FROM)), date('Y-m-d', strtotime($CA_RCVDT_TO))])
            ->whereBetween('CA_RCVDT', [$CA_RCVDT_FROM, $CA_RCVDT_TO]);
//            ->whereNotNull('CA_COMPLETEDT');
        if ($CA_DEPTCD != '0') {
            $senarai = $senarai->where('CA_DEPTCD', $CA_DEPTCD);
        }
        if ($DATEDIFF == 0) {
            $senarai->whereRaw("DATEDIFF(case_info.CA_COMPLETEDT,case_info.CA_RCVDT) < 1");
        }
        if ($DATEDIFF == 1) {
            $senarai->whereRaw("(DATEDIFF(case_info.CA_COMPLETEDT,case_info.CA_RCVDT) <= 1 OR DATEDIFF(case_info.CA_COMPLETEDT,case_info.CA_RCVDT) IS NULL)");
        }
        if ($DATEDIFF == 25) {
            $senarai->whereRaw("DATEDIFF(case_info.CA_COMPLETEDT,case_info.CA_RCVDT) > 1 AND DATEDIFF(case_info.CA_COMPLETEDT,case_info.CA_RCVDT) < 6");
        }
        if ($DATEDIFF == 610) {
            $senarai->whereRaw("DATEDIFF(case_info.CA_COMPLETEDT,case_info.CA_RCVDT) > 5 AND DATEDIFF(case_info.CA_COMPLETEDT,case_info.CA_RCVDT) < 11");
        }
        if ($DATEDIFF == 1115) {
            $senarai->whereRaw("DATEDIFF(case_info.CA_COMPLETEDT,case_info.CA_RCVDT) > 10 AND DATEDIFF(case_info.CA_COMPLETEDT,case_info.CA_RCVDT) < 16");
        }
        if ($DATEDIFF == 1621) {
            $senarai->whereRaw("DATEDIFF(case_info.CA_COMPLETEDT,case_info.CA_RCVDT) > 15 AND DATEDIFF(case_info.CA_COMPLETEDT,case_info.CA_RCVDT) < 22");
        }
        if ($DATEDIFF == 2231) {
            $senarai->whereRaw("DATEDIFF(case_info.CA_COMPLETEDT,case_info.CA_RCVDT) > 21 AND DATEDIFF(case_info.CA_COMPLETEDT,case_info.CA_RCVDT) < 32");
        }
        if ($DATEDIFF == 3260) {
            $senarai->whereRaw("DATEDIFF(case_info.CA_COMPLETEDT,case_info.CA_RCVDT) > 31 AND DATEDIFF(case_info.CA_COMPLETEDT,case_info.CA_RCVDT) < 61");
        }
        if ($DATEDIFF == 60) {
            $senarai->whereRaw("DATEDIFF(case_info.CA_COMPLETEDT,case_info.CA_RCVDT) > 60");
        }
        $i = 1;
        $lists = $senarai->get();
        if ($request->get('excel') == '1') {
            return view('laporan.penerimaanpenyelesaianaduan.senaraiselesaiikuttempoh_excel',
                compact('senarai', 'i', 'CA_RCVDT_FROM', 'CA_RCVDT_TO', 'CA_DEPTCD', 'lists'));
        } elseif ($request->get('pdf') == '1') {
            $pdf = PDF::loadView('laporan.penerimaanpenyelesaianaduan.senaraiselesaiikuttempoh_pdf',
                compact('senarai', 'i', 'CA_RCVDT_FROM', 'CA_RCVDT_TO', 'CA_DEPTCD', 'lists'), [], ['default_font_size' => 7, 'title' => date('Ymd_His')]);
            return $pdf->stream('bpa' . date("Ymd_His") . '.pdf');
        } else {
            return view('laporan.penerimaanpenyelesaianaduan.senaraiselesaiikuttempoh',
                compact('senarai', 'i', 'CA_RCVDT_FROM', 'CA_RCVDT_TO', 'CA_DEPTCD', 'lists'));
        }
    }

    public function senaraiSelesaiMengikutNegeri(Request $request, $BR_STATECD, $CA_RCVDT_FROM, $CA_RCVDT_TO, $CA_DEPTCD)
    {
//        $CA_RCVDT_FROM = $request->CA_RCVDT_FROM;
//        $CA_RCVDT_TO = $request->CA_RCVDT_TO;
//        $BR_STATECD = $request->BR_STATECD;
//        $CA_DEPTCD = $request->CA_DEPTCD;
        $senarai = DB::table('case_info')
            ->select('BR_STATECD', 'CA_DEPTCD', 'CA_CASEID', 'CA_SUMMARY', 'CA_NAME', 'CA_AGAINSTNM', 'CA_CMPLCAT', 'CA_RCVDT')
            ->join('sys_brn', 'case_info.CA_BRNCD', '=', 'sys_brn.BR_BRNCD')
            ->whereIn('CA_INVSTS', [3, 9])
            ->whereBetween('CA_RCVDT', [date('Y-m-d', strtotime($CA_RCVDT_FROM)), date('Y-m-d', strtotime($CA_RCVDT_TO))])
            ->where(['BR_STATECD' => $BR_STATECD]);
        if ($CA_DEPTCD != '0') {
            $senarai = $senarai->where('CA_DEPTCD', $CA_DEPTCD);
        }
        $i = 1;
        $lists = $senarai->get();
        if ($request->get('excel') == '1') {
            return view('laporan.penerimaanpenyelesaianaduan.senaraiselesaiikutnegeri_excel',
                compact('senarai', 'i', 'CA_RCVDT_FROM', 'CA_RCVDT_TO', 'BR_STATECD', 'CA_DEPTCD', 'lists', 'request'));
        } elseif ($request->get('pdf') == '1') {
            $pdf = PDF::loadView('laporan.penerimaanpenyelesaianaduan.senaraiselesaiikutnegeri_pdf',
                compact('senarai', 'i', 'CA_RCVDT_FROM', 'CA_RCVDT_TO', 'BR_STATECD', 'CA_DEPTCD', 'lists', 'request'), [], ['default_font_size' => 7, 'title' => date('Ymd_His')]);
            return $pdf->stream('bpa' . date("Ymd_His") . '.pdf');
        } else {
            return view('laporan.penerimaanpenyelesaianaduan.senaraiselesaiikutnegeri',
                compact('senarai', 'i', 'CA_RCVDT_FROM', 'CA_RCVDT_TO', 'BR_STATECD', 'CA_DEPTCD', 'lists', 'request'));
        }
    }

    /**
     * Laporan Mengikut Status dan Kategori - Penerimaan / Penyelesaian Aduan
     * @param Request $request
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function kategoriBpgk(Request $request)
    {
        $data = $request->all();
//        dump($data);
        $search = count($data) > 0 ? true : false;

        // initialize data
        $user = Auth::user();
        $generate = isset($data['gen']) ? $data['gen'] : 'web';
        $dateStart = isset($data['dateStart']) ? Carbon::createFromFormat('d-m-Y', $data['dateStart'])->startOfDay() : Carbon::now()->startOfDay();
        $dateEnd = isset($data['dateEnd']) ? Carbon::createFromFormat('d-m-Y', $data['dateEnd'])->endOfDay() : Carbon::now()->endOfDay();
        $state = isset($data['state']) ? $data['state'] : '';
        $branch = isset($data['branch']) ? $data['branch'] : [];
        $brancharray = isset($data['branch']) ? $data['branch'] : [];
        $branchUri = '';
        $branchUri2 = '';
        $subdepartmentUri = '';
        $subdepartmentUri2 = '';
        $department = isset($data['department']) ? $data['department'] : '';
        $subdepartment = isset($data['subdepartment']) ? $data['subdepartment'] : [];
        $subdepartmentarray = isset($data['subdepartment']) ? $data['subdepartment'] : [];
//        $departmentDesc = $department != null ? Ref::GetDescr('315', $department) : '';
        $departmentDesc = !empty($department) ? Ref::GetDescr('315', $department) : 'SEMUA BAHAGIAN';
//        $stateDesc = $state != null ? Ref::GetDescr('17', $state) : '';
        $stateDesc = !empty($state) ? Ref::GetDescr('17', $state) : 'SEMUA NEGERI';
        foreach ($branch as $datum) {
            $branchUri .= 'branch%5B%5D=' . $datum . '&';
            $branchUri2 .= 'br%5B%5D=' . $datum . '&';
        }
        foreach ($subdepartment as $datum) {
            $subdepartmentUri .= 'subdepartment%5B%5D=' . $datum . '&';
            $subdepartmentUri2 .= 'sd%5B%5D=' . $datum . '&';
        }
        if (empty($state)) {
            $branchSelectList = DB::table('sys_brn')
                ->select('BR_BRNCD', 'BR_BRNNM')
//                ->where('BR_STATECD', '=', $state)
                ->where('BR_STATUS', '1')
//                ->orderBy('BR_BRNNM')
                ->get();
        } else if (!empty($state)) {
            $branchSelectList = DB::table('sys_brn')
                ->select('BR_BRNCD', 'BR_BRNNM')
                ->where('BR_STATECD', '=', $state)
                ->where('BR_STATUS', '1')
//                ->orderBy('BR_BRNNM')
                ->get();
        }
        $dataTemplate = [
            'selesai' => 0,
            'belum agih' => 0,
            'dalam siasatan' => 0,
            'belum selesai' => 0,
            'tutup' => 0,
            'agensi lain' => 0,
            'tribunal' => 0,
            'pertanyaan' => 0,
            'maklumat tak lengkap' => 0,
            'luar bidang' => 0,
            'total' => 0,
        ];
        $dataFinal = [];
        $dataCounter = $dataTemplate;

        // reference data
        $statuses = Ref::where('cat', 292)
            ->where('status', 1)
            ->whereBetween('code', [3, 9])->get();
        $qsubdepartment = Ref::where('cat', 244);

        if ($department == '0') {
            if (count($subdepartment) > 0) {
                $qsubdepartment = $qsubdepartment->whereIn('code', $subdepartment);
            } else {
                $qsubdepartment = $qsubdepartment->whereNotNull('status');
            }
        } else {
            $qsubdepartment = $qsubdepartment->whereIn('code', $subdepartment);
        }

        $subdepartmentList = $qsubdepartment->pluck('descr', 'code');
        $refCategory = DB::table('sys_ref')
            ->where('cat', '244')
//            ->where('status', '1')
            ->get();
        if ($search && (($department != '0' && count($subdepartment) > 0) || ($department == '0'))) {
            // query data
            $q = DB::table('case_info')
                ->select(DB::raw('case_info.CA_CMPLCAT AS CA_CMPLCAT, '
                    . 'SUM(CASE WHEN CA_INVSTS = 3 THEN 1 ELSE 0 END) AS SELESAI,'
                    . 'SUM(CASE WHEN CA_CASESTS = 1 THEN 1 ELSE 0 END) AS BELUMAGIH, '
                    . 'SUM(CASE WHEN CA_CASESTS = 2 AND CA_INVSTS = 2 THEN 1 ELSE 0 END) AS DALAMSIASATAN, '
                    . 'SUM(CASE WHEN CA_CASESTS = 2 AND CA_INVSTS = 9 THEN 1 ELSE 0 END) AS TUTUP, '
                    . 'SUM(CASE WHEN CA_CASESTS = 2 AND CA_INVSTS = 4 THEN 1 ELSE 0 END) AS AGENSILAIN, '
                    . 'SUM(CASE WHEN CA_CASESTS = 2 AND CA_INVSTS = 5 THEN 1 ELSE 0 END) AS TRIBUNAL, '
                    . 'SUM(CASE WHEN CA_CASESTS = 2 AND CA_INVSTS = 6 THEN 1 ELSE 0 END) AS PERTANYAAN, '
                    . 'SUM(CASE WHEN CA_CASESTS = 2 AND CA_INVSTS = 7 THEN 1 ELSE 0 END) AS MKLUMATXLENGKAP, '
                    . 'SUM(CASE WHEN CA_CASESTS = 2 AND CA_INVSTS = 8 THEN 1 ELSE 0 END) AS LUARBIDANG, '
                    . 'SUM(CASE WHEN CA_CASESTS = 2 AND CA_INVSTS = 12 THEN 1 ELSE 0 END) AS SELESAIMAKLUMATXLENGKAP, '
                    . 'SUM(CASE WHEN CA_CASESTS = 2 AND CA_INVSTS = 11 THEN 1 ELSE 0 END) AS TUTUPMAKLUMATXLENGKAP, '
                    . 'COUNT(CA_CASEID)AS COUNT_CA_CASEID', 'CA_BRNCD'))
                ->join('sys_brn', 'case_info.CA_BRNCD', '=', 'sys_brn.BR_BRNCD')
                ->whereBetween('CA_RCVDT', [$dateStart, $dateEnd])
                ->whereRaw('CA_CMPLCAT != ""')
                ->whereNotNull('CA_CASEID')
//                ->whereNotIn('CA_INVSTS', [10]);
                ->where('CA_INVSTS', '!=', 10);

            // iff negeri != null then check array else get all negeri
            if ($state != '0' && count($branch) > 0) {
                $q = $q->whereIn('CA_BRNCD', $branch);
            }

            // iff department != null then check array else get all subdepartment
//            if ($department != '0' && count($subdepartment) > 0) {
            if (count($subdepartment) > 0) {
                $q = $q->whereIn('CA_CMPLCAT', $subdepartment);
            }

            $dataRaw = $q->groupBy('CA_CMPLCAT')->get();

            foreach ($subdepartmentList as $key => $datum) {
                $dataFinal[$key] = $dataTemplate;
            }

            // populate data to dataFinal & dataCounter
            foreach ($dataRaw as $datum) {
//                $dataFinal[$datum->CA_CMPLCAT] = $dataTemplate;
                $dataFinal[$datum->CA_CMPLCAT] = [
//                    'selesai' => $datum->SELESAI,
                    'selesai' => $datum->SELESAI + $datum->SELESAIMAKLUMATXLENGKAP,
                    'belum agih' => $datum->BELUMAGIH,
                    'dalam siasatan' => $datum->DALAMSIASATAN,
                    'belum selesai' => $datum->DALAMSIASATAN + $datum->BELUMAGIH,
//                    'tutup' => $datum->TUTUP,
                    'tutup' => $datum->TUTUP + $datum->TUTUPMAKLUMATXLENGKAP,
                    'agensi lain' => $datum->AGENSILAIN,
                    'tribunal' => $datum->TRIBUNAL,
                    'pertanyaan' => $datum->PERTANYAAN,
                    'maklumat tak lengkap' => $datum->MKLUMATXLENGKAP,
                    'luar bidang' => $datum->LUARBIDANG,
                    'total' => $datum->SELESAI + $datum->SELESAIMAKLUMATXLENGKAP + $datum->BELUMAGIH + $datum->DALAMSIASATAN + $datum->TUTUP + $datum->TUTUPMAKLUMATXLENGKAP + $datum->AGENSILAIN + $datum->TRIBUNAL + $datum->PERTANYAAN + $datum->LUARBIDANG + $datum->MKLUMATXLENGKAP
                ];
//                $dataCounter['selesai'] += $datum->SELESAI;
                $dataCounter['selesai'] += $datum->SELESAI + $datum->SELESAIMAKLUMATXLENGKAP;
                $dataCounter['belum agih'] += $datum->BELUMAGIH;
                $dataCounter['dalam siasatan'] += $datum->DALAMSIASATAN;
                $dataCounter['belum selesai'] += $datum->DALAMSIASATAN + $datum->BELUMAGIH;
//                $dataCounter['tutup'] += $datum->TUTUP;
                $dataCounter['tutup'] += $datum->TUTUP + $datum->TUTUPMAKLUMATXLENGKAP;
                $dataCounter['agensi lain'] += $datum->AGENSILAIN;
                $dataCounter['tribunal'] += $datum->TRIBUNAL;
                $dataCounter['pertanyaan'] += $datum->PERTANYAAN;
                $dataCounter['maklumat tak lengkap'] += $datum->MKLUMATXLENGKAP;
                $dataCounter['luar bidang'] += $datum->LUARBIDANG;
                $dataCounter['total'] += $datum->SELESAI + $datum->SELESAIMAKLUMATXLENGKAP + $datum->BELUMAGIH + $datum->DALAMSIASATAN + $datum->TUTUP + $datum->TUTUPMAKLUMATXLENGKAP + $datum->AGENSILAIN + $datum->TRIBUNAL + $datum->PERTANYAAN + $datum->LUARBIDANG + $datum->MKLUMATXLENGKAP;
            }
        }

        switch ($generate) {
            case 'pdf':
                $pdf = @PDF::loadView('laporan.penerimaanpenyelesaianaduan.kategori_bpgk.pdf',
                    compact('dateStart', 'dateEnd', 'departmentDesc', 'stateDesc', 'subdepartmentList', 'statuses', 'user', 'branchSelectList', 'search', 'department', 'state', 'dataFinal', 'dataCounter'), [], [
                        'format' => 'A4-L'
                    ]);
                return $pdf->stream('Laporan_Pegawai' . date("_Ymd_His") . '.pdf');
                break;
            case 'excel':
                // return Excel::create('Laporan Mengikut Status Dan Kategori Aduan' . date("_Ymd_His"), function ($excel) use ($dateStart, $dateEnd, $departmentDesc, $stateDesc, $subdepartmentList, $statuses, $user, $branchSelectList, $search, $department, $state, $dataFinal, $dataCounter) {
                //     $excel->sheet('Report', function ($sheet) use ($dateStart, $dateEnd, $departmentDesc, $stateDesc, $subdepartmentList, $statuses, $user, $branchSelectList, $search, $department, $state, $dataFinal, $dataCounter) {
                //         $sheet->loadView('laporan.penerimaanpenyelesaianaduan.kategori_bpgk.excel')
                //             ->with([
                //                 'dateStart' => $dateStart,
                //                 'dateEnd' => $dateEnd,
                //                 'departmentDesc' => $departmentDesc,
                //                 'stateDesc' => $stateDesc,
                //                 'subdepartmentList' => $subdepartmentList,
                //                 'statuses' => $statuses,
                //                 'user' => $user,
                //                 'branchSelectList' => $branchSelectList,
                //                 'search' => $search,
                //                 'department' => $department,
                //                 'state' => $state,
                //                 'dataFinal' => $dataFinal,
                //                 'dataCounter' => $dataCounter
                //             ]);
                //     });
                // })->export('xlsx');
                return view(
                    'laporan.penerimaanpenyelesaianaduan.kategori_bpgk.excel', 
                    compact(
                        'dateStart', 'dateEnd', 'departmentDesc', 'stateDesc', 'subdepartmentList', 
                        'statuses', 'user', 'branchSelectList', 'search', 'department', 
                        'state', 'dataFinal', 'dataCounter'
                    )
                );
                break;
            case 'web':
            default:
                // prepare uri to drill down report
                $dd_uri = "ds=" . $dateStart->toDateString() . "&st=" . $state . "&de=" . $dateEnd->toDateString() . "&" . $branchUri2 . "&dp=" . (($department != 0 || $department != '') ? $department : '') . "&ge=w";
                // push data to javascript by binding it to layouts.bottom
                JavaScript::put([
                    'dataCounter' => [
                        $dataCounter['belum agih'],
                        $dataCounter['dalam siasatan'],
                        $dataCounter['maklumat tak lengkap'],
                        $dataCounter['selesai'],
                        $dataCounter['tutup'],
                        $dataCounter['agensi lain'],
                        $dataCounter['tribunal'],
                        $dataCounter['pertanyaan'],
                        $dataCounter['luar bidang'],
                    ]
                ]);

                return view('laporan.penerimaanpenyelesaianaduan.kategori_bpgk.index',
                    compact('dateStart', 'dateEnd', 'subdepartmentList', 'statuses', 'user', 'branchSelectList', 'search', 'department', 'state', 'dataFinal', 'dataCounter', 'branchUri', 'subdepartmentUri', 'dd_uri', 'brancharray', 'subdepartmentarray', 'refCategory'));
                break;
        }
    }

    /**
     * Drill Down Laporan Mengikut Status dan Kategori - Penerimaan / Penyelesaian Aduan
     * @param Request $request
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function ddKategoriBpgk(Request $request)
    {
//        dd($request->all());
        $data = $request->all();
        $is_search = count($data) > 0 ? true : false;

        // initialize data
        $generate = isset($data['ge']) ? $data['ge'] : 'w';
        $date_start = isset($data['ds']) ? Carbon::parse($data['ds'])->startOfDay() : Carbon::now()->startOfDay();
        $date_end = isset($data['de']) ? Carbon::parse($data['de'])->endOfDay() : Carbon::now()->endOfDay();
        $state = isset($data['st']) ? $data['st'] : '';
        $branch = isset($data['br']) ? $data['br'] : [];
        $department = isset($data['dp']) ? $data['dp'] : '';
        $subdepartment = isset($data['sd']) ? $data['sd'] : '';
        $case_status = isset($data['cs']) ? $data['cs'] : '';
        $investigation_status = isset($data['is']) ? $data['is'] : '';
        $uri_gen = '/penerimaanpenyelesaianaduan/laporan_kategori_BPGK/dd';
        if ($generate == 'w') {
            $uri = '?ds=' . $date_start->format('d-m-Y') .
                '&de=' . $date_end->format('d-m-Y') .
                '&st=' . $state .
                '&dp=' . $department .
                '&sd=' . $subdepartment .
                '&cs=' . $case_status .
                '&';
            foreach ($branch as $datum) {
                $uri .= 'br%5B%5D=' . $datum . '&';
            }

            if (is_array($investigation_status)) {
                foreach ($investigation_status as $datum) {
                    $uri .= 'is%5B%5D=' . $datum . '&';
                }
            } else {
                $uri .= 'is=' . $investigation_status . '&';
            }
            $uri_gen .= $uri;
        }
        if ($is_search) {
            // data query
            $q = Carian::join('sys_ref', 'sys_ref.code', '=', 'case_info.CA_CMPLCAT')
                ->join('sys_brn', 'case_info.CA_BRNCD', '=', 'sys_brn.BR_BRNCD')
                ->whereBetween('CA_RCVDT', [$date_start, $date_end]);

            // iff negeri != null then check array else get all negeri
            if ($state != '0' && count($branch) > 0) {
                $q = $q->whereIn('CA_BRNCD', $branch);
            }

            // iff subdepartment != null
            if ($subdepartment != '') {
                $q = $q->where('CA_CMPLCAT', $subdepartment);
            }

//            if ($department != '') {
//                $q = $q->where('CA_BRNCD', $department);
//            }
            if ($case_status == 3) {
                $q->where(function ($w) {
                    return $w->where('CA_CASESTS', '1')
                        ->orWhere(function ($e) {
                            return $e->where('CA_CASESTS', '2')
                                ->where('CA_INVSTS', '2');
                        });
                });
            } else {
                if ($case_status != '') {
                    $q = $q->where('CA_CASESTS', $case_status);
                }

//                if ($investigation_status != '') {
//                    $q = $q->whereIn('CA_INVSTS', $investigation_status);
//                }

                if (is_array($investigation_status)) {
                    $q = $q->whereIn('CA_INVSTS', $investigation_status);
                } else {
                    $q = $q->where('CA_INVSTS', $investigation_status);
                }
            }

            $data_final = $dataRaw = $q->get();

            switch ($generate) {
                case 'e':
                    // return Excel::create('LaporanMengikutStatusDanKategoriAduan' . date("_Ymd_His"), function ($excel) use (
                    //     $is_search, $date_start, $date_end, $department, $subdepartment,
                    //     $state, $branch, $case_status, $data_final
                    // ) {
                    //     $excel->sheet('Report', function ($sheet) use (
                    //         $is_search, $date_start, $date_end, $department, $subdepartment,
                    //         $state, $branch, $case_status, $data_final
                    //     ) {
                    //         $sheet->loadView('laporan.penerimaanpenyelesaianaduan.kategori_bpgk_dd.excel')
                    //             ->with([
                    //                 'is_search' => $is_search,
                    //                 'date_start' => $date_start,
                    //                 'date_end' => $date_end,
                    //                 'department' => $department,
                    //                 'subdepartment' => $subdepartment,
                    //                 'state' => $state,
                    //                 'branch' => $branch,
                    //                 'case_status' => $case_status,
                    //                 'data_final' => $data_final,
                    //             ]);
                    //     });
                    // })->export('xlsx');
                    return view(
                        'laporan.penerimaanpenyelesaianaduan.kategori_bpgk_dd.excel', 
                        compact(
                            'is_search', 'date_start', 'date_end', 'department', 'subdepartment', 
                            'state', 'branch', 'case_status', 'data_final'
                        )
                    );
                    break;
                case 'p':
                    $pdf = PDF::loadView('laporan.penerimaanpenyelesaianaduan.kategori_bpgk_dd.pdf',
                        compact('is_search', 'date_start', 'date_end', 'department', 'subdepartment', 'state', 'branch', 'data_final')
                    );
                    return $pdf->stream('LaporanMengikutStatusDanKategoriAduan' . date("_Ymd_His") . '.pdf');
                    break;
                case 'w':
                default:
                    return view('laporan.penerimaanpenyelesaianaduan.kategori_bpgk_dd.index',
                        compact('is_search', 'date_start', 'date_end', 'department', 'subdepartment', 'state', 'branch', 'data_final', 'uri_gen'));
                    break;
            }
        }
    }

    /**
     * Laporan Mengikut Status dan Subkategori - Penerimaan / Penyelesaian Aduan
     * @param Request $request
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function subkategoriBpgk(Request $request)
    {
        $data = $request->all();
//        dump($data);
        $search = count($data) > 0 ? true : false;

        // initialize data
        $user = Auth::user();
        $generate = isset($data['gen']) ? $data['gen'] : 'web';
        $dateStart = isset($data['dateStart']) ? Carbon::createFromFormat('d-m-Y', $data['dateStart'])->startOfDay() : Carbon::now()->startOfDay();
        $dateEnd = isset($data['dateEnd']) ? Carbon::createFromFormat('d-m-Y', $data['dateEnd'])->endOfDay() : Carbon::now()->endOfDay();
        $state = isset($data['state']) ? $data['state'] : '';
        $branch = isset($data['branch']) ? $data['branch'] : [];
        $brancharray = isset($data['branch']) ? $data['branch'] : [];
        $branchUri = '';
        $branchUri2 = '';
        $kategori = isset($data['kategori']) ? $data['kategori'] : '';
        $kategoriDesc = $kategori != null ? Ref::GetDescr('244', $kategori) : '';
        $stateDesc = $state != null ? Ref::GetDescr('17', $state) : '';
        foreach ($branch as $datum) {
            $branchUri .= 'branch%5B%5D=' . $datum . '&';
            $branchUri2 .= 'br%5B%5D=' . $datum . '&';
        }
        $branchSelectList = DB::table('sys_brn')
            ->select('BR_BRNCD', 'BR_BRNNM')
            ->where('BR_STATECD', '=', $user->state_cd)
            ->orderBy('BR_BRNNM')
            ->get();
        $dataTemplate = [
            'selesai' => 0,
            'belum agih' => 0,
            'dalam siasatan' => 0,
            'belum selesai' => 0,
            'tutup' => 0,
            'agensi lain' => 0,
            'tribunal' => 0,
            'pertanyaan' => 0,
            'maklumat tak lengkap' => 0,
            'luar bidang' => 0,
            'total' => 0,
        ];
        $dataFinal = [];
        $dataCounter = $dataTemplate;

        // reference data
        $statuses = Ref::where('cat', 292)
            ->where('status', 1)
            ->whereBetween('code', [3, 9])->get();

        $subdepartmentList = Ref::where('cat', 634)->where('code', 'LIKE', $kategori . '%')->pluck('descr', 'code');
        $refCategory = DB::table('sys_ref')
            ->where('cat', '244')
            ->where('status', '1')
            ->get();
        if ($search && (($kategori != '0') || ($kategori == '0'))) {
            // query data
            $q = DB::table('case_info')
                ->select(DB::raw('case_info.CA_CMPLCD AS CA_CMPLCD, '
                    . 'SUM(CASE WHEN CA_INVSTS = 3 THEN 1 ELSE 0 END) AS SELESAI,'
                    . 'SUM(CASE WHEN CA_CASESTS = 1 THEN 1 ELSE 0 END) AS BELUMAGIH, '
                    . 'SUM(CASE WHEN CA_CASESTS = 2 AND CA_INVSTS = 2 THEN 1 ELSE 0 END) AS DALAMSIASATAN, '
                    . 'SUM(CASE WHEN CA_CASESTS = 2 AND CA_INVSTS = 9 THEN 1 ELSE 0 END) AS TUTUP, '
                    . 'SUM(CASE WHEN CA_CASESTS = 2 AND CA_INVSTS = 4 THEN 1 ELSE 0 END) AS AGENSILAIN, '
                    . 'SUM(CASE WHEN CA_CASESTS = 2 AND CA_INVSTS = 5 THEN 1 ELSE 0 END) AS TRIBUNAL, '
                    . 'SUM(CASE WHEN CA_CASESTS = 2 AND CA_INVSTS = 6 THEN 1 ELSE 0 END) AS PERTANYAAN, '
                    . 'SUM(CASE WHEN CA_CASESTS = 2 AND CA_INVSTS = 7 THEN 1 ELSE 0 END) AS MKLUMATXLENGKAP, '
                    . 'SUM(CASE WHEN CA_CASESTS = 2 AND CA_INVSTS = 8 THEN 1 ELSE 0 END) AS LUARBIDANG, '
                    . 'SUM(CASE WHEN CA_CASESTS = 2 AND CA_INVSTS = 12 THEN 1 ELSE 0 END) AS SELESAIMAKLUMATXLENGKAP, '
                    . 'SUM(CASE WHEN CA_CASESTS = 2 AND CA_INVSTS = 11 THEN 1 ELSE 0 END) AS TUTUPMAKLUMATXLENGKAP, '
                    . 'COUNT(CA_CASEID)AS COUNT_CA_CASEID', 'CA_BRNCD'))
                ->join('sys_brn', 'case_info.CA_BRNCD', '=', 'sys_brn.BR_BRNCD')
                ->whereBetween('CA_RCVDT', [$dateStart, $dateEnd])
                ->whereRaw('CA_CMPLCD != "" AND CA_CMPLCD IS NOT NULL AND CA_CMPLCD != "null" AND CA_CMPLCD != "0"')
                ->whereNotNull('CA_CASEID')
                ->whereNotIn('CA_INVSTS', [10]);

            // iff negeri != null then check array else get all negeri
            if ($state != '0' && count($branch) > 0) {
                $q = $q->whereIn('CA_BRNCD', $branch);
            }

            // iff kategori != null
            if ($kategori != '') {
                $q = $q->where('CA_CMPLCAT', $kategori);
            }

            $dataRaw = $q->groupBy('CA_CMPLCD')->get();

            // populate data to dataFinal & dataCounter
            foreach ($dataRaw as $datum) {
                if($subdepartmentList->has($datum->CA_CMPLCD)) {
                    $dataFinal[$datum->CA_CMPLCD] = $dataTemplate;
                    $dataFinal[$datum->CA_CMPLCD] = [
//                    'selesai' => $datum->SELESAI,
                        'selesai' => $datum->SELESAI + $datum->SELESAIMAKLUMATXLENGKAP,
                        'belum agih' => $datum->BELUMAGIH,
                        'dalam siasatan' => $datum->DALAMSIASATAN,
                        'belum selesai' => $datum->DALAMSIASATAN + $datum->BELUMAGIH,
//                    'tutup' => $datum->TUTUP,
                        'tutup' => $datum->TUTUP + $datum->TUTUPMAKLUMATXLENGKAP,
                        'agensi lain' => $datum->AGENSILAIN,
                        'tribunal' => $datum->TRIBUNAL,
                        'pertanyaan' => $datum->PERTANYAAN,
                        'maklumat tak lengkap' => $datum->MKLUMATXLENGKAP,
                        'luar bidang' => $datum->LUARBIDANG,
                        'total' => $datum->COUNT_CA_CASEID,
                    ];
//                $dataCounter['selesai'] += $datum->SELESAI;
                    $dataCounter['selesai'] += $datum->SELESAI + $datum->SELESAIMAKLUMATXLENGKAP;
                    $dataCounter['belum agih'] += $datum->BELUMAGIH;
                    $dataCounter['dalam siasatan'] += $datum->DALAMSIASATAN;
                    $dataCounter['belum selesai'] += $datum->DALAMSIASATAN + $datum->BELUMAGIH;
//                $dataCounter['tutup'] += $datum->TUTUP;
                    $dataCounter['tutup'] += $datum->TUTUP + $datum->TUTUPMAKLUMATXLENGKAP;
                    $dataCounter['agensi lain'] += $datum->AGENSILAIN;
                    $dataCounter['tribunal'] += $datum->TRIBUNAL;
                    $dataCounter['pertanyaan'] += $datum->PERTANYAAN;
                    $dataCounter['maklumat tak lengkap'] += $datum->MKLUMATXLENGKAP;
                    $dataCounter['luar bidang'] += $datum->LUARBIDANG;
                    $dataCounter['total'] += $datum->COUNT_CA_CASEID;
                }
            }
        }

        switch ($generate) {
            case 'pdf':
                $pdf = @PDF::loadView('laporan.penerimaanpenyelesaianaduan.subkategori_bpgk.pdf',
                    compact('dateStart', 'dateEnd', 'stateDesc', 'subdepartmentList', 'statuses', 'user', 'branchSelectList', 'search', 'state', 'dataFinal', 'dataCounter', 'kategori', 'kategoriDesc'), [], [
                        'format' => 'A4-L'
                    ]);
                return $pdf->stream('Laporan_Pegawai' . date("_Ymd_His") . '.pdf');
                break;
            case 'excel':
                // return Excel::create('Laporan Mengikut Status Dan Kategori Aduan' . date("_Ymd_His"), function ($excel) use ($dateStart, $dateEnd, $stateDesc, $subdepartmentList, $statuses, $user, $branchSelectList, $search, $state, $dataFinal, $dataCounter, $kategori, $kategoriDesc) {
                //     $excel->sheet('Report', function ($sheet) use ($dateStart, $dateEnd, $stateDesc, $subdepartmentList, $statuses, $user, $branchSelectList, $search, $state, $dataFinal, $dataCounter, $kategori, $kategoriDesc) {
                //         $sheet->loadView('laporan.penerimaanpenyelesaianaduan.subkategori_bpgk.excel')
                //             ->with([
                //                 'dateStart' => $dateStart,
                //                 'dateEnd' => $dateEnd,
                //                 'stateDesc' => $stateDesc,
                //                 'subdepartmentList' => $subdepartmentList,
                //                 'statuses' => $statuses,
                //                 'user' => $user,
                //                 'branchSelectList' => $branchSelectList,
                //                 'search' => $search,
                //                 'state' => $state,
                //                 'dataFinal' => $dataFinal,
                //                 'dataCounter' => $dataCounter,
                //                 'kategori' => $kategori,
                //                 'kategoriDesc' => $kategoriDesc,
                //             ]);
                //     });
                // })->export('xlsx');
                return view(
                    'laporan.penerimaanpenyelesaianaduan.subkategori_bpgk.excel', 
                    compact('dateStart','dateEnd','stateDesc','subdepartmentList','statuses',
                        'user','branchSelectList','search','state',
                        'dataFinal','dataCounter','kategori','kategoriDesc'
                    )
                );
                break;
            case 'web':
            default:
                // prepare uri to drill down report
                $dd_uri = "ds=" . $dateStart->toDateString() . "&st=" . $state . "&de=" . $dateEnd->toDateString() . "&" . $branchUri2 . "&dp=" . (($kategori != 0 || $kategori != '') ? $kategori : '') . "&ge=w";
                // push data to javascript by binding it to layouts.bottom
                JavaScript::put([
                    'dataCounter' => [
                        $dataCounter['belum agih'],
                        $dataCounter['dalam siasatan'],
                        $dataCounter['maklumat tak lengkap'],
                        $dataCounter['selesai'],
                        $dataCounter['tutup'],
                        $dataCounter['agensi lain'],
                        $dataCounter['tribunal'],
                        $dataCounter['pertanyaan'],
                        $dataCounter['luar bidang'],
                    ]
                ]);

                return view('laporan.penerimaanpenyelesaianaduan.subkategori_bpgk.index',
                    compact('dateStart', 'dateEnd', 'subdepartmentList', 'statuses', 'user', 'branchSelectList', 'search', 'state', 'dataFinal', 'dataCounter', 'branchUri', 'subdepartmentUri', 'dd_uri', 'brancharray', 'subdepartmentarray', 'refCategory', 'kategori'));
                break;
        }
    }

    /**
     * Drill Down Laporan Mengikut Status dan Kategori - Penerimaan / Penyelesaian Aduan
     * @param Request $request
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function ddSubKategoriBpgk(Request $request)
    {
//        dd($request->all());
        $data = $request->all();
        $is_search = count($data) > 0 ? true : false;

        // initialize data
        $generate = isset($data['ge']) ? $data['ge'] : 'w';
        $date_start = isset($data['ds']) ? Carbon::parse($data['ds'])->startOfDay() : Carbon::now()->startOfDay();
        $date_end = isset($data['de']) ? Carbon::parse($data['de'])->endOfDay() : Carbon::now()->endOfDay();
        $state = isset($data['st']) ? $data['st'] : '';
        $branch = isset($data['br']) ? $data['br'] : [];
        $kategori = isset($data['dp']) ? $data['dp'] : '';
        $subkategori = isset($data['sd']) ? $data['sd'] : '';
        $case_status = isset($data['cs']) ? $data['cs'] : '';
        $investigation_status = isset($data['is']) ? $data['is'] : '';

        if ($is_search) {
            // data query
            $q = Carian::join('sys_ref', 'sys_ref.code', '=', 'case_info.CA_CMPLCAT')
                ->join('sys_brn', 'case_info.CA_BRNCD', '=', 'sys_brn.BR_BRNCD')
                ->whereBetween('CA_RCVDT', [$date_start, $date_end]);

            // iff negeri != null then check array else get all negeri
            if ($state != '0' && count($branch) > 0) {
                $q = $q->whereIn('CA_BRNCD', $branch);
            }

            // iff subkategori != null
            if ($subkategori != '') {
                $q = $q->where('CA_CMPLCD', $subkategori);
            }

//            if ($department != '') {
//                $q = $q->where('CA_BRNCD', $department);
//            }
            if ($case_status == 3) {
                $q->where(function ($w) {
                    return $w->where('CA_CASESTS', '1')
                        ->orWhere(function ($e) {
                            return $e->where('CA_CASESTS', '2')
                                ->where('CA_INVSTS', '2');
                        });
                });
            } else {
                if ($case_status != '') {
                    $q = $q->where('CA_CASESTS', $case_status);
                }

//                if ($investigation_status != '') {
//                    $q = $q->whereIn('CA_INVSTS', $investigation_status);
//                }

                if (is_array($investigation_status)) {
                    $q = $q->whereIn('CA_INVSTS', $investigation_status);
                } else {
                    $q = $q->where('CA_INVSTS', $investigation_status);
                }
            }

            $data_final = $dataRaw = $q->get();

            if (count($investigation_status) == 2) {
                if (in_array('0', $investigation_status, true) && in_array('1', $investigation_status, true)) {
                    $status = 'Aduan Baru';
                } else if (in_array('3', $investigation_status, true) && in_array('12', $investigation_status, true)) {
                    $status = 'Diselesaikan';
                } else if (in_array('9', $investigation_status, true) && in_array('11', $investigation_status, true)) {
                    $status = 'Ditutup';
                }
            } else if (count($investigation_status) == 1) {
                $status = Ref::GetDescr('292', $investigation_status, 'ms');
            } else {
                $status = 'Semua';
            }

            switch ($generate) {
                case 'w':
                default:
                    return view('laporan.penerimaanpenyelesaianaduan.subkategori_bpgk_dd.index',
                        compact('date_start', 'date_end', 'kategori', 'subkategori', 'state', 'data_final', 'status'));
                    break;
            }
        }
    }

    /**
     * Laporan Mengikut Status dan Cara Penerimaan - Penerimaan / Penyelesaian Aduan
     * @param Request $request
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function caraPenerimaan(Request $request)
    {
        $data = $request->all();
//        dump($data);
        $search = count($data) > 0 ? true : false;

        // initialize data
        $user = Auth::user();
        $generate = isset($data['gen']) ? $data['gen'] : 'web';
        $dateStart = isset($data['dateStart']) ? Carbon::createFromFormat('d-m-Y', $data['dateStart'])->startOfDay() : Carbon::now()->startOfDay();
        $dateEnd = isset($data['dateEnd']) ? Carbon::createFromFormat('d-m-Y', $data['dateEnd'])->endOfDay() : Carbon::now()->endOfDay();
        $state = isset($data['state']) ? $data['state'] : '';
        $branch = isset($data['branch']) ? $data['branch'] : [];
        $brancharray = isset($data['branch']) ? $data['branch'] : [];
        $branchUri = '';
        $branchUri2 = '';
        $subdepartmentUri = '';
        $subdepartmentUri2 = '';
        $subdepartment = isset($data['subdepartment']) ? $data['subdepartment'] : [];
        $subdepartmentarray = isset($data['subdepartment']) ? $data['subdepartment'] : [];
        $departmentDesc = !empty($subdepartment) && count($subdepartment) == 1 ? Ref::GetDescr('259', $subdepartment) : 'SEMUA CARA PENERIMAAN';
        $stateDesc = !empty($state) ? Ref::GetDescr('17', $state) : 'SEMUA NEGERI';
        foreach ($branch as $datum) {
            $branchUri .= 'branch%5B%5D=' . $datum . '&';
            $branchUri2 .= 'br%5B%5D=' . $datum . '&';
        }
        foreach ($subdepartment as $datum) {
            $subdepartmentUri .= 'subdepartment%5B%5D=' . $datum . '&';
            $subdepartmentUri2 .= 'sd%5B%5D=' . $datum . '&';
        }
        if(empty($state)){
            $branchSelectList = DB::table('sys_brn')
                ->select('BR_BRNCD', 'BR_BRNNM')
//                ->where('BR_STATECD', '=', $state)
                ->where('BR_STATUS', '1')
//                ->orderBy('BR_BRNNM')
                ->get();
        } else if(!empty($state)){
            $branchSelectList = DB::table('sys_brn')
                ->select('BR_BRNCD', 'BR_BRNNM')
                ->where('BR_STATECD', '=', $state)
                ->where('BR_STATUS', '1')
//                ->orderBy('BR_BRNNM')
                ->get();
        }
        $dataTemplate = [
            'selesai' => 0,
            'belum agih' => 0,
            'dalam siasatan' => 0,
            'belum selesai' => 0,
            'tutup' => 0,
            'agensi lain' => 0,
            'tribunal' => 0,
            'pertanyaan' => 0,
            'maklumat tak lengkap' => 0,
            'luar bidang' => 0,
            'total' => 0,
        ];
        $dataFinal = [];
        $dataCounter = $dataTemplate;

        // reference data
        $statuses = Ref::where('cat', 292)
            ->where('status', 1)
            ->whereBetween('code', [3, 9])->get();
        $qsubdepartment = Ref::where('cat', 259);

        if(count($subdepartment) > 0){
            $qsubdepartment = $qsubdepartment->whereIn('code', $subdepartment);
        } else {
            $qsubdepartment = $qsubdepartment->whereNotNull('status');
        }        

        $subdepartmentList = $qsubdepartment->pluck('descr', 'code');
        $refCategory = DB::table('sys_ref')
            ->where('cat', '259')
//            ->where('status', '1')
            ->get()
        ;
        if ($search && (count($subdepartment) > 0)) {
            // query data
            $q = DB::table('case_info')
                ->select(DB::raw('case_info.CA_RCVTYP AS CA_RCVTYP, '
                    . 'SUM(CASE WHEN CA_INVSTS = 3 THEN 1 ELSE 0 END) AS SELESAI,'
                    . 'SUM(CASE WHEN CA_CASESTS = 1 THEN 1 ELSE 0 END) AS BELUMAGIH, '
                    . 'SUM(CASE WHEN CA_CASESTS = 2 AND CA_INVSTS = 2 THEN 1 ELSE 0 END) AS DALAMSIASATAN, '
                    . 'SUM(CASE WHEN CA_CASESTS = 2 AND CA_INVSTS = 9 THEN 1 ELSE 0 END) AS TUTUP, '
                    . 'SUM(CASE WHEN CA_CASESTS = 2 AND CA_INVSTS = 4 THEN 1 ELSE 0 END) AS AGENSILAIN, '
                    . 'SUM(CASE WHEN CA_CASESTS = 2 AND CA_INVSTS = 5 THEN 1 ELSE 0 END) AS TRIBUNAL, '
                    . 'SUM(CASE WHEN CA_CASESTS = 2 AND CA_INVSTS = 6 THEN 1 ELSE 0 END) AS PERTANYAAN, '
                    . 'SUM(CASE WHEN CA_CASESTS = 2 AND CA_INVSTS = 7 THEN 1 ELSE 0 END) AS MKLUMATXLENGKAP, '
                    . 'SUM(CASE WHEN CA_CASESTS = 2 AND CA_INVSTS = 8 THEN 1 ELSE 0 END) AS LUARBIDANG, '
                    . 'SUM(CASE WHEN CA_CASESTS = 2 AND CA_INVSTS = 12 THEN 1 ELSE 0 END) AS SELESAIMAKLUMATXLENGKAP, '
                    . 'SUM(CASE WHEN CA_CASESTS = 2 AND CA_INVSTS = 11 THEN 1 ELSE 0 END) AS TUTUPMAKLUMATXLENGKAP, '
                    . 'COUNT(CA_CASEID)AS COUNT_CA_CASEID', 'CA_BRNCD'))
                ->join('sys_brn', 'case_info.CA_BRNCD', '=', 'sys_brn.BR_BRNCD')
                // ->join('sys_ref', 'sys_ref.code', '=', 'case_info.CA_RCVTYP')
                ->join('sys_ref', function ($join) {
                    $join->on('sys_ref.code', '=', 'case_info.CA_RCVTYP')
                    ->where('sys_ref.cat', 259);
                })
                ->whereBetween('CA_RCVDT', [$dateStart, $dateEnd])
                // ->where('sys_ref.cat', 259)
                ->whereNotNull('CA_CASEID')
                ->where('CA_INVSTS','!=',10);
            
            // iff negeri != null then check array else get all negeri
            if ($state != '0' && count($branch) > 0) {
                $q = $q->whereIn('CA_BRNCD', $branch);
            }

            // iff department != null then check array else get all subdepartment
//            if ($department != '0' && count($subdepartment) > 0) {
            if (count($subdepartment) > 0) {
                $q = $q->whereIn('CA_RCVTYP', $subdepartment);
            } 

            $dataRaw = $q->groupBy('CA_RCVTYP')->get();

            foreach ($subdepartmentList as $key => $datum) {
                $dataFinal[$key] = $dataTemplate;
            }
            
            // populate data to dataFinal & dataCounter
            foreach ($dataRaw as $datum) {
//                $dataFinal[$datum->CA_CMPLCAT] = $dataTemplate;
                $dataFinal[$datum->CA_RCVTYP] = [
//                    'selesai' => $datum->SELESAI,
                    'selesai' => $datum->SELESAI + $datum->SELESAIMAKLUMATXLENGKAP,
                    'belum agih' => $datum->BELUMAGIH,
                    'dalam siasatan' => $datum->DALAMSIASATAN,
                    'belum selesai' => $datum->DALAMSIASATAN + $datum->BELUMAGIH,
//                    'tutup' => $datum->TUTUP,
                    'tutup' => $datum->TUTUP + $datum->TUTUPMAKLUMATXLENGKAP,
                    'agensi lain' => $datum->AGENSILAIN,
                    'tribunal' => $datum->TRIBUNAL,
                    'pertanyaan' => $datum->PERTANYAAN,
                    'maklumat tak lengkap' => $datum->MKLUMATXLENGKAP,
                    'luar bidang' => $datum->LUARBIDANG,
                    'total' => $datum->SELESAI + $datum->SELESAIMAKLUMATXLENGKAP + $datum->BELUMAGIH + $datum->DALAMSIASATAN + $datum->TUTUP + $datum->TUTUPMAKLUMATXLENGKAP + $datum->AGENSILAIN + $datum->TRIBUNAL + $datum->PERTANYAAN + $datum->LUARBIDANG + $datum->MKLUMATXLENGKAP
                ];
//                $dataCounter['selesai'] += $datum->SELESAI;
                $dataCounter['selesai'] += $datum->SELESAI + $datum->SELESAIMAKLUMATXLENGKAP;
                $dataCounter['belum agih'] += $datum->BELUMAGIH;
                $dataCounter['dalam siasatan'] += $datum->DALAMSIASATAN;
                $dataCounter['belum selesai'] += $datum->DALAMSIASATAN + $datum->BELUMAGIH;
//                $dataCounter['tutup'] += $datum->TUTUP;
                $dataCounter['tutup'] += $datum->TUTUP + $datum->TUTUPMAKLUMATXLENGKAP;
                $dataCounter['agensi lain'] += $datum->AGENSILAIN;
                $dataCounter['tribunal'] += $datum->TRIBUNAL;
                $dataCounter['pertanyaan'] += $datum->PERTANYAAN;
                $dataCounter['maklumat tak lengkap'] += $datum->MKLUMATXLENGKAP;
                $dataCounter['luar bidang'] += $datum->LUARBIDANG;
                $dataCounter['total'] += $datum->SELESAI + $datum->SELESAIMAKLUMATXLENGKAP + $datum->BELUMAGIH + $datum->DALAMSIASATAN + $datum->TUTUP + $datum->TUTUPMAKLUMATXLENGKAP + $datum->AGENSILAIN + $datum->TRIBUNAL + $datum->PERTANYAAN + $datum->LUARBIDANG + $datum->MKLUMATXLENGKAP;
            }
        }

        switch ($generate) {
            case 'pdf':
                $pdf = @PDF::loadView('laporan.penerimaanpenyelesaianaduan.cara_penerimaan.pdf',
                    compact('dateStart', 'dateEnd', 'departmentDesc', 'stateDesc', 'subdepartmentList', 'statuses', 'user', 'branchSelectList', 'search', 'department', 'state', 'dataFinal', 'dataCounter'), [], [
                        'format' => 'A4-L'
                    ]);
                return $pdf->stream('Laporan_Status_Cara_Penerimaan' . date("_Ymd_His") . '.pdf');
                break;
            case 'excel':
                // return Excel::create('Laporan Mengikut Status Dan Cara Penerimaan Aduan' . date("_Ymd_His"), function ($excel) use ($dateStart, $dateEnd, $departmentDesc, $stateDesc, $subdepartmentList, $statuses, $user, $branchSelectList, $search, $state, $dataFinal, $dataCounter) {
                //     $excel->sheet('Report', function ($sheet) use ($dateStart, $dateEnd, $departmentDesc, $stateDesc, $subdepartmentList, $statuses, $user, $branchSelectList, $search, $state, $dataFinal, $dataCounter) {
                //         $sheet->loadView('laporan.penerimaanpenyelesaianaduan.cara_penerimaan.excel')
                //             ->with([
                //                 'dateStart' => $dateStart,
                //                 'dateEnd' => $dateEnd,
                //                 'departmentDesc' => $departmentDesc,
                //                 'stateDesc' => $stateDesc,
                //                 'subdepartmentList' => $subdepartmentList,
                //                 'statuses' => $statuses,
                //                 'user' => $user,
                //                 'branchSelectList' => $branchSelectList,
                //                 'search' => $search,
                //                 'state' => $state,
                //                 'dataFinal' => $dataFinal,
                //                 'dataCounter' => $dataCounter
                //             ]);
                //     });
                // })->export('xlsx');
                return view(
                    'laporan.penerimaanpenyelesaianaduan.cara_penerimaan.excel', 
                    compact(
                        'dateStart', 'dateEnd', 'departmentDesc', 'stateDesc', 'subdepartmentList', 
                        'statuses', 'user', 'branchSelectList', 'search', 'state', 
                        'dataFinal', 'dataCounter'
                    )
                );
                break;
            case 'web':
            default:
                // prepare uri to drill down report
                $dd_uri = "ds=" . $dateStart->toDateString() . "&st=" . $state . "&de=" . $dateEnd->toDateString() . "&" . $branchUri2 . "&ge=w";
                // push data to javascript by binding it to layouts.bottom
                JavaScript::put([
                    'dataCounter' => [
                        $dataCounter['total'],
                        $dataCounter['belum agih'],
                        $dataCounter['dalam siasatan'],
                        $dataCounter['maklumat tak lengkap'],
                        $dataCounter['selesai'],
                        $dataCounter['tutup'],
                        $dataCounter['agensi lain'],
                        $dataCounter['tribunal'],
                        $dataCounter['pertanyaan'],
                        $dataCounter['luar bidang'],
                    ]
                ]);

                return view('laporan.penerimaanpenyelesaianaduan.cara_penerimaan.index',
                    compact('dateStart', 'dateEnd', 'subdepartmentList', 'statuses', 'user', 'branchSelectList', 'search', 'department', 'state', 'dataFinal', 'dataCounter', 'branchUri', 'subdepartmentUri', 'dd_uri', 'brancharray', 'subdepartmentarray', 'refCategory'));
                break;
        }
    }

    /**
     * Drill Down Laporan Mengikut Status dan Cara Penerimaan - Penerimaan / Penyelesaian Aduan
     * @param Request $request
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function ddCaraPenerimaan(Request $request)
    {
//       dd($request->all());
        $data = $request->all();
        $is_search = count($data) > 0 ? true : false;

        // initialize data
        $generate = isset($data['ge']) ? $data['ge'] : 'w';
        $date_start = isset($data['ds']) ? Carbon::parse($data['ds'])->startOfDay() : Carbon::now()->startOfDay();
        $date_end = isset($data['de']) ? Carbon::parse($data['de'])->endOfDay() : Carbon::now()->endOfDay();
        $state = isset($data['st']) ? $data['st'] : '';
        $branch = isset($data['br']) ? $data['br'] : [];
        $subdepartment = isset($data['sd']) ? $data['sd'] : '';
        $case_status = isset($data['cs']) ? $data['cs'] : '';
        $investigation_status = isset($data['is']) ? $data['is'] : '';
        $uri_gen = '/penerimaanpenyelesaianaduan/laporan_cara_penerimaan/dd';
        if ($generate == 'w') {
            $uri = '?ds=' . $date_start->format('d-m-Y') . 
                '&de=' . $date_end->format('d-m-Y') . 
                '&st=' . $state . 
                '&sd=' . $subdepartment . 
                '&cs=' . $case_status . 
                '&';
            foreach ($branch as $datum) {
                $uri .= 'br%5B%5D=' . $datum . '&';
            }

            if (is_array($investigation_status)) {
                foreach ($investigation_status as $datum) {
                    $uri .= 'is%5B%5D=' . $datum . '&';
                }
            } else {
                $uri .= 'is=' . $investigation_status . '&';
            }
            $uri_gen .= $uri;
        }
        if ($is_search) {
            // data query
            $q = Carian::join('sys_brn', 'case_info.CA_BRNCD', '=', 'sys_brn.BR_BRNCD')
                ->join('sys_ref', function ($join) {
                    $join->on('sys_ref.code', '=', 'case_info.CA_RCVTYP')
                    ->where('sys_ref.cat', 259);
                })
                ->whereBetween('CA_RCVDT', [$date_start, $date_end])
                // ->where('cat', 259)
                ->whereNotNull('CA_CASEID')
                ->where('CA_INVSTS','!=',10);

            // iff negeri != null then check array else get all negeri
            if ($state != '0' && count($branch) > 0) {
                $q = $q->whereIn('CA_BRNCD', $branch);
            }

            // iff subdepartment != null
            if ($subdepartment != '') {
                $q = $q->where('CA_RCVTYP', $subdepartment);
            }

//            if ($department != '') {
//                $q = $q->where('CA_BRNCD', $department);
//            }
            if ($case_status == 3) {
                $q->where(function($w){
                    return $w->where('CA_CASESTS', '1')
                        ->orWhere(function($e){
                            return $e->where('CA_CASESTS', '2')
                                ->where('CA_INVSTS', '2');
                        });
                });
            } else {
                if ($case_status != '') {
                    $q = $q->where('CA_CASESTS', $case_status);
                }

//                if ($investigation_status != '') {
//                    $q = $q->whereIn('CA_INVSTS', $investigation_status);
//                }

                if (is_array($investigation_status)) {
                    $q = $q->whereIn('CA_INVSTS', $investigation_status);
                } else {
                    $q = $q->where('CA_INVSTS', $investigation_status);
                }
            }

            $data_final = $dataRaw = $q->get();

            switch ($generate) {
                case 'e':
                    // return Excel::create('LaporanMengikutStatusDanCaraPenerimaanAduan' . date("_Ymd_His"), function ($excel) use (
                    //         $is_search, $date_start, $date_end, $subdepartment,
                    //         $state, $branch, $case_status, $data_final
                    //         ) {
                    //     $excel->sheet('Report', function ($sheet) use (
                    //         $is_search, $date_start, $date_end, $subdepartment,
                    //         $state, $branch, $case_status, $data_final
                    //             ) {
                    //         $sheet->loadView('laporan.penerimaanpenyelesaianaduan.cara_penerimaan_dd.excel')
                    //             ->with([
                    //                 'is_search' => $is_search,
                    //                 'date_start' => $date_start,
                    //                 'date_end' => $date_end,
                    //                 'subdepartment' => $subdepartment,
                    //                 'state' => $state,
                    //                 'branch' => $branch,
                    //                 'case_status' => $case_status,
                    //                 'data_final' => $data_final,
                    //             ]);
                    //     });
                    // })->export('xlsx');
                    return view(
                        'laporan.penerimaanpenyelesaianaduan.cara_penerimaan_dd.excel', 
                        compact(
                            'is_search', 'date_start', 'date_end', 'subdepartment', 'state', 
                            'branch', 'case_status', 'data_final'
                        )
                    );
                    break;
                case 'p':
                    $pdf = PDF::loadView('laporan.penerimaanpenyelesaianaduan.cara_penerimaan_dd.pdf',
                        compact('is_search', 'date_start', 'date_end', 'department', 'subdepartment', 'state', 'branch', 'data_final')
                    );
                    return $pdf->stream('LaporanMengikutStatusDanCaraPenerimaanAduan' . date("_Ymd_His") . '.pdf');
                    break;
                case 'w':
                default:
                    return view('laporan.penerimaanpenyelesaianaduan.cara_penerimaan_dd.index',
                        compact('is_search', 'date_start', 'date_end', 'department', 'subdepartment', 'state', 'branch', 'data_final', 'uri_gen'));
                    break;
            }
        }
    }

    public function PrintExcel($SelectYear, $MonthFrom, $MonthTo, $senNegeri, $CA_DEPTCD)
    {
        $writer = WriterFactory::create(Type::XLSX); // for XLSX files
        $SenaraiAgihan = TerimaSelesaiAduan::SenaraiAgihan($SelectYear, $MonthFrom, $MonthTo, $senNegeri, $CA_DEPTCD);
        $time = date('YmdHis');
        $writer->openToBrowser("Laporan{$time}.xlsx"); // stream data directly to the browser 
        $writer->addRow(array('Bil', 'Negeri', 'Jumlah Aduan', '1Hari', '> 1 Hari'));
//        $writer->addRows($SenaraiAgihan); // add multiple rows at a time
        $writer->close();
    }

    public function GetBranchList($state_cd)
    {
        if (empty($state_cd)) {
            $mDistList = DB::table('sys_brn')
//                ->where('BR_STATECD', '=', $state_cd)
                ->where('BR_STATUS', '1')
                ->pluck('BR_BRNNM', 'BR_BRNCD');
        } else {
            $mDistList = DB::table('sys_brn')
                ->where('BR_STATECD', '=', $state_cd)
                ->where('BR_STATUS', '1')
                ->pluck('BR_BRNNM', 'BR_BRNCD');
        }
        return json_encode($mDistList);
    }

    public function GetCategoryList($bahagianCd)
    {
        if ($bahagianCd == '0') {
            $mKategoriList = DB::table('sys_ref')
//                ->where('status', '1')
                ->where('cat', '244')
                ->pluck('descr', 'code');
        } else {
            $mKategoriList = DB::table('sys_ref')
                ->where('code', 'LIKE', $bahagianCd . '%')->where('cat', '=', '244')
//                ->where('status', '1')
                ->where('cat', '244')->pluck('descr', 'code');
        }
        return json_encode($mKategoriList);
    }

    public function GetNegeri()
    {
        $mNegeriList = DB::table('sys_ref')
            ->where('cat', 'NEG')->pluck('descr', 'code');
        return json_encode($mNegeriList);
    }

    public function senaraiAgihan($SelectYear, $MonthFrom, $MonthTo, $BR_STATECD, $CA_DEPTCD)
    {

        return view('laporan.penerimaanpenyelesaianaduan.senaraiagihan', compact('SelectYear', 'MonthFrom', 'MonthTo', 'BR_STATECD', 'CA_DEPTCD'));
    }

    public function GetDataTableAgihan($SelectYear, $MonthFrom, $MonthTo, $BR_STATECD, $CA_DEPTCD)
    {
        //    public function senaraiAgihan(){
//        $mAdminDoc= CallCenterCaseDoc::where('CC_CASEID',$CASEID);
        $senarai = DB::table('case_info')
            ->join('sys_brn', 'case_info.CA_BRNCD', '=', 'sys_brn.BR_BRNCD')
            ->select('BR_STATECD')
            ->whereIn('CA_INVSTS', [0, 4, 5])
            ->whereYear('CA_RCVDT', $SelectYear)
            ->whereMonth('CA_RCVDT', '>=', $MonthFrom)
            ->whereMonth('CA_RCVDT', '<=', $MonthTo)
            ->where(['BR_STATECD' => $BR_STATECD, 'CA_DEPTCD' => $CA_DEPTCD])
            ->whereRaw('DATEDIFF(case_info.CA_MODDT,case_info.CA_RCVDT) BETWEEN 2 AND 900')
            ->get();
        dd($senarai);
        $datatables = Datatables::of($senarai)
            ->addIndexColumn()
            ->rawColumns(['CC_IMG_NAME', 'action']);

        return $datatables->make(true);
    }

    public function getdeptlistbystate($statecd)
    {
        if ($statecd != '16') {
            $mRef = DB::table('sys_ref')
                ->where('cat', '315')
                ->whereIn('code', ['BPGK'])
                ->where('status', '1')
                ->orderBy('sort', 'asc')
                ->pluck('code', 'descr');
        } else {
            $mRef = DB::table('sys_ref')
                ->where('cat', '315')
                ->where('status', '1')
                ->orderBy('sort', 'asc')
                ->pluck('code', 'descr');
        }
        $mRef->prepend('', 'SEMUA');
        return json_encode($mRef);
    }
}
