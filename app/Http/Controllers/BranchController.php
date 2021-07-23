<?php

namespace App\Http\Controllers;

use App\Branch;
use Illuminate\Http\Request;
use Yajra\DataTables\Facades\DataTables;
use DB;
use App\Log;
use App\Ref;

class BranchController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        return view('branch.indexbrn');//
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
//        return view('branch.create');
        return view('branch.createbrn');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $this->validate($request, [
            'BR_BRNCD' => 'required|max:5|alpha_num|unique:sys_brn',
            'BR_BRNNM' => 'required|max:60',
            'BR_ADDR1' => 'max:100',
            'BR_ADDR2' => 'max:100',
            'BR_POSCD' => 'max:10',
            'BR_STATECD' => 'required',
            'BR_DISTCD' => 'required',
            'BR_TELNO' => 'max:20',
            'BR_FAXNO' => 'max:20',
            'BR_REFNM' => 'max:200',
            'BR_REFADD1' => 'max:100',
            'BR_REFADD2' => 'max:100',
            'BR_REFADD3' => 'max:100',
        ]);

        $mBranch = new Branch;
        $mBranch->BR_BRNCD = request('BR_BRNCD');
        $mBranch->BR_BRNNM = request('BR_BRNNM');
        $mBranch->BR_ADDR1 = request('BR_ADDR1');
        $mBranch->BR_ADDR2 = request('BR_ADDR2');
        $mBranch->BR_POSCD = request('BR_POSCD');
        $mBranch->BR_STATECD = request('BR_STATECD');
        $mBranch->BR_DISTCD = request('BR_DISTCD');
        $mBranch->BR_TELNO = request('BR_TELNO');
        $mBranch->BR_FAXNO = request('BR_FAXNO');
        $mBranch->BR_EMAIL = request('BR_EMAIL');
        $mBranch->BR_REFNM = request('BR_REFNM');
        $mBranch->BR_REFADD1 = request('BR_REFADD1');
        $mBranch->BR_REFADD2 = request('BR_REFADD2');
        $mBranch->BR_REFADD3 = request('BR_REFADD3');
        $mBranch->BR_DEPTCD = request('BR_DEPTCD');
        $mBranch->BR_STATUS = request('BR_STATUS');
        $mBranch->BR_OTHSTATE = request('BR_OTHSTATE');
        $arrOthDist = '';
        foreach (request('BR_OTHDIST') as $othdist) {
            $arrOthDist .= $othdist . ',';
        }
        $arrOthDist = rtrim($arrOthDist, ",");
        $mBranch->BR_OTHDIST = $arrOthDist;
        if ($mBranch->save()) {
            $mLog = new Log;
            $mLog->details = $request->path();
            $mLog->parameter = $mBranch->BR_BRNCD;
            $mLog->ip_address = $request->ip();
            $mLog->user_agent = $request->header('User-Agent');
            if ($mLog->save()) {
                $request->session()->flash('success', 'Maklumat Cawangan telah berjaya ditambah');
                return redirect('/branch');
            }
        }
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Branch $branch
     * @return \Illuminate\Http\Response
     */
