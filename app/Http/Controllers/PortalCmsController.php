<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Yajra\DataTables\Facades\DataTables;
use App\PortalCms;
use App\Log;
use DB;
use Storage;
use Image;
use Carbon\Carbon;

class PortalCmsController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }
    
    public function getdatatable(Request $request) {
        $mPortalCms = PortalCms::join('sys_ref AS b','sys_articles.cat','=','b.code')
                        ->select(DB::raw("DISTINCT(sys_articles.cat),b.descr,COUNT(sys_articles.cat) AS Bilangan"))
                        ->where([['sys_articles.cat','<>',null],['b.cat','=',1258]])
                        ->groupBy('sys_articles.cat', 'b.descr');
        
        $datatables = DataTables::of($mPortalCms)
            ->addIndexColumn()
            ->addColumn('action', '
                <a href="{{ route("portalcms.listcontent", $cat) }}"class="btn btn-xs btn-success" data-toggle="tooltip" data-placement="right" title="Senarai">
                <i class="fa fa-bars"></i></a>
            ')
            ->filter(function ($query) use ($request) {
                if ($request->has('title_my')) {
                    $query->where('title_my', 'like', "%{$request->get('title_my')}%");
                }
                if ($request->has('title_en')) {
                    $query->where('title_en', 'like', "%{$request->get('title_en')}%");
                }
            })
        ;
        return $datatables->make(true);
    }
    
    public function ListContent($cat)
    {
        return view('portalcms.list_content', compact('cat'));
    }
    
    public function GetListContent(Request $request, $cat) {
        $mPortalCms = PortalCms::where('cat', $cat);
        $datatables = DataTables::of($mPortalCms)
            ->addIndexColumn()
            ->editColumn('status', function(PortalCms $mPortalCms) {
                if($mPortalCms->status == 1) {
                    return 'Aktif';
                }else{
                    return 'Tidak Aktif';
                }
            })
            ->editColumn('updated_by', function(PortalCms $mPortalCms) {
                if(isset($mPortalCms->updated_by)) {
                    return $mPortalCms->UpdatedBy->name;
                }else{
                    return '';
                }
            })
            ->editColumn('updated_at', function(PortalCms $mPortalCms) {
                return $mPortalCms->updated_at ? with(new Carbon($mPortalCms->updated_at))->format('d-m-Y h:i A') : '';
            })
            ->addColumn('action', '
                <a href="{{ route("portalcms.edit", $id) }}" class="btn btn-xs btn-primary" data-toggle="tooltip" data-placement="right" title="Kemaskini">
                <i class="fa fa-pencil"></i></a>
            ')
            ->filter(function ($query) use ($request) {
                if ($request->has('title_my')) {
                    $query->where('title_my', 'like', "%{$request->get('title_my')}%");
                }
                if ($request->has('title_en')) {
                    $query->where('title_en', 'like', "%{$request->get('title_en')}%");
                }
            })
        ;
        return $datatables->make(true);
    }
    
    public function index()
    {
        return view('portalcms.index');
    }

    public function create($cat)
    {
        return view('portalcms.create', compact('cat'));
    }

    public function store(Request $request, $cat)
    {
        $this->validate($request,[
            'title_my' => 'required',
            'title_en' => 'required',
            'content_my' => 'required',
            'content_en' => 'required',
            'photo' => 'required | mimes:jpeg,jpg,png'
        ],[
            'content_my.required' => 'Ruangan Keterangan (BM) diperlukan.',
            'content_en.required' => 'Ruangan Keterangan (BI) diperlukan.',
            'photo.required' => 'Ruangan Gambar diperlukan.',
            'photo.mimes' => 'Format gambar mesti jpeg,jpg,png.',
        ]);
        
        $date = date('Ymdhis');
        $file = $request->file('photo');
        if($file) {
            $filename = $cat.'_'.$date.'.'.$file->getClientOriginalExtension();
            if($file->getClientSize() > 2000000) // if file size lebih 2mb
            {
                $resize = Image::make($file)->resize(null, 600, function ($constraint) { // returns Intervention\Image\Image
                            $constraint->aspectRatio();
                            $constraint->upsize();
                        });
                $resize->stream();
                Storage::disk('portal')->put($filename, $resize);
            }
            else
            {
                Storage::disk('portal')->putFileAs('', $request->file('photo'), $filename);
            }
        }
        
        $model = new PortalCms();
        $model->fill(request()->all());
        $model->photo = $filename;
        $model->cat = $cat;
        if($model->save()) {
            $request->session()->flash('success', 'Rekod telah berjaya ditambah');
            return redirect()->route('portalcms.listcontent',$cat);
        }
    }

    public function show($id)
    {
        //
    }

    public function edit($id)
    {
        $model = PortalCms::find($id);
        return view('portalcms.edit', compact('id', 'model'));
    }

    public function update(Request $request, $id)
    {
        $model = PortalCms::find($id);
//        dd($model);
        $date = date('Ymdhis');
        $file = $request->file('photo');
        if($model->cat == 2 || $model->cat == 4 || $model->cat == 10) {
            if($file) {
                $filename = $model->cat.'_'.$date.'.'.$file->getClientOriginalExtension();
//                $directory = '/'.$Year.'/'.$Month.'/';
    //            Storage::disk('bahan')->makeDirectory($directory);

                if($file->getClientSize() > 2000000) // if file size lebih 2mb
                {
                    $resize = Image::make($file)->resize(null, 600, function ($constraint) { // returns Intervention\Image\Image
                                $constraint->aspectRatio();
                                $constraint->upsize();
                            });
                    $resize->stream();
                    Storage::disk('portal')->put($filename, $resize);
                }
                else
                {
                    Storage::disk('portal')->putFileAs('', $request->file('photo'), $filename);
                }
            }
        }
        $model->fill(request()->all());
        if($file)
            $model->photo = $filename;
        if($model->save()) {
            $request->session()->flash('success', 'Keterangan telah berjaya dikemaskini');
            return redirect()->route('portalcms.listcontent',$model->cat);
        }
    }

    public function destroy($id)
    {
        //
    }
}
