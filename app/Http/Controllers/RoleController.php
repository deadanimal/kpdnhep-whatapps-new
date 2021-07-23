<?php

namespace App\Http\Controllers;

use Yajra\DataTables\Facades\DataTables;
use DB;
use Illuminate\Http\Request;
use App\Ref;
use App\Menu;
use App\Role;

class RoleController extends Controller {

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function __construct() {
        $this->middleware('auth');
    }

    public function index() {
//        $menu_utama = Menu::select('menu_text', 'id')->where(['module_ind' => '1'])->take(100)->get();
        $peranan = Ref::select('descr', 'code')->where(['cat' => '152'])->take(100)->get();
//        return view('role.index');
        return view('role.index', compact('peranan'));
    }

//    public function get_datatable(Datatables $datatables, Request $request) {
//        $mRole = DB::table('sys_role_mapping')
//                ->select(['role_code', 'menu_id']);
//
//        $datatables = app('datatables')->of($mRole)
//                ->addColumn('action', function ($role) {
//            return '<a href="#edit-' . $role->role_code . '" class="btn btn-xs btn-primary"><i class="fa fa-pencil"></i> Edit</a>';
//        });
//
////        return Datatables::of($refs)
////                ->addColumn('action', function ($ref) {
////                    return '<a href="#edit-'.$ref->id.'" class="btn btn-xs btn-primary"><i class="glyphicon glyphicon-edit"></i> Edit</a>';
////                })
////                ->make(true);
////        print_r($datatables->request->get());exit;
//        if ($role_code = $datatables->request->get('role_code')) {
//            $datatables->where('role_code', 'like', "%$role_code%");
//        }
//
//        return $datatables->make(true);
//    }
    
    public function GetDatatable(Datatables $datatables, Request $request) {
//        $mRole = Role::pluck(['role_code', 'menu_id'])->get();
//        $mRole = DB::table('sys_role_mapping')
//                ->select(['role_code', 'menu_id']);
        $mRole = Role::join('sys_menu', 'sys_role_mapping.menu_id', '=', 'sys_menu.id')
                ->select('role_code', 'menu_id', 'sys_menu.sort');

        $datatables = Datatables::of($mRole)
                ->addIndexColumn()
                ->editColumn('role_code', function ($role) {
                    return Role::ShowPeranan($role->role_code);
                })
                ->editColumn('menu_id', function ($role) {
                    return Role::ShowMenu($role->menu_id);
                })
//                ->editColumn('menu_id', function ($role) {
//                    return Role::ShowMenuSort($role->menu_id);
//                })
                ->addColumn('action', '
                                    <a href="{{ url(\'role\edit\', array($role_code,$menu_id)) }}" class="btn btn-xs btn-primary" data-toggle="tooltip" data-placement="right" title="Kemaskini"><i class="fa fa-pencil"></i></a>
                                    <a href="{{ url(\'role\delete\', array($role_code,$menu_id)) }}" class="btn btn-xs btn-danger" data-toggle="tooltip" data-placement="right" title="Hapus"><i class="fa fa-trash" onclick = "return confirm(\'{{ trans("action.delete") }}\')"></i></a>')
                ->filter(function ($query) use ($request) {
                    if ($request->has('role_code')) {
                        $query->where('role_code', 'like', "%{$request->get('role_code')}%");
                        $query->orderBy('sys_menu.sort');
                    }
                    if ($request->has('menu_id')) {
                        $query->where('menu_id', $request->get('menu_id'));
                        $query->orderBy('sys_menu.sort');
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
    public function create() {
        $peranan = Ref::select('descr', 'code')->where(['cat' => '152'])->take(100)->get();
        $menu = Menu::select('menu_txt', 'id')->take(100)->get();
//        return view('role.create');
        return view('role.create', compact('peranan', 'menu'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request) {
        $attributeNames = array(
            'role_code' => 'Peranan',
            'menu_id' => 'Menu',
        );
        $this->validate($request, [
            'role_code' => 'required',
            'menu_id' => 'required',
        ]);
        
        $model = new Role;
        $model->role_code = request('role_code');
        $model->menu_id = request('menu_id');
        if ($model->save()) {
            $request->session()->flash('success', 'Peranan telah berjaya ditambah');
                return redirect('/role');
        }
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id) {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($role_code, $menu_id) {
//        $mRole = DB::table('sys_role_mapping')->where(['role_code' => $role_code, 'menu_id' => $menu_id])->get()->first();
        $mRole = Role::where('role_code', '=', $role_code)
                    ->where('menu_id', '=', $menu_id)
                    ->first();
        return view('role.edit', compact('mRole', 'role_code', 'menu_id'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update($role_code,$menu_id) {
        
        Role::where(['role_code' => $role_code, 'menu_id' => $menu_id])
            ->update(['role_code' => request('role_code'), 'menu_id' => request('menu_id')]);
        
        return redirect('/role');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function delete($role_code,$menu_id) {
//        $Role = Role::where('role_code', '=', $role_code)
//                    ->where('menu_id', '=', $menu_id)
//                    ;
//        $Role->first()->delete();
         DB::table('sys_role_mapping')->where(['role_code' => $role_code, 'menu_id' => $menu_id])->delete(); 
//        Role::where('role_code', '=', $role_code)
//                    ->where('menu_id', '=', $menu_id)
//                    ->get()->first()->delete();
        session()->flash('success', 'Peranan telah berjaya dihapus');
        return redirect()->back();
    }
    
    public function destroy($id) {
        //
    }

}
