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
 * Penutupan Fail Kes EP
 *
 * Class ClosureController
 * @package App\Http\Controllers\CaseEnquiryPaper
 */
class ClosureController extends Controller
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
        return view('caseenquirypaper.closure.index');
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
        $enquiryPaperCase = EnquiryPaperCase::where('id', $id)->first();
        if (empty($enquiryPaperCase)) {
            return redirect()->route('caseenquirypaper.closures.index')
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
        return view('caseenquirypaper.closure.edit', compact('id', 'enquiryPaperCase', 'user', 'data', 'collections'));
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
        $v = Validator::make($request->all(), [
            'close_by_user_id' => 'required',
            'close_by_user_name' => 'required',
            'note_detail' => 'required',
        ],
        [
            'close_by_user_id.required' => 'Ruangan Penutupan Oleh diperlukan.',
            'close_by_user_name.required' => 'Ruangan Penutupan Oleh diperlukan.',
            'note_detail.required' => 'Ruangan Catatan diperlukan.',
        ]);
        $v->validate();
        $enquiryPaperCase = EnquiryPaperCase::where('id', $id)->first();
        if (empty($enquiryPaperCase)) {
            return redirect()->route('caseenquirypaper.closures.index')
                ->with('error', 'Maklumat EP tidak dijumpai.');
        }
        $enquiryPaperCase->fill($request->all());
        $enquiryPaperCase->case_status_code = 6;
        $enquiryPaperCase->save();
        return redirect()->route('caseenquirypaper.closures.index')
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
     * get DT data for case enquiry closure
     * @param Request $request
     * @return mixed
     * @throws \Exception
     */
    public function dt(Request $request)
    {
        $enquiryPaperCases = EnquiryPaperCase::where('kod_cawangan', Auth::user()->brn_cd)
            ->whereIn('case_status_code', [5]);

        $datatables = DataTables::of($enquiryPaperCases)
            ->addIndexColumn()
            ->editColumn('no_kes', function (EnquiryPaperCase $caseEnquiryPaper) {
                return view('caseenquirypaper.info.show_link', compact('caseEnquiryPaper'))->render();
            })
            ->addColumn('action', '
                <a href="{{ route("caseenquirypaper.closures.edit", $id) }}" class="btn btn-xs btn-primary" data-toggle="tooltip" data-placement="right" title="Kemaskini">
                <i class="fa fa-edit"></i></a>
            ')
            ->rawColumns(['no_kes', 'action']);

        return $datatables->make(true);
    }
}
