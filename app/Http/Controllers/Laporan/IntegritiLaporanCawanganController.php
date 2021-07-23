<?php

namespace App\Http\Controllers\Laporan;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Ref;
use Carbon\Carbon;
use DB;
use Maatwebsite\Excel\Facades\Excel;
use PDF;

class IntegritiLaporanCawanganController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        $query = DB::table('integriti_case_info')
//                    ->join('sys_ref', 'sys_ref.code', '=', 'integriti_case_info.IN_RCVTYP');
            ->join('sys_brn', 'sys_brn.BR_BRNCD', '=', 'integriti_case_info.IN_BRNCD');
            // $query->rightJoin('sys_ref', function ($rightjoin) use ($Request){
            //     $rightjoin->on('sys_ref.code', '=', 'integriti_case_info.IN_RCVTYP')
            //         ->whereYear('integriti_case_info.IN_RCVDT', $Request->year);
            // });
            $query->select(DB::raw("
                sys_brn.BR_BRNNM,"
//                    COUNT(sys_ref.code) AS Bilangan,
                    .
                    // COUNT(integriti_case_info.IN_RCVTYP) AS Bilangan,
                    "
                    SUM(CASE WHEN MONTH(integriti_case_info.IN_RCVDT) = '01' THEN 1 ELSE 0 END) AS 'JAN',
                    SUM(CASE WHEN MONTH(integriti_case_info.IN_RCVDT) = '02' THEN 1 ELSE 0 END) AS 'FEB',
                    SUM(CASE WHEN MONTH(integriti_case_info.IN_RCVDT) = '03' THEN 1 ELSE 0 END) AS 'MAC',
                    SUM(CASE WHEN MONTH(integriti_case_info.IN_RCVDT) = '04' THEN 1 ELSE 0 END) AS 'APR',
                    SUM(CASE WHEN MONTH(integriti_case_info.IN_RCVDT) = '05' THEN 1 ELSE 0 END) AS 'MEI',
                    SUM(CASE WHEN MONTH(integriti_case_info.IN_RCVDT) = '06' THEN 1 ELSE 0 END) AS 'JUN',
                    SUM(CASE WHEN MONTH(integriti_case_info.IN_RCVDT) = '07' THEN 1 ELSE 0 END) AS 'JUL',
                    SUM(CASE WHEN MONTH(integriti_case_info.IN_RCVDT) = '08' THEN 1 ELSE 0 END) AS 'OGO',
                    SUM(CASE WHEN MONTH(integriti_case_info.IN_RCVDT) = '09' THEN 1 ELSE 0 END) AS 'SEP',
                    SUM(CASE WHEN MONTH(integriti_case_info.IN_RCVDT) = '10' THEN 1 ELSE 0 END) AS 'OKT',
                    SUM(CASE WHEN MONTH(integriti_case_info.IN_RCVDT) = '11' THEN 1 ELSE 0 END) AS 'NOV',
                    SUM(CASE WHEN MONTH(integriti_case_info.IN_RCVDT) = '12' THEN 1 ELSE 0 END) AS 'DIS'
                    "));
            // $query->where('sys_ref.cat','1249');
            $query->groupBy('sys_brn.BR_BRNNM');
            $query->orderBy('sys_brn.BR_BRNNM');
            $query = $query->get();
            return view(
                'laporan.integriti.cawangan.cara_penerimaan', 
                compact('request','query')
            );
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
    }
}
