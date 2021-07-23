<?php

namespace App\Http\Controllers;

use App\Articles;
use Illuminate\Http\Request;
use Yajra\DataTables\Facades\DataTables;
use App\Log;
use DB;
use Storage;
use Image;
use Carbon\Carbon;

class ArticleController extends Controller
{

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        return view('article.index');
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        return view('article.create');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $this->validate($request, [
            'start_dt' => 'required',
            'end_dt' => 'required',
            'title_my' => 'required',
            'title_en' => 'required',
            'content_my' => 'required',
            'content_en' => 'required',
            'status' => 'required',
        ]);

        $date = date('Ymdhis');
        $file = $request->file('photo');
        if($file) {
            $filename = request('cat').'_'.$date.'.'.$file->getClientOriginalExtension();
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

        $mArticle = new Articles;
        $mArticle->fill(request()->all());
        $mArticle->start_dt = date('Y-m-d', strtotime(request('start_dt')));
        $mArticle->end_dt = date('Y-m-d', strtotime(request('end_dt')));
        $mArticle->photo = $filename;

        if ($mArticle->save()) {
            $mLog = new Log;
            $mLog->details = $request->path();
            $mLog->parameter = $mArticle->id;
            $mLog->ip_address = $request->ip();
            $mLog->user_agent = $request->header('User-Agent');
            if ($mLog->save()) {
                $request->session()->flash('success', 'Artikal telah berjaya ditambah');
                return redirect('/article');
            }
        }
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Articles $articles
     * @return \Illuminate\Http\Response
     */
    public function show(Articles $articles)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Articles $articles
     * @return \Illuminate\Http\Response
     */
//    public function edit(Articles $articles) {
    public function edit($id)
    {
        $mArticle = DB::table('sys_articles')->find($id);
        $mArticle->start_dt = date('d-m-Y', strtotime($mArticle->start_dt));
        $mArticle->end_dt = date('d-m-Y', strtotime($mArticle->end_dt));
        return view('article.edit', compact('mArticle', 'id'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request $request
     * @param  \App\Articles $articles
     * @return \Illuminate\Http\Response
     */
//    public function update(Request $request, Articles $articles) {
//    public function update($id) {
    public function update(Request $request, $id)
    {
        $this->validate($request, [
            'start_dt' => 'required',
            'end_dt' => 'required',
            'title_my' => 'required',
            'title_en' => 'required',
            'content_my' => 'required',
            'content_en' => 'required',
        ]);

        $date = date('Ymdhis');
        $file = $request->file('photo');
        if($file) {
            $filename = request('cat').'_'.$date.'.'.$file->getClientOriginalExtension();
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

        $mArticle = Articles::find($id);
        $mArticle->fill(request()->all());
        $mArticle->start_dt = date('Y-m-d', strtotime(request('start_dt')));
        $mArticle->end_dt = date('Y-m-d', strtotime(request('end_dt')));
        if($file)
            $mArticle->photo = $filename;

        if ($mArticle->save()) {
            $mLog = new Log;
            $mLog->details = $request->path();
            $mLog->parameter = $mArticle->id;
            $mLog->ip_address = $request->ip();
            $mLog->user_agent = $request->header('User-Agent');
            if ($mLog->save()) {
                $request->session()->flash('success', 'Artikal telah berjaya dikemaskini');
                return redirect()->route('article.edit', $mArticle->id);
            }
        }
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Articles $articles
     * @return \Illuminate\Http\Response
     */
    public function destroy(Articles $articles)
    {
        //
    }

//    public function delete($id)
    public function delete(Request $request, $id)
    {
        $mArticle = Articles::find($id);
        $mArticle->delete($id);
        $mLog = new Log;
        $mLog->details = $request->path();
        $mLog->parameter = $mArticle->id;
        $mLog->ip_address = $request->ip();
        $mLog->user_agent = $request->header('User-Agent');
        if ($mLog->save()) {
            session()->flash('success', 'Artikel telah berjaya dihapus.');
            return redirect('/article');
        }
    }

//    public function get_datatable(DataTables $datatables, Request $request) {
    public function get_datatable(Request $request)
    {
        $mArticles = Articles::orderby('created_at', 'asc');
        $datatables = DataTables::of($mArticles)
            ->addIndexColumn()
            ->editColumn('menu_id', function (Articles $articles) {
                if ($articles->menu_id != '')
                    return $articles->menu->menu_txt;
                else
                    return '';
            })
            ->editColumn('start_dt', function ($log) {
                return $log->start_dt ? with(new Carbon($log->start_dt))->format('d-m-Y') : '';
            })
            ->editColumn('end_dt', function ($log) {
                return $log->end_dt ? with(new Carbon($log->end_dt))->format('d-m-Y') : '';
            })
            ->addColumn('action', '
                <a href="{{ url(\'article\edit\', $id) }}" class="btn btn-xs btn-primary" data-toggle="tooltip" data-placement="right" title="Kemaskini">
                    <i class="fa fa-pencil"></i>
                </a>
                <a href="{{ url(\'article\delete\', $id) }}" class="btn btn-xs btn-danger" data-toggle="tooltip" data-placement="right" title="Hapus" onclick="return confirm(\'{{ trans("action.delete") }}\')">
                    <i class="fa fa-trash"></i>
                </a>
            ')
            ->filter(function ($query) use ($request) {
                if ($request->has('menu_id')) {
                    $query->where('menu_id', $request->get('menu_id'));
                }
                if ($request->has('cat')) {
                    $query->where('cat', $request->get('cat'));
                }
                if ($request->has('start_dt')) {
                    $query->whereDate('start_dt', '>=', date('Y-m-d', strtotime($request->get('start_dt'))));
                }
                if ($request->has('end_dt')) {
                    $query->whereDate('end_dt', '<=', date('Y-m-d', strtotime($request->get('end_dt'))));
                }
                if ($request->has('title_my')) {
                    $query->where('title_my', 'like', "%{$request->get('title_my')}%");
                }
                if ($request->has('title_en')) {
                    $query->where('title_en', 'like', "%{$request->get('title_en')}%");
                }
            });

        return $datatables->make(true);
    }

    public function __construct()
    {
        $this->middleware('auth');
    }
}
