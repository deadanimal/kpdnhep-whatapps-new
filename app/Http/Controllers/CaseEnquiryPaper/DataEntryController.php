<?php

namespace App\Http\Controllers\CaseEnquiryPaper;

use App\Aduan\PenyiasatanKes;
use App\Branch;
use App\Http\Controllers\Controller;
use App\Models\Cases\CaseEnquiryPaper;
use App\Models\EnquiryPaper\EnquiryPaperCaseDetail;
use App\Ref;
use App\Repositories\EnquiryPaper\EnquiryPaperRefRepository;
use Auth;
use Carbon\Carbon;
use DB;
use Illuminate\Http\Request;
use Yajra\DataTables\Facades\DataTables;

/**
 * Daftar Fail Kes EP
 *
 * Class DataEntryController
 * @package App\Http\Controllers\CaseEnquiryPaper
 */
class DataEntryController extends Controller
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
        return view('caseenquirypaper.dataentry.index');
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create(Request $request)
    {
        $collections = self::parameters();
        $data['kod_negeri'] = $request->old('kod_negeri') ? $request->old('kod_negeri') : null;
        $data['asas_tindakan'] = $request->old('asas_tindakan') ? $request->old('asas_tindakan') : null;
        $data['tkh_kejadian'] = $request->old('tkh_kejadian') ? $request->old('tkh_kejadian') : null;
        $data['tkh_serahan'] = $request->old('tkh_serahan') ? $request->old('tkh_serahan') : null;
        $collections['branches'] = isset($data['kod_negeri'])
            ? Branch::GetListByState($data['kod_negeri'], false) : [];
        $collections['serahanAgensi'] = EnquiryPaperRefRepository::senaraiSerahanAgensi();
        return view('caseenquirypaper.dataentry.create', compact('collections', 'data'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $this->validate($request, [
            'asas_tindakan' => 'required|max:50',
            'no_kes' => 'required|max:50|unique:enquiry_paper_cases',
            'kod_negeri' => 'required|max:3',
            'kod_cawangan' => 'required|max:5',
            'akta' => 'required|max:10',
            'kesalahan' => 'max:190',
        ],
        [
            'asas_tindakan.required' => 'Ruangan Asas Tindakan diperlukan.',
            'asas_tindakan.max' => 'Maklumat Asas Tindakan mesti tidak melebihi :max aksara.',
            'no_kes.required' => 'Ruangan Nombor Fail Kes EP diperlukan.',
            'no_kes.max' => 'Maklumat Nombor Fail Kes EP mesti tidak melebihi :max aksara.',
            'kod_negeri.required' => 'Ruangan Negeri diperlukan.',
            'kod_negeri.max' => 'Maklumat Negeri mesti tidak melebihi :max aksara.',
            'kod_cawangan.required' => 'Ruangan Cawangan diperlukan.',
            'kod_cawangan.max' => 'Maklumat Cawangan mesti tidak melebihi :max aksara.',
            'akta.required' => 'Ruangan Akta diperlukan.',
            'akta.max' => 'Maklumat Akta mesti tidak melebihi :max aksara.',
            'kesalahan.max' => 'Maklumat Kesalahan mesti tidak melebihi :max aksara.',
        ]);

        $input = $request->all();

        $user = Auth::user();

        $caseEnquiryPaper = new CaseEnquiryPaper;
        $caseEnquiryPaper->fill($input);
        $caseEnquiryPaper->complaint_case_number =
            isset($request->asas_tindakan) && $request->asas_tindakan == 'ADUAN' ? $request->complaint_case_number : null;
        $caseEnquiryPaper->tkh_kejadian =
            isset($request->tkh_kejadian) ? Carbon::parse($request->tkh_kejadian)->toDateString() : null;
        $caseEnquiryPaper->tkh_serahan =
            isset($request->tkh_serahan) ? Carbon::parse($request->tkh_serahan)->toDateString() : null;
        $caseEnquiryPaper->case_status_code = 1;
        $caseEnquiryPaper->save();

        /** @var EnquiryPaperCaseDetail $enquiryPaperCaseDetail */
        $enquiryPaperCaseDetail = new EnquiryPaperCaseDetail;
        $enquiryPaperCaseDetail->fill($input);
        $enquiryPaperCaseDetail->case_number = $caseEnquiryPaper->no_kes;
        $enquiryPaperCaseDetail->case_status_code = $caseEnquiryPaper->case_status_code;
        $enquiryPaperCaseDetail->is_current_status = 1;
        if (!empty($user)) {
            $enquiryPaperCaseDetail->action_from_user_id = $user->id ? $user->id : null;
            $enquiryPaperCaseDetail->action_from_branch_code = $user->brn_cd ? $user->brn_cd : null;
        }
        $enquiryPaperCaseDetail->save();

        return redirect()->route('caseenquirypaper.dataentries.processes.edit', ['id' => $caseEnquiryPaper->id, 'page' => 2])
            ->with('success', 'Maklumat Fail Kes EP telah <b>berjaya</b> disimpan.');
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
        $caseEnquiryPaper = CaseEnquiryPaper::find($id);
        if (empty($caseEnquiryPaper)
            || empty($caseEnquiryPaper->case_status_code)
            || !in_array($caseEnquiryPaper->case_status_code, [1])
        ) {
            return redirect()->route('caseenquirypaper.dataentries.index')
                ->with('alert', 'Maklumat Fail Kes EP tidak dijumpai.');
        }
        $collections = self::parameters();
        $data['kod_negeri'] = $request->old('kod_negeri') ? $request->old('kod_negeri')
            : (isset($caseEnquiryPaper->kod_negeri) && !empty($caseEnquiryPaper->kod_negeri) ? $caseEnquiryPaper->kod_negeri : null);
        $data['asas_tindakan'] = $request->old('asas_tindakan') ? $request->old('asas_tindakan')
            : (isset($caseEnquiryPaper->asas_tindakan) && !empty($caseEnquiryPaper->asas_tindakan) ? $caseEnquiryPaper->asas_tindakan : null);
        $data['tkh_kejadian'] = $request->old('tkh_kejadian') ? $request->old('tkh_kejadian')
            : (isset($caseEnquiryPaper->tkh_kejadian) && !empty($caseEnquiryPaper->tkh_kejadian)
            ? Carbon::parse($caseEnquiryPaper->tkh_kejadian)->format('d-m-Y') : null);
        $data['tkh_serahan'] = $request->old('tkh_serahan') ? $request->old('tkh_serahan')
            : (isset($caseEnquiryPaper->tkh_serahan) && !empty($caseEnquiryPaper->tkh_serahan)
            ? Carbon::parse($caseEnquiryPaper->tkh_serahan)->format('d-m-Y') : null);
        $collections['branches'] = isset($data['kod_negeri'])
            ? Branch::GetListByState($data['kod_negeri'], false) : [];
        $collections['serahanAgensi'] = EnquiryPaperRefRepository::senaraiSerahanAgensi();
        return view('caseenquirypaper.dataentry.edit', compact('id', 'caseEnquiryPaper', 'collections', 'data'));
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
        $this->validate($request, [
            'asas_tindakan' => 'required|max:50',
            'no_kes' => 'required|max:50',
            'kod_negeri' => 'required|max:3',
            'kod_cawangan' => 'required|max:5',
            'akta' => 'required|max:10',
            'kesalahan' => 'max:190',
        ],
        [
            'asas_tindakan.required' => 'Ruangan Asas Tindakan diperlukan.',
            'asas_tindakan.max' => 'Maklumat Asas Tindakan mesti tidak melebihi :max aksara.',
            'no_kes.required' => 'Ruangan Nombor Fail Kes EP diperlukan.',
            'no_kes.max' => 'Maklumat Nombor Fail Kes EP mesti tidak melebihi :max aksara.',
            'kod_negeri.required' => 'Ruangan Negeri diperlukan.',
            'kod_negeri.max' => 'Maklumat Negeri mesti tidak melebihi :max aksara.',
            'kod_cawangan.required' => 'Ruangan Cawangan diperlukan.',
            'kod_cawangan.max' => 'Maklumat Cawangan mesti tidak melebihi :max aksara.',
            'akta.required' => 'Ruangan Akta diperlukan.',
            'akta.max' => 'Maklumat Akta mesti tidak melebihi :max aksara.',
            'kesalahan.max' => 'Maklumat Kesalahan mesti tidak melebihi :max aksara.',
        ]);

        /** @var CaseEnquiryPaper $caseEnquiryPaper */
        $caseEnquiryPaper = CaseEnquiryPaper::find($id);
        if (empty($caseEnquiryPaper)
            || empty($caseEnquiryPaper->case_status_code)
            || !in_array($caseEnquiryPaper->case_status_code, [1])
        ) {
            return redirect()->route('caseenquirypaper.dataentries.index')
                ->with('alert', 'Maklumat Fail Kes EP tidak dijumpai.');
        }
        $caseEnquiryPaper->fill($request->all());
        $caseEnquiryPaper->complaint_case_number =
            isset($request->asas_tindakan) && $request->asas_tindakan == 'ADUAN' ? $request->complaint_case_number : null;
        $caseEnquiryPaper->tkh_kejadian =
            isset($request->tkh_kejadian) ? Carbon::parse($request->tkh_kejadian)->toDateString() : null;
        $caseEnquiryPaper->tkh_serahan =
            isset($request->tkh_serahan) ? Carbon::parse($request->tkh_serahan)->toDateString() : null;
        $caseEnquiryPaper->case_status_code = 1;
        $caseEnquiryPaper->save();
        return redirect()->route('caseenquirypaper.dataentries.processes.edit', ['id' => $caseEnquiryPaper->id, 'page' => 2])
            ->with('success', 'Maklumat Fail Kes EP telah <b>berjaya</b> dikemaskini.');
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
     * get DT data for data entry case enquiry paper
     * @param Request $request
     * @return mixed
     * @throws \Exception
     */
    public function dt(Request $request)
    {
        $caseEnquiryPapers = CaseEnquiryPaper::whereNotNull('no_kes')
            ->where('created_by', Auth::User()->id);

        $datatables = DataTables::of($caseEnquiryPapers)
            ->addIndexColumn()
            ->editColumn('no_kes', function (CaseEnquiryPaper $caseEnquiryPaper) {
                return view('caseenquirypaper.info.show_link', compact('caseEnquiryPaper'))->render();
            })
            ->addColumn('action', function ($enquiryPaperCase) {
                return view('caseenquirypaper.dataentry.datatables_actions', compact('enquiryPaperCase'))->render();
            })
            // ->addColumn('action', '
            //     <a href="{{ route("caseenquirypaper.dataentries.edit", $id) }}" class="btn btn-xs btn-primary" data-toggle="tooltip" data-placement="right" title="Kemaskini">
            //     <i class="fa fa-pencil"></i></a>
            // ')
            ->rawColumns(['no_kes', 'action']);

        return $datatables->make(true);
    }

    public function getcaseactdetail($epno)
    {
        $caseActDetail = PenyiasatanKes::where('id', $epno)->pluck('id', 'CT_EPNO');
        return json_encode($caseActDetail);
    }

    public function parameters()
    {
        $collections['refStates'] = Ref::where(['cat' => '17', 'status' => '1'])
            ->orderBy('sort', 'asc')->pluck('descr', 'code')->toArray();
        $collections['refActs'] = Ref::where(['cat' => '713', 'status' => '1'])
            ->orderBy('sort', 'asc')->pluck('descr', 'code')->toArray();
        $collections['asasTindakan'] = [
            'OPS BARANG TIRUAN' => 'OPS BARANG TIRUAN',
            'ADUAN' => 'ADUAN',
            'JUALAN MURAH' => 'JUALAN MURAH',
            'OPS BERSEPADU' => 'OPS BERSEPADU',
        ];
        $collections['serahanAgensi'] = [
            'AKSEM' => 'AKSEM',
            'APMM' => 'APMM',
            'ATM' => 'ATM',
            'FRANCAIS' => 'FRANCAIS',
            'KASTAM' => 'KASTAM',
            'NRRET' => 'NRRET',
            'PDRM' => 'PDRM',
            'PGA' => 'PGA',
            'PPM' => 'PPM',
            'SPRM' => 'SPRM',
        ];
        $collections['pengelasanKes'] = [
            'A1' => 'A1 - PREMIS TERBUKA-KOTS',
            'A2' => 'A2 - PREMIS TETAP-KOTS',
            'B3' => 'B3 - RTT-DGN NOTIS',
            'B4' => 'B4 - RTT-TANPA NOTIS',
            'B5' => 'B5 - RTT',
            'C6' => 'C6 - KOMPAUN DIBAYAR DLM TEMPOH DITETAPKAN',
            'D7' => 'D7 - SIASATAN SUKAR',
            'D8' => 'D8 - RAYUAN KOMPAUN ATAU KOMPAUN TIDAK DIBAYAR',
        ];
        $collections['kategoriKesalahan'] = [
            'BARANG TIRUAN' => 'BARANG TIRUAN',
            'JUALAN MURAH' => 'JUALAN MURAH',
            'LABEL CAKERA OPTIK' => 'LABEL CAKERA OPTIK',
        ];
        $collections['kesalahan'] = [
            'SEK 8 (2) (b) APD 2011' => 'SEK 8 (2) (b) APD 2011',
            'PER 3 (1) PPPD (HJM) 1997' => 'PER 3 (1) PPPD (HJM) 1997',
            'PER 9 (1) PPPD (HJM) (PINDAAN) 2016' => 'PER 9 (1) PPPD (HJM) (PINDAAN) 2016',
        ];
        return $collections;
    }
}
