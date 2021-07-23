<?php

namespace App\Http\Controllers\Integriti;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Integriti\IntegritiAdmin;
use App\Integriti\IntegritiAdminDoc;
use Carbon\Carbon;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use Image;
use Yajra\DataTables\Facades\DataTables;

class IntegritiKemaskiniDocController extends Controller
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
        // return view('aduan.admin-case.doccreate', compact('id'));
        return view('integriti.kemaskinidoc.create', compact('id'));
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
        $model = IntegritiAdmin::find($request->IC_CASEID);
        // dd($request, $model);
        if ($model) {
            // dd($request, $model);
            $count = IntegritiAdminDoc::
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
                $userid = Auth::user()->id;
                $Year = date('Y');
                $Month = date('m');
                $file = $request->file('file');

                if($file) {
                    // dd($request, $model, $count, $file);
                    $filename = $userid.'_'.$request->IC_CASEID.'_'.$date.'.'.$file->getClientOriginalExtension();
                    $directory = '/'.$Year.'/'.$Month.'/';
        //            Storage::disk('bahan')->makeDirectory($directory);
                    if (in_array($file->getClientOriginalExtension(), ['jpg', 'jpeg', 'png', 'gif'])) {
                        // dd($request, $model, $count, $file);
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
                    else if (in_array($file->getClientOriginalExtension(), ['pdf'])) {
                        // dd($request, $model, $count, $file, $file->getClientSize());
                        if($file->getClientSize() < 2000000) // if file size lebih 2mb
                        {
                            // dd($request, $model, $count, $file);
                            // Storage::disk('bahan')->putFileAs('/'.$Year.'/'.$Month.'/', $request->file('file'), $filename);
                            Storage::disk('integritibahan')->putFileAs('/'.$Year.'/'.$Month.'/', $request->file('file'), $filename);
                        }
                        else {
                            session()->flash(
                                'alert', 
                                Auth::user()->lang == 'ms' 
                                    ? 'Saiz fail format pdf mesti tidak melebihi 2 MB.' 
                                    : 'Attachment format pdf size must not exceed 2 MB.'
                            );
                            return redirect()->back();
                        }
                    }
                    else {
                        session()->flash(
                            'alert', 
                            Auth::user()->lang == 'ms' 
                                ? 'Lampiran anda gagal dimuat naik.' 
                                : 'Attachment upload failed.'
                        );
                        return redirect()->back();
                    }
                    
                    $mPublicCaseDoc = new IntegritiAdminDoc();
                    $mPublicCaseDoc->IC_CASEID = $request->IC_CASEID;
                    // $mPublicCaseDoc->IC_PATH = Storage::disk('bahan')->url($directory);
                    $mPublicCaseDoc->IC_PATH = Storage::disk('integritibahan')->url($directory);
                    $mPublicCaseDoc->IC_DOCNAME = $filename;
                    $mPublicCaseDoc->IC_DOCFULLNAME = $file->getClientOriginalName();
                    $mPublicCaseDoc->IC_REMARKS = $request->IC_REMARKS;
                    $mPublicCaseDoc->IC_DOCCAT = 1;
                    if($mPublicCaseDoc->save()) {
                        $request->session()->flash(
                            'success', 'Lampiran telah berjaya dimuat naik.'
                        );
                        // return redirect()->back();
                        return redirect()->route('integritikemaskini.attachment',$request->IC_CASEID);
                    }
                }
                else {
                    // session()->flash(
                    //     'alert', 
                    //     Auth::user()->lang == 'ms' 
                    //         ? 'Lampiran anda gagal dimuat naik.' 
                    //         : 'Attachment upload failed.'
                    // );
                    session()->flash(
                        'alert', 'Lampiran anda gagal dimuat naik.'
                    );
                    // return redirect()->back();
                    return redirect()->route('integritikemaskini.attachment',$request->IC_CASEID);
                }
            }
            else {
                // session()->flash(
                //     'alert', 
                //     Auth::user()->lang == 'ms' 
                //         ? 'Anda telah memuat naik maksimum 5 lampiran.' 
                //         : 'You have uploaded maximum of 5 attachment.'
                // );
                session()->flash(
                    'alert', 'Anda telah memuat naik maksimum 5 lampiran.'
                );
                // return redirect()->back();
                return redirect()->route('integritikemaskini.attachment',$request->IC_CASEID);
            }
        }
        else {
            // session()->flash(
            //     'alert', 
            //     Auth::user()->lang == 'ms' 
            //         ? 'Lampiran anda gagal dimuat naik.' 
            //         : 'Attachment upload failed.'
            // );
            session()->flash(
                'alert', 'Lampiran anda gagal dimuat naik.'
            );
            // return redirect()->back();
            return redirect()->route('integritikemaskini.attachment',$request->IC_CASEID);
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
        $mAdminCaseDoc = IntegritiAdminDoc::find($id);
        return view('integriti.kemaskinidoc.edit', compact('id', 'mAdminCaseDoc'));
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
        $mPublicCaseDoc = IntegritiAdminDoc::find($id);
        if($mPublicCaseDoc){
            // dd($request, $mPublicCaseDoc);
            $file = $request->file('file');
            $date = date('YmdHis');
            $userid = Auth()->user()->id;
            $Year = date('Y');
            $Month = date('m');

            if ($file) {
                // dd($request, $mPublicCaseDoc, $file, 'file');
                Storage::delete($mPublicCaseDoc->IC_PATH.$mPublicCaseDoc->IC_DOCNAME); // Delete old attachment
                $filename = $userid.'_'.$mPublicCaseDoc->IC_CASEID.'_'.$date.'.'.$file->getClientOriginalExtension(); // Store new attachment
                $directory = '/'.$Year.'/'.$Month.'/';
                if (in_array($file->getClientOriginalExtension(), ['jpg', 'jpeg', 'png', 'gif'])) {
                    if($file->getClientSize() > 2000000) // if file size lebih 2mb
                    {
                        $resize = Image::make($file)->resize(null, 600, function ($constraint) { // returns Intervention\Image\Image
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
                        session()->flash(
                            'alert', 
                            Auth::user()->lang == 'ms' 
                                ? 'Saiz fail format pdf mesti tidak melebihi 2 MB.' 
                                : 'Attachment format pdf size must not exceed 2 MB.'
                        );
                        // return redirect()->back();
                        return redirect()->route('integritikemaskini.attachment',$mPublicCaseDoc->IC_CASEID);
                    }
                }
                else {
                    session()->flash(
                        'alert', 
                        Auth::user()->lang == 'ms' 
                            ? 'Lampiran anda gagal dimuat naik.' 
                            : 'Attachment upload failed.'
                    );
                    // return redirect()->back();
                    return redirect()->route('integritikemaskini.attachment',$mPublicCaseDoc->IC_CASEID);
                }
                // Update record
                // $mPublicCaseDoc->IC_PATH = Storage::disk('bahan')->url($directory);
                $mPublicCaseDoc->IC_PATH = Storage::disk('integritibahan')->url($directory);
                $mPublicCaseDoc->IC_DOCNAME = $filename;
                $mPublicCaseDoc->IC_DOCFULLNAME = $file->getClientOriginalName();
                $mPublicCaseDoc->IC_REMARKS = $request->IC_REMARKS;
                // Save record
                if ($mPublicCaseDoc->save()) {
                    session()->flash(
                        'success', 'Maklumat Lampiran telah berjaya dikemas kini.'
                    );
                } else {
                    session()->flash(
                        'alert', 'Maklumat Lampiran anda gagal dikemas kini.'
                    );
                }
                // return redirect()->back();
                return redirect()->route('integritikemaskini.attachment',$mPublicCaseDoc->IC_CASEID);
            } else {
                // dd($request, $mPublicCaseDoc, $file, ' no file');
                $mPublicCaseDoc->IC_REMARKS = $request->IC_REMARKS;
                if ($mPublicCaseDoc->save()) {
                    session()->flash(
                        'success', 'Maklumat Lampiran telah berjaya dikemas kini.'
                    );
                } else {
                    session()->flash(
                        'alert', 'Maklumat Lampiran anda gagal dikemas kini.'
                    );
                }
                // return redirect()->back();
                return redirect()->route('integritikemaskini.attachment',$mPublicCaseDoc->IC_CASEID);
            }
        }
        else {
            session()->flash(
                'alert', 'Maklumat Lampiran anda gagal dikemas kini.'
            );
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
        $model = IntegritiAdminDoc::find($id);
        if ($model) {
            // $model->delete();
            // $model->IC_DELETED_BY = Auth::user()->id;
            // dd($model);
            // Storage::delete($model->IC_PATH.$model->IC_DOCNAME);
            // $userid = Auth()->user()->id;
            if($model->delete()){
                session()->flash(
                    'success', 'Lampiran telah berjaya dihapus.'
                );
            } else {
                session()->flash(
                    'alert', 'Lampiran anda gagal dihapus.'
                );
            }
            // return redirect()->back();
            return redirect()->route('integritikemaskini.attachment',$model->IC_CASEID);
        } else {
            session()->flash(
                'alert', 'Lampiran anda gagal dihapus.'
            );
            return redirect()->back();
        }
    }

    /**
     * Middleware
     */
    public function __construct()
    {
        $this->middleware(['locale','auth']);
    }
    
    public function getdatatable($id)
    {
        $model = IntegritiAdmin::find($id);
        $modelCaseDoc = IntegritiAdminDoc::
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
            ->addColumn('action', function (IntegritiAdminDoc $adminCaseDoc) use ($model){
                return view('integriti.kemaskinidoc.actionbutton', compact('adminCaseDoc','model'))->render();
            })
            ->editColumn('IC_DOCFULLNAME', function(IntegritiAdminDoc $PublicCaseDoc) {
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
            ->editColumn('IC_REMARKS', function(IntegritiAdminDoc $PublicCaseDoc) {
                if($PublicCaseDoc->IC_REMARKS != ''){
                    // return implode(' ', array_slice(explode(' ', ucfirst($PublicCaseDoc->IC_REMARKS)), 0, 7)) . '...';
                    return substr($PublicCaseDoc->IC_REMARKS, 0, 20) . '...';
                } else {
                    return '';
                }
            })
            ->editColumn('IC_UPDATED_AT', function(IntegritiAdminDoc $adminCaseDoc) {
                if($adminCaseDoc->IC_UPDATED_AT != '')
                    return $adminCaseDoc->IC_UPDATED_AT ? with(new Carbon($adminCaseDoc->IC_UPDATED_AT))->format('d-m-Y h:i A') : '';
                else
                    return '';
            })
            ->rawColumns(['IC_DOCNAME','IC_DOCFULLNAME','IC_REMARKS','action'])
            ;
        return $datatables->make(true);
    }
}
