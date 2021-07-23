<?php

namespace App\Http\Controllers\CaseEnquiryPaper;

use App\Http\Controllers\Controller;
use App\Models\Cases\CaseEnquiryPaper;
use App\Models\EnquiryPaper\EnquiryPaperCase;
use Auth;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Validator;
use Yajra\DataTables\Facades\DataTables;

/**
 * Penyiasatan Fail Kes EP
 *
 * Class InvestigationController
 * @package App\Http\Controllers\CaseEnquiryPaper
 */
class InvestigationController extends Controller
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
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        return view('caseenquirypaper.investigation.index');
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
    public function edit(Request $request, $id)
    {
        /** @var CaseEnquiryPaper $caseEnquiryPaper */
        // $enquiryPaperCase = CaseEnquiryPaper::where('id', $id)->first();
        $enquiryPaperCase = EnquiryPaperCase::find($id);
        if (empty($enquiryPaperCase) || $enquiryPaperCase->io_user_id != Auth::user()->id) {
            return redirect()->route('caseenquirypaper.investigations.index')
                ->with('alert', 'Maklumat Fail Kes EP tidak dijumpai.');
        }
        $user = Auth::user();
        $arrayDateColums = ['tkh_kompaun_dikeluarkan', 'tkh_kompaun_diserahkan',
            'tkh_kompaun_dibayar', 'tkh_daftar_mahkamah', 'tkh_sebutan',
            'tkh_bicara', 'tkh_denda', 'tkh_penjara',
            'tkh_dnaa', 'tkh_nfa', 'tkh_ad', 'tkh_kes_tutup'
        ];
        foreach($arrayDateColums as $column) {
            $data[$column] = $request->old($column) ? $request->old($column)
                : (isset($enquiryPaperCase->$column) && !empty($enquiryPaperCase->$column)
                ? Carbon::parse($enquiryPaperCase->$column)->format('d-m-Y') : null);
        }
        $collections = self::parameters();
        return view('caseenquirypaper.investigation.edit', compact('id', 'enquiryPaperCase', 'user', 'data', 'collections'));
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
        // $this->validate($request, [
        $v = Validator::make($request->all(), [
            'note_detail' => 'required',
            // 'result_detail' => 'required',
            // 'answer_detail' => 'required',
        ],
        [
            'note_detail.required' => 'Ruangan Catatan diperlukan.',
            // 'result_detail.required' => 'Ruangan Hasil Siasatan diperlukan.',
            // 'answer_detail.required' => 'Ruangan Jawapan Kepada Pengadu diperlukan.',
        ]);
        $v->validate();

        // $enquiryPaperCase = EnquiryPaperCase::where('id', $id)->first();
        $enquiryPaperCase = EnquiryPaperCase::find($id);
        if (empty($enquiryPaperCase)) {
            return redirect()->route('caseenquirypaper.investigations.index')
                ->with('error', 'Maklumat EP tidak dijumpai.');
        }
        $enquiryPaperCase->fill($request->all());
        $arrayDateColums = ['tkh_kompaun_dikeluarkan', 'tkh_kompaun_diserahkan',
            'tkh_kompaun_dibayar', 'tkh_daftar_mahkamah', 'tkh_sebutan',
            'tkh_bicara', 'tkh_denda', 'tkh_penjara',
            'tkh_dnaa', 'tkh_nfa', 'tkh_ad', 'tkh_kes_tutup'
        ];
        foreach($arrayDateColums as $column) {
            $enquiryPaperCase->$column = isset($request->$column)
                ? Carbon::parse($request->$column)->toDateString() : null;
        }
        $enquiryPaperCase->case_status_code = 5;
        $enquiryPaperCase->save();
        return redirect()->route('caseenquirypaper.investigations.index')
            ->with('success', 'Maklumat EP telah berjaya disimpan.');
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

    /**
     * get DT data for case enquiry paper assignment
     * @param Request $request
     * @return mixed
     * @throws \Exception
     */
    public function dt(Request $request)
    {
        $enquiryPaperCases = CaseEnquiryPaper::where('kod_cawangan', Auth::user()->brn_cd)
            ->where('io_user_id', Auth::user()->id)->whereIn('case_status_code', [4]);

        $datatables = DataTables::of($enquiryPaperCases)
            ->addIndexColumn()
            ->editColumn('no_kes', function (CaseEnquiryPaper $caseEnquiryPaper) {
                return view('caseenquirypaper.info.show_link', compact('caseEnquiryPaper'))->render();
            })
            ->addColumn('action', '
                <a href="{{ route("caseenquirypaper.investigations.edit", $id) }}" class="btn btn-xs btn-primary" data-toggle="tooltip" data-placement="right" title="Kemaskini">
                <i class="fa fa-edit"></i></a>
            ')
            ->rawColumns(['no_kes', 'action']);

        return $datatables->make(true);
    }

    /**
     * Parameter collections.
     *
     * @return array
     */
    public function parameters()
    {
        $collections['statusGroup'] = [
            'BS' => 'BS - BELUM SELESAI',
            'S' => 'S - SELESAI',
            'T' => 'T - DITUTUP',
        ];
        $collections['statusKes'] = [
            'BELUM SELESAI' => [
                'BS' => 'BS - BELUM SELESAI',
                'M' => 'M - MAHKAMAH',
                'E' => 'E - EKSIBIT',
            ],
            'SELESAI' => ['S' => 'S - SELESAI'],
            'DITUTUP' => ['T' => 'T - DITUTUP'],
        ];
        $collections['statusKesDet'] = [
            'BELUM SELESAI' => [
                'BS01' => 'BS01 - DALAM SIASATAN',
                'BS02' => 'BS02 - LAPORAN PAKAR/KIMIA',
                'BS03' => 'BS03 - TPR',
                'BS04' => 'BS04 - TAWARAN KOMPAUN',
                'BS05' => 'BS05 - RAYUAN KOMPAUN',
                'BS06' => 'BS06 - DNAA',
            ],
            'MAHKAMAH' => [
                'M01' => 'M01 - DAFTAR MAHKAMAH',
                'M02' => 'M02 - SEBUT KES',
                'M03' => 'M03 - BICARA',
            ],
            'EKSIBIT' => [
                'E01' => 'E01 - PEMULANGAN',
                'E02' => 'E02 - MOHON PELUPUSAN',
                'E03' => 'E03 - PELUPUSAN',
            ],
            'SELESAI' => [
                'S01' => 'S01 - NFA',
                'S02' => 'S02 - KOMPAUN DIBAYAR',
                'S03' => 'S03 - DENDA',
                'S04' => 'S04 - PENJARA',
                'S05' => 'S05 - DENDA & PENJARA',
                'S06' => 'S06 - A&D (KALAH)',
            ],
        ];
        return $collections;
    }
}
