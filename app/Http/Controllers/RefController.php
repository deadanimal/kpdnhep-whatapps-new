<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Ref;
use App\Letter;
use App\Log;
use Yajra\DataTables\Facades\DataTables;
use DB;
use Nexmo;
use PDF;

class RefController extends Controller {

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function __construct() {
        $this->middleware('auth');
    }

    public function Pdf() {
        $mLetter = Letter::find('1'); // Contoh surat
        $data = [
            'mLetter' => $mLetter,
        ];
        $pdf = PDF::loadView('ref.pdf', $data);
        return $pdf->stream('document.pdf');
    }

    public function SendSms() {
        $client = new Nexmo\Client(new Nexmo\Client\Credentials\Basic('9f9076de', 'b1b1c105899cbfea'));
        $message = $client->message()->send([
            'to' => '+60199757703',
            'from' => '+60199757703',
            'text' => 'Test message from the Nexmo PHP Client'
        ]);

        echo 'Berjaya';
        exit;
    }

    public function IndexKat() {
        return view('ref.indexkat');
    }

    public function GetDatatableKat(Datatables $datatables, Request $request) {
        $mRef = DB::table('sys_ref')
                ->select(['id', 'cat', 'code', 'descr', 'sort'])
                ->where(['cat' => 'KAT']);
//                ->whereNotIn('id',['17','18']);

        $datatables = Datatables::of($mRef)
                ->addIndexColumn()
                ->addColumn('action', '
                    <a href="{{ route("listparam", $id) }}" class="btn btn-xs btn-success"><i class="fa fa-bars" data-toggle="tooltip" data-placement="right" title="Senarai"></i></a>
                    <a href="{{ route("ref.editkat", $id) }}" class="btn btn-xs btn-primary" data-toggle="tooltip" data-placement="right" title="Kemaskini"><i class="fa fa-pencil"></i></a>
                    <a href="{{ route("ref.deletekat", $id) }}" class="btn btn-xs btn-danger" data-toggle="tooltip" onclick = "return confirm(\'{{ trans("action.delete") }}\')" data-placement="right" title="Hapus"><i class="fa fa-trash"></i></a>
                ')
//                                    <a href="{{ url(\'ref\listparam\', $id) }}" class="btn btn-xs btn-success"><i class="fa fa-bars" data-toggle="tooltip" data-placement="right" title="Senarai"></i></a>
//                                    <a href="{{ url(\'ref\editkat\', $id) }}" class="btn btn-xs btn-primary" data-toggle="tooltip" data-placement="right" title="Kemaskini"><i class="fa fa-pencil"></i></a>
//                                    <a href="{{ url(\'ref\deletekat\', $id) }}" class="btn btn-xs btn-danger" data-toggle="tooltip" onclick = "return confirm(\'{{ trans("action.delete") }}\')" data-placement="right" title="Hapus"><i class="fa fa-trash"></i></a>')
                ->filter(function ($query) use ($request) {
                    if ($request->has('cat')) {
                        $query->where('id', $request->get('cat'));
                    }
                    if ($request->has('descr')) {
                        $query->where('descr', 'like', "%{$request->get('descr')}%");
                    }
                });

//        if ($descr = $datatables->request->get('descr')) {
//            $datatables->where('descr', 'like', "%$descr%");
//        }
//        echo '<pre>';
//        print_r($datatables->make(true));
//        echo '</pre>';exit;
        return $datatables->make(true);
    }

    public function CreateKat() {
        return view('ref.createkat');
    }

    public function StoreKat(Request $request) {
        $this->validate($request, [
            'descr' => 'required|max:150',
        ]);

        $model = new Ref;
        $model->cat = 'KAT';
        $model->descr = request('descr');
        $model->status = '1';
        if ($model->save()) {
            $modelUpdate = Ref::find($model->id);
            $modelUpdate->code = $model->id;
            if ($modelUpdate->save()) {
                $mLog = new Log;
                $mLog->details = $request->path();
                $mLog->parameter = $model->id;
                $mLog->ip_address = $request->ip();
                $mLog->user_agent = $request->header('User-Agent');
                if($mLog->save()) {
                    $request->session()->flash('success', 'Kategori parameter telah berjaya ditambah');
                    return redirect('/ref');
                }
            }
        }
    }

    public function DeleteKat(request $request, $id) {
        $mRefParamFind = Ref::where('cat', $id)->first();
        if ($mRefParamFind === NULL) {
            $mRef = Ref::find($id);
            if ($mRef->delete()) {
                $mLog = new Log;
                $mLog->details = $request->path();
                $mLog->parameter = $id;
                $mLog->ip_address = $request->ip();
                $mLog->user_agent = $request->header('User-Agent');
                if($mLog->save()) {
                    session()->flash('success', 'Kategori parameter telah berjaya dihapus');
                    return redirect()->back();
                }
            }
        } else {
            $mRefParam = DB::table('sys_ref')->where('cat', $id);
            if ($mRefParam->delete()) {
                $mRef = Ref::find($id);
                if ($mRef->delete()) {
                    $mLog = new Log;
                    $mLog->details = $request->path();
                    $mLog->parameter = $id;
                    $mLog->ip_address = $request->ip();
                    $mLog->user_agent = $request->header('User-Agent');
                    if($mLog->save()) {
                        session()->flash('success', 'Kategori parameter telah berjaya dihapus');
                        return redirect()->back();
                    }
                }
            }
        }
    }

    public function ListParam($id) {
        $mRef = Ref::find($id);
        return view('ref.listkat', compact('mRef'));
    }

    public function GetDatatableParam(Datatables $datatables, Request $request, $id) {
        $mRef = DB::table('sys_ref')
                ->select(['id', 'cat', 'code', 'descr', 'descr_en', 'status', 'sort'])
                ->where(['cat' => $id]);

        $datatables = Datatables::of($mRef)
                ->addIndexColumn()
                ->editColumn('status', function ($ref) {
                    return Ref::ShowStatus($ref->status);
                })
                ->addColumn('action', '
                    <a href="{{ route("ref.editparam", $id) }}" class="btn btn-xs btn-primary" data-toggle="tooltip" data-placement="right" title="Kemaskini"><i class="fa fa-pencil"></i></a>
                    <a href="{{ route("ref.deleteparam", $id) }}" class="btn btn-xs btn-danger" data-toggle="tooltip" data-placement="right" title="Hapus"><i class="fa fa-trash" onclick = "return confirm(\'{{ trans("action.delete") }}\')"></i></a>
                ')
//                                    <a href="{{ url(\'ref\editparam\', $id) }}" class="btn btn-xs btn-primary" data-toggle="tooltip" data-placement="right" title="Kemaskini"><i class="fa fa-pencil"></i></a>
//                                    <a href="{{ url(\'ref\deleteparam\', $id) }}" class="btn btn-xs btn-danger" data-toggle="tooltip" data-placement="right" title="Hapus"><i class="fa fa-trash" onclick = "return confirm(\'{{ trans("action.delete") }}\')"></i></a>')
                ->filter(function ($query) use ($request) {
                    if ($request->has('descr')) {
                        $query->where('descr', 'like', "%{$request->get('descr')}%");
                    }
                    if ($request->has('code')) {
                        $query->where('code', 'like', "%{$request->get('code')}%");
                    }
                    if ($request->has('status')) {
                        $status = explode('-', $request->get('status'));
                        $query->where('status', $status[1]);
                    }
                })
                ;
        return $datatables->make(true);
    }

    public function CreateParam($id) {
        return view('ref.createparam', compact('id'));
    }

    public function StoreParam(Request $request) {
        $this->validate($request, [
            'cat' => 'required|max:25',
            'code' => 'required|max:25',
            'descr' => 'required|max:150',
            'sort' => 'required',
            'status' => 'required',
        ]);
        
        $model = new Ref;
        $model->cat = request('cat');
        $model->code = request('code');
        $model->descr = request('descr');
        $model->descr_en = request('descr_en');
        $model->sort = request('sort');
        $model->status = request('status');
        if($model->save()) {
            $mLog = new Log;
            $mLog->details = $request->path();
            $mLog->parameter = $model->id;
            $mLog->ip_address = $request->ip();
            $mLog->user_agent = $request->header('User-Agent');
            if($mLog->save()) {
                $request->session()->flash('success', 'Parameter telah berjaya ditambah');
                return redirect()->route('listparam', [request('cat')]);
            }
        }
    }

    public function EditParam($id) {
        $mRef = Ref::find($id);
        return view('ref.editparam', compact('mRef'));
    }

    public function PatchParam(Request $r, $id) {
//        dd($r);exit;
        $mRef = Ref::find($id);
        $mRef->code = $r->code;
        $mRef->descr = $r->descr;
        $mRef->descr_en = $r->descr_en;
        $mRef->sort = $r->sort;
        $mRef->status = $r->status;
        if ($mRef->save()) {
            $mLog = new Log;
            $mLog->details = $r->path();
            $mLog->parameter = $mRef->id;
            $mLog->ip_address = $r->ip();
            $mLog->user_agent = $r->header('User-Agent');
            if($mLog->save()) {
                return redirect()->route('listparam', [$mRef->cat]);
            }
        }
    }

    public function DeleteParam(Request $r, $id) {
        Ref::find($id)->delete();
        $mLog = new Log;
        $mLog->details = $r->path();
        $mLog->parameter = $id;
        $mLog->ip_address = $r->ip();
        $mLog->user_agent = $r->header('User-Agent');
        if($mLog->save()) {
            session()->flash('success', 'Parameter telah berjaya dihapus');
            return redirect()->back();
        }
    }

    public function show($id) {
        $model = Ref::find($id);

        return view('ref.show');
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function EditKat($id) {
        $mRef = Ref::find($id);
        return view('ref.editkat', compact('mRef'));
    }

    public function PutKat(Request $r, $id) {
        $mRef = Ref::find($id);
        $mRef->code = $r->code;
        $mRef->descr = $r->descr;
        if ($mRef->save()) {
            $mLog = new Log;
            $mLog->details = $r->path();
            $mLog->parameter = $id;
            $mLog->ip_address = $r->ip();
            $mLog->user_agent = $r->header('User-Agent');
            if($mLog->save()) {
                return redirect('/ref');
            }
        }
    }

    public function edit($id) {

        $mRef = Ref::find($id);
        return view('ref.edit')->with(compact('descr'))->with('mRef', $mRef);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function updateKategori($id) {

        $mRef = Ref::find($id);
        $mRef->update($id);
//                (request()->all());





        return redirect('/ref');
//            ->back();
//        $mRef->fill(Input::all())->save();
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id) {
        $mRef = Ref::find($id);

        $mRef->delete($id);

//        Session::flash('flash_message', 'Task successfully deleted!');
//        return redirect()->route('tasks.index');
        return redirect('/ref');
    }

}
