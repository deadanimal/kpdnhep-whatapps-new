<?php

namespace App\Http\Controllers\Laporan\Integrity;

use App\Http\Controllers\Controller;
use App\Integriti\IntegritiAdmin;
use Carbon\Carbon;
use DB;
use Excel;
use Illuminate\Http\Request;
use PDF;

class ReportIntegrityBranchController extends Controller
{
    /**
     * Laporan Aduan Integriti Mengikut Bahagian / Cawangan / Agensi
     */
    public function index(Request $request)
    {
        $data = $request->all();
        $vars['title'] = 'Laporan Aduan Integriti Mengikut Bahagian / Cawangan / Agensi';
        $vars['titleshort'] = 'Laporan Integriti Bahagian Cawangan Agensi';
        $vars['gen'] = isset($data['gen']) ? $data['gen'] : 'web';
        $vars['is_search'] = count($data) > 0 ? true : false;
        $vars['datestart'] = isset($data['datestart']) 
            ? Carbon::parse($data['datestart'])->startOfDay() 
            : Carbon::now()->startOfDay();
        $vars['dateend'] = isset($data['dateend']) 
            ? Carbon::parse($data['dateend'])->endOfDay() 
            : Carbon::now()->endOfDay();
        $vars['location'] = isset($data['location']) ? $data['location'] : '0';
        $vars['locations'] = [
            'BRN' => 'Bahagian / Cawangan KPDNHEP',
            'AGN' => 'Agensi KPDNHEP',
        ];
        $vars['locationname'] = array_has($vars['locations'], $vars['location'])
            ? $vars['locations'][$vars['location']]
            : '';
        $vars['dataCountTotal'] = 0;
        if ($vars['is_search']) {
            if ($vars['location'] == 'BRN') {
                $vars['query'] = IntegritiAdmin::
                    leftJoin('sys_brn', 'sys_brn.BR_BRNCD', '=', 'integriti_case_info.IN_BRNCD')
                    ->leftJoin('sys_ref', 'sys_ref.code', '=', 'sys_brn.BR_STATECD')
                    ->select(
                        // 'sys_brn.BR_STATECD',
                        'sys_brn.BR_BRNNM as branch_agency_name',
                        DB::raw('COUNT(*) as total')
                    )
                    ->where([
                        ['integriti_case_info.IN_CASEID','<>',null],
                        ['integriti_case_info.IN_INVSTS','!=','010'],
                        ['sys_ref.cat','=','17'],
                        ['integriti_case_info.IN_AGAINSTLOCATION','=',$vars['location']]
                    ])
                    ->whereBetween('integriti_case_info.IN_RCVDT', [$vars['datestart'], $vars['dateend']])
                    ->groupBy('integriti_case_info.IN_BRNCD', 'sys_brn.BR_BRNCD', 'sys_brn.BR_BRNNM')
                    // ->orderBy('sys_brn.BR_STATECD')
                    ->get()
                    ;
                foreach ($vars['query'] as $value) {
                    $vars['dataCountTotal'] += $value->total;
                }
            }
            else if ($vars['location'] == 'AGN') {
                $vars['query'] = IntegritiAdmin::
                    leftJoin('sys_min', 'sys_min.MI_MINCD', '=', 'integriti_case_info.IN_AGENCYCD')
                    ->select(
                        'sys_min.MI_DESC as branch_agency_name',
                        DB::raw('COUNT(*) as total')
                    )
                    ->where([
                        ['integriti_case_info.IN_CASEID','<>',null],
                        ['integriti_case_info.IN_INVSTS','!=','010'],
                        // ['sys_ref.cat','=','17'],
                        ['integriti_case_info.IN_AGAINSTLOCATION','=',$vars['location']]
                    ])
                    ->whereBetween('integriti_case_info.IN_RCVDT', [$vars['datestart'], $vars['dateend']])
                    ->groupBy('integriti_case_info.IN_AGENCYCD', 'sys_min.MI_MINCD', 'sys_min.MI_DESC')
                    ->get()
                    ;
                foreach ($vars['query'] as $value) {
                    $vars['dataCountTotal'] += $value->total;
                }
            }
            else {
                $vars['query'] = [];
            }
        }
        else
        {
            $vars['query'] = [];
        }
        switch ($vars['gen']) {
            case 'pdf':
                $pdf = PDF::loadView(
                    'laporan.integriti.branch.pdf', compact('request','data','vars')
                );
                return $pdf->download($vars['titleshort'] . date(" YmdHis") . '.pdf');
                break;
            case 'xls':
                return view(
                    'laporan.integriti.branch.excelxls', 
                    compact('request','data','vars')
                );
                break;
            case 'web':
            default:
                return view(
                    'laporan.integriti.branch.index', 
                    compact('request','data','vars')
                );
                break;
        }
    }

    /**
     * Constructor.
     */
    public function __construct()
    {
        $this->middleware('auth');
    }
}
