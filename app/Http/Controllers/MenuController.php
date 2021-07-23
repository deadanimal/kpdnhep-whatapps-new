<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Yajra\DataTables\Facades\DataTables;
use DB;
use App\Menu;
use App\Ref;
use App\Log;

class MenuController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $mMenu_text = \Request::get('search'); //<-- we use global request to get the param of URI
        $menu_utama = Menu::select('menu_txt','id')->where(['module_ind' => '1'])->take(100)->get();
        $mMenu = Menu::where('menu_txt','like','%'.$mMenu_text.'%')
        ->orderBy('id')
        ->paginate(20);
 
//    return view('offices.index',compact('offices'));
        return view('menu.index',compact('mMenu','menu_utama'));
    }
    
    public function get_datatable(Datatables $datatables, Request $request)
    {
        DB::statement(DB::raw('set @rownum=0'));
        $mMenu = Menu::select([
                        DB::raw('@rownum  := @rownum  + 1 AS rownum'),
                        'id',
                        'menu_text',
                        'menu_loc',
                        'sort',
                        ]);

        
        $datatables =  app('datatables')->of($mMenu)
                        ->addColumn('action', function ($menu) {
//                            return '<a href="#edit-'.$menu->id.'" class="btn btn-xs btn-primary"><i class="fa fa-pencil"></i> Edit</a>';
                            return '<a href='.url('menu/edit/'.$menu->id). ' class="btn btn-xs btn-primary"><i class="fa fa-pencil"></i></a> 
                                    <a href='.url('menu/delete/'.$menu->id).' onclick = "return confirm(\'{{ trans("action.delete") }}\')" data-method="delete" button type="button" class="btn btn-danger btn-xs delete-user" name="delete"><i class="fa fa-trash"></i></a>';
//                                    <a href='.url('menu/delete/'.$menu->id). ' class="btn btn-xs btn-danger" onclick = "return confirm("Are you sure want to Delete?")"))><i class="fa fa-trash"></i></a>';
//                                    <a href='.url('menu/destroy/'.$menu->id). ' class="btn btn-xs btn-danger" method = "post" confirm="Are you sure you want to delete?"><i class="fa fa-trash"></i></a>';
                        });
        
          if($menu_txt = $request->get('menu_text')) {
                $datatables->filterColumn('rownum', 'whereRaw', '@rownum  + 1 like ?', ["%{$menu_txt}%"]);
            }
//        if ($menu_txt = $datatables->request->get('menu_text')) {
//            $datatables->where('menu_text', 'like', "%$menu_txt%");
//        }
        
        return $datatables->make(true);
    }
    
    public function GetDatatableKat(Datatables $datatables, Request $request) {
        $mMenu = Menu::where(['menu_cat'=>1]);
//        $mMenu = Menu::orderBy('created_at','desc');

        $datatables = Datatables::of($mMenu)
                ->addIndexColumn()
                ->editColumn('menu_txt', function (Menu $menu) {
                    if($menu->remarks != '')
                    return $menu->menu_txt.' - '.$menu->remarks;
                    else
                    return $menu->menu_txt;
                })
                ->editColumn('hide_ind', function (Menu $menu) {
                    return $menu->ShowStatus($menu->hide_ind);
                })
                ->editColumn('module_ind', function (Menu $menu) {
                    return $menu->ShowModule($menu->module_ind);
                })
                ->editColumn('menu_parent_id', function(Menu $menu) {
                    if($menu->menu_parent_id != '')
                    return $menu->MainMenu->menu_txt;
                    else
                    return '';
                })
                ->addColumn('action', '
                    <a href="{{ route("menu.edit", $id) }}" class="btn btn-xs btn-primary" data-toggle="tooltip" data-placement="right" title="Kemaskini"><i class="fa fa-pencil"></i></a>
                    <a href="{{ route("menu.delete", $id) }}" class="btn btn-xs btn-danger" data-toggle="tooltip" data-placement="right" onclick = "return confirm(\'{{ trans("action.delete") }}\')" title="Hapus"><i class="fa fa-trash"></i></a>
                ')
//                                    <a href="{{ url(\'menu\edit\', $id) }}" class="btn btn-xs btn-primary" data-toggle="tooltip" data-placement="right" title="Kemaskini"><i class="fa fa-pencil"></i></a>
//                                    <a href="{{ url(\'menu\delete\', $id) }}" class="btn btn-xs btn-danger" data-toggle="tooltip" data-placement="right" onclick = "return confirm(\'{{ trans("action.delete") }}\')" title="Hapus"><i class="fa fa-trash"></i></a>')
                ->filter(function ($query) use ($request) {
                    if ($request->has('menu_txt')) {
                        $query->where('menu_txt', 'like', "%{$request->get('menu_txt')}%");
                    }
                    if ($request->has('menu_parent_id')) {
                        $query->where('menu_parent_id', $request->get('menu_parent_id'));
                    }
                })
                ;
        return $datatables->make(true);
    }
    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        return view('menu.create');
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
            ]);
        $model = new Menu;
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
        if(isset($request->hide_ind)){
            $model->hide_ind = '1';
        }
        if ($model->save()) {
            $mLog = new Log;
            $mLog->details = $request->path();
            $mLog->parameter = $model->id;
            $mLog->ip_address = $request->ip();
            $mLog->user_agent = $request->header('User-Agent');
            if($mLog->save()){
                $request->session()->flash('success', 'Menu telah berjaya ditambah');
                return redirect()->route('menu');
            }
        }
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show()
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($menu_id)
    {
        $mMenu = Menu::find($menu_id);
        $menu_utama = Menu::select('menu_txt','id')->where(['module_ind' => '1'])->take(100)->get();
        return view('menu.edit', compact('menu_id','menu_utama'))->with('mMenu', $mMenu);
        
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request,$menu_id)
    {
//        $mMenu = Menu::find($menu_id);
//        $mMenu->update(request()->all());
//        
//        return redirect('/menu');
        $model = Menu::find($menu_id);
        $model->menu_txt = $request->menu_txt;
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
        if($request->hide_ind != null){
            $model->hide_ind = '1';
        } else {
            $model->hide_ind = null;
        }
        if ($model->save()) {
            $mLog = new Log;
            $mLog->details = $request->path();
            $mLog->parameter = $model->id;
            $mLog->ip_address = $request->ip();
            $mLog->user_agent = $request->header('User-Agent');
            if($mLog->save()){
                $request->session()->flash('success', 'Menu telah berjaya dikemaskini');
                return redirect()->route('menu');
            }
        }
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($menu_id)
    {
         $mMenu = Menu::find($menu_id);
        $mMenu->delete($menu_id);
        if($mMenu->delete()){
//            return redirect('/public-case/'.$model->CC_CASEID.'/edit#attachment');
            $request->session()->flash('success', 'Menu telah berjaya dihapus');
//            return redirect()->route('admin-case.edit', ['id' => $model->CC_CASEID, '#case-attachment']);
            return redirect()->route('/menu');
        }
    
//        Menu::delete($menu_id);   
//        return redirect('/menu');
    }
    public function delete($menu_id)
    {
        $mMenu = Menu::find($menu_id);
        $mMenu->delete($menu_id);
        return redirect('/menu');
    }
    public function findListMenu(){
        
        $menu_utama = Menu::select('menu_text','id')->where(['module_ind' => '1'])->take(100)->get();
        return response()->json($menu_utama);
    }
    
    public function __construct()
    {
        $this->middleware('auth');
    }
}
