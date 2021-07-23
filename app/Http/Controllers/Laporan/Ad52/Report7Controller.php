<?php

namespace App\Http\Controllers\Laporan\Ad52;

use App\Http\Controllers\Controller;
use App\Models\Cases\CaseInfo;
use App\Ref;
use Carbon\Carbon;
use DB;
use Excel;
use Illuminate\Http\Request;
use PDF;

/**
 * AD52 Penyelesaian
 *
 * Laporan Akta
 */

class Report7Controller extends Controller
{
    /**
     * Instantiate a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('auth');
    }

    /**
     * Display a listing of the resource.
     *
     * @param Request $request
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function index(Request $request)
    {
        $title = 'AD52 Laporan Akta';
        $input = $request->all();
        $isSearch = count($input) > 0 ? true : false;
        $date_start = isset($input['date_start'])
            ? Carbon::parse($input['date_start'])->startOfDay()->toDateTimeString()
            : Carbon::now()->startOfDay()->toDateTimeString();
        $date_end = isset($input['date_end'])
            ? Carbon::parse($input['date_end'])->endOfDay()->toDateTimeString()
            : Carbon::now()->endOfDay()->toDateTimeString();
        $gen = isset($input['gen']) ? $input['gen'] : 'web';
        $dataTemplate = ['table1' => 0, 'table2' => 0];
        $actTemplates = Ref::where(['cat' => '713', 'status' => '1'])
            ->orderBy('sort', 'asc')->pluck('descr', 'code')->toArray();
        foreach ($actTemplates as $key => $value) {
            $dataCount[$key] = $dataTemplate;
        }
        $dataCount['total'] = $dataTemplate;
        if ($isSearch) {
            $caseInfos = $this->query($date_start, $date_end);
            foreach ($caseInfos as $v) {
                switch (true) {
                    case $v->case_indicator == 'YA':
                        $dataCount['total']['table1'] += $v->countcase;
                        $dataCount['total']['table2'] += $v->countcase;
                        break;
                    default:
                        break;
                }
            }
            switch ($gen) {
                case 'xls':
                    return view('laporan.ad52.report7.excelxls',
                        compact('request', 'title', 'date_start', 'date_end', 'gen', 'actTemplates', 'dataCount')
                    );
                    break;
                case 'pdf':
                    $pdf = PDF::loadView('laporan.ad52.report7.pdf',
                        compact('request', 'title', 'date_start', 'date_end', 'gen', 'actTemplates', 'dataCount')
                    );
                    return $pdf->download(env('APP_NAME', 'eAduan 2.0') . ' ' . $title . date(" YmdHis") . '.pdf');
                    break;
                case 'web':
                default:
                    return view('laporan.ad52.report7.index',
                        compact('request', 'isSearch', 'title', 'date_start', 'date_end', 'gen', 'actTemplates', 'dataCount')
                    );
                    break;
            }
        }
        return view('laporan.ad52.report7.index',
            compact('request', 'isSearch', 'title', 'date_start', 'date_end', 'gen', 'actTemplates', 'dataCount')
        );
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    // public function show($id)
    // {
        
    // }

    /**
     * query for get data
     *
     * @param string $date_start
     * @param string $date_end
     */
    public function query(string $date_start, string $date_end)
    {
        return CaseInfo::join('sys_brn', 'case_info.CA_BRNCD', '=', 'sys_brn.BR_BRNCD')
            ->join('sys_ref', 'sys_brn.BR_STATECD', '=', 'sys_ref.code')
            ->select(DB::raw("
                case_info.CA_CASEID AS caseid,
                case_info.CA_FILEREF,
                case_info.CA_DEPTCD,
                case_info.CA_RCVDT,
                sys_brn.BR_STATECD,
                sys_ref.descr,
                CASE
                    WHEN case_info.CA_SSP = 'YES' THEN 'YA'
                    ELSE 'TIDAK'
                END AS case_indicator,
                1 AS countcase
            "))
            ->whereBetween('case_info.CA_RCVDT', [$date_start, $date_end])
            ->where([
                ['case_info.CA_RCVDT', '<>', null],
                ['case_info.CA_CASEID', '<>', null],
                ['case_info.CA_RCVTYP', '<>', null],
                ['case_info.CA_RCVTYP', '<>', ''],
                ['case_info.CA_CMPLCAT', '<>', ''],
                ['case_info.CA_INVSTS', '!=', '10'],
                ['sys_ref.cat', '=', '17']
            ])
            ->get();
    }
}
