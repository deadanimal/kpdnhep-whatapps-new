<?php

namespace App\Http\Controllers\Aduan;

use App\Aduan\AdminCaseDoc;
//use App\Attachment;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Yajra\DataTables\Facades\DataTables;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use Carbon\Carbon;
use Validator;
use Image;

class AdminCaseDocController extends Controller
{
    public function __construct()
    {
        $this->middleware(['auth']);
    }

    public function index()
    {
        //
    }

    public function create($id)
    {
        return view('aduan.admin-case.doccreate', compact('id'));
    }

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
                Storage::disk('bahan')->put($directory.$filename, $resize->__toString());
            }
            else
            {
                Storage::disk('bahan')->putFileAs('/'.$Year.'/'.$Month.'/', $request->file('file'), $filename);
            }
            $mAdminCaseDoc = new AdminCaseDoc();
            $mAdminCaseDoc->CC_CASEID = $request->CC_CASEID;
            $mAdminCaseDoc->CC_PATH = Storage::disk('bahan')->url($directory);
            $mAdminCaseDoc->CC_IMG = $filename;
            $mAdminCaseDoc->CC_IMG_NAME = $file->getClientOriginalName();
            $mAdminCaseDoc->CC_REMARKS = $request->CC_REMARKS;
            $mAdminCaseDoc->CC_IMG_CAT = 1;
            if($mAdminCaseDoc->save()) {
                $request->session()->flash('success', 'Bahan Bukti telah berjaya ditambah');
                return redirect()->route('admin-case.attachment',$request->CC_CASEID);
            }
        }
    }

    public function show($id)
    {
        //
    }

    public function edit($id)
    {
        $mAdminCaseDoc = AdminCaseDoc::find($id);
        return view('aduan.admin-case.docedit', compact('mAdminCaseDoc'));
    }

    public function update(Request $request, $id)
    {
        $mAdminCaseDoc = AdminCaseDoc::find($id);
        $file = $request->file('file');
        $date = date('YmdHis');
        $userid = Auth()->user()->id;
        $Year = date('Y');
        $Month = date('m');

        if ($file) {
            Storage::delete($mAdminCaseDoc->CC_PATH.$mAdminCaseDoc->CC_IMG); // Delete old attachment
            $filename = $userid.'_'.$mAdminCaseDoc->CC_CASEID.'_'.$date.'.'.$file->getClientOriginalExtension(); // Store new attachment
            $directory = '/'.$Year.'/'.$Month.'/';
            
            if($file->getClientSize() > 2000000) // if file size lebih 2mb
            {
                $resize = Image::make($file)->resize(null, 4096, function ($constraint) { // returns Intervention\Image\Image
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
            $mAdminCaseDoc->CC_PATH = Storage::disk('bahan')->url($directory);
            $mAdminCaseDoc->CC_IMG = $filename;
            $mAdminCaseDoc->CC_IMG_NAME = $file->getClientOriginalName();
            $mAdminCaseDoc->CC_REMARKS = $request->CC_REMARKS;
            // Save record
            if ($mAdminCaseDoc->save()) {
                $request->session()->flash('success', 'Bahan Bukti telah berjaya dikemaskini');
                return redirect()->route('admin-case.attachment', $mAdminCaseDoc->CC_CASEID);
            }
        } else {
            $mAdminCaseDoc->CC_REMARKS = $request->CC_REMARKS;
            if ($mAdminCaseDoc->save()) {
                $request->session()->flash('success', 'Bahan Bukti telah berjaya dikemaskini');
                return redirect()->route('admin-case.attachment', $mAdminCaseDoc->CC_CASEID);
            }
        }
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
//    public function destroy(Request $request, $CASEID, $DOCATTCHID)
    public function destroy(Request $request, $id)
    {
//        $mAttachment = Attachment::find($DOCATTCHID);
//        $path = 'public/' . $mAttachment->file_name_sys;
//        Storage::delete($path);
//        $mAdminCaseDoc = AdminCaseDoc::where(['CC_CASEID'=>$CASEID, 'CC_DOCATTCHID'=>$DOCATTCHID]);
//        if($mAdminCaseDoc->delete()){
//            if($mAttachment->delete()){
//                $request->session()->flash('success', 'Bahan Bukti telah berjaya dihapus');
////                return redirect('/admin-case/edit/'.$CASEID.'#case-attachment');
////                return redirect()->route('admin-case.edit', ['id' => $request->CA_CASEID, '#case-attachment']);
//                return redirect()->route('admin-case.edit', ['id' => $CASEID, '#case-attachment']);
//            }
//        }
        $model = AdminCaseDoc::find($id);
        Storage::delete($model->CC_PATH.$model->CC_IMG);
        if($model->delete()){
//            return redirect('/public-case/'.$model->CC_CASEID.'/edit#attachment');
            $request->session()->flash('success', 'Bahan Bukti telah berjaya dihapus');
//            return redirect()->route('admin-case.edit', ['id' => $model->CC_CASEID, '#case-attachment']);
            return redirect()->route('admin-case.attachment',$model->CC_CASEID);
        }
    }
    
    public function getdatatable($CASEID)
    {
        $mAdminCaseDoc = AdminCaseDoc::where('CC_CASEID', $CASEID);
        $datatables = DataTables::of($mAdminCaseDoc)
            ->addIndexColumn()
            ->editColumn('CC_IMG_NAME', function(AdminCaseDoc $adminCaseDoc) {
                if($adminCaseDoc->CC_IMG_NAME != '')
                    return '<a href='.Storage::disk('bahanpath')->url($adminCaseDoc->CC_PATH.$adminCaseDoc->CC_IMG).' target="_blank">'.$adminCaseDoc->CC_IMG_NAME.'</a>';
                else
                    return '';
            })
//            ->editColumn('doc_title', function(AdminCaseDoc $adminCaseDoc) {
//                if($adminCaseDoc->CC_DOCATTCHID != '')
//                    return $adminCaseDoc->attachment->doc_title;
//                else
//                    return '';
//            })
            ->editColumn('updated_at', function(AdminCaseDoc $adminCaseDoc) {
                if($adminCaseDoc->updated_at != '')
                    return $adminCaseDoc->updated_at ? with(new Carbon($adminCaseDoc->updated_at))->format('d-m-Y h:i A') : '';
                else
                    return '';
            })
            ->addColumn('action', function(AdminCaseDoc $adminCaseDoc) {
//                <a onclick="caseattachmenteditbutton({{ $CC_DOCATTCHID }})" class="btn btn-xs btn-primary" data-toggle="tooltip" data-placement="right" title="Kemaskini">
//                <i class="fa fa-pencil"></i></a>
//                {!! Form::open(["url" => "admin-case-doc/$CC_CASEID/$CC_DOCATTCHID", "method" => "DELETE", "style"=>"display:inline"]) !!}
//                {!! Form::button("<i class=\'fa fa-trash\'></i>", ["type" => "submit", "class" => "btn btn-danger btn-xs", "data-toggle"=>"tooltip", "data-placement"=>"right", "title"=>"Hapus", "onclick" => "return confirm(\'Anda pasti untuk hapuskan rekod ini?\')"] ) !!}
//                {!! Form::close() !!}
//            ''
                return view('aduan.admin-case.attachmentactionbutton', compact('adminCaseDoc'))->render();
            })
            ->rawColumns(['CC_IMG_NAME', 'action'])
        ;
        return $datatables->make(true);
    }
    
    public function ajaxvalidatestoreattachment(Request $request)
    {
        $file = $request->file('file');
        if(empty($file)) {
            $validator = Validator::make($request->all(), 
                [
                    'file' => 'required'
                ],
                [
                    'file.required' => 'Ruangan Fail diperlukan.',
                ]
            );
        } else if($file->getClientOriginalExtension() == 'pdf' || $file->getClientOriginalExtension() == 'PDF') {
            $validator = Validator::make($request->all(), 
                [
                    'file' => 'required | max:2048'
                ],
                [
                    'file.required' => 'Ruangan Fail diperlukan.',
                    'file.max' => 'Fail format pdf mesti tidak melebihi 2Mb.',
                ]
            );
        } else {
            $validator = Validator::make($request->all(), 
                [
                    'file' => 'required | mimes:jpeg,jpg,png,gif,pdf'
                ], 
                [
                    'file.required' => 'Ruangan Fail diperlukan.',
                    'file.mimes' => 'Format fail mesti gif, jpeg, jpg, png, pdf.',
                ]
            );
        }
        
        if ($validator->fails()) {
            return response()->json(['fails' => $validator->getMessageBag()]);
        }else{
            return response()->json(['success']);
        }
    }
    
    public function ajaxvalidateupdatettachment(Request $request)
    {
        $validator = Validator::make($request->all(),
            [
//                'doc_title' => 'required',
            ],
            [
//                'doc_title.required' => 'Ruangan Nama Fail diperlukan.',
            ]
        );
        if ($validator->fails()) {
            return response()->json(['fails' => $validator->getMessageBag()]);
        } else {
            return response()->json(['success']);
        }
    }
}
