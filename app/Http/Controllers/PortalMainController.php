<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Yajra\DataTables\Facades\DataTables;
use App\PortalCms;

class PortalMainController extends Controller
{
    public function index()
    {
        return view('portal-main.index');
    }

    public function GetDataTable(Request $Request)
    {
        $PortalMain = PortalCms::where('cat', '1');
        $datatables = DataTables::of($PortalMain)
            ->addIndexColumn()
            ->addColumn('action', '
                <a href="{{ url("portal-main/edit", $id) }}" class="btn btn-xs btn-primary" data-toggle="tooltip" data-placement="right" title="Kemaskini">
                <i class="fa fa-pencil"></i></a>
            ')
//            ->filter(function ($query) use ($Request) {
//                if ($Request->has('title_my')) {
//                    $query->where('title_my', 'like', "%{$Request->get('title_my')}%");
//                }
//                if ($Request->has('title_en')) {
//                    $query->where('title_en', 'like', "%{$Request->get('title_en')}%");
//                }
//            })
        ;
        return $datatables->make(true);
    }
    
    public function create()
    {
        //
    }

    public function store(Request $request)
    {
        //
    }

    public function show($id)
    {
        //
    }

    public function edit($id)
    {
        $model = PortalCms::find($id);
        return view('portal-main.edit', compact('model'));
    }

    public function update(Request $request, $id)
    {dd($request);
        $model = PortalCms::find($id);
        $model->fill(Request()->all());
        if($model->save()) {
            $request->session()->flash('success', 'Keterangan telah berjaya dikemaskini');
            return redirect()->back();
        }
    }

    public function destroy($id)
    {
        //
    }
}
