<?php

namespace App\Http\Controllers\Pertanyaan;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Pertanyaan\PertanyaanAdminDoc;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use Yajra\DataTables\Facades\DataTables;
use Validator;
use Image;
use Carbon\Carbon;

class PertanyaanAdminDocJawapanController extends Controller
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
        return view('pertanyaan.pertanyaan-admin-doc-jawapan.create', compact('id'));
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
        if ($request->userid) {
            $userid = $request->userid;
        } else {
            $userid = Auth::user()->id;
        }
        $Year = date('Y');
        $Month = date('m');
        $file = $request->file('file');
        if($file) {
            $filename = $userid.'_'.$request->askid.'_'.$date.'.'.$file->getClientOriginalExtension();
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
            $mPublicCaseDoc = new PertanyaanAdminDoc();
            $mPublicCaseDoc->askid = $request->askid;
            $mPublicCaseDoc->path = Storage::disk('bahan')->url($directory);
            $mPublicCaseDoc->img = $filename;
            $mPublicCaseDoc->img_name = $file->getClientOriginalName();
            $mPublicCaseDoc->remarks = $request->remarks;
            $mPublicCaseDoc->img_cat = 2;
            if($mPublicCaseDoc->save()) {
                if ($request->userid) {
                    return response()->json(['data' => 'ok']);
                }
                return response()->json(['success']);
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
        $model = PertanyaanAdminDoc::find($id);
        return view('pertanyaan.pertanyaan-admin-doc-jawapan.edit', compact('model', 'id'));
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
        $model = PertanyaanAdminDoc::find($id);
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
                $resize = Image::make($file)->resize(null, 600, function ($constraint) { // returns Intervention\Image\Image
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
            $model->img_cat = 2;
            if($model->save()) {
//                return redirect()->route('pertanyaan-admin.attachment', $model->askid);
                return response()->json(['success']);
            }
        } else {
            $model->remarks = $request->remarks;
            if ($model->save()) {
//                return redirect()->route('pertanyaan-admin.attachment', $model->askid);
                return response()->json(['success']);
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
        //
        $model = PertanyaanAdminDoc::findOrFail($id);
        Storage::delete($model->path.$model->img);
        if($model->delete()){
//            return redirect()->route('pertanyaan.attachment',$model->askid);
            return response()->json(['success']);
        }
    }
    
    public function __construct() 
    {
        $this->middleware('auth');
    }
    
    public function getdatatable($id)
    {
        $mPertanyaanAdminDoc = PertanyaanAdminDoc::where([
            'askid' => $id,
            'img_cat' => '2',
            ]);
        $datatables = DataTables::of($mPertanyaanAdminDoc)
            ->addIndexColumn()
            ->editColumn('img_name', function(PertanyaanAdminDoc $PertanyaanAdminDoc) {
                if($PertanyaanAdminDoc->img_name != ''){
                    return '<a href='.Storage::disk('bahanpath')
                        ->url($PertanyaanAdminDoc->path.$PertanyaanAdminDoc->img).' target="_blank">'
                        .$PertanyaanAdminDoc->img_name.'</a>';
                } else {
                    return '';
                }
            })
            ->editColumn('updated_at', function(PertanyaanAdminDoc $PertanyaanAdminDoc) {
                if($PertanyaanAdminDoc->updated_at != ''){
                    return $PertanyaanAdminDoc->updated_at ? 
                        with(new Carbon($PertanyaanAdminDoc->updated_at))->format('d-m-Y h:i A') : '';
                }
                else {
                    return '';
                }
            })
            ->addColumn('action', function (PertanyaanAdminDoc $PertanyaanAdminDoc) {
                return view('pertanyaan.pertanyaan-admin-doc-jawapan.actionbutton', 
                    compact('PertanyaanAdminDoc'))->render();
            })
            ->rawColumns(['img_name','action']);
        return $datatables->make(true);
    }
}
