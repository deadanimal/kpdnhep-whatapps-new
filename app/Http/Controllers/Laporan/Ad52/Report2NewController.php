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
 * Laporan Analisa Data
 */

class Report2NewController extends Controller
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
        $title = 'AD52 Laporan Analisa Data';
        $appName = config('app.name', 'eAduan 2.0');
        $fileName = $appName . ' ' . $title . date(" YmdHis");
        $input = $request->all();
        $isSearch = count($input) > 0 ? true : false;
        $date_start = isset($input['date_start'])
            ? Carbon::parse($input['date_start'])->startOfDay()->toDateTimeString()
            : Carbon::now()->startOfDay()->toDateTimeString();
        $date_end = isset($input['date_end'])
            ? Carbon::parse($input['date_end'])->endOfDay()->toDateTimeString()
            : Carbon::now()->endOfDay()->toDateTimeString();
        $gen = isset($input['gen']) ? $input['gen'] : 'web';
        $actTemplates = Ref::where(['cat' => '713', 'status' => '1'])
            ->orderBy('sort', 'asc')->pluck('descr', 'code')->toArray();
        $countActTemplate = count($actTemplates);
        foreach ($actTemplates as $key => $value) {
            $dataTemplates[$key] = 0;
        }
        $dataTemplateRows = ['achieveObjective', 'notAchieveObjective',
            'totalComplaintTakenAction', 'inProgress', 'total',
            'average', 'mode', 'median', 'min', 'max'
        ];
        foreach ($dataTemplateRows as $value) {
            $dataCount[$value] = $dataTemplates;
        }
        if ($isSearch) {
            switch ($gen) {
                case 'xls':
                    return view('laporan.ad52.report2new.excelxls', 
                        compact('request', 'title', 'date_start', 'date_end', 'gen', 'dataCount', 'dataTemplates', 'actTemplates', 'countActTemplate', 'fileName', 'appName')
                    );
                    break;
                case 'pdf':
                    $pdf = PDF::loadView('laporan.ad52.report2new.pdf',
                        compact('request', 'title', 'date_start', 'date_end', 'gen', 'dataCount', 'dataTemplates', 'actTemplates', 'countActTemplate', 'fileName', 'appName')
                    );
                    return $pdf->download($fileName . '.pdf');
                    break;
                case 'web':
                default:
                    return view('laporan.ad52.report2new.index',
                        compact('request', 'isSearch', 'title', 'date_start', 'date_end', 'gen', 'dataCount', 'dataTemplates', 'actTemplates', 'countActTemplate', 'fileName', 'appName')
                    );
                    break;
            }
        }
        return view('laporan.ad52.report2new.index',
            compact('request', 'isSearch', 'title', 'date_start', 'date_end', 'gen', 'dataCount', 'dataTemplates', 'actTemplates', 'countActTemplate', 'fileName', 'appName')
        );
    }

    /**
     * query for get data
     *
     * @param string $date_start
     * @param string $date_end
     */
    // public function query(string $date_start, string $date_end)
    // {

    // }
}
