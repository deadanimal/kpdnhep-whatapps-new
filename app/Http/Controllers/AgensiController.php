<?php

namespace App\Http\Controllers;

use App\Agensi;
use Illuminate\Http\Request;
use Yajra\DataTables\Facades\DataTables;

class AgensiController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function __construct()
    {
        $this->middleware('auth');
    }

    public function index()
    {
        return view('agensi.index');
    }

    public function GetData(Request $request)
    {
        $mAgensi = Agensi::orderBy('MI_MINCD', 'ASC');

        $datatables = Datatables::of($mAgensi)
            ->addIndexColumn()
            ->editColumn('MI_STS', function (Agensi $Agensi) {
                return Agensi::ShowStatus($Agensi->MI_STS);
            })
            ->addColumn('action', function (Agensi $Agensi) {
                return view('agensi.indexactionbtn', compact('Agensi'));
            })
            ->filter(function ($query) use ($request) {
                if ($request->has('MI_MINCD')) {
                    $query->where('MI_MINCD', 'LIKE', "%{$request->get('MI_MINCD')}%");
                }
                if ($request->has('MI_DESC')) {
                    $query->where('MI_DESC', 'LIKE', "%{$request->get('MI_DESC')}%");
                }
                if ($request->has('status')) {
                    $status = explode('-', $request->get('status'));
                    $query->where('MI_STS', $status[1]);
                }
            });
        return $datatables->make(true);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
//        echo 'Berjaya';exit;
        return view('agensi.create');
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
            'MI_ADDR' => 'required',
            'MI_POSCD' => 'required',
            'MI_STATECD' => 'required',
            'MI_DISTCD' => 'required',
            'MI_DESC' => 'required',
            'MI_EMAIL' => 'required',
            'MI_STS' => 'required',
            'MI_MINTYP' => 'required',
        ]);
        $model = new Agensi;
        $model->fill($request->all());
        if ($model->save()) {
            return redirect()->route('agensi.index')->with('success', 'Maklumat agensi telah BERJAYA ditambah');
        }
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Agensi $agensi
     * @return \Illuminate\Http\Response
     */
    public function show(Agensi $agensi)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Agensi $agensi
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $model = Agensi::find($id);
        return view('agensi.edit', compact('model'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request $request
     * @param  \App\Agensi $agensi
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        $this->validate($request, [
            'MI_ADDR' => 'required',
            'MI_POSCD' => 'required',
            'MI_STATECD' => 'required',
            'MI_DISTCD' => 'required',
            'MI_DESC' => 'required',
            'MI_EMAIL' => 'required',
            'MI_STS' => 'required',
            'MI_MINTYP' => 'required',
        ],
            [
                'MI_ADDR.required' => 'Ruangan Alamat diperlukan.',
                'MI_POSCD.required' => 'Ruangan Poskod diperlukan.',
                'MI_STATECD.required' => 'Ruangan Negeri diperlukan.',
                'MI_DISTCD.required' => 'Ruangan Daerah diperlukan.',
                'MI_DESC.required' => 'Ruangan Nama Agensi/Kementerian diperlukan.',
                'MI_EMAIL.required' => 'Ruangan Email diperlukan.',
                'MI_STS.required' => 'Ruangan Status diperlukan.',
                'MI_MINTYP.required' => 'Ruangan Jenis Agensi diperlukan.',
            ]);

        $model = Agensi::find($id);
        $model->fill($request->all());
        if ($model->save()) {
            return redirect()->route('agensi.index')->with('success', 'Maklumat agensi telah BERJAYA dikemaskini');
        }
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Agensi $agensi
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {

    }
}
