<?php

namespace App\Http\Controllers\Laporan\Ad52;

use App\Aduan\Carian;
use App\Http\Controllers\Controller;
use App\Models\Cases\CaseInfo;
use App\Ref;
use Carbon\Carbon;
use DB;
use Illuminate\Http\Request;
use PDF;
use Yajra\DataTables\Facades\DataTables;

/**
 * AD52 Penyelesaian
 *
 * Senarai Aduan
 */

class ListController extends Controller
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
        $title = 'AD52 Laporan Fail Aduan';
        $input = $request->all();
        $isSearch = count($input) > 0 ? true : false;
        $date_start = isset($input['date_start']) ? $input['date_start'] : date('Y') . '-01-01';
        $date_end = isset($input['date_end']) ? $input['date_end'] : date('Y-m-d');
        $gen = isset($input['gen']) ? $input['gen'] : 'web';
        $states = Ref::where(['cat' => '17', 'status' => '1'])->pluck('descr', 'code');
        $state = isset($input['state']) ? $input['state'] : '';
        if ($isSearch) {
            switch ($gen) {
                case 'xls':
                    return view('laporan.ad52.list.excelxls', 
                        compact('request', 'title', 'date_start', 'date_end', 'gen')
                    );
                    break;
                case 'pdf':
                    $pdf = PDF::loadView('laporan.ad52.list.pdf',
                        compact('request', 'title', 'date_start', 'date_end', 'gen')
                    );
                    return $pdf->download($title . date(" YmdHis") . '.pdf');
                    break;
                case 'web':
                default:
                    return view('laporan.ad52.list.index',
                        compact('request', 'isSearch', 'title', 'date_start', 'date_end', 'gen', 'states')
                    );
                    break;
            }
        }
        return view('laporan.ad52.list.index',
            compact('request', 'isSearch', 'title', 'date_start', 'date_end', 'gen', 'states')
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
     * Get DT data
     *
     * @param Request $request
     */
    public function dt(Request $request)
    {
        $input = $request->all();
        $date_start = isset($input['date_start'])
            ? Carbon::parse($input['date_start'])->startOfDay()->toDateTimeString()
            : Carbon::now()->startOfDay()->toDateTimeString();
        $date_end = isset($input['date_end'])
            ? Carbon::parse($input['date_end'])->endOfDay()->toDateTimeString()
            : Carbon::now()->endOfDay()->toDateTimeString();
        $state = isset($input['state']) ? $input['state'] : '';
        $search_ind = isset($input['search_ind']) ? $input['search_ind'] : '0';
        if ($search_ind) {
            $case_infos = $this->query($date_start, $date_end, $state);
        } else {
            $case_infos = [];
        }
        $datatables = DataTables::of($case_infos)
            ->addIndexColumn();
        return $datatables->make(true);
    }

    /**
     * query for get data
     *
     * @param string $date_start
     * @param string $date_end
     */
    public function query(string $date_start, string $date_end, string $state)
    {
        $query = CaseInfo::join('sys_brn', 'case_info.CA_BRNCD', '=', 'sys_brn.BR_BRNCD')
            ->join('sys_ref', 'sys_brn.BR_STATECD', '=', 'sys_ref.code')
            ->select(DB::raw("
                case_info.CA_CASEID AS caseid,
                case_info.CA_FILEREF,
                case_info.CA_DEPTCD,
                case_info.CA_RCVDT,
                case_info.CA_FA_DURATION,
                sys_brn.BR_STATECD,
                sys_ref.descr,
                CASE
                    WHEN case_info.CA_MAGNCD IS NOT NULL THEN
                        (SELECT MI_DESC FROM sys_min
                        WHERE sys_min.MI_MINCD = case_info.CA_MAGNCD)
                    ELSE 'KPDNHEP'
                END AS department,
                CASE
                    WHEN case_info.CA_SSP = 'YES' THEN
                        (SELECT sys_ref.descr FROM case_act
                        JOIN sys_ref
                        ON (case_act.CT_AKTA = sys_ref.code
                        AND sys_ref.cat = '713')
                        WHERE CT_CASEID = caseid
                        LIMIT 1)
                    ELSE ''
                END AS case_act_descr,
                (
                    SELECT
                    a.CD_INVSTS
                    FROM case_dtl a
                    JOIN case_dtl b ON (
                        a.CD_CASEID = b.CD_CASEID
                        AND b.CD_INVSTS = '1'
                    )
                    JOIN case_info ON a.CD_CASEID = case_info.CA_CASEID
                    WHERE a.CD_INVSTS IS NOT NULL
                    AND a.CD_INVSTS NOT IN ('10', '1')
                    AND case_info.CA_CASEID = caseid
                    ORDER BY
                    a.CD_CREDT
                    LIMIT 1
                ) AS firstactionstatus,
                (
                    SELECT
                    a.CD_REASON_DURATION
                    FROM case_dtl a
                    JOIN case_dtl b ON (
                        a.CD_CASEID = b.CD_CASEID
                        AND b.CD_INVSTS = '1'
                    )
                    JOIN case_info ON a.CD_CASEID = case_info.CA_CASEID
                    WHERE a.CD_INVSTS IS NOT NULL
                    AND a.CD_INVSTS NOT IN ('10', '1')
                    AND case_info.CA_CASEID = caseid
                    ORDER BY
                    a.CD_CREDT
                    LIMIT 1
                ) AS firstactionduration,
                (
                    SELECT
                    case_reason_templates.descr
                    FROM case_dtl a
                    JOIN case_dtl b ON (
                        a.CD_CASEID = b.CD_CASEID
                        AND b.CD_INVSTS = '1'
                    )
                    JOIN case_info ON a.CD_CASEID = case_info.CA_CASEID
                    LEFT JOIN case_reason_templates ON a.CD_REASON = case_reason_templates.code
                    WHERE a.CD_INVSTS IS NOT NULL
                    AND a.CD_INVSTS NOT IN ('10', '1')
                    AND case_info.CA_CASEID = caseid
                    AND case_reason_templates.category = 'AD51'
                    ORDER BY
                    a.CD_CREDT
                    LIMIT 1
                ) AS firstactionreason,
                (
                    SELECT
                    a.CD_CREDT
                    FROM case_dtl a
                    JOIN case_dtl b ON (
                        a.CD_CASEID = b.CD_CASEID
                        AND b.CD_INVSTS = '1'
                    )
                    JOIN case_info ON a.CD_CASEID = case_info.CA_CASEID
                    WHERE a.CD_INVSTS IS NOT NULL
                    AND a.CD_INVSTS NOT IN ('10', '1')
                    AND case_info.CA_CASEID = caseid
                    ORDER BY
                    a.CD_CREDT
                    LIMIT 1
                ) AS firstactiondate,
                CASE
                    WHEN (SELECT
                        a.CD_CREDT
                        FROM
                        case_dtl a
                        JOIN case_dtl b
                            ON (
                                a.CD_CASEID = b.CD_CASEID
                                AND b.CD_INVSTS = '1'
                            )
                        JOIN case_info
                            ON a.CD_CASEID = case_info.CA_CASEID
                        WHERE a.CD_INVSTS IS NOT NULL
                            AND a.CD_INVSTS = '2'
                            AND case_info.CA_CASEID = caseid
                            AND a.CD_CREDT > firstactiondate
                        ORDER BY a.CD_CREDT DESC
                        LIMIT 1
                        ) IS NOT NULL
                    THEN (SELECT
                        a.CD_CREDT
                        FROM
                            case_dtl a
                        JOIN case_dtl b
                            ON (
                                a.CD_CASEID = b.CD_CASEID
                                AND b.CD_INVSTS = '1'
                            )
                        JOIN case_info
                            ON a.CD_CASEID = case_info.CA_CASEID
                        WHERE a.CD_INVSTS IS NOT NULL
                            AND a.CD_INVSTS = '2'
                            AND case_info.CA_CASEID = caseid
                            AND a.CD_CREDT > firstactiondate
                        ORDER BY a.CD_CREDT DESC
                        LIMIT 1)
                    ELSE NULL
                END AS assign_latest_date,
                CASE
                    WHEN (SELECT
                        a.CD_CREDT
                        FROM
                            case_dtl a
                            JOIN case_dtl b
                            ON (
                                a.cd_caseid = b.cd_caseid
                                AND b.cd_invsts = '1'
                            )
                            JOIN case_info
                            ON a.cd_caseid = case_info.CA_CASEID
                        WHERE a.cd_invsts IS NOT NULL
                            AND case_info.CA_CASEID = caseid
                            AND a.cd_credt > assign_latest_date
                            ORDER BY a.cd_credt
                            LIMIT 1
                        ) IS NOT NULL
                    THEN (SELECT
                        a.CD_CREDT
                        FROM
                            case_dtl a
                            JOIN case_dtl b
                            ON (
                                a.cd_caseid = b.cd_caseid
                                AND b.cd_invsts = '1'
                            )
                            JOIN case_info
                            ON a.cd_caseid = case_info.CA_CASEID
                        WHERE a.cd_invsts IS NOT NULL
                            AND case_info.CA_CASEID = caseid
                            AND a.cd_credt > assign_latest_date
                            ORDER BY a.cd_credt
                            LIMIT 1)
                    ELSE NULL
                END AS answer_date,
                CASE
                    WHEN (SELECT
                        a.CD_REASON_DURATION
                        FROM
                            case_dtl a
                            JOIN case_dtl b
                            ON (
                                a.cd_caseid = b.cd_caseid
                                AND b.cd_invsts = '1'
                            )
                        JOIN case_info
                            ON a.cd_caseid = case_info.CA_CASEID
                        WHERE a.cd_invsts IS NOT NULL
                            AND case_info.CA_CASEID = caseid
                            AND a.cd_credt > assign_latest_date
                        ORDER BY a.cd_credt
                        LIMIT 1)
                    THEN
                        (SELECT
                            a.CD_REASON_DURATION
                        FROM
                            case_dtl a
                            JOIN case_dtl b
                            ON (
                                a.cd_caseid = b.cd_caseid
                                AND b.cd_invsts = '1'
                            )
                        JOIN case_info
                            ON a.cd_caseid = case_info.CA_CASEID
                        WHERE a.cd_invsts IS NOT NULL
                            AND case_info.CA_CASEID = caseid
                            AND a.cd_credt > assign_latest_date
                        ORDER BY a.cd_credt
                        LIMIT 1)
                    ELSE NULL
                END AS answer_duration,
                CASE WHEN (SELECT
                        a.CD_CREDT
                    FROM
                        case_dtl a
                        JOIN case_dtl b
                        ON (
                            a.cd_caseid = b.cd_caseid
                            AND b.cd_invsts = '1'
                        )
                        JOIN case_info
                        ON a.cd_caseid = case_info.CA_CASEID
                    WHERE a.cd_invsts IS NOT NULL
                        AND case_info.CA_CASEID = caseid
                        AND a.cd_credt > assign_latest_date
                    ORDER BY a.cd_credt
                    LIMIT 1 ) IS NOT NULL
                THEN (SELECT
                        case_reason_templates.descr
                    FROM
                        case_dtl a
                        JOIN case_dtl b
                        ON (
                            a.cd_caseid = b.cd_caseid
                            AND b.cd_invsts = '1'
                        )
                        JOIN case_info
                        ON a.cd_caseid = case_info.CA_CASEID
                        LEFT JOIN case_reason_templates ON a.CD_REASON = case_reason_templates.code
                    WHERE a.cd_invsts IS NOT NULL
                        AND case_info.CA_CASEID = caseid
                        AND a.cd_credt > assign_latest_date
                        AND case_reason_templates.category = 'AD52'
                    ORDER BY a.cd_credt
                    LIMIT 1)
                ELSE NULL
                END AS answer_reason,
                case_info.CA_COMPLETEDT,
                CASE
                    WHEN case_info.CA_SSP = 'YES' THEN 'YA'
                    ELSE 'TIDAK'
                END AS case_indicator,
                '' AS emptystring
            "))
            ->whereBetween('case_info.CA_RCVDT', [$date_start, $date_end])
            ->where([
                ['case_info.CA_CASEID', '<>', null],
                ['case_info.CA_RCVTYP', '<>', null],
                ['case_info.CA_RCVTYP', '<>', ''],
                ['case_info.CA_CMPLCAT', '<>', ''],
                ['case_info.CA_INVSTS', '!=', '10'],
                ['sys_ref.cat', '=', '17']
            ])
            ->whereNotNull('case_info.CA_RCVDT');
            if(!empty($state)){
                $query = $query->where('sys_brn.BR_STATECD', $state);
            }
            $query = $query->get();
            return $query;
    }
}
