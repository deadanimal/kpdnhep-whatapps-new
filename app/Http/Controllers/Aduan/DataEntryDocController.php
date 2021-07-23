<?php

namespace App\Http\Controllers\Aduan;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Aduan\DataEntryDoc;
use Auth;
use Carbon\Carbon;
use Illuminate\Support\Facades\Storage;
use Image;
use Yajra\DataTables\Facades\DataTables;

class DataEntryDocController extends Controller
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
//    public function create()
    public function create($id)
    {
        return view('aduan.dataentrydoc.create', compact('id'));
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
                $resize = Image::make($file)->resize(null, 4096, function ($constraint) { // returns Intervention\Image\Image
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
            $mCaseDoc = new DataEntryDoc;
            $mCaseDoc->CC_CASEID = $request->CC_CASEID;
            $mCaseDoc->CC_PATH = Storage::disk('bahan')->url($directory);
            $mCaseDoc->CC_IMG = $filename;
            $mCaseDoc->CC_IMG_NAME = $file->getClientOriginalName();
            $mCaseDoc->CC_REMARKS = $request->CC_REMARKS;
            $mCaseDoc->CC_IMG_CAT = 1;
            if($mCaseDoc->save()) {
                return redirect()->route('dataentry.edit', ['id' => $request->CC_CASEID, '#attachment']);
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
        $dataEntryDoc = DataEntryDoc::find($id);
        return view('aduan.dataentrydoc.edit', compact('dataEntryDoc'));
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
        $mDataEntryDoc = DataEntryDoc::find($id);
        $file = $request->file('file');
        $date = date('YmdHis');
        $userid = Auth()->user()->id;
        $Year = date('Y');
        $Month = date('m');
        if ($file) {
            Storage::delete($mDataEntryDoc->CC_PATH.$mDataEntryDoc->CC_IMG); // Delete old attachment
            $filename = $userid.'_'.$mDataEntryDoc->CC_CASEID.'_'.$date.'.'.$file->getClientOriginalExtension(); // Store new attachment
            $directory = '/'.$Year.'/'.$Month.'/';
            if($file->getClientSize() > 2000000) // if file size lebih 2mb
            {
                $resize = Image::make($file)->resize(null, 4096, function ($constraint) { // returns Intervention\Image\Image
                    $constraint->aspectRatio();
                    $constraint->upsize();
                });
                $resize->stream();
                Storage::disk('bahan')->put($directory.$filename, $resize);
            } else {
                Storage::disk('bahan')->putFileAs('/'.$Year.'/'.$Month.'/', $request->file('file'), $filename);
            }
            // Update record
            $mDataEntryDoc->CC_PATH = Storage::disk('bahan')->url($directory);
            $mDataEntryDoc->CC_IMG = $filename;
            $mDataEntryDoc->CC_IMG_NAME = $file->getClientOriginalName();
            $mDataEntryDoc->CC_REMARKS = $request->CC_REMARKS;
            // Save record
            if ($mDataEntryDoc->save()) {
                return redirect()->route('dataentry.edit', ['id' => $mDataEntryDoc->CC_CASEID, '#attachment']);
            }
        } else {
            $mDataEntryDoc->CC_REMARKS = $request->CC_REMARKS;
            if ($mDataEntryDoc->save()) {
                return redirect()->route('dataentry.edit', ['id' => $mDataEntryDoc->CC_CASEID, '#attachment']);
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
        $model = DataEntryDoc::find($id);
        Storage::delete($model->CC_PATH.$model->CC_IMG);
        if($model->delete()){
            return redirect()->route('dataentry.edit', ['id' => $model->CC_CASEID, '#attachment']);
        }
    }
    
    public function __construct()
    {
        $this->middleware(['auth']);
    }
    
    public function getdatadoc($id)
    {
        $mCaseDoc = DataEntryDoc::where(['CC_CASEID' => $id, 'CC_IMG_CAT' => 1]);
        $datatables = DataTables::of($mCaseDoc)
            ->addIndexColumn()
            ->editColumn('CC_IMG_NAME', function(DataEntryDoc $dataEntryDoc) {
                if($dataEntryDoc->CC_IMG_NAME != '')
                    return '<a href='.Storage::disk('bahanpath')->url($dataEntryDoc->CC_PATH.$dataEntryDoc->CC_IMG).' target="_blank">'.$dataEntryDoc->CC_IMG_NAME.'</a>';
                else
                    return '';
            })
            ->editColumn('updated_at', function(DataEntryDoc $dataEntryDoc) {
                if($dataEntryDoc->updated_at != '')
                    return $dataEntryDoc->updated_at ? with(new Carbon($dataEntryDoc->updated_at))->format('d-m-Y h:i A') : '';
                else
                    return '';
            })
            ->addColumn('action', function(DataEntryDoc $dataEntryDoc) {
                return view('aduan.dataentrydoc.actionbutton', compact('dataEntryDoc'))->render();
            })
            ->rawColumns(['CC_IMG_NAME', 'action'])
        ;
        return $datatables->make(true);
    }
}
