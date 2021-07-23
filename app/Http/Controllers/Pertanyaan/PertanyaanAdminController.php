<?php

namespace App\Http\Controllers\Pertanyaan;

use App\Http\Controllers\Controller;
use App\Mail\PertanyaanTerimaAdmin;
use App\Pertanyaan\AskAnswerTemplate;
use App\Pertanyaan\PertanyaanAdmin;
use App\Pertanyaan\PertanyaanAdminDoc;
use App\Pertanyaan\PertanyaanDetailAdmin;
use App\Pertanyaan\PertanyaanEmail;
use App\Pertanyaan\PertanyaanPublic;
use App\Repositories\RunnerRepository;
use Carbon\Carbon;
use DB;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Mail;
use PDF;
use Yajra\DataTables\Facades\DataTables;

class PertanyaanAdminController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */

    public function __construct()
    {
        $this->middleware('auth');
    }

    public function index()
    {
        return view('pertanyaan.pertanyaan-admin.index');
    }

    public function GetDatatable(Request $request)
    {
        $Pertanyaan = PertanyaanAdmin::
        whereNotNull('id')
//                where('AS_ASKID','<>',NULL)
//                ->
            // orderBy('AS_CREDT','DESC')
        ;

        if ($request->mobile) {
            return response()->json(['data' => $Pertanyaan->offset($request->offset)->limit($request->count)->get()->toArray()]);
        }
        $Datatable = DataTables::of($Pertanyaan)
            ->addIndexColumn()
            ->editColumn('AS_ASKID', function (PertanyaanAdmin $PertanyaanAdmin) {
                return view('pertanyaan.pertanyaan-admin.show_summary_link', compact('PertanyaanAdmin'))->render();
            })
            ->editColumn('AS_SUMMARY', function (PertanyaanAdmin $PertanyaanAdmin) {
                if ($PertanyaanAdmin->AS_SUMMARY != '')
                    return implode(' ', array_slice(explode(' ', ucfirst($PertanyaanAdmin->AS_SUMMARY)), 0, 7)) . '...';
                else
                    return '';
            })
            /*->editColumn('AS_USERID', function(PertanyaanAdmin $PertanyaanAdmin) {
                if($PertanyaanAdmin->AS_USERID)
                    return $PertanyaanAdmin->PublicUser->name;
                else
                    return '';
            })*/
            ->editColumn('AS_ASKSTS', function (PertanyaanAdmin $PertanyaanAdmin) {
                return $PertanyaanAdmin->ShowStatus->descr;
            })
            ->editColumn('AS_RCVDT', function (PertanyaanAdmin $PertanyaanAdmin) {
                if ($PertanyaanAdmin->AS_RCVDT)
                    return Carbon::parse($PertanyaanAdmin->AS_RCVDT)->format('d-m-Y h:i A');
                else
                    return '';
            })
            ->editColumn('AS_COMPLETEDT', function (PertanyaanAdmin $PertanyaanAdmin) {
                if ($PertanyaanAdmin->AS_COMPLETEDT)
                    return Carbon::parse($PertanyaanAdmin->AS_COMPLETEDT)->format('d-m-Y h:i A');
                else
                    return '';
            })
            ->editColumn('AS_COMPLETEBY', function (PertanyaanAdmin $PertanyaanAdmin) {
                if ($PertanyaanAdmin->AS_COMPLETEBY)
                    return $PertanyaanAdmin->CompleteBy->name;
                else
                    return '';
            })
            ->addColumn('tempoh', function (PertanyaanAdmin $PertanyaanAdmin) {
                $totalDuration = $PertanyaanAdmin->getduration($PertanyaanAdmin->AS_RCVDT, $PertanyaanAdmin->AS_ASKID, 'view');
                return $totalDuration;
            })
//                    ->addColumn('action', '<a href="{{ url("pertanyaan-admin/{$AS_ASKID}/edit") }}" class="btn btn-xs btn-primary" data-toggle="tooltip" data-placement="right" title="Kemaskini"><i class="fa fa-pencil"></i></a>')
            ->addColumn('action', function (PertanyaanAdmin $PertanyaanAdmin) {
                return view('pertanyaan.pertanyaan-admin.action_btn', compact('PertanyaanAdmin'))->render();
            })
            ->rawColumns(['AS_ASKID', 'action', 'tempoh'])
            ->filter(function ($query) use ($request) {
                if ($request->has('AS_ASKID')) {
                    $query->where('AS_ASKID', 'LIKE', "%{$request->get('AS_ASKID')}%");
                }
                if ($request->has('AS_SUMMARY')) {
                    $query->where('AS_SUMMARY', 'LIKE', "%{$request->get('AS_SUMMARY')}%");
                }
                if ($request->has('AS_ASKSTS')) {
                    $query->where('AS_ASKSTS', $request->get('AS_ASKSTS'));
                }
                if ($request->has('date_start')) {
                    $query->where('AS_RCVDT', '>=', Carbon::parse($request->get('date_start'))->format('Y-m-d 00:00:01'));
                    if ($request->has('date_end')) {
                        $query->where('AS_RCVDT', '<=', Carbon::parse($request->get('date_end'))->format('Y-m-d 23:59:59'));
                    }
                }
            });

        return $Datatable->make(true);
    }

    public function GetDatatableTransaction($ASKID)
    {
        $PertanyaanDetail = PertanyaanDetailAdmin::where(['AD_ASKID' => $ASKID])->orderBy('AD_CREDT', 'DESC');

        $Datatable = DataTables::of($PertanyaanDetail)
            ->addIndexColumn()
            ->editColumn('AD_ASKSTS', function (PertanyaanDetailAdmin $PertanyaanDetailAdmin) {
                return $PertanyaanDetailAdmin->ShowStatus->descr;
            })
            ->editColumn('AD_CURSTS', function (PertanyaanDetailAdmin $PertanyaanDetailAdmin) {
                return $PertanyaanDetailAdmin->ShowCurrentStatus->descr;
            })
            ->editColumn('AD_CREDT', function (PertanyaanDetailAdmin $PertanyaanDetailAdmin) {
                return Carbon::parse($PertanyaanDetailAdmin->AD_CREDT)->format('d-m-Y h:i A');
            });

        return $Datatable->make(true);
    }

    public function GetDatatableEmail($ASKID)
    {
        $PertanyaanEmail = PertanyaanEmail::where(['AE_ASKID' => $ASKID])->orderBy('AE_CREDT', 'DESC');

        $Datatable = DataTables::of($PertanyaanEmail)
            ->addIndexColumn()
            ->editColumn('AE_CREBY', function (PertanyaanEmail $PertanyaanEmail) {
                return $PertanyaanEmail->CreBy->name;
            });

        return $Datatable->make(true);
    }

    public function create()
    {
        return view('pertanyaan.pertanyaan-admin.create');
    }

    public function store(Request $request)
    {
        $this->validate($request, [
//            'AS_DOCNO' => 'required',
            'AS_MOBILENO' => 'required',
//            'AS_ADDR' => 'required',
            'AS_NAME' => 'required',
//            'AS_STATECD' => 'required',
//            'AS_DISTCD' => 'required',
//            'AS_COUNTRYCD' => 'required_if:AS_NATCD,0',
            'AS_SUMMARY' => 'required',
            'AS_RCVTYP' => 'required'
        ],
            [
//            'AS_DOCNO.required' => 'Ruangan No. Kad Pengenalan/Pasport diperlukan.',
                'AS_MOBILENO.required' => 'Ruangan No. Telefon (Bimbit) diperlukan.',
//            'AS_ADDR.required' => 'Ruangan Alamat diperlukan.',
                'AS_NAME.required' => 'Ruangan Nama diperlukan.',
//            'AS_STATECD.required' => 'Ruangan Negeri diperlukan.',
//            'AS_DISTCD.required' => 'Ruangan Daerah diperlukan.',
//            'AS_COUNTRYCD.required_if' => 'Ruangan Negara Asal diperlukan.',
                'AS_SUMMARY.required' => 'Ruangan Keterangan Pertanyaan/Cadangan diperlukan.',
                'AS_RCVTYP.required' => 'Ruangan Cara Penerimaan diperlukan.'
            ]
        );
        // adhoc. kill user from ezadu.
        if(in_array($request->AS_USERID, [12121])) {
            if ($request->expectsJson()) {
                $user = Auth::guard('api')->user();

                if ($user) {
                    $user->api_token = null;
                    $user->save();
                }

                return response()->json(['data' => 'User logged out.'], 200);
            }
        }

        $model = new PertanyaanAdmin();
        $model->AS_DEPTCD = 'UKK'; // UNIT KOMUNIKASI KORPORAT
        $model->AS_BRNCD = 'WHQR2'; // UNIT KOMUNIKASI KORPORAT
//        $model->AS_USERID = Auth::user()->id;
        $model->AS_ASKSTS = '1'; // Deraf
        $model->fill($request->all());
        if ($model->save()) {
            $mAskDetail = new PertanyaanDetailAdmin();
            $mAskDetail->AD_ASKID = $model->id;
            $mAskDetail->AD_ASKSTS = $model->AS_ASKSTS;
            $mAskDetail->AD_CURSTS = '1';
            if ($mAskDetail->save()) {
//                if($request->btnSimpan) {
//                    return redirect()->route('pertanyaan-public.edit',$model->AS_ASKID)->with('success', 'Pertanyaan / Cadangan anda telah BERJAYA disimpan');
//                }else{
//                return redirect()->route('dashboard',['#enquery'])->with('success', 'Pertanyaan / Cadangan anda telah BERJAYA  dihantar');
//                return redirect()->route('pertanyaan.attachment',['id'=>$model->id])->with('success', 'Pertanyaan / Cadangan anda telah BERJAYA  disimpan');
                if ($request->expectsJson()) {
                    return response()->json(['data' => $model->id], 200);
                }
                return redirect()->route('pertanyaan-admin.attachment', ['id' => $model->id]);
//                return redirect()->route('pertanyaan-admin.index');
//                }
            }
        }
    }

    public function show($id)
    {
        //
    }

    public function GetCmplList($cat_cd)
    {
        $mCatList = DB::table('sys_ref')
            ->whereIn('cat', ['634', '1280'])
            ->where('code', 'like', "$cat_cd%")
            ->where('status', '1')
            ->orderBy('sort', 'asc')
//            ->orderBy('descr', 'asc')
            ->pluck('code', 'descr');
//            ->prepend('', '-- SILA PILIH --');
        if (count($mCatList) != 1) {
            $mCatList->prepend('0', '-- SILA PILIH --');
        }
        return json_encode($mCatList);
    }

    public function edit($ASKID)
    {
        $PertanyaanAdmin = PertanyaanAdmin::where(['AS_ASKID' => $ASKID])->first();
        $data['answerTemplates'] = AskAnswerTemplate::pluck('title', 'id')->toArray();
        return view('pertanyaan.pertanyaan-admin.edit', compact('PertanyaanAdmin', 'data'));
    }

    public function update(Request $Request)
    {
        if ($Request->btnHantar) {
            $this->validate($Request, [
                'AS_ANSWER' => 'required',
                'AS_CMPLCAT' => 'required',
                'AS_CMPLCD' => 'required'
            ],
                [
                    'AS_ANSWER.required' => 'Ruangan Jawapan diperlukan.',
                    'AS_CMPLCAT.required' => 'Ruangan Kategori diperlukan.',
                    'AS_CMPLCD.required' => 'Ruangan Subkategori diperlukan.'
                ]);
        }

        $PertanyaanAdmin = PertanyaanAdmin::where(['AS_ASKID' => $Request->AS_ASKID])->first();
        // $PertanyaanAdminDoc = PertanyaanAdminDoc::where(['askid' => $Request->AS_ASKID])->get();
        $PertanyaanAdminDoc = PertanyaanAdminDoc::where(['askid' => $Request->AS_ASKID])
            ->where(function ($query) {
                $query->whereNull('img_cat')
                    ->orWhere('img_cat', '=', '1');
            })
            ->get();
        
        if($Request->btnSimpan) {
            $PertanyaanAdmin->AS_ANSWER = $Request->AS_ANSWER;
            $PertanyaanAdmin->AS_CMPLCAT = $Request->AS_CMPLCAT;
            $PertanyaanAdmin->AS_CMPLCD = $Request->AS_CMPLCD;

            if ($Request->email && $Request->content) {

                $model = new PertanyaanEmail();

                $model->AE_TITLE = $Request->title;
                $model->AE_TO = $Request->email;
                $model->AE_CC = $Request->cc;
                $model->AE_BCC = $Request->bcc;
                $model->AE_MESSAGE = $Request->content;
                $model->AE_CREBY = Auth::user()->id;
                $model->AE_ASKID = $Request->AS_ASKID;

                if ($model->save()) {
                    $emails = explode(";", $Request->email);

                    foreach ($emails as $email) {
                        Mail::send('pertanyaan.pertanyaan-admin.pegawaitanya', ['content' => $Request->content, 'pertanyaan' => $PertanyaanAdmin->AS_SUMMARY, 'no' => $PertanyaanAdmin->AS_ASKID], function ($message) use ($Request, $email, $PertanyaanAdminDoc) {
                            $message->to($email)->subject($Request->title);
                            $message->from(Auth::user()->email);

                            if ($Request->cc != '') {
                                $ccs = explode(";", $Request->cc);
                                foreach ($ccs as $cc) {
                                    $message->cc($cc);
                                }
                            }

                            if ($Request->bcc != '') {
                                $bccs = explode(";", $Request->bcc);
                                foreach ($bccs as $bcc) {
                                    $message->bcc($bcc);
                                }
                            }

                            if (!empty($PertanyaanAdminDoc)) {
                                foreach ($PertanyaanAdminDoc as $pertanyaandoc) {
                                    $message->attach(public_path('storage/' . $pertanyaandoc->path . $pertanyaandoc->img), [
                                        'as' => $pertanyaandoc->img_name
                                    ]);
                                }
                            }

                        });
                    }
                }
            }

            if ($PertanyaanAdmin->save()) {
                if ($Request->expectsJson()) {
                    return response()->json(['data' => 'Pertanyaan / Cadangan anda telah berjaya disimpan']);
                }
                return redirect()->route('pertanyaan-admin.edit', $PertanyaanAdmin->AS_ASKID)->with('success', 'Pertanyaan / Cadangan anda telah <b>BERJAYA</b> disimpan');
            }
        } else {
            $PertanyaanAdmin->AS_ASKSTS = 3;
            $PertanyaanAdmin->AS_RCVBY = Auth::user()->id;
            $PertanyaanAdmin->AS_COMPLETEBY = Auth::user()->id;
            $PertanyaanAdmin->AS_COMPLETEDT = Carbon::now();
            $PertanyaanAdmin->AS_ANSWER = $Request->AS_ANSWER;
            $PertanyaanAdmin->AS_CMPLCAT = $Request->AS_CMPLCAT;
            $PertanyaanAdmin->AS_CMPLCD = $Request->AS_CMPLCD;
            if ($PertanyaanAdmin->save()) {
                PertanyaanDetailAdmin::where(['AD_ASKID' => $PertanyaanAdmin->AS_ASKID, 'AD_CURSTS' => '1'])->update(['AD_CURSTS' => '0']);
                $mAskDetail = new PertanyaanDetailAdmin();
                $mAskDetail->AD_ASKID = $PertanyaanAdmin->AS_ASKID;
                $mAskDetail->AD_ASKSTS = 3;
                $mAskDetail->AD_CURSTS = 1;
                if ($mAskDetail->save()) {
                    if ($Request->expectsJson()) {
                        return response()->json(['data' => 'Pertanyaan / Cadangan telah berjaya dihantar']);
                    }
                    return redirect()->route('pertanyaan-admin.index')->with('success', 'Pertanyaan / Cadangan telah <b>BERJAYA</b> dihantar');
                }
            }
        }
    }

    public function ShowSummary($ASKID)
    {
        $model = PertanyaanAdmin::where(['AS_ASKID' => $ASKID])->first();
        $trnsksi = PertanyaanDetailAdmin::where(['AD_ASKID' => $ASKID])->get();
//        $img = \App\Pertanyaan\PertanyaanPublicDoc::where(['askid' => $ASKID])->get();
        $img = \App\Pertanyaan\PertanyaanPublicDoc::where(['askid' => $ASKID])
            ->where(function ($query) {
                $query->whereNull('img_cat')
                    ->orWhere('img_cat', '=', '1');
            })
            ->get();
        $imganswer = \App\Pertanyaan\PertanyaanPublicDoc::where(['askid' => $ASKID])
            ->where('img_cat', '=', '2')->get();
        $emails = PertanyaanEmail::where(['AE_ASKID' => $ASKID])->get();
        return view('pertanyaan.pertanyaan-admin.show_summary_modal', compact('model', 'img', 'trnsksi', 'emails', 'imganswer'));
    }

    public function PrintSummary($ASKID)
    {
        $model = PertanyaanAdmin::where(['AS_ASKID' => $ASKID])->first();
        $trnsksi = PertanyaanDetailAdmin::where(['AD_ASKID' => $ASKID])->get();
//        $img = \App\Pertanyaan\PertanyaanPublicDoc::where(['askid' => $ASKID])->get();
        $img = \App\Pertanyaan\PertanyaanPublicDoc::where(['askid' => $ASKID])
            ->where(function ($query) {
                $query->whereNull('img_cat')
                    ->orWhere('img_cat', '=', '1');
            })
            ->get();
        $imganswer = \App\Pertanyaan\PertanyaanPublicDoc::where(['askid' => $ASKID])
            ->where('img_cat', '=', '2')->get();
        $emails = PertanyaanEmail::where(['AE_ASKID' => $ASKID])->get();
        $GeneratePdfSummary = PDF::loadView('pertanyaan.pertanyaan-admin.show_summary_modal', compact('model', 'trnsksi', 'img', 'emails', 'imganswer'), [], ['default_font_size' => 7]);
        $GeneratePdfSummary->stream();
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

    public function editadmin($id)
    {
        $model = PertanyaanAdmin::find($id);
        return view('pertanyaan.pertanyaan-admin.editadmin', compact('model'));
    }

    public function updateadmin(Request $request, $id)
    {
        $this->validate($request, [
//            'AS_DOCNO' => 'required',
            'AS_MOBILENO' => 'required',
//            'AS_ADDR' => 'required',
            'AS_NAME' => 'required',
//            'AS_STATECD' => 'required',
//            'AS_DISTCD' => 'required',
//            'AS_COUNTRYCD' => 'required_if:AS_NATCD,0',
            'AS_SUMMARY' => 'required',
            'AS_RCVTYP' => 'required'
        ],
            [
//            'AS_DOCNO.required' => 'Ruangan No. Kad Pengenalan/Pasport diperlukan.',
                'AS_MOBILENO.required' => 'Ruangan No. Telefon (Bimbit) diperlukan.',
//            'AS_ADDR.required' => 'Ruangan Alamat diperlukan.',
                'AS_NAME.required' => 'Ruangan Nama diperlukan.',
//            'AS_STATECD.required' => 'Ruangan Negeri diperlukan.',
//            'AS_DISTCD.required' => 'Ruangan Daerah diperlukan.',
//            'AS_COUNTRYCD.required_if' => 'Ruangan Negara Asal diperlukan.',
                'AS_SUMMARY.required' => 'Ruangan Keterangan Pertanyaan/Cadangan diperlukan.',
                'AS_RCVTYP.required' => 'Ruangan Cara Penerimaan diperlukan.'
            ]);
        $model = PertanyaanAdmin::find($id);
        $model->fill($request->all());
        if ($model->save()) {
            if ($request->expectsJson()) {
                return response()->json(['data' => $model->id], 200);
            }
            return redirect()->route('pertanyaan-admin.attachment', ['id' => $model->id]);
        }
    }

    public function attachment($id)
    {
        $model = PertanyaanAdmin::find($id);
        $countDoc = DB::table('ask_doc')
            ->where('askid', $id)
            ->count('askid');
        return view('pertanyaan.pertanyaan-admin.attachment', compact('model', 'countDoc'));
    }

    public function preview($id)
    {
        $model = PertanyaanAdmin::find($id);
        $mPertanyaanAdminDoc = PertanyaanAdminDoc::where(['askid' => $id])->get();
        return view('pertanyaan.pertanyaan-admin.preview', compact('model', 'mPertanyaanAdminDoc'));
    }

    public function submit(Request $request, $id)
    {
        // adhoc. kill user from ezadu.
        if(in_array($request->AS_USERID, [12121])) {
            if ($request->expectsJson()) {
                $user = Auth::guard('api')->user();

                if ($user) {
                    $user->api_token = null;
                    $user->save();
                }

                return response()->json(['data' => 'User logged out.'], 200);
            }
        }
        $model = PertanyaanAdmin::find($id);
        if ($model->AS_ASKSTS == '1') {
//            $model->AS_ASKID = PertanyaanPublic::getNoPertanyaan();
            $model->AS_ASKID = RunnerRepository::generateAppNumber('P', date('y'));
            $model->AS_ASKSTS = '2'; // Diterima
            $model->AS_RCVDT = Carbon::now();
            if ($model->save()) {
                PertanyaanAdminDoc::where('askid', $id)->update(['askid' => $model->AS_ASKID]);
                PertanyaanDetailAdmin::where(['AD_ASKID' => $id, 'AD_CURSTS' => '1'])->update(['AD_CURSTS' => '0']);
                PertanyaanDetailAdmin::where('AD_ASKID', $id)->update(['AD_ASKID' => $model->AS_ASKID]);
                $mPertanyaanDetailAdmin = new PertanyaanDetailAdmin();
                $mPertanyaanDetailAdmin->AD_ASKID = $model->AS_ASKID;
                $mPertanyaanDetailAdmin->AD_TYPE = 'D';
                $mPertanyaanDetailAdmin->AD_ASKSTS = '2';
                $mPertanyaanDetailAdmin->AD_CURSTS = '1';
                if ($mPertanyaanDetailAdmin->save()) {
                    if ($model->AS_EMAIL != '') {
                        Mail::to($model->AS_EMAIL)->send(new PertanyaanTerimaAdmin($model)); // Send biasa
                    }
                    if ($request->expectsJson()) {
                        return response()->json(['data' => $model->AS_ASKID, 'message' => 'Pertanyaan / Cadangan anda telah berjaya diterima']);
                    }
                    $request->session()->flash(
                        'success', 'Pertanyaan / Cadangan anda telah <b>berjaya</b> diterima.'
                        . '<br />No. Pertanyaan / Cadangan: ' . $model->AS_ASKID
                    );
//                    return redirect()->route('pertanyaan-admin.index');
                }
            }
        } else {
            $request->session()->flash(
                'warning', 'Harap maaf, Pertanyaan / Cadangan anda telah <b>dihantar</b>.'
                . '<br />No. Pertanyaan / Cadangan: ' . $model->AS_ASKID
            );
//            return redirect()->route('pertanyaan-admin.index');
        }
        return redirect()->route('pertanyaan-admin.index');
    }
}
