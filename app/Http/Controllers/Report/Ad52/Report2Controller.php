<?php

namespace App\Http\Controllers\Report\Ad52;

use App\Aduan\Carian;
use App\Http\Controllers\Controller;
use App\Libraries\DateTimeLibrary;
use App\Models\Cases\CaseInfo;
use App\Ref;
use App\Repositories\Ref\RefRepository;
use App\Repositories\Report\Ad52Repository;
use Carbon\Carbon;
use DB;
use Excel;
use Illuminate\Http\Request;
use PDF;
use View;

/**
 * AD52 Penyelesaian
 * Laporan Analisa Data
 *
 * Class Report2Controller
 * @package App\Http\Controllers\Report\Ad52
 */
class Report2Controller extends Controller
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
        $input = $request->all();

        $data['issearch'] = count($input) > 0 ? true : false;

        $data['title'] = 'AD52 Laporan Analisa Data';

        $data['datestart'] = $input['datestart'] ?? Carbon::today()->format('d-m-Y');
        $data['dateend'] = $input['dateend'] ?? Carbon::today()->format('d-m-Y');

        $data['states'] = RefRepository::getList($cat = '17', $sort = 'sort', $lang = 'ms');
        $data['state'] = $input['state'] ?? null;

        $data['generate'] = $input['generate'] ?? null;

        if ($data['issearch']) {
            $data['datestartvalidate'] = DateTimeLibrary::validate($data['datestart']);
            $data['dateendvalidate'] = DateTimeLibrary::validate($data['dateend']);

            $data['datestartvalid'] = Carbon::parse($data['datestartvalidate']);
            $data['dateendvalid'] = Carbon::parse($data['dateendvalidate']);

            $data['datestartstring'] = $data['datestartvalid']->format('d-m-Y');
            $data['dateendstring'] = $data['dateendvalid']->format('d-m-Y');

            $data['datetimestart'] = $data['datestartvalid']->startOfDay()->toDateTimeString();
            $data['datetimeend'] = $data['dateendvalid']->endOfDay()->toDateTimeString();

            $data['datetext'] = 'Tarikh Penerimaan Aduan : Dari ' . ($data['datestartstring'] ?? '')
                . ' Hingga ' . ($data['dateendstring'] ?? '');

            $data['statetext'] = 'Negeri : ' . ($data['states'][$data['state']] ?? '');
            $data['statefilter'] = $data['states']->has($data['state']);

            $data['acttemplates'] = RefRepository::getList($cat = '713', $sort = 'sort', $lang = 'ms');
            $data['countacttemplate'] = count($data['acttemplates']);
            $data['countacttemplatecolumn'] = $data['countacttemplate'] > 0 ? $data['countacttemplate'] + 1 : 1;
            foreach ($data['acttemplates'] as $key => $value) {
                $data['templates'][$key] = 0;
            }
            $data['templateRows'] = ['achieveObjective', 'notAchieveObjective',
                'totalComplaintTakenAction', 'inProgress', 'total',
                'average', 'mode', 'median', 'min', 'max'
            ];
            foreach ($data['templateRows'] as $key => $value) {
                $data['count'][$value] = $data['templates'];
            }

            $data['caseinfos'] = self::query($data);
            foreach ($data['caseinfos'] as $key => $caseinfo) {
                $caseact = Ad52Repository::getCaseAct($caseinfo->CA_CASEID ?? '');
                $caseinfo->act = $caseact ?? '';
                $casedetail = Ad52Repository::getCaseDetailAnswer($caseinfo->CA_CASEID ?? '');
                $caseinfo->reasonduration = $casedetail->CD_REASON_DURATION ?? 0;
                foreach ($data['acttemplates'] as $keyActTemplate => $actTemplate) {
                    if ($keyActTemplate === $caseinfo->act) {
                        switch (true) {
                            case $caseinfo->reasonduration <= 21:
                                $data['count']['achieveObjective'][$keyActTemplate]++;
                                break;
                            case $caseinfo->reasonduration > 21:
                                $data['count']['notAchieveObjective'][$keyActTemplate]++;
                                break;
                            default:
                                break;
                        }
                        $data['count']['totalComplaintTakenAction'][$keyActTemplate]++;
                        $data['count']['total'][$keyActTemplate]++;
                        break;
                    }
                }
            }

            foreach ($data['acttemplates'] as $keyActTemplate => $actTemplate) {
                $filtered = $data['caseinfos']->where('act', $keyActTemplate);
                if ($filtered->isNotEmpty()) {
                    $data['count']['average'][$keyActTemplate] = number_format($filtered->avg('reasonduration'));
                    $data['count']['mode'][$keyActTemplate] = $filtered->mode('reasonduration');
                    $data['count']['median'][$keyActTemplate] = number_format($filtered->median('reasonduration'));
                    $data['count']['min'][$keyActTemplate] = $filtered->min('reasonduration');
                    $data['count']['max'][$keyActTemplate] = $filtered->max('reasonduration');
                }
            }
            $data['appname'] = config('app.name', 'eAduan 2.0');
            $data['filename'] = $data['appname'] . ' ' . $data['title'] . date(" YmdHis");
            $data['urldetail'] = '/report/ad52/report2detail' . '?' . explode('?', url()->full())[1] ?? '';
        }

        switch ($data['generate']) {
            case 'excel':
                if (View::exists('report.ad52.report2.excel')) {
                    return Excel::create($data['filename'] ?? $data['generate'], function ($excel) use ($request, $data) {
                        $excel->sheet('Laporan', function ($sheet) use ($request, $data) {
                            $sheet->loadView('report.ad52.report2.excel')
                                ->with([
                                    'request' => $request,
                                    'data' => $data,
                                ]);
                        });
                    })->export('xlsx');
                } else {
                    abort(404);
                }
                break;
            case 'pdf':
                if (View::exists('report.ad52.report2.pdf')) {
                    $pdf = PDF::loadView('report.ad52.report2.pdf', compact('request', 'data'));
                    return $pdf->download($data['filename'] . '.pdf');
                } else {
                    abort(404);
                }
                break;
            default:
                if (View::exists('report.ad52.report2.index')) {
                    return view('report.ad52.report2.index', compact('request', 'data'));
                } else {
                    abort(404);
                }
                break;
        }
    }

    /**
     * query for get data
     *
     * @param array $data
     */
    public function query($data)
    {
        $query = CaseInfo::join('sys_brn', 'case_info.CA_BRNCD', '=', 'sys_brn.BR_BRNCD')
            ->join('sys_ref', 'sys_brn.BR_STATECD', '=', 'sys_ref.code')
            ->select(
                'case_info.CA_CASEID',
                'case_info.CA_FILEREF',
                'case_info.CA_DEPTCD',
                'case_info.CA_RCVDT',
                'case_info.CA_FA_DURATION',
                'case_info.CA_MAGNCD',
                'case_info.CA_COMPLETEDT',
                'case_info.CA_SSP',
                'sys_brn.BR_STATECD',
                'sys_ref.descr'
            )
            ->whereBetween('case_info.CA_RCVDT', [$data['datetimestart'], $data['datetimeend']])
            ->where([
                ['case_info.CA_CASEID', '<>', null],
                ['case_info.CA_RCVTYP', '<>', null],
                ['case_info.CA_RCVTYP', '<>', ''],
                ['case_info.CA_CMPLCAT', '<>', ''],
                ['case_info.CA_INVSTS', '!=', '10'],
                ['case_info.CA_SSP', '=', 'YES'],
                ['sys_ref.cat', '=', '17']
            ])
            ->whereNotNull('case_info.CA_RCVDT');
            if (isset($data['statefilter']) && $data['statefilter'] && isset($data['state']) && !empty($data['state'])) {
                $query = $query->where('sys_brn.BR_STATECD', $data['state']);
            }
            $query = $query->get();
        return $query;
    }
}
