<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Validator;
use Illuminate\Support\Facades\Storage;
use App\Attachment;
use App\FailKes;
use Yajra\DataTables\Facades\DataTables;
use Box\Spout\Reader\ReaderFactory;
use Box\Spout\Common\Type;
use DB;

class FailKesController extends Controller
{
    public function __construct()
    {
        $this->middleware(['auth']);
    }
    
    public function index()
    {
        return view('fail-kes.index');
    }

    public function GetDataTable(Request $request)
    {
        $mFailKes = FailKes::orderBy('created_at','DESC');
        
        $datatables = Datatables::of($mFailKes)
                ->addIndexColumn()
                ->editColumn('doc_attach_id', function(FailKes $FailKes) {
                    if($FailKes->doc_attach_id != '')
                    return '<a href='.Storage::disk('tips')->url($FailKes->Attachment->file_name_sys).' target="_blank">'.$FailKes->title.'</a>';
                    else
                    return '';
                })
                ->editColumn('status', function(FailKes $FailKes) {
                    if($FailKes->status != '')
                    return $FailKes->Status->descr;
                    else
                    return '';
                })
                ->addColumn('action', function (FailKes $FailKes) {
                    return view('fail-kes.action_btn', compact('FailKes'))->render();
                })
                ->rawColumns(['action','doc_attach_id'])
                ->filter(function ($query) use ($request) {
                    if ($request->has('title')) {
                        $query->where('title', 'like', "%{$request->get('title')}%");
                    }
                    if ($request->has('remark')) {
                        $query->where('remark', 'like', "%{$request->get('remark')}%");
                    }
                })
                ;
        return $datatables->make(true);
    }
    
