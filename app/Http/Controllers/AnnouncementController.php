<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\PortalCms;
use Yajra\DataTables\Facades\DataTables;

class AnnouncementController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    public function index()
    {
        return view('announcement.index');
    }

    public function getdatatable(Request $request)
    {
        $mAnnouncemetn = PortalCms::whereIn('cat', [7, 8, 9]);
        $datatables = DataTables::of($mAnnouncemetn)
            ->addIndexColumn()
            ->editColumn('status', function (PortalCms $mAnnouncemetn) {
                if ($mAnnouncemetn->status == 1) {
                    return 'Aktif';
                } else {
                    return 'Tidak Aktif';
                }
            })
            ->editColumn('cat', function (PortalCms $mAnnouncemetn) {
                if ($mAnnouncemetn->cat == 7) {
                    return 'Pengguna Awam';
                } else if ($mAnnouncemetn->cat == 8) {
                    return 'Pengguna Dalaman';
                } else {
                    return 'Pengguna Awam & Dalaman';
                }
            })
            ->addColumn('action', '
                <a href="{{ route("announcement.edit", $id) }}" class="btn btn-xs btn-primary" data-toggle="tooltip" data-placement="right" title="Kemaskini">
                <i class="fa fa-pencil"></i></a>
            ')
            ->filter(function ($query) use ($request) {
                if ($request->has('title_my')) {
                    $query->where('title_my', 'like', "%{$request->get('title_my')}%");
                }
                if ($request->has('title_en')) {
                    $query->where('title_en', 'like', "%{$request->get('title_en')}%");
                }
            });
        return $datatables->make(true);
    }

    public function create()
    {
        return view('announcement.create');
    }

    public function store(Request $request)
    {
        $model = new PortalCms();
        $model->fill(request()->all());
//        $model->cat = 7;
        if ($model->save()) {
            $request->session()->flash('success', 'Rekod telah berjaya ditambah');
            return redirect()->route('announcement.index');
        }
    }

    public function show($id)
    {
        //
    }

    public function edit($id)
    {
        $model = PortalCms::find($id);
        return view('announcement.edit', compact('model'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request $request
     * @param  int $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        $model = PortalCms::find($id);
        $model->fill(request()->all());
        if ($model->save()) {
            $request->session()->flash('success', 'Maklumat telah berjaya dikemaskini');
            return redirect()->route('announcement.index');
        }
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
    }
}
