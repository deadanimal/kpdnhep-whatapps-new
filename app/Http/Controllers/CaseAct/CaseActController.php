<?php

namespace App\Http\Controllers\CaseAct;

use App\Aduan\PenyiasatanKes;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Yajra\DataTables\Facades\DataTables;

class CaseActController extends Controller
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
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $caseAct =
            PenyiasatanKes::select(
                    'case_act.id as id',
                    'case_act.CT_CASEID',
                    'case_act.CT_EPNO'
                )
                ->where('case_act.id', $id)
                ->whereNotNull('case_act.CT_EPNO')
                ->first();
        return response()->json($caseAct);
    }

    /**
     * Datatable Case Act, By EP number.
     */
    public function dt(Request $request)
    {
        $caseActs = PenyiasatanKes::whereNotNull('CT_EPNO');

        $datatables = DataTables::of($caseActs)
            ->addIndexColumn()
            ->addColumn('action', '
                <a class="btn btn-xs btn-success" onclick="selectCaseEpNo({{ $id }})"><i class="fa fa-check"></i></a>
            ')
            ->filter(function ($query) use ($request) {
                if ($request->has('CT_CASEID')) {
                    $query->where('CT_CASEID', 'like', "%{$request->get('CT_CASEID')}%");
                }
                if ($request->has('CT_EPNO')) {
                    $query->where('CT_EPNO', 'like', "%{$request->get('CT_EPNO')}%");
                }
            });

        return $datatables->make(true);
    }
}
