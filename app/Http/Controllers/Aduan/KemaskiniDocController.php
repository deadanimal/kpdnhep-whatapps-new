<?php

namespace App\Http\Controllers\Aduan;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use Yajra\DataTables\Facades\DataTables;
use Carbon\Carbon;
use Image;
//use Validator;
use App\Aduan\KemaskiniDoc;

class KemaskiniDocController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        //
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create($id)
    {
        return view('aduan.kemaskini.doccreate', compact('id'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $date = date('YmdHis');
        $userid = Auth::user()->id;
        $Year = date('Y');
        $Month = date('m');
        $file = $request->file('file');
        if($file) {
            $filename = $userid.'_'.$request->CC_CASEID.'_'.$date.'.'.$file->getClientOriginalExtension();
            $directory = '/'.$Year.'/'.$Month.'/';
            if($file->getClientSize() > 2000000) // if file size lebih 2mb
            {
                $resize = Image::make($file)->resize(null, 600, function ($constraint) { // returns Intervention\Image\Image
                    $constraint->aspectRatio();
                    $constraint->upsize();
                });
                $resize->stream();
                Storage::disk('bahan')->put($directory.$filename, $resize);
            }
            else
            {
                Storage::disk('bahan')->putFileAs('/'.$Year.'/'.$Month.'/', $request->file('file'), $filename);
            }
            $mKemaskiniDoc = new KemaskiniDoc();
            $mKemaskiniDoc->CC_CASEID = $request->CC_CASEID;
            $mKemaskiniDoc->CC_PATH = Storage::disk('bahan')->url($directory);
            $mKemaskiniDoc->CC_IMG = $filename;
            $mKemaskiniDoc->CC_IMG_NAME = $file->getClientOriginalName();
            $mKemaskiniDoc->CC_REMARKS = $request->CC_REMARKS;
            $mKemaskiniDoc->CC_IMG_CAT = '1';
            if($mKemaskiniDoc->save()) {
                return redirect()->back();
            }
        }
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $modelDoc = KemaskiniDoc::find($id);
        return view('aduan.kemaskini.docedit', compact('modelDoc'));
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
        $modelDoc = KemaskiniDoc::find($id);
        $file = $request->file('file');
        $date = date('YmdHis');
        $userid = Auth()->user()->id;
        $Year = date('Y');
        $Month = date('m');
        if ($file) {
            Storage::delete($modelDoc->CC_PATH.$modelDoc->CC_IMG); // Delete old attachment
            $filename = $userid.'_'.$modelDoc->CC_CASEID.'_'.$date.'.'.$file->getClientOriginalExtension(); // Store new attachment
            $directory = '/'.$Year.'/'.$Month.'/';
            if($file->getClientSize() > 2000000) // if file size lebih 2mb
            {
                $resize = Image::make($file)->resize(null, 600, function ($constraint) { // returns Intervention\Image\Image
                    $constraint->aspectRatio();
                    $constraint->upsize();
                });
                $resize->stream();
                Storage::disk('bahan')->put($directory.$filename, $resize->__toString());
            }
            else
            {
                Storage::disk('bahan')->putFileAs('/'.$Year.'/'.$Month.'/', $request->file('file'), $filename);
            }
            
            // Update record
            $modelDoc->CC_PATH = Storage::disk('bahan')->url($directory);
            $modelDoc->CC_IMG = $filename;
            $modelDoc->CC_IMG_NAME = $file->getClientOriginalName();
            $modelDoc->CC_REMARKS = $request->CC_REMARKS;
            // Save record
            if ($modelDoc->save()) {
                return redirect()->back();
//                return redirect()->route('admin-case.attachment', $modelDoc->CC_CASEID);
            }
        } else {
            $modelDoc->CC_REMARKS = $request->CC_REMARKS;
            if ($modelDoc->save()) {
                return redirect()->back();
//                return redirect()->route('admin-case.attachment', $modelDoc->CC_CASEID);
            }
        }
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $model = KemaskiniDoc::find($id);
        Storage::delete($model->CC_PATH.$model->CC_IMG);
        if($model->delete()){
            return redirect()->back();
        }
    }
    
    public function __construct()
    {
        $this->middleware('auth');
    }
    
    public function getdatatable($id)
    {
        $model = KemaskiniDoc::where('CC_CASEID', $id)
            ->where('CC_IMG_CAT', '1')
        ;
        $datatables = DataTables::of($model)
            ->addIndexColumn()
            ->editColumn('CC_IMG_NAME', function(KemaskiniDoc $kemaskiniDoc) {
                if($kemaskiniDoc->CC_IMG_NAME != ''){
                    return '<a href='.Storage::disk('bahanpath')->url($kemaskiniDoc->CC_PATH.$kemaskiniDoc->CC_IMG).' target="_blank">'.$kemaskiniDoc->CC_IMG_NAME.'</a>';
                }
                else{
                    return '';
                }
            })
            ->editColumn('updated_at', function(KemaskiniDoc $kemaskiniDoc) {
                if($kemaskiniDoc->updated_at != ''){
                    return $kemaskiniDoc->updated_at ? with(new Carbon($kemaskiniDoc->updated_at))->format('d-m-Y h:i A') : '';
                }
                else{
                    return '';
                }
            })
            ->addColumn('action', function(KemaskiniDoc $kemaskiniDoc) {
                return view('aduan.kemaskini.docbuktiaduanaction', compact('kemaskiniDoc'))->render();
            })
            ->rawColumns(['CC_IMG_NAME', 'action'])
        ;
        return $datatables->make(true);
    }
}
