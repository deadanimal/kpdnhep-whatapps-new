<?php

namespace App\Http\Controllers\Laporan;

use App\Http\Controllers\Controller;
use Carbon\Carbon;
use DB;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use PDF;
use App\Ref;

class LaporanJenisBaranganController extends Controller
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
        $this->middleware('auth');
    }

    /**
     * Laporan Jenis Barangan
     *
     * @return \Illuminate\Http\Response
     */
    public function jenisbarangan(Request $request)
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
        $category = isset($data['category']) ? $data['category'] : '';
        $categorydesc = $category != null ? Ref::GetDescr('244', $category) : 'SEMUA';
        if ($request->gen) {
            $query = DB::table('case_info');
            $query->join('sys_ref', 'sys_ref.code', '=', 'case_info.CA_CMPLKEYWORD');
            $query->select(DB::raw("
                sys_ref.descr,
                SUM(CASE WHEN CA_INVSTS = 3 THEN 1 ELSE 0 END) AS SELESAI,
                SUM(CASE WHEN CA_CASESTS = 1 THEN 1 ELSE 0 END) AS BELUMAGIH, 
                SUM(CASE WHEN CA_CASESTS = 2 AND CA_INVSTS = 2 THEN 1 ELSE 0 END) AS DALAMSIASATAN, 
                SUM(CASE WHEN CA_CASESTS = 2 AND CA_INVSTS = 9 THEN 1 ELSE 0 END) AS TUTUP, 
                SUM(CASE WHEN CA_CASESTS = 2 AND CA_INVSTS = 4 THEN 1 ELSE 0 END) AS AGENSILAIN, 
                SUM(CASE WHEN CA_CASESTS = 2 AND CA_INVSTS = 5 THEN 1 ELSE 0 END) AS TRIBUNAL, 
                SUM(CASE WHEN CA_CASESTS = 2 AND CA_INVSTS = 6 THEN 1 ELSE 0 END) AS PERTANYAAN, 
                SUM(CASE WHEN CA_CASESTS = 2 AND CA_INVSTS = 7 THEN 1 ELSE 0 END) AS MAKLUMATXLENGKAP, 
                SUM(CASE WHEN CA_CASESTS = 2 AND CA_INVSTS = 8 THEN 1 ELSE 0 END) AS LUARBIDANG, 
                SUM(CASE WHEN CA_CASESTS = 2 AND CA_INVSTS = 12 THEN 1 ELSE 0 END) AS SELESAIMAKLUMATXLENGKAP, 
                SUM(CASE WHEN CA_CASESTS = 2 AND CA_INVSTS = 11 THEN 1 ELSE 0 END) AS TUTUPMAKLUMATXLENGKAP,
                COUNT(CA_CMPLKEYWORD) AS counttotal
            "));
            $query->where('sys_ref.cat', '1051');
            $query->whereNotNull('case_info.CA_RCVDT');
            $query->whereBetween('case_info.CA_RCVDT', [$datestart, $dateend]);
            $query->where([
                ['CA_CASEID','<>',null],
                ['CA_RCVTYP','<>',null],
                ['CA_RCVTYP','<>',''],
                ['CA_CMPLCAT','<>',''],
                ['CA_INVSTS','!=','10']
            ]);
            if ($category != '') {
                $query->where('case_info.CA_CMPLCAT', $category);
            } else {
                $query->whereIn('case_info.CA_CMPLCAT', ['BPGK 03','BPGK 01']);
            }
            $query->groupBy('sys_ref.code', 'sys_ref.descr', 'sys_ref.sort');
            // $query->orderBy('counttotal', 'desc');
            $query = $query->get();
            // dd($query->toSql());
        }
        switch ($generate) {
            case 'excel':
                return view(
                    'laporan.laporanlainlain.jenisbarangan.excel', 
                    compact('request', 'query', 'datestart', 'dateend', 'category', 'categorydesc')
                );
                break;
            case 'web':
            default:
                return view(
                    'laporan.laporanlainlain.jenisbarangan.index',
                    compact('request', 'query', 'datestart', 'dateend', 'category', 'categorydesc')
                );
                break;
        }
    }
}
