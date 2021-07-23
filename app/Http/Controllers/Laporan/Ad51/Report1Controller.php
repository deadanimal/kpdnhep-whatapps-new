<?php

namespace App\Http\Controllers\Laporan\Ad51;

use App\Http\Controllers\Controller;
use App\Models\Cases\CaseInfo;
use App\Ref;
use Carbon\Carbon;
use DB;
use Excel;
use Illuminate\Http\Request;
use PDF;

/**
 * AD51 Tindakan Pertama
 *
 * Laporan Pengagihan Aduan Mengikut Negeri
 */

class Report1Controller extends Controller
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
        $title = 'AD51 Laporan Pengagihan Aduan Mengikut Negeri';
        $input = $request->all();
        $isSearch = count($input) > 0 ? true : false;
        $date_start = isset($input['date_start'])
            ? Carbon::parse($input['date_start'])->startOfDay()->toDateTimeString()
            : Carbon::now()->startOfDay()->toDateTimeString();
        $date_end = isset($input['date_end'])
            ? Carbon::parse($input['date_end'])->endOfDay()->toDateTimeString()
            : Carbon::now()->endOfDay()->toDateTimeString();
        $gen = isset($input['gen']) ? $input['gen'] : 'web';
        $dataTemplate = ['<=3' => 0, '>3' => 0, 'total' => 0];
        $dataCount['total'] = $dataTemplate;
        $states = Ref::where('cat', '17')->pluck('descr', 'code')->toArray();
        foreach ($states as $key => $state) {
            $dataCount[$key] = $dataTemplate;
        }
        if ($isSearch) {
            $caseInfos = $this->query($date_start, $date_end);
            foreach ($caseInfos as $v) {
                switch (true) {
                    case $v->firstactionduration <= 3:
                        $dataCount[$v->BR_STATECD]['<=3'] += $v->countcase;
                        $dataCount['total']['<=3'] += $v->countcase;
                        break;
                    case $v->firstactionduration > 3:
                        $dataCount[$v->BR_STATECD]['>3'] += $v->countcase;
                        $dataCount['total']['>3'] += $v->countcase;
                        break;
                    default:
                        break;
                }
                $dataCount[$v->BR_STATECD]['total'] += $v->countcase;
                $dataCount['total']['total'] += $v->countcase;
            }
            foreach ($dataCount as $key => $datum) {
                if($dataCount[$key]['total'] > 0) {
                    $dataCount[$key]['pct'] = number_format(($dataCount[$key]['>3'] / $dataCount[$key]['total'] * 100), 2);
                } else {
                    $dataCount[$key]['pct'] = number_format(0, 2);
                }
            }
            switch ($gen) {
                case 'xls':
                    return view('laporan.ad51.report1.excelxls',
                        compact('request', 'title', 'date_start', 'date_end', 'gen', 'dataCount', 'states')
                    );
                    break;
                case 'pdf':
                    $pdf = PDF::loadView('laporan.ad51.report1.pdf',
                        compact('request', 'title', 'date_start', 'date_end', 'gen', 'dataCount', 'states')
                    );
                    return $pdf->download(env('APP_NAME', 'eAduan 2.0') . ' ' . $title . date(" YmdHis") . '.pdf');
                    break;
                case 'web':
                default:
                    return view('laporan.ad51.report1.index',
                        compact('request', 'isSearch', 'title', 'date_start', 'date_end', 'gen', 'dataCount', 'states')
                    );
                    break;
            }
        }
        return view('laporan.ad51.report1.index',
            compact('request', 'isSearch', 'title', 'date_start', 'date_end', 'gen', 'dataCount', 'states')
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
        return CaseInfo::join('sys_brn', 'sys_brn.BR_BRNCD', '=', 'case_info.CA_BRNCD')
            ->select(DB::raw("
                case_info.CA_DEPTCD,
                sys_brn.BR_STATECD,
                case_info.CA_RCVDT,
                case_info.CA_CASEID AS caseid,
                case_info.CA_FA_DURATION,
                (
                    SELECT
                    a.cd_invsts
                    FROM case_dtl a
                    JOIN case_dtl b ON (
                        a.cd_caseid = b.cd_caseid
                        AND b.cd_invsts = '1'
                    )
                    JOIN case_info ON a.cd_caseid = case_info.CA_CASEID
                    WHERE a.cd_invsts IS NOT NULL
                    AND a.cd_invsts NOT IN ('10', '1')
                    AND case_info.CA_CASEID = caseid
                    ORDER BY
                    a.cd_credt
                    LIMIT 1
                ) AS firstactionstatus,
                (
                    SELECT
                    a.CD_REASON_DURATION
                    FROM case_dtl a
                    JOIN case_dtl b ON (
                        a.cd_caseid = b.cd_caseid
                        AND b.cd_invsts = '1'
                    )
                    JOIN case_info ON a.cd_caseid = case_info.CA_CASEID
                    WHERE a.cd_invsts IS NOT NULL
                    AND a.cd_invsts NOT IN ('10', '1')
                    AND case_info.CA_CASEID = caseid
                    ORDER BY
                    a.cd_credt
                    LIMIT 1
                ) AS firstactionduration,
                1 AS countcase
            "))
            ->where([
                ['case_info.CA_RCVDT', '<>', null],
                ['case_info.CA_CASEID', '<>', null],
                ['case_info.CA_RCVTYP', '<>', null],
                ['case_info.CA_RCVTYP', '<>', ''],
                ['case_info.CA_CMPLCAT', '<>', ''],
                ['case_info.CA_INVSTS', '!=', '10']
            ])
            ->whereBetween('case_info.CA_RCVDT', [$date_start, $date_end])
            // ->havingRaw('firstactionstatus IS NOT NULL')
            ->get();
    }
}
