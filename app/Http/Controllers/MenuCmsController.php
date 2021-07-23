<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
//use Illuminate\Routing\Route;
use Yajra\DataTables\Facades\DataTables;
use App\MenuCms;
use App\Log;

class MenuCmsController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $mMenuText = \Request::get('search'); //<-- we use global request to get the param of URI
        $mMenuUtama = MenuCms::select('menu_txt', 'id')
            ->where(['module_ind' => '1'])
            ->get()
        ;
        $mMenu = MenuCms::where('menu_txt', 'like', '%'.$mMenuText.'%')
            ->orderBy('id')
            ->paginate(20)
        ;
        return view('menucms.index', compact('mMenuUtama', 'mMenu'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        return view('menucms.create');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $this->validate($request,[
            'menu_txt' => 'required',
            'menu_txt_en' => 'required',
            'sort' => 'required',
        ],
        [
            'menu_txt.required' => 'Ruangan Nama Menu (BM) diperlukan.',
            'menu_txt_en.required' => 'Ruangan Nama Menu (BI) diperlukan.',
            'sort.required' => 'Ruangan Susunan diperlukan.',
        ]
        );
        $model = new MenuCms;
        $model->fill($request->all());
        $model->menu_txt = $request->menu_txt;
        $model->menu_loc = $request->menu_loc;
        $model->route_name = $request->route_name;
        if(isset($request->module_ind)){
            $model->module_ind = '1';
        }
        $model->menu_parent_id = $request->menu_parent_id;
        $model->sort = $request->sort;
        $model->remarks = $request->remarks;
        $model->icon_name = $request->icon_name;
        $model->menu_cat = $request->menu_cat;
        if(isset($request->hide_ind)){
            $model->hide_ind = '1';
        }
        if ($model->save()) {
            $mLog = new Log;
            $mLog->details = $request->route()->getName();
            $mLog->parameter = $model->id;
            $mLog->ip_address = $request->ip();
            $mLog->user_agent = $request->header('User-Agent');
            if($mLog->save()){
                $request->session()->flash('success', 'Menu CMS telah berjaya ditambah');
                return redirect()->route('menucms.index');
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
        $mMenuCms = MenuCms::find($id);
        $menuUtama = MenuCms::select('menu_txt', 'id')->where('module_ind', '1')->take(100)->get();
        return view('menucms.edit', compact('id', 'mMenuCms', 'menuUtama'));
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
        $this->validate($request,[
            'menu_txt' => 'required',
            'menu_txt_en' => 'required',
            'sort' => 'required',
        ],
        [
            'menu_txt.required' => 'Ruangan Nama Menu (BM) diperlukan.',
            'menu_txt_en.required' => 'Ruangan Nama Menu (BI) diperlukan.',
            'sort.required' => 'Ruangan Susunan diperlukan.',
        ]
        );
        $model = MenuCms::find($id);
        $model->menu_txt = $request->menu_txt;
        $model->menu_txt_en = $request->menu_txt_en;
        $model->menu_loc = $request->menu_loc;
        $model->route_name = $request->route_name;
        if($request->module_ind != null){
            $model->module_ind = '1';
        } else {
            $model->module_ind = null;
        }
        $model->menu_parent_id = $request->menu_parent_id;
        $model->sort = $request->sort;
        $model->remarks = $request->remarks;
        $model->icon_name = $request->icon_name;
        $model->menu_cat = $request->menu_cat;
        if($request->hide_ind != null){
            $model->hide_ind = '1';
        } else {
            $model->hide_ind = null;
        }
        if ($model->save()) {
            $mLog = new Log;
            $mLog->details = $request->route()->getName();
            $mLog->parameter = $model->id;
            $mLog->ip_address = $request->ip();
            $mLog->user_agent = $request->header('User-Agent');
            if($mLog->save()) {
                $request->session()->flash('success', 'Menu CMS telah berjaya dikemaskini');
                return redirect()->route('menucms.index');
            }
        }
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy(Request $request, $id)
    {
        $mMenuCms = MenuCms::find($id);
        $mMenuCms->delete($id);
        $mLog = new Log;
        $mLog->details = $request->route()->getName();
        $mLog->parameter = $mMenuCms->id;
        $mLog->ip_address = $request->ip();
        $mLog->user_agent = $request->header('User-Agent');
        if($mLog->save()) {
            $request->session()->flash('success', 'Menu CMS telah berjaya dihapus');
            return redirect()->route('menucms.index');
        }
    }
    
    public function getdatatable(Request $request) {
        $mMenuCms = MenuCms::whereIn('menu_cat', ['2','3']);
        $datatables = DataTables::of($mMenuCms)
            ->addIndexColumn()
            ->editColumn('menu_parent_id', function(MenuCms $menuCms) {
                if($menuCms->menu_parent_id != '')
                    return $menuCms->MainMenu->menu_txt;
                else
                    return '';
            })
            ->editColumn('module_ind', function (MenuCms $menuCms) {
                return $menuCms->ShowModule($menuCms->module_ind);
            })
            ->editColumn('hide_ind', function (MenuCms $menuCms) {
                return $menuCms->ShowStatus($menuCms->hide_ind);
            })
            ->editColumn('menu_cat', function(MenuCms $menuCms) {
                if($menuCms->menu_cat == '2')
                    return 'Kepenggunaan';
                else
                    return 'Integriti';
            })
            ->addColumn('action', '
                <a href="{{ route("menucms.edit", $id) }}" class="btn btn-xs btn-primary" data-toggle="tooltip" data-placement="right" title="Kemaskini">
                <i class="fa fa-pencil"></i></a>
                {!! Form::open(["route" => ["menucms.destroy", $id], "method" => "DELETE", "style"=>"display:inline"]) !!}
                {!! Form::button("<i class=\'fa fa-trash\'></i>", 
                ["type" => "submit", "class" => "btn btn-danger btn-xs", "data-toggle"=>"tooltip", "data-placement"=>"right", "title"=>"Hapus", "onclick" => "return confirm(\'Anda pasti untuk hapuskan rekod ini?\')"] ) !!}
                {!! Form::close() !!}
            ')
            ->filter(function ($query) use ($request) {
                if ($request->has('menu_txt')) {
                    $query->where('menu_txt', 'like', "%{$request->get('menu_txt')}%");
                }
                if ($request->has('menu_txt_en')) {
                    $query->where('menu_txt_en', 'like', "%{$request->get('menu_txt_en')}%");
                }
                if ($request->has('menu_parent_id')) {
                    $query->where('menu_parent_id', $request->get('menu_parent_id'));
                }
            })
        ;
//        if ($menu_txt = $datatables->request->get('menu_txt')) {
//            $datatables->filter(function ($query) use ($menu_txt) {
//                $query->where('menu_txt', 'like', "%$menu_txt%");
//            });
//        }
//        if ($menu_parent_id = $datatables->request->get('menu_parent_id')) {
//            $datatables->filter(function ($query) use ($menu_parent_id) {
//                $query->where('menu_parent_id', '=', $menu_parent_id);
//            });
//        }
        return $datatables->make(true);
    }
    
    public function __construct()
    {
        $this->middleware('auth');
    }
}
