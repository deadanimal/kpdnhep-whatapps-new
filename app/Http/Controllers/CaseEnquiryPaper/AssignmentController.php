<?php

namespace App\Http\Controllers\CaseEnquiryPaper;

use App\Http\Controllers\Controller;
use App\Models\Cases\CaseEnquiryPaper;
use Auth;
use Illuminate\Http\Request;
use Validator;
use Yajra\DataTables\Facades\DataTables;

/**
 * Penugasan Fail Kes EP
 *
 * Class AssignmentController
 * @package App\Http\Controllers\CaseEnquiryPaper
 */
class AssignmentController extends Controller
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
        return view('caseenquirypaper.assignment.index');
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
        $caseEnquiryPaper = CaseEnquiryPaper::where('id', $id)->first();
        if (empty($caseEnquiryPaper) || $caseEnquiryPaper->kod_cawangan != Auth::user()->brn_cd) {
            return redirect()->route('caseenquirypaper.assignments.index')
                ->with('alert', 'Maklumat Fail Kes EP tidak dijumpai.');
        }
        $user = Auth::user();
        return view('caseenquirypaper.assignment.edit', compact('id', 'caseEnquiryPaper', 'user'));
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
            'pegawai_penyiasat_io' => 'required',
            'pegawai_penyiasat_aio' => 'required',
            'note' => 'required',
        ],
        [
            'pegawai_penyiasat_io.required' => 'Ruangan Pegawai Penyiasat IO diperlukan.',
            'pegawai_penyiasat_aio.required' => 'Ruangan Pegawai Penyiasat AIO diperlukan.',
            'note.required' => 'Ruangan Catatan diperlukan.',
        ]);
        $v->validate();
        $caseEnquiryPaper = CaseEnquiryPaper::where('id', $id)->first();
        if (empty($caseEnquiryPaper)) {
            return redirect()->route('caseenquirypaper.assignments.index')
                ->with('error', 'Maklumat EP tidak dijumpai.');
        }
        $caseEnquiryPaper->fill($request->all());
        $caseEnquiryPaper->case_status_code = 4;
        $caseEnquiryPaper->save();
        return redirect()->route('caseenquirypaper.assignments.index')
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
        $caseEps = CaseEnquiryPaper::where('kod_cawangan', Auth::user()->brn_cd)
            ->whereIn('case_status_code', [2,3]);

        $datatables = DataTables::of($caseEps)
            ->addIndexColumn()
            ->editColumn('no_kes', function (CaseEnquiryPaper $caseEnquiryPaper) {
                return view('caseenquirypaper.info.show_link', compact('caseEnquiryPaper'))->render();
            })
            ->addColumn('action', '
                <a href="{{ route("caseenquirypaper.assignments.edit", $id) }}" class="btn btn-xs btn-primary" data-toggle="tooltip" data-placement="right" title="Kemaskini">
                <i class="fa fa-edit"></i></a>
            ')
            ->rawColumns(['no_kes','action']);

        return $datatables->make(true);
    }
}
