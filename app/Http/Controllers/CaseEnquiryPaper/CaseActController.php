<?php

namespace App\Http\Controllers\CaseEnquiryPaper;

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
     * Datatable Case Act, By EP number.
     */
    public function dt(Request $request)
    {
        $caseActEps = PenyiasatanKes::whereNotNull('CT_EPNO');

        $datatables = DataTables::of($caseActEps)
            ->addIndexColumn()
            ->addColumn('action', '
                <a class="btn btn-xs btn-success" onclick="selectCaseEpNo({{ $id }})"><i class="fa fa-check"></i></a>
            ')
            ->filter(function ($query) use ($request) {
                if ($request->has('icnew')) {
                    $query->where('icnew', 'like', "%{$request->get('icnew')}%");
                }
                if ($request->has('name')) {
                    $query->where('name', 'like', "%{$request->get('name')}%");
                }
                if ($request->has('state_cd')) {
                    $query->where('state_cd', $request->get('state_cd'));
                }
                if ($request->has('brn_cd')) {
                    $query->where('brn_cd', $request->get('brn_cd'));
                }
            });

        return $datatables->make(true);
    }

    /**
     * Get Case Act detail by EP number.
     */
    public function getcaseactdetail($epno)
    {
        $caseActDetail = PenyiasatanKes::where('id', $epno)
            ->pluck('id', 'CT_CASEID');
        return json_encode($caseActDetail);
    }
}
