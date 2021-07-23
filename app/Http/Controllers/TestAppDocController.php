<?php

namespace App\Http\Controllers;

use App\Aduan\PublicCaseDoc;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use Image;
use Yajra\DataTables\Facades\DataTables;

class TestAppDocController extends Controller
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
    public function create()
    {
        return view('test.doccreate');
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
            // $filename = $userid.'_'.$request->CC_CASEID.'_'.$date.'.'.$file->getClientOriginalExtension();
            $filename = $userid.'_0_'.$date.'.'.$file->getClientOriginalExtension();
            $directory = '/'.$Year.'/'.$Month.'/';
            // Storage::disk('bahan')->makeDirectory($directory);
            if (in_array($file->getClientOriginalExtension(), ['jpg', 'jpeg', 'png', 'gif'])) {
                if($file->getClientSize() > 2000000) // if file size lebih 2mb
                {
                    $resize = Image::make($file)->resize(
                        null, 600, function ($constraint) { 
                            // returns Intervention\Image\Image
                            $constraint->aspectRatio();
                            $constraint->upsize();
                        }
                    );
                    // $hash = md5($resize->__toString()); // calculate md5 hash of encoded image
                    // $path = "images/{$hash}.jpg"; // use hash as a name
                    // $resize->save(public_path($path)); // save it locally to ~/public/images/{$hash}.jpg
                    $resize->stream();
                    Storage::disk('bahan')->put($directory.$filename, $resize);
                    // unlink($path);
                }
                else
                {
                    Storage::disk('bahan')->putFileAs('/'.$Year.'/'.$Month.'/', $request->file('file'), $filename);
                }
            }
            else if (in_array($file->getClientOriginalExtension(), ['pdf'])) {
                // dd($request, $model, $count, $file, $file->getClientSize());
                if($file->getClientSize() < 2000000) // if file size lebih 2mb
                {
                    // dd($request, $model, $count, $file);
                    Storage::disk('bahan')->putFileAs('/'.$Year.'/'.$Month.'/', $request->file('file'), $filename);
                }
                else {
                    // session()->flash(
                    //     'alert', 
                    //     Auth::user()->lang == 'ms' 
                    //         ? 'Saiz fail format pdf mesti tidak melebihi 2 MB.' 
                    //         : 'Attachment format pdf size must not exceed 2 MB.'
                    // );
                    $request->session()->flash(
                        'alert', 
                        Auth::user()->lang == 'ms' 
                            ? 'Saiz fail format pdf mesti tidak melebihi 2 MB.' 
                            : 'Attachment format pdf size must not exceed 2 MB.'
                    );
                    return redirect()->back();
                }
            }
            else {
                $request->session()->flash(
                    'alert', 
                    Auth::user()->lang == 'ms' 
                        ? 'Lampiran anda gagal dimuat naik.' 
                        : 'Attachment upload failed.'
                );
                return redirect()->back();
            }
            
            $mDoc = new PublicCaseDoc();
            // $mDoc->CC_CASEID = $request->CC_CASEID;
            $mDoc->CC_CASEID = 0;
            $mDoc->CC_PATH = Storage::disk('bahan')->url($directory);
            $mDoc->CC_IMG = $filename;
            $mDoc->CC_IMG_NAME = $file->getClientOriginalName();
            $mDoc->CC_REMARKS = $request->CC_REMARKS;
            // $mDoc->CC_IMG_CAT = 1;
            if($mDoc->save()) {
                $request->session()->flash(
                    'success', 'Lampiran telah berjaya dimuat naik.'
                );
                return redirect()->back();
                // return redirect()
                //     ->back()
                //     ->with('success', 'Lampiran telah berjaya dimuat naik.');
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
        //
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
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $model = PublicCaseDoc::find($id);
        Storage::delete($model->CC_PATH.$model->CC_IMG);
        if($model->delete()){
            return redirect()->back()->with('success', 'Lampiran telah berjaya dihapus.');
        }
    }

    public function __construct()
    {
        $this->middleware(['locale','auth']);
    }

    public function getdatatable()
    {
        $mPublicCaseDoc = PublicCaseDoc::
            where(['CC_CASEID' => 0])
            ->whereNull('CC_IMG_CAT')
            ;
        
        $datatables = DataTables::of($mPublicCaseDoc)
            ->addIndexColumn()
            ->editColumn('CC_IMG_NAME', function(PublicCaseDoc $PublicCaseDoc) {
                if($PublicCaseDoc->CC_IMG_NAME != ''){
                    return '<a href='.Storage::disk('bahanpath')->url($PublicCaseDoc->CC_PATH.$PublicCaseDoc->CC_IMG).' target="_blank">'.$PublicCaseDoc->CC_IMG_NAME.'</a>';
                } else {
                    return '';
                }
            })
            ->editColumn('CC_REMARKS', function(PublicCaseDoc $PublicCaseDoc) {
                if($PublicCaseDoc->CC_REMARKS != ''){
                    // return implode(' ', array_slice(explode(' ', ucfirst($PublicCaseDoc->CC_REMARKS)), 0, 7)) . '...';
                    return nl2br(htmlspecialchars(substr($PublicCaseDoc->CC_REMARKS, 0, 20) . '...'));
                } else {
                    return '';
                }
            })
            ->editColumn('updated_at', function(PublicCaseDoc $PublicCaseDoc) {
                if($PublicCaseDoc->updated_at != ''){
                    return $PublicCaseDoc->updated_at ? with(new Carbon($PublicCaseDoc->updated_at))->format('d-m-Y h:i A') : '';
                } else {
                    return '';
                }
            })
            ->addColumn('action', function (PublicCaseDoc $PublicCaseDoc){
                return view('test.docactionbutton', compact('PublicCaseDoc'))->render();
            })
            ->rawColumns(['CC_IMG_NAME','CC_REMARKS','action']);
        return $datatables->make(true);
    }
}
