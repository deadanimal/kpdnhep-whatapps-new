<?php

namespace App\Http\Controllers\Pertanyaan;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Pertanyaan\PertanyaanPublicDoc;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use Yajra\DataTables\DataTables;
use Image;

class PertanyaanPublicDocController extends Controller
{
    public function __construct()
    {
        $this->middleware(['locale','auth']);
    }
    
    public function index()
    {
        //
    }

    public function GetDataTable($id)
    {
        $mPertanyaanPublicDoc = PertanyaanPublicDoc::where(['askid' => $id]);
        
        $datatables = DataTables::of($mPertanyaanPublicDoc)
                ->addIndexColumn()
                ->editColumn('img_name', function(PertanyaanPublicDoc $PertanyaanPublicDoc) {
                    if($PertanyaanPublicDoc->img_name != '')
                    return '<a href='.Storage::disk('bahanpath')->url($PertanyaanPublicDoc->path.$PertanyaanPublicDoc->img).' target="_blank">'.$PertanyaanPublicDoc->img_name.'</a>';
                    else
                    return '';
                })
                ->addColumn('action', function (PertanyaanPublicDoc $PertanyaanPublicDoc) {
                    return view('pertanyaan.pertanyaan-public-doc.AttachmentActionBtn', compact('PertanyaanPublicDoc'))->render();
                })
                ->rawColumns(['img_name','action']);
        return $datatables->make(true);
    }
    
    public function create($id)
    {
        return view('pertanyaan.pertanyaan-public-doc.create', compact('id'));
    }

    public function store(Request $request)
    {
        $date = date('Ymdhis');
        $userid = Auth::user()->id;
        $Year = date('Y');
        $Month = date('m');
        $file = $request->file('file');
        
        if($file) {
            $filename = $userid.'_'.$request->askid.'_'.$date.'.'.$file->getClientOriginalExtension();
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
            
                $mPublicCaseDoc = new PertanyaanPublicDoc();
                $mPublicCaseDoc->askid = $request->askid;
                $mPublicCaseDoc->path = Storage::disk('bahan')->url($directory);
                $mPublicCaseDoc->img = $filename;
                $mPublicCaseDoc->img_name = $file->getClientOriginalName();
                $mPublicCaseDoc->remarks = $request->remarks;
                if($mPublicCaseDoc->save()) {
                    return redirect()->route('pertanyaan.attachment',$request->askid);
                }
        }
    }

    public function show($id)
    {
        //
    }

    public function edit($id)
    {
        $model = PertanyaanPublicDoc::find($id);
        return view('pertanyaan.pertanyaan-public-doc.edit', compact('model', 'id'));
    }

    public function update(Request $request, $id)
    {
        $model = PertanyaanPublicDoc::find($id);
        $date = date('YmdHis');
        $userid = Auth::user()->id;
        $Year = date('Y');
        $Month = date('m');
        $file = $request->file('file');
        if($file) {
            Storage::delete($model->path.$model->img); // Delete old attachment
            $filename = $userid.'_'.$model->askid.'_'.$date.'.'.$file->getClientOriginalExtension();
            $directory = '/'.$Year.'/'.$Month.'/';
            Storage::disk('bahan')->makeDirectory($directory);
            if($file->getClientSize() > 2000000) // if file size lebih 2mb
            {
                $resize = Image::make($file)->resize(null, 4096, function ($constraint) { // returns Intervention\Image\Image
                    $constraint->aspectRatio();
                    $constraint->upsize();
                });
                $hash = md5($resize->__toString()); // calculate md5 hash of encoded image
                $path = "images/{$hash}.{$file->getClientOriginalExtension()}"; // use hash as a name
                $resize->save(public_path($path)); // save it locally to ~/public/images/{$hash}.jpg
                Storage::disk('bahan')->put($directory.$filename, $resize->__toString());
                unlink($path);
            }
            else
            {
                Storage::disk('bahan')->putFileAs('/'.$Year.'/'.$Month.'/', $request->file('file'), $filename);
            }
            $model->path = Storage::disk('bahan')->url($directory);
            $model->img = $filename;
            $model->img_name = $file->getClientOriginalName();
            $model->remarks = $request->remarks;
            if($model->save()) {
                return redirect()->route('pertanyaan.attachment', $model->askid);
            }
        } else {
            $model->remarks = $request->remarks;
            if ($model->save()) {
                return redirect()->route('pertanyaan.attachment', $model->askid);
            }
        }
    }

    public function destroy($id)
    {
        $model = PertanyaanPublicDoc::find($id);
        Storage::delete($model->path.$model->img);
        if($model->delete()){
            return redirect()->route('pertanyaan.attachment',$model->askid);
        }
    }
}