//    public function show(Branch $branch)
    public function show($branch_code)
    {
        $mBranch = Branch::find($branch_code);//
        return view('branch.showbrn');
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Branch $branch
     * @return \Illuminate\Http\Response
     */
//    public function edit(Branch $branch)
    public function edit($branch_code)
    {
        $mBranch = Branch::find($branch_code);
        $ListOthDist = DB::table('sys_ref')
            ->select('code', 'descr')
            ->where('cat', "18")
            ->where('code', 'LIKE', "$mBranch->BR_OTHSTATE%")
            ->orderBy('descr')
            ->get();
        $arrOthDist = explode(',', $mBranch->BR_OTHDIST);
        return view('branch.editbrn', compact('mBranch', 'ListOthDist', 'arrOthDist'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request $request
     * @param  \App\Branch $branch
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $branch_code)
    {
        $this->validate($request, [
            'BR_BRNNM' => 'required|max:60',
            'BR_ADDR1' => 'max:100',
            'BR_ADDR2' => 'max:100',
            'BR_POSCD' => 'max:10',
            'BR_STATECD' => 'required',
            'BR_DISTCD' => 'required',
            'BR_TELNO' => 'max:20',
            'BR_FAXNO' => 'max:20',
            'BR_REFNM' => 'max:200',
            'BR_REFADD1' => 'max:100',
            'BR_REFADD2' => 'max:100',
            'BR_REFADD3' => 'max:100',
        ]);
//        dd($request);
        $mBranch = Branch::find($branch_code);
        $mBranch->fill(request()->all());
        if ($request->BR_OTHDIST) {
            $arrOthDist = '';
            foreach (request('BR_OTHDIST') as $othdist) {
                $arrOthDist .= $othdist . ',';
            }
            $arrOthDist = rtrim($arrOthDist, ",");
            $mBranch->BR_OTHDIST = $arrOthDist;
        }

        if ($mBranch->save()) {
            $mLog = new Log;
            $mLog->details = $request->path();
            $mLog->parameter = $mBranch->BR_BRNCD;
            $mLog->ip_address = $request->ip();
            $mLog->user_agent = $request->header('User-Agent');
            if ($mLog->save()) {
                $request->session()->flash('success', 'Maklumat Cawangan telah berjaya dikemaskini');
                return redirect('branch');
            }
        }
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Branch $branch
     * @return \Illuminate\Http\Response
     */
//    public function destroy(Branch $branch)
    public function destroy($branch_code)
    {
        $mBranch = Branch::find($branch_code);//
        $mBranch->delete($branch_code);
        return redirect('/branch');
    }

//    public function delete($branch_code)
    public function delete(Request $request, $branch_code)
    {
        $mBranch = Branch::find($branch_code);//
        $mBranch->delete($branch_code);
        $mLog = new Log;
        $mLog->details = $request->path();
        $mLog->parameter = $mBranch->BR_BRNCD;
        $mLog->ip_address = $request->ip();
        $mLog->user_agent = $request->header('User-Agent');
        if ($mLog->save()) {
            session()->flash('success', 'Maklumat Cawangan telah berjaya dihapus');
            return redirect('/branch');
        }
    }

    public function get_datatable(DataTables $datatables, Request $request)
    {
        $mBranch = DB::table('sys_brn')
            ->select(['BR_BRNCD', 'BR_BRNNM', 'BR_DISTCD', 'BR_POSCD', 'BR_STATECD', 'BR_TELNO',
                'BR_FAXNO', 'BR_OTHDIST', 'BR_REFNM', 'BR_DEPTCD', 'BR_OTHSTATE', 'BR_STATUS']);

        $datatables = DataTables::of($mBranch)
            ->addIndexColumn()
            ->editColumn('BR_STATECD', function ($branch) {
                return Branch::Negeri($branch->BR_STATECD);
            })
            ->editColumn('BR_STATUS', function ($branch) {
                return Branch::ShowStatus($branch->BR_STATUS);
            })
            ->addColumn('action', '
                        <a href="{{ url(\'branch\edit\', $BR_BRNCD) }}" class="btn btn-xs btn-primary" data-toggle="tooltip" data-placement="right" title="Kemaskini">
                            <i class="fa fa-pencil"></i>
                        </a>
                ')
            ->filter(function ($query) use ($request) {
                if ($request->has('BR_BRNCD')) {
                    $query->where('BR_BRNCD', 'like', "%{$request->get('BR_BRNCD')}%");
                }
                if ($request->has('BR_BRNNM')) {
                    $query->where('BR_BRNNM', 'like', "%{$request->get('BR_BRNNM')}%");
                }
                if ($request->has('BR_STATECD')) {
                    $query->where('BR_STATECD', $request->get('BR_STATECD'));
                }
                if ($request->has('BR_STATUS')) {
                    $status = explode('-', $request->get('BR_STATUS'));
                    $query->where('BR_STATUS', $status[1]);
                }
            });

        return $datatables->make(true);
    }

    public function __construct()
    {
        $this->middleware('auth');
    }

    public function GetDistList($state_cd)
    {
//        $mDistList = DB::table('sys_ref')
//            ->where('code', 'LIKE', $state_cd.'%')->where('code', '!=', $state_cd)
//            ->where('cat', '18')->pluck('descr', 'code');
        $mDistList = DB::table('sys_ref')
            ->where('code', 'LIKE', $state_cd . '%')
            ->where('code', '!=', $state_cd)
            ->where('cat', '18')
            ->orderBy('descr', 'asc')
            ->pluck('code', 'descr');
        $mDistList->prepend('', '-- SILA PILIH --');
        return json_encode($mDistList);
    }
}
