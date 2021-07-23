<?php

namespace App\Http\Controllers\Laporan;

use App\Laporan\TerimaSelesaiAduan;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Input;
use App\Laporan\Bpa;
use Carbon\Carbon;
use Maatwebsite\Excel\Facades\Excel;
use DB;
use Box\Spout\Writer\WriterFactory;
use Box\Spout\Common\Type;
use PDF;

class BpaController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    /**
     * @param Request $request
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function PenerimaanPenyelesaianBulan(Request $request)
    {
        $CA_RCVDT_YEAR = date('Y', strtotime(Carbon::now()));
        $CA_RCVDT_MONTH = '';
        $BR_STATECD = '';
        $CA_DEPTCD = '';
        $action = '';

        if (Input::has('CA_RCVDT_YEAR')) {
            $CA_RCVDT_YEAR = Input::get('CA_RCVDT_YEAR');
        }
        if (Input::has('CA_RCVDT_MONTH')) {
            $CA_RCVDT_MONTH = Input::get('CA_RCVDT_MONTH');
        }
        if ($request->BR_STATECD) {
            $BR_STATECD = $request->BR_STATECD;
        }
        if (Input::has('CA_DEPTCD')) {
            $CA_DEPTCD = Input::get('CA_DEPTCD');
        }
        if (Input::has('action')) {
            $action = Input::get('action');
            return view('laporan.bpa.penerimaan_penyelesaian_bulan',
                compact('CA_RCVDT_YEAR', 'CA_RCVDT_MONTH', 'BR_STATECD', 'CA_DEPTCD', 'action', 'senarai', 'request'));
        }
        if ($request->get('excel') == '1') {
            return view('laporan.bpa.penerimaan_penyelesaian_bulan_excel',
                compact('CA_RCVDT_YEAR', 'CA_RCVDT_MONTH', 'BR_STATECD', 'CA_DEPTCD', 'action', 'senarai', 'request'));
        }elseif ($request->get('pdf') == '1') {
            $pdf = PDF::loadView('laporan.bpa.penerimaan_penyelesaian_bulan_pdf', compact('CA_RCVDT_YEAR', 'CA_RCVDT_MONTH', 'BR_STATECD', 'CA_DEPTCD', 'action', 'senarai', 'request'), [], ['default_font_size' => 7, 'title' => date('Ymd_His')]);
            return $pdf->stream('bpa' . date("Ymd_His") . '.pdf');
        } else {
        return view('laporan.bpa.penerimaan_penyelesaian_bulan',
            compact('CA_RCVDT_YEAR', 'CA_RCVDT_MONTH', 'BR_STATECD', 'CA_DEPTCD', 'action', 'senarai', 'request'));
        }
    }

    public function PenerimaanPenyelesaianKumulatif(Request $request)
    {
        $CA_RCVDT_YEAR = date('Y', strtotime(Carbon::now()));
        $CA_DEPTCD = '';
        $action = '';
        if (Input::has('CA_RCVDT_YEAR')) {
            $CA_RCVDT_YEAR = Input::get('CA_RCVDT_YEAR');
        }
        if (Input::has('CA_DEPTCD')) {
            $CA_DEPTCD = Input::get('CA_DEPTCD');
        }
        if (Input::has('action')) {
            $action = Input::get('action');
        }
//        SELECT MONTHNAME(CA_RCVDT) AS MONTH,CA_AGAINST_STATECD, CA_INVSTS, COUNT(id) AS countcaseid,
//SUM(CASE WHEN CA_INVSTS='1' THEN 1 ELSE 0 END) AS Count1,
//SUM(CASE WHEN CA_INVSTS='2' THEN 1 ELSE 0 END) AS Count2,
//SUM(CASE WHEN CA_INVSTS='3' THEN 1 ELSE 0 END) AS Count3,
//SUM(CASE WHEN CA_INVSTS='4' THEN 1 ELSE 0 END) AS Count4,
//SUM(CASE WHEN CA_INVSTS='5' THEN 1 ELSE 0 END) AS Count5,
//SUM(CASE WHEN CA_INVSTS='6' THEN 1 ELSE 0 END) AS Count6,
//SUM(CASE WHEN CA_INVSTS='7' THEN 1 ELSE 0 END) AS Count7,
//SUM(CASE WHEN CA_INVSTS='8' THEN 1 ELSE 0 END) AS Count8,
//SUM(CASE WHEN CA_INVSTS='9' THEN 1 ELSE 0 END) AS Count9 
//FROM case_info
//INNER JOIN sys_brn
//ON case_info.CA_BRNCD=sys_brn.BR_BRNCD 
//WHERE 
//CA_RCVDT != ''
//-- CA_CASEID>'0' &&  CA_AGAINST_STATECD > '0' && YEAR(CA_RCVDT)='$year' && CA_DEPTCD='$bahagaian' 
//GROUP BY MONTH(CA_RCVDT)
        $senarai = DB::table('case_info')
            ->join('sys_brn', 'case_info.CA_BRNCD', '=', 'sys_brn.BR_BRNCD')
            ->select(DB::raw('MONTH(CA_RCVDT) AS month, COUNT(CA_CASEID) as countcaseid,
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
            ->whereNotNull('CA_RCVDT')
            ->when($CA_RCVDT_YEAR, function ($query) use ($CA_RCVDT_YEAR) {
                return $query->whereYear('CA_RCVDT', $CA_RCVDT_YEAR);
            })
            ->when($CA_DEPTCD, function ($query) use ($CA_DEPTCD) {
                return $query->where('CA_DEPTCD', 'like', "%$CA_DEPTCD%");
            })
            ->groupBy(DB::raw('month'))
            ->get();
          if ($request->get('excel') == '1') {
            return view('laporan.bpa.penerimaanpenyelesaiankumulatif_excel',
                 compact('CA_RCVDT_YEAR', 'CA_DEPTCD', 'action', 'senarai','request'));
        }elseif ($request->get('pdf') == '1') {
            $pdf = PDF::loadView('laporan.bpa.penerimaanpenyelesaiankumulatif_pdf',  compact('CA_RCVDT_YEAR', 'CA_DEPTCD', 'action', 'senarai','request'), [], ['default_font_size' => 7, 'title' => date('Ymd_His')]);
            return $pdf->stream('bpa' . date("Ymd_His") . '.pdf');
        } else {   
        return view('laporan.bpa.penerimaanpenyelesaiankumulatif',
            compact('CA_RCVDT_YEAR', 'CA_DEPTCD', 'action', 'senarai','request'));
        }
    }

    public function SumberPenerimaanBulan(Request $request)
    {
        $CA_RCVDT_YEAR = date('Y', strtotime(Carbon::now()));
        $CA_RCVDT_MONTH = '';
        $CA_DEPTCD = '';
        $BR_STATECD = '';
        $action = '';
        if (Input::has('CA_RCVDT_YEAR')) {
            $CA_RCVDT_YEAR = Input::get('CA_RCVDT_YEAR');
        }
        if (Input::has('CA_RCVDT_MONTH')) {
            $CA_RCVDT_MONTH = Input::get('CA_RCVDT_MONTH');
        }
        if (Input::has('CA_DEPTCD')) {
            $CA_DEPTCD = Input::get('CA_DEPTCD');
        }
        if ($request->BR_STATECD) {
            $BR_STATECD = $request->BR_STATECD;
        }
        if (Input::has('action')) {
            $action = Input::get('action');
        }
        $refsumberaduan = DB::table('sys_ref')
            ->where('cat', '259')
            ->get();
//        $senaraisumberaduan = Bpa::sumberpenerimaanbulan($CA_RCVDT_YEAR, $CA_RCVDT_MONTH, $CA_DEPTCD, $BR_STATECD);
        if ($request->get('excel') == '1') {
            return view('laporan.bpa.sumberterimabulan_excel', compact('CA_RCVDT_YEAR', 'CA_RCVDT_MONTH', 'CA_DEPTCD', 'BR_STATECD', 'action', 'refsumberaduan', 'senaraisumberaduan'));
        } elseif ($request->get('pdf') == '1') {
            $pdf = PDF::loadView('laporan.bpa.sumberterimabulan_pdf', compact('CA_RCVDT_YEAR', 'CA_RCVDT_MONTH', 'CA_DEPTCD', 'BR_STATECD', 'action', 'refsumberaduan', 'senaraisumberaduan'), [], ['default_font_size' => 7, 'title' => date('Ymd_His')]);
            return $pdf->stream('bpa' . date("Ymd_His") . '.pdf');
        } else {
            return view('laporan.bpa.sumberterimabulan',
                compact('CA_RCVDT_YEAR', 'CA_RCVDT_MONTH', 'CA_DEPTCD', 'BR_STATECD', 'action', 'refsumberaduan', 'senaraisumberaduan'));
        }
    }

    public function SumberPenerimaanKumulatif(Request $request)
    {
        $CA_RCVDT_YEAR = date('Y', strtotime(Carbon::now()));
        $CA_RCVDT_MONTH_FROM = '';
        $CA_RCVDT_MONTH_TO = '12';
        $CA_DEPTCD = '';
        $BR_STATECD = '';
        $action = '';
        if (Input::has('CA_RCVDT_YEAR')) {
            $CA_RCVDT_YEAR = Input::get('CA_RCVDT_YEAR');
        }
        if (Input::has('CA_RCVDT_MONTH_FROM')) {
            $CA_RCVDT_MONTH_FROM = Input::get('CA_RCVDT_MONTH_FROM');
        }
        if (Input::has('CA_RCVDT_MONTH_TO')) {
            $CA_RCVDT_MONTH_TO = Input::get('CA_RCVDT_MONTH_TO');
        }
        if (Input::has('CA_DEPTCD')) {
            $CA_DEPTCD = Input::get('CA_DEPTCD');
        }
        if ($request->BR_STATECD) {
            $BR_STATECD = $request->BR_STATECD;
        }
        if (Input::has('action')) {
            $action = Input::get('action');
        }
        $refsumberaduan = DB::table('sys_ref')
            ->where('cat', '259')
            ->get();
        if ($request->get('excel') == '1') {
            return view('laporan.bpa.sumberterimakumulatif_excel',  compact('CA_RCVDT_YEAR', 'CA_RCVDT_MONTH_FROM', 'CA_RCVDT_MONTH_TO', 'CA_DEPTCD', 'BR_STATECD', 'action', 'refsumberaduan','request'));
        } elseif ($request->get('pdf') == '1') {
            $pdf = PDF::loadView('laporan.bpa.sumberterimakumulatif_pdf',  compact('CA_RCVDT_YEAR', 'CA_RCVDT_MONTH_FROM', 'CA_RCVDT_MONTH_TO', 'CA_DEPTCD', 'BR_STATECD', 'action', 'refsumberaduan','request'), [], ['default_font_size' => 7, 'title' => date('Ymd_His')]);
            return $pdf->stream('bpa' . date("Ymd_His") . '.pdf');
        } else {
        return view('laporan.bpa.sumberterimakumulatif',
            compact('CA_RCVDT_YEAR', 'CA_RCVDT_MONTH_FROM', 'CA_RCVDT_MONTH_TO', 'CA_DEPTCD', 'BR_STATECD', 'action', 'refsumberaduan','request'));
    
        }
        }

    /**
     * Tempoh penyelsaian aduan (Bulan)
     * @param Request $request
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function TempohPenyelesaianBulan(Request $request)
    {
        $data = $request->all();
        $CA_RCVDT_YEAR = isset($data['CA_RCVDT_YEAR']) ? $data['CA_RCVDT_YEAR'] : date('Y');
        $CA_RCVDT_MONTH_START = isset($data['CA_RCVDT_MONTH']) ? $data['CA_RCVDT_MONTH'] : 1;
        $CA_RCVDT_MONTH_END = isset($data['CA_RCVDT_MONTH']) ? $data['CA_RCVDT_MONTH'] : 12;
        $CA_RCVDT_MONTH = isset($data['CA_RCVDT_MONTH']) ? $data['CA_RCVDT_MONTH'] : '';
        $BR_STATECD = isset($data['BR_STATECD']) ? $data['BR_STATECD'] : '';
        $CA_DEPTCD = isset($data['CA_DEPTCD']) ? $data['CA_DEPTCD'] : 0;
        $action = isset($data['action']) ? $data['action'] : '';
        $dataCount = [];
        $dataPctg = ['<1' => 0, '1' => 0, '2-5' => 0, '6-10' => 0, '11-15' => 0, '16-21' => 0, '22-31' => 0, '32-60' => 0, '>60' => 0, 'total' => 0,];
        $CA_INVSTS = [3,4,5,6,7,8,9];
        
        // get all data
        $senaraiAgihanNegeriWithDateDiff = TerimaSelesaiAduan::SenaraiAgihanWithDateDiff($CA_RCVDT_YEAR, $CA_RCVDT_MONTH_START, $CA_RCVDT_MONTH_END, $BR_STATECD, $CA_DEPTCD, 0, $CA_INVSTS);

        $dataCount = $this->populate($senaraiAgihanNegeriWithDateDiff);

        // count percentage
        foreach ($dataPctg as $key => $pctg) {
            $dataPctg[$key] = $dataCount['total'] != 0 ? number_format(($dataCount[$key] / $dataCount['total'] * 100), 2, '.', '') : 0;
        }
          if ($request->get('excel') == '1') {
            return view('laporan.bpa.tempohpenyelesaianbulan_excel', compact('CA_RCVDT_YEAR', 'CA_RCVDT_MONTH', 'BR_STATECD', 'CA_DEPTCD', 'action', 'dataCount', 'dataPctg','request'));
        } elseif ($request->get('pdf') == '1') {
            $pdf = PDF::loadView('laporan.bpa.tempohpenyelesaianbulan_pdf', compact('CA_RCVDT_YEAR', 'CA_RCVDT_MONTH', 'BR_STATECD', 'CA_DEPTCD', 'action', 'dataCount', 'dataPctg','request'), [], ['default_font_size' => 7, 'title' => date('Ymd_His')]);
            return $pdf->stream('bpa' . date("Ymd_His") . '.pdf');
        } else {
        return view('laporan.bpa.tempohpenyelesaianbulan',
            compact('CA_RCVDT_YEAR', 'CA_RCVDT_MONTH', 'BR_STATECD', 'CA_DEPTCD', 'action', 'dataCount', 'dataPctg','request'));
        }
    }

    /**
     * Tempoh penyelesaian aduan (kumulatif)
     * @param Request $request
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function TempohPenyelesaianKumulatif(Request $request)
    {
        $data = $request->all();
        $CA_RCVDT_YEAR = isset($data['CA_RCVDT_YEAR']) ? $data['CA_RCVDT_YEAR'] : date('Y');
        $CA_RCVDT_MONTH_FROM = isset($data['CA_RCVDT_MONTH_FROM']) ? $data['CA_RCVDT_MONTH_FROM'] : date('m');
        $CA_RCVDT_MONTH_TO = isset($data['CA_RCVDT_MONTH_TO']) ? $data['CA_RCVDT_MONTH_TO'] : 12;
        $BR_STATECD = isset($data['BR_STATECD']) ? $data['BR_STATECD'] : '';
        $CA_DEPTCD = isset($data['CA_DEPTCD']) ? $data['CA_DEPTCD'] : 0;
        $action = isset($data['action']) ? $data['action'] : '';
        $dataCount = [];
        $dataPctg = ['<1' => 0, '1' => 0, '2-5' => 0, '6-10' => 0, '11-15' => 0, '16-21' => 0, '22-31' => 0, '32-60' => 0, '>60' => 0, 'total' => 0,];
        $CA_INVSTS = [3,4,5,6,7,8,9];
        
        // get all data
        $senaraiAgihanNegeriWithDateDiff = TerimaSelesaiAduan::SenaraiAgihanWithDateDiff($CA_RCVDT_YEAR, $CA_RCVDT_MONTH_FROM, $CA_RCVDT_MONTH_TO, $BR_STATECD, $CA_DEPTCD, 0, $CA_INVSTS);

        $dataCount = $this->populate($senaraiAgihanNegeriWithDateDiff);

        // count percentage
        foreach ($dataPctg as $key => $pctg) {
            $dataPctg[$key] = $dataCount['total'] != 0 ? number_format(($dataCount[$key] / $dataCount['total'] * 100), 2, '.', '') : 0;
        }
        if ($request->get('excel') == '1') {
            return view('laporan.bpa.tempohpenyelesaiankumulatif_excel',   compact('CA_RCVDT_YEAR', 'CA_RCVDT_MONTH_FROM', 'CA_RCVDT_MONTH_TO', 'CA_DEPTCD', 'BR_STATECD', 'action', 'dataCount', 'dataPctg','request'));
        } elseif ($request->get('pdf') == '1') {
            $pdf = PDF::loadView('laporan.bpa.tempohpenyelesaiankumulatif_pdf',   compact('CA_RCVDT_YEAR', 'CA_RCVDT_MONTH_FROM', 'CA_RCVDT_MONTH_TO', 'CA_DEPTCD', 'BR_STATECD', 'action', 'dataCount', 'dataPctg','request'), [], ['default_font_size' => 7, 'title' => date('Ymd_His')]);
            return $pdf->stream('bpa' . date("Ymd_His") . '.pdf');
        } else {
        return view('laporan.bpa.tempohpenyelesaiankumulatif',
            compact('CA_RCVDT_YEAR', 'CA_RCVDT_MONTH_FROM', 'CA_RCVDT_MONTH_TO', 'CA_DEPTCD', 'BR_STATECD', 'action', 'dataCount', 'dataPctg','request'));
        }
    }

    /**
     * populate data from raw to array
     * @param $dataCountRaw
     * @return array
     */
    public function populate($dataCountRaw)
    {
        $dataCount = ['<1' => 0, '1' => 0, '2-5' => 0, '6-10' => 0, '11-15' => 0, '16-21' => 0, '22-31' => 0, '32-60' => 0, '>60' => 0, 'total' => 0,];

        foreach ($dataCountRaw as $v) {
            switch (true) {
                case $v->date_diff < 1:
                    $dataCount['<1'] += $v->total_cases;
                    break;
                case $v->date_diff == 1:
                    $dataCount['1'] += $v->total_cases;
                    break;
                case ($v->date_diff > 1 && $v->date_diff < 6):
                    $dataCount['2-5'] += $v->total_cases;
                    break;
                case ($v->date_diff > 5 && $v->date_diff < 11):
                    $dataCount['6-10'] += $v->total_cases;
                    break;
                case ($v->date_diff > 10 && $v->date_diff < 16):
                    $dataCount['11-15'] += $v->total_cases;
                    break;
                case ($v->date_diff > 15 && $v->date_diff < 21):
                    $dataCount['16-21'] += $v->total_cases;
                    break;
                case ($v->date_diff > 20 && $v->date_diff < 31):
                    $dataCount['22-31'] += $v->total_cases;
                    break;
                case ($v->date_diff > 30 && $v->date_diff < 61):
                    $dataCount['32-60'] += $v->total_cases;
                    break;
                case $v->date_diff > 60:
                    $dataCount['>60'] += $v->total_cases;
                    break;
                default:
                    # code...
                    break;
            }
            $dataCount['total'] += $v->total_cases;
        }

        return $dataCount;
    }
}
