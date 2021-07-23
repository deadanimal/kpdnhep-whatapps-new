<?php

namespace App\Http\Controllers\Pertanyaan;

use App\Http\Controllers\Controller;
use App\Models\Cases\CaseInfo;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Yajra\DataTables\Facades\DataTables;

/**
 * Class ConsumerComplaintController
 *
 * @package App\Http\Controllers\Pertanyaan
 */
class ConsumerComplaintController extends Controller
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
     * GET /inquiry/consumercomplaints
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        $input = $request->all();
        $search_status = $input['search_status'] ?? '0';
        if ($search_status) {
            $caseInfos = CaseInfo::join('sys_users', 'case_info.CA_INVBY', '=', 'sys_users.id')
                ->select('case_info.id', 'case_info.CA_CASEID', 'case_info.CA_ANSWER', 'case_info.CA_INVBY',
                    'case_info.CA_RCVDT', 'sys_users.icnew', 'sys_users.name')
                ->where([
                    ['CA_CASEID', '<>', null],
                    ['CA_RCVTYP', '<>', null],
                    ['CA_RCVTYP', '<>', ''],
                    ['CA_CMPLCAT', '<>', ''],
                    ['CA_INVSTS', '!=', '10']
                ]);
        } else {
            $caseInfos = [];
        }
        $datatables = DataTables::of($caseInfos)
            ->addIndexColumn()
            ->editColumn('CA_ANSWER', function ($caseInfo) {
                return $caseInfo->CA_ANSWER ?
                    implode(' ', array_slice(explode(' ', ucfirst($caseInfo->CA_ANSWER)), 0, 7)) . '...'
                    : '';
            })
            ->editColumn('CA_RCVDT', function ($caseInfo) {
                return $caseInfo->CA_RCVDT ? with(new Carbon($caseInfo->CA_RCVDT))->format('d-m-Y h:i A') : '';
            })
            ->addColumn('investigator_action', function ($caseInfo) {
                return '<a onclick="selectComplaint('.$caseInfo->id.')" class="btn btn-xs btn-primary">
                    <i class="fa fa-check"></i>
                    </a>';
            })
            ->rawColumns(['investigator_action'])
            ->filter(function ($query) use ($request) {
                $query->where('CA_CASEID', 'like', "%{$request->get('CA_CASEID')}%");
                $query->where(function ($query) use ($request) {
                    $query->where('sys_users.name', 'LIKE', "%{$request->get('investigator')}%");
                });
            });
        return $datatables->make(true);
    }

    /**
     * Display the specified resource.
     * GET /inquiry/consumercomplaints/{id}
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $caseInfo = CaseInfo::join('sys_users', 'case_info.CA_INVBY', '=', 'sys_users.id')
            ->select('case_info.id', 'case_info.CA_CASEID', 'case_info.CA_ANSWER', 'case_info.CA_INVBY',
                'case_info.CA_RCVDT', 'sys_users.icnew', 'sys_users.name')
            ->where('case_info.id', $id)
            ->first();
        return response()->json($caseInfo);
    }
}
