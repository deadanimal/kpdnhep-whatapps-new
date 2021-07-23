<?php

namespace App\Http\Controllers;

use App\Letter;
use App\Log;
use Illuminate\Http\Request;
use Yajra\DataTables\Facades\DataTables;
use DB;
use PDF;

class LetterController extends Controller {

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index() {
        return view('letter.index'); // 
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create() {
        return view('letter.create'); // 
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request) {
        $this->validate($request, [
            'title' => 'required|max:100',
            'letter_type' => 'required',
            'letter_code' => 'required|max:10',
        ]);

        $mLetter = new Letter;
        $mLetter->title = request('title');
        $mLetter->header = request('header');
        $mLetter->body = request('body');
        $mLetter->footer = request('footer');
        $mLetter->letter_type = request('letter_type');
        $mLetter->letter_cat = request('letter_cat');
        $mLetter->letter_code = request('letter_code');
        $mLetter->status = request('status');
        if ($mLetter->save()) {
            $mLog = new Log;
            $mLog->details = $request->path();
            $mLog->parameter = $mLetter->id;
            $mLog->ip_address = $request->ip();
            $mLog->user_agent = $request->header('User-Agent');
            if ($mLog->save()) {
                $request->session()->flash('success', 'Templat Surat telah berjaya ditambah');
                return redirect('/letter');
            }
        }
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Letter  $letter
     * @return \Illuminate\Http\Response
     */
//    public function show(Letter $id) {
    public function show($id) {
        $mLetter = DB::table('template_letter')->find($id);
//        $letter = Letter::findOrFail($id);
//        return view('letter.read');
//        return $mLetter;
        return view('letter.read', compact('mLetter', 'id'));
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Letter  $letter
     * @return \Illuminate\Http\Response
     */
//    public function edit(Letter $id) {
    public function edit($id) {
        $mLetter = DB::table('template_letter')->find($id);
//        $mLetter = Letter::find($id);
        return view('letter.edit', compact('mLetter', 'id'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Letter  $letter
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id) {
//    public function update($id) {
        $this->validate($request, [
            'title' => 'required|max:100',
            'letter_type' => 'required',
            'letter_code' => 'required|max:10',
        ]);

        $mLetter = Letter::find($id); //
        $mLetter->update(request()->all());

        if ($mLetter->save()) {
            $mLog = new Log;
            $mLog->details = $request->path();
            $mLog->parameter = $mLetter->id;
            $mLog->ip_address = $request->ip();
            $mLog->user_agent = $request->header('User-Agent');
            if ($mLog->save()) {
                $request->session()->flash('success', 'Templat Surat telah berjaya dikemaskini');
                return redirect('/letter');
            }
        }
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param int $id
     * @return \Illuminate\Http\Response
     */
//    * @param  \App\Letter  $letter
//    public function destroy(Letter $letter) {
    public function destroy($id) {
//        $mLetter = Letter::find($id);//
//        $mLetter->delete($id);   
//        return redirect('/letter');
//        $mLetter = Letter::findOrFail($id);
//        $mLetter->delete();
//        return redirect('letter.index');
//        $mLetter = Letter::find($id);
//        $mLetter->delete();
//
//        Session::flash('message', 'Successfully deleted the letter!');
//        return redirect('letter.index');

        Letter::find($id)->delete();
        session()->flash('success', 'Templat Surat telah berjaya dihapus');
        return redirect()->back();
    }

    public function delete(Request $request, $id) {
        $mLetter = Letter::find($id);
        $mLetter->delete($id);

        $mLog = new Log;
        $mLog->details = $request->path();
        $mLog->parameter = $mLetter->id;
        $mLog->ip_address = $request->ip();
        $mLog->user_agent = $request->header('User-Agent');
        if ($mLog->save()) {
            session()->flash('success', 'Templat Surat telah berjaya dihapus');
            return redirect('/letter');
//            redirect()->back();
        }
    }

    public function get_datatable(Datatables $datatables, Request $request) {
        $mLetter = 
            // DB::table('template_letter')->
            Letter::
            select([
                'id', 'title', 'header', 'body', 'footer', 'letter_type', 'letter_cat', 'letter_code', 'status'
            ]);

//        $datatables = Datatables::of($mLetter)->addColumn('action', function ($letter) {
//            return '<a href="letter/edit/' . $letter->id . '" class="btn btn-xs btn-primary"><i class="fa fa-pencil"></i></a>'
//                    . ' <a href="letter/show/' . $letter->id . '" class="btn btn-xs btn-primary"><i class="fa fa-eye"></i></a>'
//                    . ' <a href="letter/destroy/' . $letter->id . '" class="btn btn-xs btn-danger" method="Delete" data-confirm="Are you sure you want to delete?"><i class="fa fa-trash"></i></a>'
//                    ;});

        $datatables = Datatables::of($mLetter)
            ->addIndexColumn()
            ->editColumn('letter_type', function ($letter) {
                return Letter::ShowType($letter->letter_type);
            })
            ->editColumn('letter_code', function ($letter) {
                // return Letter::StatusAduan($letter->letter_code);
                if($letter->letter_code != ''){
                    if($letter->cainvsts){
                        return $letter->cainvsts->descr;
                    } else if($letter->ininvsts){
                        return $letter->ininvsts->descr;
                    } else {
                        return $letter->letter_code;
                    }
                } else {
                    return '';
                }
            })
            ->editColumn('status', function ($letter) {
                return Letter::ShowStatus($letter->status);
            })
            ->addColumn('action', '
                <a href="{{ url(\'letter\show\', $id) }}" class="btn btn-xs btn-success" data-toggle="tooltip" data-placement="right" title="Lihat">
                    <i class="fa fa-eye" ></i>
                </a>
                <a href="{{ url(\'letter\pdf\', $id)}}" class="btn btn-xs btn-info btn-sm" data-toggle="tooltip" data-placement="right" target="_blank" title="Cetak PDF">
                    <i class="fa fa-file-pdf-o"></i>
                </a>
                <a href="{{ url(\'letter\edit\', $id) }}" class="btn btn-xs btn-primary" data-toggle="tooltip" data-placement="right" title="Kemaskini">
                    <i class="fa fa-pencil"></i>
                </a>
                '.
//                    <a href="{{ url(\'letter\destroy\', $id) }}" class="btn btn-xs btn-danger" data-toggle="tooltip" data-placement="right" title="Hapus">
//                        <i class="fa fa-trash" onclick = "return confirm(\'{{ trans("action.delete") }}\')"></i>
//                    </a>
        '
                <a href="{{ url(\'letter\delete\', $id) }}" class="btn btn-xs btn-danger" data-toggle="tooltip" onclick = "return confirm(\'{{ trans("action.delete") }}\')" data-placement="right" title="Hapus">
                    <i class="fa fa-trash" ></i>
                </a> '
            )
            ->filter(function ($query) use ($request) {
                if ($request->has('title')) {
                    $query->where('title', 'like', "%{$request->get('title')}%");
                }
                if ($request->has('letter_type')) {
                    $query->where('letter_type', '=', $request->get('letter_type'));
                }
                if ($request->has('status')) {
                    $status = explode('-', $request->get('status'));
                    $query->where('status', $status[1]);
                }
                if ($request->has('letter_code')) {
                    $query->where('letter_code', '=', $request->get('letter_code'));
                }
//                if ($title = $datatables->request->get('title')) {
//                $datatables->where('title', 'like', "%$title%");
//                }
//                if ($letter_type = $datatables->request->get('letter_type')) {
//                $datatables->where('letter_type', '=', $letter_type);
//                }
//                if ($letter_code = $datatables->request->get('letter_code')) {
//                $datatables->where('letter_code', 'like', "%$letter_code%");
//                }
//        if ($status = $datatables->request->get('status')) {
//            $datatables->where('status', '=', $status);
//        }
//        if ($status = $datatables->request->get('status')) {
//        $stat = explode('-', $status);
////            $datatables->where('status', '=', $status);
//        $datatables->where('status', '=', $stat[1]);
//        }

        });

//        return Datatables::of($refs)
//                ->addColumn('action', function ($ref) {
//                    return '<a href="#edit-'.$ref->id.'" class="btn btn-xs btn-primary"><i class="glyphicon glyphicon-edit"></i> Edit</a>';
//                })
//                ->make(true);
//        print_r($datatables->request->get());exit;

        return $datatables->make(true);
    }

    public function __construct() {
        $this->middleware('auth');
    }

    public function pdf($id) {
        $mLetter = Letter::find($id);
        $data = [
            'mLetter' => $mLetter,
        ];
        $pdf = PDF::loadView('letter.pdf', $data);
        return $pdf->stream('document.pdf');
    }

}