    public function create()
    {
        return view('fail-kes.create');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function storevalidate(Request $request)
    {
        $validator = Validator::make(
            [
                'file' => $request->file,
//                'extension' => explode('.', $request->file)[1],
            ], 
            [
                'file' => 'required',
//                'extension' => 'required|in:xls,xlsx,csv'
            ],
            [
                'file.required' => 'Ruangan Fail diperlukan.',
//                'extension.in' => 'Hanya format xls,xlsx dan csv sahaja.',
            ]
        );
        if ($validator->fails()) {
            return response()->json(['fails' => $validator->getMessageBag()]);
        }
        else
        {
            if(empty($request->file)) {
                
            }
            return response()->json(['success']);
        }
    }
    
    public function store(Request $request)
    {
        $file = $request->file('file');
        $date = date('Ymdhis');
        $userid = Auth()->user()->id;
        
        if ($file) {
            $filename = $userid.'_'.$date.'.'.$file->getClientOriginalExtension();
            Storage::disk('tips')->putFileAs('/', $request->file('file'), $filename);
            
            $mAttach = new Attachment();
            $mAttach->doc_title = $file-> getClientOriginalName();
            $mAttach->file_name = $file-> getClientOriginalName();
            $mAttach->file_name_sys = $filename;
            $mAttach->remarks = $request->remarks;
            if($mAttach->save()) {
                $mFailKes = new FailKes();
                $mFailKes->doc_attach_id = $mAttach->id;
                $mFailKes->title = $file-> getClientOriginalName();
                $mFailKes->status = 1;
                $mFailKes->migrated_ind = 0;
                $mFailKes->remark = $request->remarks;
                if($mFailKes->save()) {
                    return redirect()->route('fail-kes.index');
                }
            }
        }
    }

    public function CheckFile($id)
    {
        $model = FailKes::find($id);
        $mAttachment = Attachment::find($model->doc_attach_id);
        $reader = ReaderFactory::create(Type::XLSX);
        $reader->open(Storage::disk('tips')->url($mAttachment->file_name_sys));
        foreach ($reader->getSheetIterator() as $sheet) {
            $first = true;
            foreach ($sheet->getRowIterator() as $key => $rows) {
                if($first)
                {
                    if($key == '1' && $rows[1] == 'KOD_NEGERI') {
                        FailKes::where('id', $id)->update(['status' => '2']);
                        return redirect()->route('fail-kes.index')->with('success', 'Fail telah <b>BERJAYA</b> disahkan');
                    }else{
                        return redirect()->route('fail-kes.index')->with('alert', 'Fail <b>TIDAK BERJAYA</b> disahkan');
                    }
                    $first = false;
                }
            }
        }
    }
    
    public function migrate($id)
    {
        $model = FailKes::find($id);
        $mAttachment = Attachment::find($model->doc_attach_id);
        $reader = ReaderFactory::create(Type::XLSX);
        $reader->open(Storage::disk('tips')->url($mAttachment->file_name_sys));
        foreach ($reader->getSheetIterator() as $sheet) {
            $first = true;
            foreach ($sheet->getRowIterator() as $key => $rows) {
                if($first)
                {
//                    if($key == '1' && $rows[1] == 'KOD_NEGERI') {
//                        $test = 'Y';
//                    }else{
//                        $test = 'N';
//                    }
                    $first = false;
                }
                else
                {
                    DB::table('tip')->insert([
                        'NO_KES' => $rows[0],
                        'KOD_NEGERI' => $rows[1],
                        'KOD_CAWANGAN' => $rows[2],
                        'AKTA' => $rows[3],
                        'ASAS_TINDAKAN' => $rows[4],
                        'SERAHAN_AGENSI' => $rows[5],
                        'C01' => $rows[6],
                        'TKH_KEJADIAN' => $rows[7],
                        'TKH_SERAHAN' => $rows[8],
                        'PENGELASAN_KES' => $rows[9],
                        'KATEGORI_KESALAHAN' => $rows[10],
                        'KESALAHAN' => $rows[11],
                        'PEGAWAI_SERBUAN_RO' => $rows[12],
                        'PEGAWAI_PENYIASAT_AIO' => $rows[13],
                        'PEGAWAI_PENYIASAT_IO' => $rows[14],
                        'NO_REPORT_POLIS' => $rows[15],
                        'NO_SSM' => $rows[16],
                        'NAMA_PREMIS_SYARIKAT' => $rows[17],
                        'ALAMAT' => $rows[18],
                        'JENAMA_PREMIS' => $rows[19],
                        'KAWASAN' => $rows[20],
                        'JENIS_PERNIAGAAN' => $rows[21],
                        'KATEGORI_PREMIS' => $rows[22],
                        'JENIS_PREMIS' => $rows[23],
                        'STATUS_OKT' => $rows[24],
                        'JANTINA' => $rows[25],
                        'NAMA_OKT' => $rows[26],
                        'TARAF_KERAKYATAN' => $rows[27],
                        'CATATAN_KERAKYATAN' => $rows[28],
                        'NO_IC_PASPORT' => $rows[29],
                        'NILAI_TRANSAKSI' => $rows[30],
                        'KESBRG_ID' => $rows[31],
                        'KATEGORI_BRG_RAMPASAN' => $rows[33],
                        'JENIS_BRG_RAMPASAN' => $rows[34],
                        'JENIS_BRG_RAMPASAN2' => $rows[35],
                        'KARYA_TEMPATAN_ANTARABANGSA' => $rows[36],
                        'JENAMA_BRG_RAMPASAN' => $rows[37],
                        'BOB' => $rows[38],
                        'NO_SEAL_SSM' => $rows[39],
                        'UNIT' => $rows[40],
                        'TONG' => $rows[41],
                        'TANGKI' => $rows[42],
                        'LITER' => $rows[43],
                        'KG' => $rows[44],
                        'NILAI_RAMPASAN' => $rows[45],
                        'TKH_DITERIMA' => $rows[51],
                        'TKH_KOMPAUN_DIKELUARKAN' => $rows[52],
                        'NILAI_KOMPAUN_DITAWARKAN' => $rows[53],
                        'TKH_KOMPAUN_DISERAHKAN' => $rows[54],
                        'TKH_KOMPAUN_DIBAYAR' => $rows[55],
                        'NILAI_KOMPAUN_DIBAYAR' => $rows[56],
                        'NO_RESIT_PEMBAYARAN_KOMPAUN' => $rows[57],
                        'PEGAWAI_PENDAKWA' => $rows[58],
                        'MAHKAMAH' => $rows[59],
                        'NO_PENDAFTARAN_MAHKAMAH' => $rows[60],
                        'TKH_DAFTAR_MAHKAMAH' => $rows[61],
                        'TKH_SEBUTAN' => $rows[62],
                        'TKH_BICARA' => $rows[63],
                        'TKH_DENDA' => $rows[64],
                        'NILAI_DENDA' => $rows[65],
                        'TKH_PENJARA' => $rows[66],
                        'TEMPOH_PENJARA' => $rows[67],
                        'TKH_DNAA' => $rows[68],
                        'TKH_NFA' => $rows[69],
                        'TKH_AD' => $rows[70],
                        'TKH_KES_TUTUP' => $rows[71],
                        'STATUS_GROUP' => $rows[72],
                        'STATUS_KES' => $rows[73],
                        'STATUS_KES_DET' => $rows[74],
                        'PERGERAKAN_IP' => $rows[75],
                        'STATUS_EKSIBIT' => $rows[76],
                        'WEEK' => $rows[77],
                        'TPR' => $rows[78],
                        'BS_DALAM_SIASATAN' => $rows[79],
                        'C02' => $rows[84],
                        'C03' => $rows[85],
                        'C04' => $rows[86],
                        'C05' => $rows[87],
                        'C06' => $rows[88],
                        'C07' => $rows[89],
                        'C08' => $rows[90],
                        'C09' => $rows[91],
                        'C10' => $rows[92]
                    ]);
                }
            }
        }
        FailKes::where('id', $id)->update(['status' => '3']);
        return redirect()->route('fail-kes.index')->with('success', 'Fail telah <b>BERJAYA</b> dipindahkan');
    }
    
    public function Report()
    {
        return view('fail-kes.report');
    }
    
    public function GetDataReport(Request $request)
    {
        $mTip = \App\Tip::leftJoin('case_info AS b', 'b.CA_IPNO', 'tip.NO_KES')
                    ->select('tip.*','b.CA_CASEID')
                    ->orderBy('TKH_DITERIMA','DESC');
        
        $datatables = Datatables::of($mTip)
                ->addIndexColumn()
//                ->editColumn('doc_attach_id', function(Tip $Tip) {
//                    if($FailKes->doc_attach_id != '')
//                    return '<a href='.Storage::disk('tips')->url($FailKes->Attachment->file_name_sys).' target="_blank">'.$FailKes->title.'</a>';
//                    else
//                    return '';
//                })
//                ->editColumn('status', function(Tip $Tip) {
//                    if($FailKes->status != '')
//                    return $FailKes->Status->descr;
//                    else
//                    return '';
//                })
//                ->addColumn('action', function (Tip $Tip) {
//                    return view('fail-kes.action_btn', compact('FailKes'))->render();
//                })
//                ->rawColumns(['action','doc_attach_id'])
                ->filter(function ($query) use ($request) {
                    if ($request->has('NO_KES')) {
                        $query->where('tip.NO_KES', 'like', "%{$request->get('NO_KES')}%");
                    }
                    if ($request->has('CA_CASEID')) {
                        $query->where('b.CA_CASEID', 'like', "%{$request->get('CA_CASEID')}%");
                    }
                })
                ;
        return $datatables->make(true);
    }
    
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
