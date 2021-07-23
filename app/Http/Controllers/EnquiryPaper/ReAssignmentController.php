<?php

namespace App\Http\Controllers\EnquiryPaper;

use App\Http\Controllers\Controller;
use App\Http\Requests\EnquiryPaper\UpdateEnquiryPaperReAssignmentRequest;
use App\Models\EnquiryPaper\EnquiryPaperCase;
use Auth;
use Illuminate\Http\Request;
use Validator;
use Yajra\DataTables\Facades\DataTables;

/**
 * Penugasan Semula Fail Kes EP
 *
 * Class ReAssignmentController
 * @package App\Http\Controllers\EnquiryPaper
 */
class ReAssignmentController extends Controller
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
        return view('enquirypaper.reassignments.index');
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        /** @var EnquiryPaperCase $enquiryPaperCase */
        $enquiryPaperCase = EnquiryPaperCase::find($id);

        if (empty($enquiryPaperCase) || $enquiryPaperCase->kod_cawangan != Auth::user()->brn_cd) {
            return redirect(route('enquirypaper.reassignments.index'))
                ->with('alert', 'Maklumat Fail Kes EP tidak dijumpai.');
        }

        $user = Auth::user();

        return view('enquirypaper.reassignments.edit', compact('id', 'enquiryPaperCase', 'user'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param UpdateEnquiryPaperReAssignmentRequest $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(UpdateEnquiryPaperReAssignmentRequest $request, $id)
    {
        /** @var EnquiryPaperCase $enquiryPaperCase */
        $enquiryPaperCase = EnquiryPaperCase::find($id);

        if (empty($enquiryPaperCase)) {
            return redirect(route('enquirypaper.reassignments.index'))
                ->with('alert', 'Maklumat Fail Kes EP tidak dijumpai.');
        }

        $enquiryPaperCase->fill($request->all());
        $enquiryPaperCase->save();

        return redirect(route('enquirypaper.reassignments.index'))
            ->with('success', 'Maklumat Penugasan Semula Fail Kes EP telah berjaya disimpan.');
    }

    /**
     * get DT data for case enquiry paper reassignment
     * @param Request $request
     * @return \Illuminate\Http\Response
     */
    public function dt(Request $request)
    {
        $enquiryPaperCases = EnquiryPaperCase::where('kod_cawangan', Auth::user()->brn_cd)
            ->whereIn('case_status_code', [4]);

        $datatables = DataTables::of($enquiryPaperCases)
            ->addIndexColumn()
            ->editColumn('no_kes', function (EnquiryPaperCase $caseEnquiryPaper) {
                return view('caseenquirypaper.info.show_link', compact('caseEnquiryPaper'))->render();
            })
            ->addColumn('action', function ($id) {
                return view('enquirypaper.reassignments.datatables_actions', compact('id'))->render();
            })
            ->rawColumns(['no_kes', 'action']);

        return $datatables->make(true);
    }
}
