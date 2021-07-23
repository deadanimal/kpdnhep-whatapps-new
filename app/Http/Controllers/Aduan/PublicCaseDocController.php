<?php

namespace App\Http\Controllers\Aduan;

use App\Aduan\PublicCaseDoc;
use App\Attachment;
use App\Http\Controllers\Controller;
use App\User;
use Auth;
use Carbon\Carbon;
use DB;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Storage;
use Image;
use Validator;
use Yajra\DataTables\DataTables;

class PublicCaseDocController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function __construct()
    {
        $this->middleware(['locale','auth']);
    }
    
    public function index()
    {
    }

    public function GetDatatable($id) 
    {
        $mPublicCase = \App\Aduan\PublicCase::where(['id' => $id])->first();
        if($mPublicCase->CA_INVSTS == '7') {
            $mPublicCaseDoc = PublicCaseDoc::where(['CC_CASEID' => $mPublicCase->CA_CASEID])->orWhere(['CC_CASEID' => $id]);
        }else{
            $mPublicCaseDoc = PublicCaseDoc::where(['CC_CASEID' => $id])->orWhere(['CC_CASEID' => $mPublicCase->CA_CASEID]);
        }
        
        $datatables = DataTables::of($mPublicCaseDoc)
                ->addIndexColumn()
                ->editColumn('CC_IMG_NAME', function(PublicCaseDoc $PublicCaseDoc) {
                    if($PublicCaseDoc->CC_IMG_NAME != '')
                    return '<a href='.Storage::disk('bahanpath')->url($PublicCaseDoc->CC_PATH.$PublicCaseDoc->CC_IMG).' target="_blank">'.$PublicCaseDoc->CC_IMG_NAME.'</a>';
                    else
                    return '';
                })
                ->addColumn('action', function (PublicCaseDoc $PublicCaseDoc) use ($mPublicCase){
                    $PublicCase = $mPublicCase;
                    return view('aduan.public-case-doc.AttachmentActionBtn', compact('PublicCaseDoc','PublicCase'))->render();
                })
                ->rawColumns(['CC_IMG_NAME','action']);
        return $datatables->make(true);
    }
    
    public function create($id)
    {
        return view('aduan.public-case-doc.create', compact('id'));
    }

    public function store(Request $request)
    {
        $date = date('YmdHis');
        $userid = Auth::user()->id;
        $Year = date('Y');
        $Month = date('m');
        $file = $request->file('file');

        if($file) {
            $fileExtension = $file->getClientOriginalExtension();
            $resizeFileExtensions = ['jpeg', 'png', 'gif'];
            $filename = $userid.'_'.$request->CC_CASEID.'_'.$date.'.'.$file->getClientOriginalExtension();
            $directory = '/'.$Year.'/'.$Month.'/';
//            Storage::disk('bahan')->makeDirectory($directory);

            // if($file->getClientSize() > 2000000) // if file size lebih 2mb
            // if file size more than 2mb, and readable file format for resize
            if(($file->getClientSize() > 2000000) && in_array(strtolower($fileExtension), $resizeFileExtensions))
            {
                $resize = Image::make($file)->resize(null, 600, function ($constraint) { // returns Intervention\Image\Image
                    $constraint->aspectRatio();
                    $constraint->upsize();
                });
//                $hash = md5($resize->__toString()); // calculate md5 hash of encoded image
//                $path = "images/{$hash}.jpg"; // use hash as a name
//                $resize->save(public_path($path)); // save it locally to ~/public/images/{$hash}.jpg
                $resize->stream();
                Storage::disk('bahan')->put($directory.$filename, $resize);
//                unlink($path);
            }
            else
            {
                Storage::disk('bahan')->putFileAs('/'.$Year.'/'.$Month.'/', $request->file('file'), $filename);
            }
            
            $mPublicCaseDoc = new \App\Aduan\PublicCaseDoc();
            $mPublicCaseDoc->CC_CASEID = $request->CC_CASEID;
            $mPublicCaseDoc->CC_PATH = Storage::disk('bahan')->url($directory);
            $mPublicCaseDoc->CC_IMG = $filename;
            $mPublicCaseDoc->CC_IMG_NAME = $file->getClientOriginalName();
            $mPublicCaseDoc->CC_REMARKS = $request->CC_REMARKS;
            $mPublicCaseDoc->CC_IMG_CAT = 1;
            if($mPublicCaseDoc->save()) {
                return redirect()->back();
            }
        }
    }
    
    public function edit($id) {
        $mPublicCaseDoc = PublicCaseDoc::find($id);
        return view('aduan.public-case-doc.edit', compact('mPublicCaseDoc'));
    }
    
    public function update(Request $request, $id)
    {
        $mPublicCaseDoc = PublicCaseDoc::find($id);

        $file = $request->file('file');
        $date = date('YmdHis');
        $userid = Auth()->user()->id;
        $Year = date('Y');
        $Month = date('m');

        if ($file) {
            Storage::delete($mPublicCaseDoc->CC_PATH.$mPublicCaseDoc->CC_IMG); // Delete old attachment
            $fileExtension = $file->getClientOriginalExtension();
            $resizeFileExtensions = ['jpeg', 'png', 'gif'];
            $filename = $userid.'_'.$mPublicCaseDoc->CC_CASEID.'_'.$date.'.'.$file->getClientOriginalExtension(); // Store new attachment
            $directory = '/'.$Year.'/'.$Month.'/';

            // if($file->getClientSize() > 2000000) // if file size lebih 2mb
            // if file size more than 2mb, and readable file format for resize
            if(($file->getClientSize() > 2000000) && in_array(strtolower($fileExtension), $resizeFileExtensions))
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
            $mPublicCaseDoc->CC_PATH = Storage::disk('bahan')->url($directory);
            $mPublicCaseDoc->CC_IMG = $filename;
            $mPublicCaseDoc->CC_IMG_NAME = $file->getClientOriginalName();
            $mPublicCaseDoc->CC_REMARKS = $request->CC_REMARKS;
            // Save record
            if ($mPublicCaseDoc->save()) {
                return redirect()->back();
            }
        } else {
            $mPublicCaseDoc->CC_REMARKS = $request->CC_REMARKS;
            if ($mPublicCaseDoc->save()) {
                return redirect()->back();
            }
        }
    }

    public function destroy($id)
    {
        $model = PublicCaseDoc::find($id);
        Storage::delete($model->CC_PATH.$model->CC_IMG);
        if($model->delete()){
            return redirect()->back();
//            return redirect()->route('public-case.attachment',$model->CC_CASEID);
//            return redirect('/public-case/'.$model->CC_CASEID.'/edit#attachment');
        }
    }
}