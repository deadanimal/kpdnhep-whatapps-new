<?php

namespace App\Http\Controllers;

use App\Tip;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Yajra\DataTables\Facades\DataTables;

class TipController extends Controller
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
        return view('tip.index');
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        return view('tip.create');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $this->validate($request, array_add(Tip::$rules, 'NO_KES', 'required|unique:tip'));
        Tip::create($request->all());
        return redirect()->route('tips.index')
            ->with('success', 'Data Kes telah <b>Berjaya</b> disimpan.');
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
        $tip = Tip::where('NO_KES', $id)->first();
        if (empty($tip)) {
            return redirect()->route('tips.index')
                ->with('alert', 'Maklumat Data Kes tidak dijumpai.');
        }
        return view('tip.edit', compact('id', 'tip'));
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
        $this->validate($request, Tip::$rules);
        Tip::updateOrCreate(['NO_KES' => $id], $request->all());
        return redirect()->route('tips.index')
            ->with('success', 'Data Kes telah <b>Berjaya</b> dikemaskini.');
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
     */
    public function dt(Request $request)
    {
        if($request['search_ind'] == '1') {
            $data = Tip::select('NO_KES', 'ASAS_TINDAKAN', 'TKH_KEJADIAN');
        } else {
            $data = [];
        }
        $datatables = DataTables::of($data)
            ->addIndexColumn()
            ->editColumn('TKH_KEJADIAN', function(Tip $tip) {
                return $tip->TKH_KEJADIAN ? with(new Carbon($tip->TKH_KEJADIAN))->format('d-m-Y') : '';
            })
            ->addColumn('action', '
                <a href="{{ route("tips.edit", $NO_KES) }}" class="btn btn-xs btn-success" data-toggle="tooltip" data-placement="right" title="Kemaskini">
                <i class="fa fa-edit"></i></a>
            ')
            ->rawColumns(['action'])
            ->filter(function ($query) use ($request) {
                if ($request->has('date_start')) {
                    $query->whereDate('TKH_KEJADIAN', '>=', Carbon::parse($request->get('date_start'))->toDateString());
                }
                if ($request->has('date_end')) {
                    $query->whereDate('TKH_KEJADIAN', '<=', Carbon::parse($request->get('date_end'))->toDateString());
                }
                if ($request->has('no_kes')) {
                    $query->where('NO_KES', 'LIKE', "%{$request->get('no_kes')}%");
                }
            });
        return $datatables->make(true);
    }
}
