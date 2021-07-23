<?php

namespace App\Http\Controllers\Laporan;

use App\Http\Controllers\Controller;
use App\Aduan\Carian;
use Carbon\Carbon;
use DB;
use Excel;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use PDF;

class Fok2Controller extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        //
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

    public function __construct()
    {
//        $this->middleware('auth');
        $this->middleware(['locale','auth']);
    }

    /**
     * Laporan pengguna fok mengikut kategori aduan
     *
     * @return \Illuminate\Http\Response
     */
    public function fok2(Request $request)
    {
        $data = $request->all();
        $generate = isset($data['gen']) 
            ? $data['gen'] 
            : 'web'
            ;
        $datestart = isset($data['datestart']) 
            ? Carbon::parse($data['datestart'])->startOfDay() 
            : Carbon::now()->startOfDay()
            ;
        $dateend = isset($data['dateend']) 
            ? Carbon::parse($data['dateend'])->endOfDay() 
            : Carbon::now()->endOfDay()
            ;
        if ($request->gen) {
            $query = DB::table('case_info');
            $query->join('sys_ref', 'sys_ref.code', '=', 'case_info.CA_CMPLCAT');
            $query->select(DB::raw("
                sys_ref.descr,
                SUM(CASE WHEN case_info.CA_FOK_IND = 1 THEN 1 ELSE 0 END) AS fok1,
                SUM(CASE WHEN case_info.CA_FOK_IND = 0 THEN 1 ELSE 0 END) AS fok0,
                COUNT(CA_FOK_IND) AS countfokind
            "));
            $query->where('sys_ref.cat', '244');
            // $query->where('sys_ref.status', '1');
            // $query->where('case_info.CA_FOK_IND', 1);
            $query->whereNotNull('case_info.CA_RCVDT');
            $query->whereBetween('case_info.CA_RCVDT', [$datestart, $dateend]);
            $query->whereNotIn('case_info.CA_INVSTS', ['10']);
            $query->where([
                ['CA_CASEID','<>',null],
                ['CA_RCVTYP','<>',null],
                ['CA_RCVTYP','<>',''],
                ['CA_CMPLCAT','<>',''],
            ]);
            $query->groupBy('sys_ref.code', 'sys_ref.descr', 'sys_ref.sort');
            // $query->groupBy(
            //         'case_info.CA_DOCNO'
            //     )
                // ->get()
                // ;
            // $query->orderBy('total', 'desc');
            $query = $query->get();
            // dd($query->toSql());
        }
        // $query->toSql();
        switch ($generate) {
            case 'pdf':
                $pdf = PDF::loadView(
                    'laporan.laporanlainlain.laporan_fok2.pdf',
                    compact('request', 'query', 'datestart', 'dateend')
                );
                // return $pdf->stream('Laporan_FOK' . date("_Ymd_His") . '.pdf');
                return $pdf->download('Laporan_FOK_Kategori' . date("_Ymd_His") . '.pdf');
                break;
            case 'excel':
                // return Excel::create('Laporan_FOK' . date("_Ymd_His"), function ($excel) use ($query, $datestart, $dateend){
                    // $excel->sheet('Report', function ($sheet) use ($query, $datestart, $dateend){
                        // $sheet->loadView('laporan.laporanlainlain.laporan_fok.excel')
                            // ->with([
                                // 'datestart' => $datestart,
                                // 'dateend' => $dateend,
                                // 'query' => $query
                            // ])
                        // ;
                    // });
                // })->export('xlsx');
                return view(
                    'laporan.laporanlainlain.laporan_fok2.excel', 
                    compact('query', 'datestart', 'dateend')
                );
                break;
            case 'web':
            default:
                return view(
                    'laporan.laporanlainlain.laporan_fok2.index',
                    compact('request', 'query', 'datestart', 'dateend')
                );
                break;
        }
    }
}
