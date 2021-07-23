<?php

namespace App\Http\Controllers;

use App\Email;
use Illuminate\Http\Request;
use Yajra\DataTables\Facades\DataTables;
use DB;
use App\Log;

class EmailController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        return view('email.index'); //
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        return view ('email.create'); //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $this->validate(request(),[
            'title' => 'required|max:100',
            'email_type' => 'required',
            'email_code' => 'required',
        ]);
        
        $mEmail = new Email;
        $mEmail->fill($request->all());
        // $mEmail->title = request('title');
        // $mEmail->header = request('header');
        // $mEmail->body = request('body');
        // $mEmail->footer = request('footer');
        // $mEmail->email_type = request('email_type');
        // $mEmail->email_cat = request('email_cat');
        // $mEmail->status = request('status');
        
        if ($mEmail->save()) {
            $mLog = new Log;
            $mLog->details = $request->path();
            $mLog->parameter = $mEmail->id;
            $mLog->ip_address = $request->ip();
            $mLog->user_agent = $request->header('User-Agent');
            if($mLog->save()) {
                $request->session()->flash('success', 'Templat Emel telah berjaya ditambah');
                return redirect ('/email');
            }
        }
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Email  $email
     * @return \Illuminate\Http\Response
     */
    public function show(Email $email)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Email  $email
     * @return \Illuminate\Http\Response
     */
//    public function edit(Email $email)
    public function edit($id)
    {
        $mEmail = DB::table('template_email')->find($id); //
        return view('email.edit', compact('mEmail', 'id'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Email  $email
     * @return \Illuminate\Http\Response
     */
//    public function update(Request $request, Email $email)
    public function update(Request $request, $email)
//    public function update($id)
    {
        $this->validate(request(),[
            'title' => 'required|max:100',
            'email_type' => 'required',
            'email_code' => 'required',
        ]);
        
        $mEmail = Email::find($email);
        $mEmail->update(request()->all());
        
        if ($mEmail->save()) {
            $mLog = new Log;
            $mLog->details = $request->path();
            $mLog->parameter = $mEmail->id;
            $mLog->ip_address = $request->ip();
            $mLog->user_agent = $request->header('User-Agent');
            if($mLog->save()) {
                $request->session()->flash('success', 'Templat Emel telah berjaya dikemaskini');
                return redirect('/email');
            }
        }
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Email  $email
     * @return \Illuminate\Http\Response
     */
    public function destroy(Email $email)
    {
        //
    }
    
//    public function delete($id)
    public function delete(Request $request, $id)
    {
        $mEmail = Email::find($id);
        $mEmail->delete($id);
        $mLog = new Log;
        $mLog->details = $request->path();
        $mLog->parameter = $mEmail->id;
        $mLog->ip_address = $request->ip();
        $mLog->user_agent = $request->header('User-Agent');
        if($mLog->save()) {
            session()->flash('success', 'Templat Emel telah berjaya dihapus.');
            return redirect('/email');
        }
    }
    
    public function get_datatable(Datatables $datatables, Request $request) {
        $mEmail = DB::table('template_email')->select([
            'id', 'title', 'header', 'body', 'footer', 'email_type', 'email_cat', 'status'
        ]);
        
        $datatables = Datatables::of($mEmail)
            ->addIndexColumn()
//                ->editColumn('email_type', function ($email) {
//                    return Email::ShowType($email->email_type);
//                })
//                ->editColumn('status', function ($email) {
//                    return Email::ShowStatus($email->status);
//                })
//                ->editColumn('email_type', function(Email $email) {
//                    if($email->email_type != '')
//                    return $email->EmailType->descr;
//                    else
//                    return '';
//                })
            ->editColumn('email_type', function($email) {
                return Email::ShowEmailType($email->email_type);
            })
            ->editColumn('status', function ($email) {
                return Email::ShowStatus($email->status);
            })
            ->addColumn('action', '
                <a href="{{ url("email/edit", $id) }}" class="btn btn-xs btn-primary" data-toggle="tooltip" data-placement="right" title="Kemaskini">
                    <i class="fa fa-pencil"></i>
                </a>
                {!! Form::open(["url" => "email/delete/$id", "method" => "GET", "style"=>"display:inline"]) !!}
                {!! Form::button("<i class=\'fa fa-trash\'></i>", ["type" => "submit", "class" => "btn btn-danger btn-xs", "data-toggle"=>"tooltip", "data-placement"=>"right", "title"=>"Hapus", "onclick" => "return confirm(\'Anda pasti untuk hapuskan rekod ini?\')"] ) !!}
                {!! Form::close() !!}
            ')
            ->filter(function ($query) use ($request) {
                if ($request->has('title')) {
                    $query->where('title', 'like', "%{$request->get('title')}%");
                }
                if ($request->has('email_type')) {
                    $query->where('email_type', '=', $request->get('email_type'));
                }
                if ($request->has('status')) {
                    $status = explode('-', $request->get('status'));
                    $query->where('status', $status[1]);
                }
                if ($request->has('letter_code')) {
                    $query->where('letter_code', 'like', "%{$request->get('letter_code')}%");
                }
            });

//        if ($title = $datatables->request->get('title')) {
//            $datatables->where('title', 'like', "%$title%");
//        }
//        if ($email_type = $datatables->request->get('email_type')) {
//            $datatables->where('email_type', '=', $email_type);
//        }
//        if ($status = $datatables->request->get('status')) {
//            $stat = explode('-', $status);
////            $datatables->where('status', '=', $status);
//            $datatables->where('status', '=', $stat[1]);
//        }

        return $datatables->make(true);
    }
    
    public function __construct()
    {
        $this->middleware('auth');
    }
}
