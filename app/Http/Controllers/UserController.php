<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Yajra\DataTables\Facades\DataTables;
use DB;
use App\User;
use App\UserAccess;
use Illuminate\Support\Facades\Auth;
use App\Branch;
use App\Log;
use Illuminate\Support\Facades\Hash;
use Intervention\Image\Facades\Image;
use Illuminate\Support\Facades\Storage;
use URL;
use App\Notifications\SendEmailLogin;
use Carbon\Carbon;
use PhpOffice\PhpWord\PhpWord;

class UserController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function __construct()
    {
        $this->middleware(['locale', 'auth']);
    }

    public function AdminUser()
    {
        return view('user.indexadmin');
    }

    public function GetDataTableAdmin(Datatables $datatables, Request $request)
    {
        $is_admin = auth()->user()->role->role_code == 800 ? true : false;
        $mUser = User::with('role')
            ->select('sys_users.*')
            ->where('user_cat', '1');

        $datatables = Datatables::of($mUser)
            ->addIndexColumn()
            ->editColumn('state_cd', function (User $user) {
                if ($user->state_cd != '') {
                    return $user->Negeri->descr;
                } else {
                    return '';
                }
            })
            ->editColumn('brn_cd', function (User $user) {
                if ($user->brn_cd != '') {
                    return $user->Cawangan->BR_BRNNM;
                } else {
                    return '';
                }
            })
            ->editColumn('status', function (User $user) {
                return User::ShowStatus($user->status);
            })
            ->editColumn('role.role_code', function (User $user) {
                if ($user->role()->count() > 0) {
                    return User::ShowRoleName($user->role->role_code);
                } else {
                    return '';
                }
            })
            ->addColumn('action', function (User $user) use ($is_admin) {
                $button = '
                    <a href="' . route("user.editadmin", $user->id) . '"
                        class="btn btn-xs btn-primary" 
                        data-toggle="tooltip" 
                        data-placement="right" 
                        title="Kemaskini"><i class="fa fa-pencil"></i></a>';

                if ($is_admin) {
                    $button .= '
                        <a class="btn btn-xs btn-danger" rel="tooltip" 
                            data-original-title="' . __('button.impersonate') . '" 
                            href="' . route('admin.users.impersonate', $user->id) . '">
                            <i class="fa fa-play"></i>
                        </a>';
                }

                return $button;
            })
            ->filter(function ($query) use ($request) {
                if ($request->has('username')) {
                    $query->where('username', 'like', "%{$request->get('username')}%");
                }
                if ($request->has('name')) {
                    $query->where('name', 'like', "%{$request->get('name')}%");
                }
                if ($request->has('state_cd')) {
                    $query->where('state_cd', $request->get('state_cd'));
                }
                if ($request->has('brn_cd')) {
                    $query->where('brn_cd', $request->get('brn_cd'));
                }
                if ($request->has('status')) {
                    $status = explode('-', $request->get('status'));
                    $query->where('status', $status[1]);
                }
                if ($request->has('role')) {
                    $query->join('sys_user_access', 'sys_users.id', '=', 'sys_user_access.user_id')
                        ->where('sys_user_access.role_code', $request->get('role'));
                }
            });
        return $datatables->make(true);
    }

    public function CreateAdmin()
    {
        return view('user.createadmin');
    }

    public function StoreAdmin(Request $r)
    {
        $this->validate($r, [
            'icnew' => 'required|max:12|min:12|unique:sys_users,icnew,2,user_cat',
            'name' => 'required',
//            'password' => 'required|min:6|regex:/^(?=.*[a-z])(?=.*[A-Z])(?=.*\d).+$/',
            'password' => 'required|min:6',
            'state_cd' => 'required',
            'brn_cd' => 'required',
            'email' => 'required',
            'job_dest' => 'required',
            'status' => 'required',
            'role' => 'required',
        ]);
//        dd($r);exit;
        $model = new User;
        $model->username = $r->icnew;
        $model->name = $r->name;
        $model->icnew = $r->icnew;
        $model->state_cd = $r->state_cd;
        $model->email = $r->email;
        $model->user_cat = '1';
        $model->mobile_no = $r->mobile_no;
        $model->office_no = $r->office_no;
        $model->brn_cd = $r->brn_cd;
        $model->job_dest = $r->job_dest;
        $model->status = $r->status;
        $model->password = bcrypt($r->password);
        $model->password_ind = '0';
        $model->profile_ind = '0';
        if ($model->save()) {
//            foreach($r->role as $role) {
            $mRole = new UserAccess;
            $mRole->user_id = $model->id;
            $mRole->role_code = $r->role;
            if ($mRole->save()) {
                $model->notify(new SendEmailLogin($model, $r));
                $r->session()->flash('success', 'Maklumat pengguna telah berjaya ditambah');
                return redirect()->route('adminuser');
            }
//            }
        }
    }

    public function EditAdmin($id)
    {
        $mUser = User::find($id);
//        $mUserAccess = DB::table('sys_user_access')->where('user_id',$id)->pluck('role_code')->toArray();
        $mUserAccess = DB::table('sys_user_access')->select('role_code')->where('user_id', $id)->first();
        return view('user.editadmin', compact('mUser', 'mUserAccess'));
    }

    public function PatchAdmin(Request $r, $id)
    {
//        dd($r);
        $this->validate($r, [
//            'icnew' => 'required|max:12|min:12|unique:sys_users,icnew,2,user_cat',
            'name' => 'required',
            'password' => 'sometimes|nullable|min:6',
            'state_cd' => 'required',
            'brn_cd' => 'required',
            'email' => 'required',
            'job_dest' => 'required',
            'status' => 'required',
            'role' => 'required',
        ]);

        $mUser = User::find($id);
        $mUser->name = $r->name;
        $mUser->mobile_no = $r->mobile_no;
        $mUser->office_no = $r->office_no;
        $mUser->email = $r->email;
        $mUser->state_cd = $r->state_cd;
        $mUser->brn_cd = $r->brn_cd;
        $mUser->job_dest = $r->job_dest;
        $mUser->status = $r->status;
        if ($r->password != '') {
            $mUser->password = bcrypt($r->password);
        }
        if ($mUser->save()) {
            UserAccess::where('user_id', $id)->delete();
//            foreach($r->role as $role) {
            $mRole = new UserAccess;
            $mRole->user_id = $mUser->id;
//            $mRole->role_code = $role;
            $mRole->role_code = $r->role;
            if ($mRole->save()) {
//            }
                $r->session()->flash('success', 'Maklumat pengguna telah berjaya dikemaskini');
                return redirect()->route('adminuser');
            }
        }
    }

    public function PublicUser()
    {
        return view('user.indexpublic');
    }

    public function GetDataTablePublic(Datatables $datatables, Request $request)
    {
        $mUser = User::where('user_cat', '2');
        $is_admin = auth()->user()->role->role_code == 800 ? true : false;

        $datatables = Datatables::of($mUser)
            ->addIndexColumn()
            ->editColumn('gender', function (User $user) {
                if ($user->gender != '') {
                    return $user->genderdescr->descr;
                } else {
                    return '';
                }
            })
            ->editColumn('status', function (User $user) {
                return User::ShowStatus($user->status);
            })
            ->editColumn('state_cd', function (User $user) {
                if ($user->state_cd != '') {
                    if ($user->Negeri) {
                        return $user->Negeri->descr;
                    } else {
                        return $user->state_cd;
                    }
                } else {
                    return '';
                }
            })
            ->editColumn('distrinct_cd', function (User $user) {
                if ($user->distrinct_cd != '') {
                    if ($user->Daerah) {
                        return $user->Daerah->descr;
                    } else {
                        return $user->distrinct_cd;
                    }
                } else {
                    return '';
                }
            })
            ->addColumn('jumlahaduan', function (User $mUser) {
                $totalDuration = $mUser->GetTotalComplaint($mUser->id);
                if ($totalDuration != '') {
                    return '<a href=' . url("publicuser/complaintpublic?userid=$mUser->id") . '><center>' . $totalDuration . '</center></a>';
                } else {
                    return '';
                }
            })
            ->addColumn('ctry_cd', function (User $mUser) {
                if ($mUser->ctry_cd != '' && $mUser->ctry_cd != NULL) {
                    return $mUser->CtryCd->descr;
                } else {
                    return '';
                }
            })
            ->editColumn('created_at', function (User $user) {
                return $user->created_at ? with(new Carbon($user->created_at))->format('d-m-Y h:i A') : '';
            })
            ->addColumn('action', function (User $user) use ($is_admin) {
                $button = '
                    <a href="' . route("publicuser.changepasspublic", $user->id) . '" 
                        class="btn btn-xs btn-primary" 
                        data-toggle="tooltip" 
                        data-placement="right" 
                        title="Kemaskini Katalaluan">
                        <i class="fa fa-key"></i>
                    </a>';

                $button .= '
                    <a href="' . route("publicuser.edit", $user->id) . '"
                        class="btn btn-xs btn-primary" 
                        data-toggle="tooltip" 
                        data-placement="right" 
                        title="Kemaskini">
                        <i class="fa fa-pencil"></i>
                    </a>';

                if ($is_admin) {
                    $button .= '
                        <a class="btn btn-xs btn-danger" rel="tooltip" 
                            data-original-title="' . __('button.impersonate') . '" 
                            href="' . route('admin.users.impersonate', $user->id) . '">
                            <i class="fa fa-play"></i>
                        </a>';
                }

                return $button;
            })
            ->filter(function ($query) use ($request) {
                if ($request->has('username')) {
                    $query->where('username', 'LIKE', "%{$request->get('username')}%");
                }
                if ($request->has('name')) {
                    $query->where('name', 'LIKE', "%{$request->get('name')}%");
                }
                if ($request->has('state_cd')) {
                    $query->where('state_cd', '=', "{$request->get('name')}");
                }
                if ($request->has('gender')) {
                    $query->where('gender', '=', "{$request->get('gender')}");
                }
                if ($request->has('age')) {
                    $query->where('age', '=', "{$request->get('age')}");
                }
                if ($request->has('email')) {
                    $query->where('email', 'LIKE', "%{$request->get('email')}%");
                }
                if ($request->has('citizen')) {
                    $query->where('citizen', '=', "{$request->get('citizen')}");
                }
                if ($request->has('status')) {
                    $status = explode('-', $request->get('status'));
                    $query->where('status', $status[1]);
                }
                if ($request->has('created_at_from')) {
                    $query->whereDate('created_at', '>=', Carbon::parse($request->get('created_at_from'))->startOfDay());
                }
                if ($request->has('created_at_to')) {
                    $query->whereDate('created_at', '<=', Carbon::parse($request->get('created_at_to'))->endOfDay());
                }
            })
            ->rawColumns(['jumlahaduan', 'action']);

        return $datatables->make(true);
    }

    public function CreatePublic()
    {
        return view('user.createpublic');
    }

    public function StorePublic(Request $request)
    {
//        dd($request->all());
        $this->validate($request, [
            'name' => 'required',
            'username' => 'required|max:12|min:12|unique:sys_users,icnew,1,user_cat',
            'citizen' => 'required',
            'email' => 'required',
            'mobile_no' => 'required',
            'password' => 'required|min:6',
            'status' => 'required',
            'ctry_cd' => 'required_if:citizen,2',
            'age' => 'required_if:citizen,2',
            'address' => 'required_if:citizen,2',
            'postcode' => 'required_if:citizen,2',
            'state_cd' => 'required_if:citizen,2',
            'distrinct_cd' => 'required_if:citizen,2',
            'gender' => 'required_if:citizen,2',
        ]);

        $mUser = new User;
        $mUser->username = request('username');
        $mUser->icnew = request('username');
        $mUser->name = request('name');
        $mUser->email = request('email');
        $mUser->citizen = request('citizen');
        $mUser->mobile_no = request('mobile_no');
        $mUser->office_no = request('office_no');
        $mUser->lang = request('lang');
        $mUser->status = 1;
        $mUser->password = bcrypt(request('password'));
        $mUser->user_cat = 2;
        if (request('citizen') == 2) {
            $mUser->ctry_cd = request('ctry_cd');
            $mUser->age = request('age');
            $mUser->address = request('address');
            $mUser->postcode = request('postcode');
            $mUser->state_cd = request('state_cd');
            $mUser->distrinct_cd = request('distrinct_cd');
            $mUser->gender = request('gender');
        }
        if ($mUser->save()) {
            $mLog = new Log;
            $mLog->details = $request->path();
            $mLog->parameter = $mUser->id;
            $mLog->ip_address = $request->ip();
            $mLog->user_agent = $request->header('User-Agent');
            if ($mLog->save()) {
                $request->session()->flash('success', 'Pengguna Awam telah berjaya ditambah');
                return redirect('/publicuser');
            }
        }
    }

    public function EditPublic($id)
    {
        $mUser = User::find($id);
        return view('user.editpublic', compact('mUser'));
    }

    public function UpdatePublic(Request $r, $id)
    {
        $this->validate($r, [
            'name' => 'required',
//            'username' => 'required|max:12|min:12|unique:sys_users,icnew,1,user_cat',
            'citizen' => 'required',
            'email' => 'required',
            'mobile_no' => 'required',
//            'password' => 'sometimes|nullable|min:6',
            'status' => 'required',
            'ctry_cd' => 'required_if:citizen,2',
            'age' => 'required_if:citizen,2',
            'address' => 'required_if:citizen,2',
            'postcode' => 'required_if:citizen,2',
            'state_cd' => 'required_if:citizen,2',
            'distrinct_cd' => 'required_if:citizen,2',
            'gender' => 'required_if:citizen,2',
        ]);

        $mUser = User::find($id);
        $mUser->username = $r->username;
        $mUser->icnew = $r->username;
        $mUser->name = $r->name;
        $mUser->email = $r->email;
        $mUser->citizen = $r->citizen;
        $mUser->mobile_no = $r->mobile_no;
        $mUser->office_no = $r->office_no;
        $mUser->age = $r->age;
        $mUser->lang = $r->lang;
        $mUser->gender = $r->gender;
        $mUser->status = 1;
//        $dbpassword = $mUser->password;
//        if($r->password != '') {
//            $passhash = $r->password;
//            if ($passhash != $dbpassword) {
//                $hash = bcrypt($passhash);
//                $mUser->password = $hash;
//            }
//        }
        $mUser->user_cat = 2;
        if ($r->citizen == 2) {
            $mUser->ctry_cd = $r->ctry_cd;
        }
        $mUser->age = $r->age;
        $mUser->address = $r->address;
        $mUser->postcode = $r->postcode;
        $mUser->state_cd = $r->state_cd;
        $mUser->distrinct_cd = $r->distrinct_cd;
        $mUser->gender = $r->gender;
//        }
        if ($mUser->save()) {
            $mLog = new Log;
            $mLog->details = $r->path();
            $mLog->parameter = $mUser->id;
            $mLog->ip_address = $r->ip();
            $mLog->user_agent = $r->header('User-Agent');
            if ($mLog->save()) {
                $r->session()->flash('success', 'Maklumat pengguna telah berjaya dikemaskini');
                return redirect('/publicuser');
            }
        }
    }

    public function DeletePublic($id)
    {
        User::find($id)->delete();
        session()->flash('success', 'Pengguna telah berjaya dihapus');
        return redirect('/publicuser');
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param \Illuminate\Http\Request $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     *
     * @param int $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param int $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param \Illuminate\Http\Request $request
     * @param int $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param int $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
    }

    public function EditProfile($id)
    {
        $mUser = User::find($id);
        $mUserAccess = DB::table('sys_user_access')->where('user_id', $id)->pluck('role_code')->toArray();
        return view('user.editprofile', compact('mUser', 'mUserAccess'));
    }

    public function UpdateProfile(Request $Request, $id)
    {
        $this->validate($Request, [
            'name' => 'required',
            'email' => 'required|email',
            'state_cd' => 'required',
            'brn_cd' => 'required',
            'mobile_no' => 'required',
            'office_no' => 'required',
        ], [
            'state_cd.required' => 'Ruangan Negeri diperlukan.',
            'brn_cd.required' => 'Ruangan Cawangan diperlukan.',
            'mobile_no.required' => 'Ruangan No. Tel Bimbit diperlukan.',
            'office_no.required' => 'Ruangan No. Tel Pejabat diperlukan.',
        ]);

        $mUser = User::find($id);
        $mUser->name = $Request->name;
        $mUser->mobile_no = $Request->mobile_no;
        $mUser->office_no = $Request->office_no;
        $mUser->email = $Request->email;
        $mUser->state_cd = $Request->state_cd;
        $mUser->brn_cd = $Request->brn_cd;
        $mUser->profile_ind = '1';
        if ($mUser->save()) {
            if ($Request->expectsJson()) {
                return response()->json(['data' => 'Maklumat Profil telah BERJAYA dikemaskini']);
            }
            return back()->with('success', 'Maklumat Profil telah BERJAYA dikemaskini');
        }
    }

    public function AdminUpdateUserPhoto(Request $Request, $id)
    {
        $this->validate($Request, [
            'pic' => 'mimes:jpeg,jpg,png | max:2048',
        ], [
            'pic.mimes' => 'Fail mesti jenis: jpeg, jpg, png.',
            'pic.max' => 'Saiz fail mesti kurang 2mb.',
        ]);

        $mUser = User::find(Auth::user()->id);
        $file = $Request->file('pic');

        if ($file) {
            $filename = Auth::user()->id . '.' . $file->getClientOriginalExtension();
            Storage::disk('profile')->putFileAs('', $Request->file('pic'), $filename);
            //$Request->file('pic')->storeAs('public', $filename);

            $mUser->user_photo = $filename;
            if ($mUser->save()) {
                return back()->with('success', 'Gambar profil anda telah BERJAYA dikemaskini');
            }
        } else {
            return back()->with('alert', 'Gambar profil DIPERLUKAN');
        }
    }

    public function AdminDeleteUserPhoto()
    {
        $mUser = User::find(Auth::user()->id);
        $exists = Storage::disk('profile')->exists($mUser->user_photo);
        if ($exists) {
            Storage::disk('profile')->delete($mUser->user_photo);
            $mUser->user_photo = NULL;
            if ($mUser->save()) {
                return back()->with('success', 'Gambar profil anda telah BERJAYA dipadam');
            }
        } else {
            return back()->with('alert', 'Gambar tidak DIJUMPAI');
        }
    }

    public function changepassword($id)
    {
        $mUser = User::find($id);
        return view('user.changepassword', compact('mUser'));
    }

    public function updatepassword(Request $r, $id)
    {
        $this->validate($r, [
            'passwordOld' => 'required',
            'passwordNew' => 'required|min:6',
            'passwordRepeat' => 'required|same:passwordNew',
        ],
            [
                'passwordOld.required' => 'Sila Masukkan Kata Laluan Lama.',
                'passwordNew.required' => 'Sila Masukkan Kata Laluan Baru.',
                'passwordRepeat.required' => 'Sila Masukkan Ulang Kata Laluan Baru.',
                'passwordNew.min' => 'Katalaluan perlu lebih 6 aksara',
                'passwordRepeat.same' => 'Ruangan Ulang Kata Laluan Baru dan Kata Laluan Baru mesti sepadan.'
            ]);

        $passwordOld = $r->passwordOld;
        $passwordNew = $r->passwordNew;
        $passwordRepeat = $r->passwordRepeat;
        $hashNew = bcrypt($passwordNew);

        $mUser = User::find($id);
        if (Hash::check($passwordOld, $mUser->password)) {
            if ($passwordNew == $passwordRepeat) {
                $mUser->password = $hashNew;
                $mUser->password_ind = '1';
                if ($mUser->save()) {
                    if ($r->expectsJson()) {
                        return response()->json(['data' => 'Katalaluan telah berjaya dikemaskini.']);
                    }
                    $r->session()->flash('success', 'Katalaluan telah berjaya dikemaskini.');
                    return redirect()->back();
                }
            }
        } else {
            if ($r->expectsJson()) {
                return response()->json(['data' => 'Kata Laluan Lama tidak sah.']);
            }
            $r->session()->flash('warning', 'Kata Laluan Lama tidak sah.');
            return redirect()->back();
        }
    }

    public function ChangePassUser($id)
    {
        $mUser = User::find($id);
        return view('user.changepasspublic', compact('mUser'));
    }

    public function UpdatePassUser(Request $r, $id)
    {
        $this->validate($r, [
            'password' => 'required|min:6',
            'password2' => 'required|same:password',
        ],
            [
                'password.required' => 'Sila Masukkan Kata Laluan.',
                'password.min' => 'Katalaluan perlu lebih 6 aksara',
                'password2.required' => 'Sila Masukkan Ulang Kata Laluan Baru.',
                'password2.same' => 'Ruangan Ulang Kata Laluan Baru dan Kata Laluan Baru mesti sepadan.'
            ]);

        $password = $r->password;
        $repassword = $r->password2;

        $mUser = User::find($id);
        if ($password == $repassword) {
            $hash = bcrypt($password);
            $mUser->password = $hash;
            if ($mUser->save()) {
                $mLog = new Log;
                $mLog->details = $r->path();
                $mLog->parameter = $mUser->id;
                $mLog->ip_address = $r->ip();
                $mLog->user_agent = $r->header('User-Agent');
                if ($mLog->save()) {
                    $r->session()->flash('success', 'Katalaluan Pengguna telah berjaya dikemaskini');
                    return redirect('/publicuser');
                }
            }
        } else {
            $r->session()->flash('warning', 'Ulang Kata Laluan Baru must be equal to "Kata Laluan Baru".');
            return redirect('/publicuser');
        }
    }

    public function PublicEditProfile()
    {
        $mUser = User::find(Auth::user()->id);

        return view('user.publiceditprofile', compact('mUser'));
    }

    public function PublicUpdateProfile(Request $Request)
    {
        $this->validate($Request, [
            'mobile_no' => 'required',
            'email' => 'required|email',
            'address' => 'required',
//            'postcode' => 'required | max:5|min:5',
            'mobile_no' => 'required',
            'state_cd' => 'required',
            'distrinct_cd' => 'required',
            'pic' => 'mimes:jpeg,jpg,png | max:2048',
            'ctry_cd' => 'required_if:citizen,0',
            'lang' => 'required'
        ]);

        $mUser = User::find(Auth::user()->id);
        $file = $Request->file('pic');

        if ($file) {
            if (isset($mUser->user_photo)) {
                $exists = Storage::disk('profile')->exists($mUser->user_photo);
                if ($exists) {
                    Storage::disk('profile')->delete($mUser->user_photo);
                }
            }
            $filename = Auth::user()->id . '.' . $file->getClientOriginalExtension();
            Storage::disk('profile')->putFileAs('', $Request->file('pic'), $filename);
            //$userphoto = $Request->file('pic')->storeAs('storage', $filename);

            $mUser->user_photo = $filename;
        }

        $mUser->mobile_no = $Request->mobile_no;
        $mUser->email = $Request->email;
        $mUser->lang = $Request->lang;
        $mUser->address = $Request->address;
        $mUser->postcode = $Request->postcode;
        $mUser->state_cd = $Request->state_cd;
        $mUser->distrinct_cd = $Request->distrinct_cd;
        $mUser->ctry_cd = $Request->ctry_cd;
        $mUser->profile_ind = '1';
        if ($mUser->save()) {
//            
//            $userphoto = 'uploads/profile/'.Auth::user()->user_photo;
//            Image::make($userphoto)->resize(45, 45)->save($userphoto);
            if ($Request->expectsJson()) {
                return response()->json(['data' => $mUser]);
            }
            if ($mUser->lang == 'ms') {
                $Request->session()->flash('success', 'Profil anda telah berjaya dikemaskini.');
            } else {
                $Request->session()->flash('success', 'Your profile has been updated.');
            }
            return redirect('user/pubeditprofile')//                    ->with('success', 'Profil anda telah berjaya dikemaskini')
                ;
        }

//        //Display File Name
//        echo 'File Name: '.$file->getClientOriginalName();
//        echo '<br>';
//
//        //Display File Extension
//        echo 'File Extension: '.$file->getClientOriginalExtension();
//        echo '<br>';
//
//        //Display File Real Path
//        echo 'File Real Path: '.$file->getRealPath();
//        echo '<br>';
//
//        //Display File Size
//        echo 'File Size: '.$file->getSize();
//        echo '<br>';
//
//        //Display File Mime Type
//        echo 'File Mime Type: '.$file->getMimeType();
//        echo '<br>';
    }

    public function PublicChangePassword($id)
    {
        $mUser = User::find($id);
        return view('user.pubchangepassword', compact('mUser'));
    }

    public function PublicUpdatePassword(Request $r, $id)
    {
        $this->validate($r, [
            'passwordOld' => 'required',
            'passwordNew' => 'required|min:6',
            'passwordRepeat' => 'required|same:passwordNew',
        ]);

        $passwordOld = $r->passwordOld;
        $passwordNew = $r->passwordNew;
        $passwordRepeat = $r->passwordRepeat;
        $hashNew = bcrypt($passwordNew);

        $mUser = User::find($id);
        if (Hash::check($passwordOld, $mUser->password)) {
            if ($passwordNew == $passwordRepeat) {
                $mUser->password = $hashNew;
                if ($mUser->save()) {
                    if ($r->expectsJson()) {
                        return response()->json(['data' => 'ok']);
                    }
                    $r->session()->flash('success', 'Katalaluan telah berjaya dikemaskini.');
                    return redirect()->back()->with('success', 'Katalaluan telah berjaya dikemaskini.');
                }
            }
        } else {
            $r->session()->flash('warning', 'Kata Laluan Lama tidak sah.');
            return redirect()->back();
        }
    }

    public function PublicDeleteUserPhoto(Request $request)
    {
        $mUser = User::find(Auth::user()->id);
        $exists = Storage::disk('profile')->exists($mUser->user_photo);
        if ($exists) {
            // Storage::delete('profile/'.$mUser->user_photo);
            Storage::disk('profile')->delete($mUser->user_photo);
            $mUser->user_photo = NULL;
            if ($mUser->save()) {
                if (Auth::user()->lang == 'ms') {
                    $request->session()->flash('success', 'Gambar profil anda telah berjaya dihapus.');
                } else {
                    $request->session()->flash('success', 'Your profile picture has been deleted.');
                }
            }
        } else {
            if (Auth::user()->lang == 'ms') {
                $request->session()->flash('alert', 'Gambar tidak DIJUMPAI.');
            } else {
                $request->session()->flash('alert', 'Picture not found.');
            }
        }
        return redirect('user/pubeditprofile');
//                ->with('success', 'Profil anda telah berjaya dikemaskini')
//        echo Storage::getVisibility('pic', 'lDjzqF8FDnviSyCSqxVXHZVmt2D4GoWkELarFfl8.jpeg');exit;
//        Storage::delete('public', 'Kk0B4yZ3ZrXQbUomsIuWAGEPiRxtQo8aRjrWGOMz.jpeg');
//        echo $test;exit;
    }

    public function GetBrnList($state_cd)
    {
        $mBrnList = DB::table('sys_brn')
            ->where(['BR_STATECD' => $state_cd, 'BR_STATUS' => 1])
            ->pluck('BR_BRNNM', 'BR_BRNCD');
        $mBrnList->prepend('-- SILA PILIH --', '');
        return json_encode($mBrnList);
    }

    public function GetDstrtList($state_cd)
    {
        $mDstrtList = DB::table('sys_ref')
            ->where('code', 'LIKE', $state_cd . '%')->where('code', '!=', $state_cd)->where('cat', '18')
            ->pluck('descr', 'code');
        $mDstrtList->prepend('-- SILA PILIH --', '');
        return json_encode($mDstrtList);
    }

    public function GetDstrtList1($state_cd)
    {
        $array1 = array('Mathematics', 'Physics');
        array_push($array1, 'Chemistry', 'Biology');
        $mDstrtList = DB::table('sys_ref')
            ->where('code', 'LIKE', $state_cd . '%')->where('code', '!=', $state_cd)->where('cat', '18')
            ->pluck('descr', 'code');
        $mDstrtList->prepend('-- SILA PILIH --', '');
        return json_encode($mDstrtList);
    }

    public function ChangeLanguage($locale, $tab = null)
    {
        $mUser = User::find(Auth::user()->id);
        $mUser->lang = $locale;
//        if($locale == 'ms'){
//            $message = 'Bahasa Telah dikemaskini';
//        } else {
//            $message = 'Language changed';
//        }
        if ($mUser->save()) {
//            return back()->with('success', $message);
            return redirect(url(URL::previous()) . ($tab ? '#' . $tab : ''))->with('success', trans('auth.home.changelanguage'));
        }
    }

    public function complaintpublic(Request $request)
    {
        $userid = $request->userid;
        $mUser = User::find($userid);
        $complaintlist = DB::table('case_info')
//            ->when($userid, function ($query) use ($userid) {
//                return $query->where('CA_CREBY', $userid);
//            })
            ->where(function ($query) use ($mUser) {
                $query->where('CA_CREBY', $mUser->id)
                    ->orWhere('CA_EMAIL', $mUser->email)
                    ->orWhere('CA_DOCNO', $mUser->icnew);
            })
            ->whereNotNull('CA_CASEID')
            ->whereNotIn('CA_INVSTS', [10])
            ->orderBy('CA_RCVDT', 'desc')
            ->get();
        return view('user.complaintpublic', compact('userid', 'complaintlist'));
    }

    public function generatedoc(Request $request, $id)
    {
        $mUser = User::find($id);
        $mBranchName = $mUser->Cawangan ? $mUser->Cawangan->BR_BRNNM : '';
        $mJobDest = $mUser->jobdest ? $mUser->jobdest->descr : '';
        $mRoleName = $mUser->Role ? User::ShowRoleName($mUser->Role->role_code) : '';
        $mCreatedAt = $mUser->created_at ? with(new Carbon($mUser->created_at))->format('d / m / Y') : '';
        $mcreatedby = $mUser->createdby ? $mUser->createdby->name : '';
        $phpWord = new PhpWord;
        $phpWord->addParagraphStyle('Heading2', array('alignment' => 'center'));
        $section = $phpWord->addSection();
        $html = '<p style="text-align: right;font-weight: bold;font-size: 11px;">BORANG IT07</p>';
        $html .= '<p style="text-align: center;font-weight: bold;font-size:16px;">BORANG PERMOHONAN HAK CAPAIAN APLIKASI</p>';
        $html .= '<p style="text-align: center;font-weight: bold;font-size:16px;">BAHAGIAN PENGURUSAN MAKLUMAT<br />'
            . 'KEMENTERIAN PERDAGANGAN DALAM NEGERI<br />'
            . 'DAN HAL EHWAL PENGGUNA</p>';
        $html .= '<table>
                <tbody>
                    <tr>
                        <td><span style="font-weight: bold;font-size: 13px;">NO. LOG</span></td>
                        <td rowspan="2">
                            <table border="1">
                                <tbody>
                                    <tr>
                                        <td>' . $id . '</td>
                                    </tr>
                                </tbody>
                            </table>
                        </td>
                    </tr>
                    <tr>
                        <td><span style="font-weight: bold;font-size: 9px;">UNTUK KEGUNAAN BPM</span></td>
                    </tr>
                </tbody>
            </table>';
        $html .= '<br /><table>
                <tbody>
                    <tr>
                        <td><span style="font-weight: bold;font-size: 13px;background-color:#f0f0f0;">MAKLUMAT PEMOHON</span></td>
                    </tr>
                </tbody>
            </table>';
        $html .= '<br /><table cellpadding="1" cellspacing="1" style="width:100%;">
            <tbody>
                <tr>
                    <td style="width:40%">NAMA</td>
                    <td style="width:60%">:&nbsp;' . $mUser->name . '</td>
                </tr>
                <tr>
                    <td style="width:40%">NO KP</td>
                    <td style="width:60%">:&nbsp;' . $mUser->icnew . '</td>
                </tr>
                <tr>
                    <td style="width:40%">NEGERI / CAW / BAHAGIAN / UNIT</td>
                    <td style="width:60%">:&nbsp;' . $mBranchName . '</td>
                </tr>
                <tr>
                    <td style="width:40%">JAWATAN</td>
                    <td style="width:60%">:&nbsp;' . $mJobDest . '</td>
                </tr>
                <tr>
                    <td style="width:40%">E-MAIL</td>
                    <td style="width:60%">:&nbsp;' . $mUser->email . '</td>
                </tr>
                <tr>
                    <td style="width:40%">NO. TEL</td>
                    <td style="width:60%">:&nbsp;' . $mUser->office_no . '</td>
                </tr>
                <tr>
                    <td style="width:40%">BIDANG TUGAS</td>
                    <td style="width:60%">:&nbsp;' . $mRoleName . '</td>
                </tr>
            </tbody>
            </table>';
        $html .= '<br /><table>
                <tbody>
                    <tr>
                        <td><span style="font-weight: bold;font-size: 13px;background-color:#f0f0f0;">KETERANGAN PEMOHON</span></td>
                    </tr>
                </tbody>
            </table>';
        $html .= '<br /><table cellpadding="1" cellspacing="1" style="width:100%;">
            <tbody>
                <tr>
                    <td style="width:40%">JENIS PERMOHONAN</td>
                    <td style="width:60%">:&nbsp;</td>
                </tr>
                <tr>
                    <td style="width:40%">NAMA SISTEM</td>
                    <td style="width:60%">:&nbsp;Sistem e-Tribunal V2 / MyStandard / e-Aduan</td>
                </tr>
                <tr>
                    <td style="width:40%">MODUL CAPAIAN</td>
                    <td style="width:60%">:&nbsp;' . $mRoleName . '</td>
                </tr>
                <tr>
                    <td style="width:40%">PERINGKAT CAPAIAN</td>
                    <td style="width:60%">
                    :&nbsp;<p>
                    KEMASUKAN DATA<br />
                    &nbsp;&emsp;PENGESAHAN<br />
                    &nbsp;&emsp;KELULUSAN<br />
                    &nbsp;&emsp;LAIN-LAIN
                    </p>
                    </td>
                </tr>
            </tbody>
            </table>';
        $html .= '<br /><table>
                <tbody>
                    <tr>
                        <td><span style="font-weight: bold;font-size: 13px;background-color:#f0f0f0;">PENGESAHAN PENGGUNA</span></td>
                    </tr>
                </tbody>
            </table>';
        $html .= '<br /><table cellpadding="0px" cellspacing="0px" style="width:100%;border-collapse: collapse;padding: 0px;">
            <tbody>
                <tr style="height:100px;">
                    <td style="width:20%;">T.T PEMOHON</td>
                    <td style="width:30%;">:&nbsp;______________</td>
                    <td style="width:20%;">T.T KETUA PEMOHON</td>
                    <td style="width:30%;">:&nbsp;______________</td>
                </tr>
                <tr>
                    <td>TARIKH</td>
                    <td>:&nbsp;' . $mCreatedAt . '</td>
                    <td>NAMA KETUA</td>
                    <td>:&nbsp;PN WAN RUFAIDAH BINTI DATO HAJI WAN OMAR</td>
                </tr>
                <tr>
                    <td>&nbsp;</td>
                    <td>&nbsp;</td>
                    <td>TARIKH</td>
                    <td>:&nbsp;' . $mCreatedAt . '</td>
                </tr>
            </tbody>
            </table>';
        $html .= '<br /><table style="width:100%;border-collapse: collapse;padding: 0px;background-color:#f0f0f0;">
                <tbody>
                    <tr>
                        <td><span style="font-weight: bold;font-size: 13px;">UNTUK KEGUNAAN BPM</span></td>
                    </tr>
                </tbody>
            </table>';
        $html .= '<br /><table style="width:100%;border-collapse: collapse;padding: 0px">
            <tbody>
                <tr>
                    <td style="width:19%">STATUS PERMOHONAN</td>
                    <td style="width:1%">:&nbsp;</td>
                    <td style="width:30%">&nbsp;</td>
                    <td style="width:19%">SILA NYATAKAN</td>
                    <td style="width:1%">:&nbsp;</td>
                    <td style="width:30%">&nbsp;______________</td>
                </tr>
                <tr>
                    <td style="width:19%">ID</td>
                    <td style="width:1%">:&nbsp;</td>
                    <td style="width:30%">&nbsp;' . $id . '</td>
                </tr>
                <tr>
                    <td style="width:19%">KATA LALUAN</td>
                    <td style="width:1%">:&nbsp;</td>
                    <td style="width:30%">&nbsp;*****</td>
                </tr>
                <tr>
                    <td style="width:19%">PENTADIR SISTEM</td>
                    <td style="width:1%">:&nbsp;</td>
                    <td style="width:30%">&nbsp;' . $mcreatedby . '</td>
                </tr>
                <tr style="height:100px;">
                    <td style="width:19%">TARIKH</td>
                    <td style="width:1%">:&nbsp;</td>
                    <td style="width:30%">&nbsp;' . $mCreatedAt . '</td>
                    <td style="width:19%">TANDATANGAN</td>
                    <td style="width:1%">:&nbsp;</td>
                    <td style="width:30%">&nbsp;</td>
                </tr>
                <tr>
                    <td style="width:19%">CATATAN</td>
                    <td style="width:1%">:&nbsp;</td>
                    <td style="width:30%">&nbsp;</td>
                </tr>
            </tbody>
            </table>';
        \PhpOffice\PhpWord\Shared\Html::addHtml($section, $html, false, false);
        $file =
            'BORANG IT07 - BORANG PERMOHONAN HAK CAPAIAN APLIKASI - ' . $id . ' - ' . date('Ymd') . '.docx';
        $phpWord->save($file, 'Word2007', true);
    }
}
