<?php

namespace App\Http\Controllers\Aduan;

use App\Http\Controllers\Controller;
use App\Models\Cases\CaseReasonTemplate;
use App\Ref;
use Illuminate\Http\Request;
use Yajra\DataTables\Facades\DataTables;

class CaseReasonTemplateController extends Controller
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
        return view('aduan.casereasontemplate.index');
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $categories = [
            'AD51' => 'AD51',
            'AD52' => 'AD52',
        ];
        $activeStatus = [
            '1' => 'AKTIF',
            '0' => 'TIDAK AKTIF',
        ];
        return view('aduan.casereasontemplate.create', compact('categories', 'activeStatus'));
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
            'category' => 'required|max:10',
            'code' => 'required|unique:case_reason_templates|max:10',
            'descr' => 'required|max:190',
            'sort' => 'required|unique:case_reason_templates|max:100|integer',
            'status' => 'required|max:1',
        ],
        [
            'category.required' => 'Ruangan Kategori diperlukan.',
            'category.max' => 'Ruangan Kategori mesti diperlukan.',
            'status.required' => 'Ruangan Status diperlukan.',
            'status.max' => 'Ruangan Status mesti diperlukan.',
        ]);
        $caseReasonTemplate = new CaseReasonTemplate();
        $caseReasonTemplate->fill($request->all())->save();
        return redirect()->route('casereasontemplates.index');
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        abort(404);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $caseReasonTemplate = CaseReasonTemplate::find($id);
        if (empty($caseReasonTemplate)) {
            return redirect()->route('casereasontemplates.index');
        }
        $categories = [
            'AD51' => 'AD51',
            'AD52' => 'AD52',
        ];
        $activeStatus = [
            '1' => 'AKTIF',
            '0' => 'TIDAK AKTIF',
        ];
        return view('aduan.casereasontemplate.edit', compact('caseReasonTemplate', 'categories', 'activeStatus'));
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
            'category' => 'required|max:10',
            'code' => 'required|max:10',
            'descr' => 'required|max:190',
            'sort' => 'required|max:100|integer',
            'status' => 'required|max:1',
        ],
        [
            'category.required' => 'Ruangan Kategori diperlukan.',
            'category.max' => 'Ruangan Kategori mesti diperlukan.',
            'status.required' => 'Ruangan Status diperlukan.',
            'status.max' => 'Ruangan Status mesti diperlukan.',
        ]);
        $caseReasonTemplate = CaseReasonTemplate::find($id);
        if (empty($caseReasonTemplate)) {
            return redirect()->route('casereasontemplates.index');
        }
        $caseReasonTemplate->update($request->all());
        return redirect()->route('casereasontemplates.index');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        abort(404);
    }

    /**
     * get DT data
     *
     * @param Request $request
     * @return mixed
     * @throws \Exception
     */
    public function dt()
    {
        $data = CaseReasonTemplate::all();
        $datatables = DataTables::of($data)
            ->addIndexColumn()
            ->editColumn('status', function (CaseReasonTemplate $caseReasonTemplate) {
                if ($caseReasonTemplate->status == '1'){
                    return '<span class = "badge badge-primary">AKTIF</span>';
                } else if ($caseReasonTemplate->status == '0'){
                    return '<span class = "badge badge-danger">TIDAK AKTIF</span>';
                }
            })
            ->addColumn('action', '
                <a href="{{ route("casereasontemplates.edit", $id) }}" class="btn btn-xs btn-success" data-toggle="tooltip" data-placement="right" title="Kemaskini">
                <i class="fa fa-edit"></i></a>
            ')
            ->rawColumns(['status', 'action'])
            ;
        return $datatables->make(true);
    }
}
