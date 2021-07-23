<?php

namespace App\Http\Controllers\Integriti;

use App\Http\Controllers\Controller;
use App\Integriti\IntegritiPublic;
use App\Integriti\IntegritiPublicDoc;
use App\Integriti\IntegritiPublicUser;
use App\Integriti\IntegritiPublicUserDoc;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use Image;
use PDF;
use Validator;
use Yajra\DataTables\Facades\DataTables;

class IntegritiPublicUserDocController extends Controller
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
    // public function create()
    public function create($id)
    {
        return view('integriti.publicuserdoc.create', compact('id'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        // dd($request);
        $model = IntegritiPublicUser::find($request->IC_CASEID);
        // dd($request, $model);
        if ($model) {
            // dd($request, $model);
            $count = IntegritiPublicUserDoc::
                where(function ($query) use ($request, $model) {
                    $query->where('IC_CASEID', '=', $request->IC_CASEID)
                        ->orWhere('IC_CASEID', '=', $model->IN_CASEID);
                })
                ->where(function ($query){
                    $query->whereNull('IC_DOCCAT')
                        ->orWhere('IC_DOCCAT', '=', '1');
                })
                ->count()
                ;
            // dd($request, $model, $count);
            if($count < 5){
                // dd($request, $model, $count);
                $date = date('YmdHis');
                // $userid = Auth::user()->id;
                $Year = date('Y');
                $Month = date('m');
                $file = $request->file('file');

                if($file) {
                    // dd($request, $model, $count, $file);
                    $filename = 
                    // $userid.
                    '_'.$request->IC_CASEID.'_'.$date.'.'.$file->getClientOriginalExtension();
                    $directory = '/'.$Year.'/'.$Month.'/';
                    // Storage::disk('bahan')->makeDirectory($directory);
                    if (in_array(strtolower($file->getClientOriginalExtension()), ['jpg', 'jpeg', 'png', 'gif'])) {
                        // dd($request, $model, $count, $file, $file->getClientOriginalExtension());
                        if($file->getClientSize() > 2000000) // if file size lebih 2mb
                        {
                            // dd($request, $model, $count, $file);
                            $resize = Image::make($file)->resize(null, 600, function ($constraint) { // returns Intervention\Image\Image
                                $constraint->aspectRatio();
                                $constraint->upsize();
                            });
            //                $hash = md5($resize->__toString()); // calculate md5 hash of encoded image
            //                $path = "images/{$hash}.jpg"; // use hash as a name
            //                $resize->save(public_path($path)); // save it locally to ~/public/images/{$hash}.jpg
                            $resize->stream();
                            // Storage::disk('bahan')->put($directory.$filename, $resize);
                            Storage::disk('integritibahan')->put($directory.$filename, $resize);
            //                unlink($path);
                        }
                        else {
                            // dd($request, $model, $count, $file);
                            // Storage::disk('bahan')->putFileAs('/'.$Year.'/'.$Month.'/', $request->file('file'), $filename);
                            Storage::disk('integritibahan')->putFileAs('/'.$Year.'/'.$Month.'/', $request->file('file'), $filename);
                        }
                    }
                    else if (in_array(strtolower($file->getClientOriginalExtension()), ['pdf'])) {
                        // dd($request, $model, $count, $file, $file->getClientSize());
                        if($file->getClientSize() < 2000000) // if file size lebih 2mb
                        {
                            // dd($request, $model, $count, $file);
                            // Storage::disk('bahan')->putFileAs('/'.$Year.'/'.$Month.'/', $request->file('file'), $filename);
                            Storage::disk('integritibahan')->putFileAs('/'.$Year.'/'.$Month.'/', $request->file('file'), $filename);
                        }
                        else {
                            // session()->flash(
                            //     'alert', 
                            //     Auth::user()->lang == 'ms' 
                            //         ? 'Saiz fail format pdf mesti tidak melebihi 2 MB.' 
                            //         : 'Attachment format pdf size must not exceed 2 MB.'
                            // );
                            return redirect()->back();
                        }
                    }
                    else {
                        // session()->flash(
                        //     'alert', 
                        //     Auth::user()->lang == 'ms' 
                        //         ? 'Lampiran anda gagal dimuat naik.' 
                        //         : 'Attachment upload failed.'
                        // );
                        return redirect()->back();
                    }
                    
                    $mPublicCaseDoc = new IntegritiPublicUserDoc();
                    $mPublicCaseDoc->IC_CASEID = $request->IC_CASEID;
                    // $mPublicCaseDoc->IC_PATH = Storage::disk('bahan')->url($directory);
                    $mPublicCaseDoc->IC_PATH = Storage::disk('integritibahan')->url($directory);
                    $mPublicCaseDoc->IC_DOCNAME = $filename;
                    $mPublicCaseDoc->IC_DOCFULLNAME = $file->getClientOriginalName();
                    $mPublicCaseDoc->IC_REMARKS = $request->IC_REMARKS;
                    $mPublicCaseDoc->IC_DOCCAT = 1;
                    if($mPublicCaseDoc->save()) {
                        return redirect()->back();
                    }
                }
                else {
                    // session()->flash(
                    //     'alert', 
                    //     Auth::user()->lang == 'ms' 
                    //         ? 'Lampiran anda gagal dimuat naik.' 
                    //         : 'Attachment upload failed.'
                    // );
                    return redirect()->back();
                }
            }
            else {
                // session()->flash(
                //     'alert', 
                //     Auth::user()->lang == 'ms' 
                //         ? 'Anda telah memuat naik maksimum 5 lampiran.' 
                //         : 'You have uploaded maximum of 5 attachment.'
                // );
                return redirect()->back();
            }
        }
        else {
            // session()->flash(
            //     'alert', 
            //     Auth::user()->lang == 'ms' 
            //         ? 'Lampiran anda gagal dimuat naik.' 
            //         : 'Attachment upload failed.'
            // );
            return redirect()->back();
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
        $mPublicCaseDoc = IntegritiPublicUserDoc::findOrFail($id);
        return view('integriti.publicuserdoc.edit', compact('id', 'mPublicCaseDoc'));
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
        // dd($request);
        $mPublicCaseDoc = IntegritiPublicUserDoc::find($id);
        if($mPublicCaseDoc){
            // dd($request, $mPublicCaseDoc);
            $file = $request->file('file');
            $date = date('YmdHis');
            // $userid = Auth()->user()->id;
            $Year = date('Y');
            $Month = date('m');

            if ($file) {
                // dd($request, $mPublicCaseDoc, $file, 'file');
                Storage::delete($mPublicCaseDoc->IC_PATH.$mPublicCaseDoc->IC_DOCNAME); // Delete old attachment
                $filename = 
                // $userid.
                '_'.$mPublicCaseDoc->IC_CASEID.'_'.$date.'.'.$file->getClientOriginalExtension(); // Store new attachment
                $directory = '/'.$Year.'/'.$Month.'/';
                if (in_array(strtolower($file->getClientOriginalExtension()), ['jpg', 'jpeg', 'png', 'gif'])) {
                    if($file->getClientSize() > 2000000) // if file size lebih 2mb
                    {
                        $resize = Image::make($file)->resize(null, 4096, function ($constraint) { // returns Intervention\Image\Image
                            $constraint->aspectRatio();
                            $constraint->upsize();
                        });
                        $resize->stream();
                        // Storage::disk('bahan')->put($directory.$filename, $resize);
                        Storage::disk('integritibahan')->put($directory.$filename, $resize);
                    } else {
                        // Storage::disk('bahan')->putFileAs('/'.$Year.'/'.$Month.'/', $request->file('file'), $filename);
                        Storage::disk('integritibahan')->putFileAs('/'.$Year.'/'.$Month.'/', $request->file('file'), $filename);
                    }
                }
                else if (in_array($file->getClientOriginalExtension(), ['pdf'])) {
                    if($file->getClientSize() < 2000000) // if file size lebih 2mb
                    {
                        // dd($request, $mPublicCaseDoc, $file, 'pdf < 2 mb');
                        // Storage::disk('bahan')->putFileAs('/'.$Year.'/'.$Month.'/', $request->file('file'), $filename);
                        Storage::disk('integritibahan')->putFileAs('/'.$Year.'/'.$Month.'/', $request->file('file'), $filename);
                    }
                    else {
                        // dd($request, $mPublicCaseDoc, $file, 'pdf > 2 mb');
                        // session()->flash(
                        //     'alert', 
                        //     Auth::user()->lang == 'ms' 
                        //         ? 'Saiz fail format pdf mesti tidak melebihi 2 MB.' 
                        //         : 'Attachment format pdf size must not exceed 2 MB.'
                        // );
                        return redirect()->back();
                    }
                }
                else {
                    // session()->flash(
                    //     'alert', 
                    //     Auth::user()->lang == 'ms' 
                    //         ? 'Lampiran anda gagal dimuat naik.' 
                    //         : 'Attachment upload failed.'
                    // );
                    return redirect()->back();
                }
                // Update record
                // $mPublicCaseDoc->IC_PATH = Storage::disk('bahan')->url($directory);
                $mPublicCaseDoc->IC_PATH = Storage::disk('integritibahan')->url($directory);
                $mPublicCaseDoc->IC_DOCNAME = $filename;
                $mPublicCaseDoc->IC_DOCFULLNAME = $file->getClientOriginalName();
                $mPublicCaseDoc->IC_REMARKS = $request->IC_REMARKS;
                // Save record
                if ($mPublicCaseDoc->save()) {
                    return redirect()->back();
                }
            } else {
                // dd($request, $mPublicCaseDoc, $file, ' no file');
                $mPublicCaseDoc->IC_REMARKS = $request->IC_REMARKS;
                if ($mPublicCaseDoc->save()) {
                    return redirect()->back();
                }
            }
        }
        else {
            return redirect()->back();
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
        $model = IntegritiPublicUserDoc::findOrFail($id);
        // Storage::disk('public')->delete($model->IC_PATH.$model->IC_DOCNAME);
        $model->delete();
        return redirect()->back();
    }

    public function getdatatable($id)
    {
        $model = IntegritiPublicUser::find($id);
        $modelCaseDoc = IntegritiPublicUserDoc::
            where(function ($query) use ($id, $model){
                $query->where('IC_CASEID', '=', $id)
                    ->orWhere('IC_CASEID', '=', $model->IN_CASEID);
            })
            ->where(function ($query){
                $query->whereNull('IC_DOCCAT')
                    ->orWhere('IC_DOCCAT', '=', '1');
            })
            ;
        
        $datatables = DataTables::of($modelCaseDoc)
            ->addIndexColumn()
            ->addColumn('action', function (IntegritiPublicUserDoc $PublicCaseDoc) use ($model){
                return view('integriti.publicuserdoc.actionbutton', compact('PublicCaseDoc','model'))->render();
            })
            ->editColumn('IC_DOCFULLNAME', function(IntegritiPublicUserDoc $PublicCaseDoc) {
                if($PublicCaseDoc->IC_DOCFULLNAME != ''){
                    return '<a href='
                    .Storage::disk('bahanpath')
                    ->url($PublicCaseDoc->IC_PATH.$PublicCaseDoc->IC_DOCNAME)
                    .' target="_blank">'
                    .$PublicCaseDoc->IC_DOCFULLNAME
                    .'</a>';
                } else {
                    return '';
                }
            })
            ->editColumn('IC_REMARKS', function(IntegritiPublicUserDoc $PublicCaseDoc) {
                if($PublicCaseDoc->IC_REMARKS != ''){
                    // return implode(' ', array_slice(explode(' ', ucfirst($PublicCaseDoc->IC_REMARKS)), 0, 7)) . '...';
                    return substr($PublicCaseDoc->IC_REMARKS, 0, 20) . '...';
                } else {
                    return '';
                }
            })
            ->rawColumns(['IC_DOCNAME','IC_DOCFULLNAME','IC_REMARKS','action'])
            ;
        return $datatables->make(true);
    }

    public function ajaxvalidatestore(Request $request)
    {
        $file = $request->file('file');
        if($file) 
        {
            if($file->getClientOriginalExtension() == 'pdf')
            {
                $validator = Validator::make($request->all(), 
                    [
                        'file' => 'required | max:2048'
                    ], 
                    [
                        'file.required' => 'Ruangan Fail diperlukan.',
                        'file.max' => 'Fail format pdf mesti tidak melebihi 2Mb.',
                    ]
                );
            }
            else
            {
                $validator = Validator::make($request->all(), 
                    [
                        'file' => 'required | mimes:jpeg,jpg,png,pdf'
                    ], 
                    [
                        'file.required' => 'Ruangan Fail diperlukan.',
                        'file.mimes' => 'Format fail mesti jpeg,jpg,png,pdf.',
                    ]
                );
            }
        }
        else
        {
            $validator = Validator::make($request->all(), 
                [
                    'file' => 'required'
                ], 
                [
                    'file.required' => 'Ruangan Fail diperlukan.',
                ]
            );
        }

        if ($validator->fails()) {
            return response()->json(['fails' => $validator->getMessageBag()]);
        } else {
            return response()->json(['success']);
        }
    }
    
    public function ajaxvalidateupdate(Request $request)
    {
        $file = $request->file('file');
        if($file)
        {
            if($file->getClientOriginalExtension() == 'pdf')
            {
                $validator = Validator::make($request->all(), 
                    [
                        'file' => 'max:2048'
                    ], 
                    [
                        'file.max' => 'Fail format pdf mesti tidak melebihi 2Mb.',
                    ]
                );
                
                if ($validator->fails()) {
                    return response()->json(['fails' => $validator->getMessageBag()]);
                } else {
                    return response()->json(['success']);
                }
            }
            else
            {
                $validator = Validator::make($request->all(), 
                    [
                        'file' => 'mimes:jpeg,jpg,png,pdf'
                    ], 
                    [
                        'file.mimes' => 'Format fail mesti jpeg,jpg,png,pdf.',
                    ]
                );
                
                if ($validator->fails()) {
                    return response()->json(['fails' => $validator->getMessageBag()]);
                } else {
                    return response()->json(['success']);
                }
            }
        } else {
            return response()->json(['success']);
        }
    }

    /**
     * Middleware
     */
    public function __construct()
    {
        $this->middleware(['guest', 'locale']);
    }
}
