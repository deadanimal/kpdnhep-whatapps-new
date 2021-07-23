<?php

namespace App\Http\Controllers\Pertanyaan;

use App\Http\Controllers\Controller;
use App\Mail\PertanyaanTerimaPublic;
use App\Pertanyaan\PertanyaanDetailPublic;
use App\Pertanyaan\PertanyaanPublic;
use App\Pertanyaan\PertanyaanPublicDoc;
use App\Rating;
use App\Repositories\RunnerRepository;
use Carbon\Carbon;
use DB;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Mail;
use PDF;
use Yajra\DataTables\Facades\DataTables;

class PertanyaanPublicController extends Controller
{
    public function __construct()
    {
        $this->middleware(['locale', 'auth']);
    }

    public function PrintSuccess($ASKSEID)
    {
        $model = PertanyaanPublic::where(['AS_ASKID' => $ASKSEID])->first();
        $branch = DB::table('sys_brn')->where('BR_BRNCD', '=', $model->AS_BRNCD)->first();
        $state = DB::table('sys_ref')->where([['cat', '=', '17'], ['code', '=', $branch->BR_STATECD]])->first();
        $pdf = PDF::loadView('pertanyaan.pertanyaan-public.printsuccess', compact('ASKSEID', 'model', 'branch', 'state'), [], ['default_font_size' => 9, 'title' => 'eAduanV2']);
        return $pdf->stream('document.pdf');
    }

    public function GetDatatable(Request $request)
    {
        $Pertanyaan = PertanyaanPublic::where(['AS_CREBY' => Auth::user()->id])->orderBy('AS_CREDT', 'DESC');

        if ($request->mobile) {
            return response()->json(['data' => $Pertanyaan->limit($request->count)->get()->toArray()]);
        }

        $Datatable = DataTables::of($Pertanyaan)
            ->addIndexColumn()
            ->editColumn('AS_ASKID', function (PertanyaanPublic $PertanyaanPublic) {
                return view('pertanyaan.pertanyaan-public.show_summary_link', compact('PertanyaanPublic'))->render();
            })
            ->editColumn('AS_SUMMARY', function (PertanyaanPublic $PertanyaanPublic) {
                if ($PertanyaanPublic->AS_SUMMARY != '')
                    return implode(' ', array_slice(explode(' ', ucfirst($PertanyaanPublic->AS_SUMMARY)), 0, 7)) . '...';
                else
                    return '';
            })
            ->editColumn('AS_ASKSTS', function (PertanyaanPublic $PertanyaanPublic) {
                if (Auth::user()->lang == 'ms') {
                    return $PertanyaanPublic->ShowStatus->descr;
                } elseif (Auth::user()->lang == 'en') {
                    return $PertanyaanPublic->ShowStatus->descr_en;
                } else {
                    return $PertanyaanPublic->ShowStatus->descr_en;
                }
            })
            ->editColumn('AS_RCVDT', function (PertanyaanPublic $PertanyaanPublic) {
                if ($PertanyaanPublic->AS_RCVDT != '')
                    return Carbon::parse($PertanyaanPublic->AS_RCVDT)->format('d-m-Y h:i A');
                else
                    return '';
            })
            ->addColumn('action', function (PertanyaanPublic $PertanyaanPublic) {
                return view('pertanyaan.pertanyaan-public.action_btn', compact('PertanyaanPublic'))->render();
            })
            ->rawColumns(['AS_ASKID', 'action'])
            ->filter(function ($query) use ($request) {
                if ($request->has('AS_ASKID')) {
                    $query->where('AS_ASKID', 'LIKE', "%{$request->get('AS_ASKID')}%");
                }
                if ($request->has('AS_ASKSTS')) {
                    $query->where('AS_ASKSTS', $request->get('AS_ASKSTS'));
                }
                if ($request->has('AS_SUMMARY')) {
                    $query->where('AS_SUMMARY', 'LIKE', "%{$request->get('AS_SUMMARY')}%");
                }
            });

        return $Datatable->make(true);
    }

    public function GetDatatableTransaction($ASKID)
    {
        $PertanyaanDetailPublic = PertanyaanDetailPublic::where(['AD_ASKID' => $ASKID])->orderBy('AD_CREDT', 'DESC');

        $Datatable = DataTables::of($PertanyaanDetailPublic)
            ->addIndexColumn()
            ->editColumn('AD_ASKSTS', function (PertanyaanDetailPublic $PertanyaanDetailPublic) {
                if (Auth::user()->lang == 'ms') {
                    return $PertanyaanDetailPublic->ShowStatus->descr;
                } elseif (Auth::user()->lang == 'en') {
                    return $PertanyaanDetailPublic->ShowStatus->descr_en;
                } else {
                    return $PertanyaanDetailPublic->ShowStatus->descr_en;
                }
            })
            ->editColumn('AD_CREBY', function (PertanyaanDetailPublic $PertanyaanDetailPublic) {
                if ($PertanyaanDetailPublic->AD_ASKSTS == 3) {
                    return $PertanyaanDetailPublic->CreBy->name;
                } else {
                    return '';
                }
            })
            ->editColumn('AD_CURSTS', function (PertanyaanDetailPublic $PertanyaanDetailPublic) {
                if (Auth::user()->lang == 'ms') {
                    return $PertanyaanDetailPublic->ShowCurrentStatus->descr;
                } elseif (Auth::user()->lang == 'en') {
                    return $PertanyaanDetailPublic->ShowCurrentStatus->descr_en;
                } else {
                    return $PertanyaanDetailPublic->ShowCurrentStatus->descr_en;
                }
            })
            ->editColumn('AD_CREDT', function (PertanyaanDetailPublic $PertanyaanDetailPublic) {
                return Carbon::parse($PertanyaanDetailPublic->AD_CREDT)->format('d-m-Y h:i A');
            });

        return $Datatable->make(true);
    }

    public function create()
    {
        return view('pertanyaan.pertanyaan-public.create');
    }

    public function store(Request $request)
    {
        $this->validate($request, [
            'AS_SUMMARY' => 'required',
        ]);

        $model = new PertanyaanPublic();
//        $model->AS_ASKID = PertanyaanPublic::getNoPertanyaan();
        $model->AS_DEPTCD = 'UKK'; // Unit Koperasi Koperat
        $model->AS_BRNCD = 'WHQR2'; // Unit Koperasi Koperat

        if ($request->expectsJson()) {
            $model->AS_RCVTYP = 'S29';
        } else {
            $model->AS_RCVTYP = 'S23';
        }

        $model->AS_USERID = Auth::user()->id;
        $model->AS_NAME = Auth::user()->name;
        $model->AS_DOCNO = Auth::user()->icnew;
        $model->AS_SEXCD = Auth::user()->gender;
        $model->AS_AGE = Auth::user()->age;
        $model->AS_ADDR = Auth::user()->address;
        $model->AS_POSCD = Auth::user()->postcode;
        $model->AS_DISTCD = Auth::user()->distrinct_cd;
        $model->AS_STATECD = Auth::user()->state_cd;
        $model->AS_NATCD = Auth::user()->citizen;
        $model->AS_COUNTRYCD = Auth::user()->ctry_cd;
        $model->AS_EMAIL = Auth::user()->email;
        $model->AS_MOBILENO = Auth::user()->mobile_no;
        $model->AS_ASKSTS = '1'; // Deraf

//        if($request->btnSimpan) {
//            $model->AS_ASKSTS = '1'; // Deraf
//        }else{
//            $model->AS_ASKSTS = '2'; // Diterima
//            $model->AS_RCVDT = Carbon::now();
//        }

        // check if title is same in the same day then just update it.
        $inquiry = PertanyaanPublic::where('AS_USERID', Auth::user()->id)
            ->where('AS_SUMMARY', $request->AS_SUMMARY)
            ->whereDate('AS_CREDT', date('Y-m-d'))
            ->first();

        if($inquiry){
            if ($request->expectsJson()) {
                return response()->json(['data' => $inquiry->id], 200);
            }

            return redirect()->route('pertanyaan.attachment', ['id' => $inquiry->id]);
        }

        $model->fill($request->all());
        if ($model->save()) {
            $mAskDetail = new PertanyaanDetailPublic();
            $mAskDetail->AD_ASKID = $model->id;
            $mAskDetail->AD_ASKSTS = $model->AS_ASKSTS;
            $mAskDetail->AD_CURSTS = 1;
            if ($mAskDetail->save()) {
//                if($request->btnSimpan) {
//                    return redirect()->route('pertanyaan-public.edit',$model->AS_ASKID)->with('success', 'Pertanyaan / Cadangan anda telah BERJAYA disimpan');
//                }else{
//                return redirect()->route('dashboard',['#enquery'])->with('success', 'Pertanyaan / Cadangan anda telah BERJAYA  dihantar');
//                return redirect()->route('pertanyaan.attachment',['id'=>$model->id])->with('success', 'Pertanyaan / Cadangan anda telah BERJAYA  disimpan');
                if ($request->expectsJson()) {
                    return response()->json(['data' => $model->id], 200);
                }
                return redirect()->route('pertanyaan.attachment', ['id' => $model->id]);
//                }
            }
        }
    }

    public function Attachment($id)
    {
        $model = PertanyaanPublic::find($id);
        $CountDoc = DB::table('ask_doc')
            ->where('askid', $id)
            ->count('askid');
        return view('pertanyaan.pertanyaan-public.attachment', compact('model', 'CountDoc'));
    }

    public function edit(PertanyaanPublic $PertanyaanPublic)
    {
        return view('pertanyaan.pertanyaan-public.edit', compact('PertanyaanPublic'));
    }

//    public function update(Request $Request, PertanyaanPublic $PertanyaanPublic)
    public function update(Request $Request, $id)
    {
        $PertanyaanPublic = PertanyaanPublic::find($id);
//        if($Request->btnSimpan) {
        $PertanyaanPublic->AS_SUMMARY = $Request->AS_SUMMARY;
        if ($PertanyaanPublic->save()) {
            if ($Request->expectsJson()) {
                return response()->json(['data' => $PertanyaanPublic->id]);
            }
            return redirect()->route('pertanyaan.attachment', ['id' => $id]);//->with('success', 'Pertanyaan / Cadangan anda telah BERJAYA disimpan');
        }
//        }else{
//            $PertanyaanPublic->AS_SUMMARY = $Request->AS_SUMMARY;
//            $PertanyaanPublic->AS_ASKSTS = 2;
//            $PertanyaanPublic->AS_RCVDT = Carbon::now();
//            if($PertanyaanPublic->save()) {
//                PertanyaanDetailPublic::where(['AD_ASKID' => $PertanyaanPublic->AS_ASKID, 'AD_CURSTS' => '1'])->update(['AD_CURSTS' => '0']);
//                $mAskDetail = new PertanyaanDetailPublic();
//                $mAskDetail->AD_ASKID = $PertanyaanPublic->AS_ASKID;
//                $mAskDetail->AD_ASKSTS = 2;
//                $mAskDetail->AD_CURSTS = 1;
//                if($mAskDetail->save()) {
//                    return redirect()->route('dashboard')->with('success', 'Pertanyaan / Cadangan anda telah BERJAYA dihantar');
//                }
//            }
//        }
    }

    public function Preview($id)
    {
        $model = PertanyaanPublic::find($id);
        $mPertanyaanPublicCaseDoc = PertanyaanPublicDoc::where(['askid' => $id])->get();
        return view('pertanyaan.pertanyaan-public.preview', compact('model', 'mPertanyaanPublicCaseDoc'));
    }

    public function Submit(Request $request, $id)
    {
        $mPertanyaanPublic = PertanyaanPublic::find($id);

        if ($mPertanyaanPublic->AS_ASKSTS == '1') {
//            $mPertanyaanPublic->AS_ASKID = PertanyaanPublic::getNoPertanyaan();
            $mPertanyaanPublic->AS_ASKID = RunnerRepository::generateAppNumber('P', date('y'));
            $mPertanyaanPublic->AS_ASKSTS = '2'; // Diterima
            $mPertanyaanPublic->AS_RCVDT = Carbon::now();
            if ($mPertanyaanPublic->save()) {
                PertanyaanPublicDoc::where('askid', $id)->update(['askid' => $mPertanyaanPublic->AS_ASKID]);
                PertanyaanDetailPublic::where(['AD_ASKID' => $id, 'AD_CURSTS' => '1'])->update(['AD_CURSTS' => '0']);
                PertanyaanDetailPublic::where('AD_ASKID', $id)->update(['AD_ASKID' => $mPertanyaanPublic->AS_ASKID]);

                $mPertanyaanDetailPublic = new PertanyaanDetailPublic();
                $mPertanyaanDetailPublic->AD_ASKID = $mPertanyaanPublic->AS_ASKID;
                $mPertanyaanDetailPublic->AD_TYPE = 'D';
                $mPertanyaanDetailPublic->AD_ASKSTS = '2';
                $mPertanyaanDetailPublic->AD_CURSTS = '1';
                if ($mPertanyaanDetailPublic->save()) {
                    $mRating = new Rating();
                    $mRating->askid = $mPertanyaanPublic->AS_ASKID;
                    $mRating->rate = $request->rating;
                    if ($mRating->save()) {
                        if ($request->expectsJson()) {
                            return response()->json(['data' => $mPertanyaanPublic->AS_ASKID]);
                        }
                        if ($request->user()->email != '') {
                            Mail::to($request->user())->send(new PertanyaanTerimaPublic($mPertanyaanPublic)); // Send biasa
                        }
                        return redirect()->route('pertanyaan.success', $mPertanyaanPublic->AS_ASKID);
                    }
                }
            }
        } else {
            if ($request->expectsJson()) {
                return response()->json(['data' => $mPertanyaanPublic->AS_ASKID]);
            }
            return redirect()->route('pertanyaan.success', $mPertanyaanPublic->AS_ASKID);
        }
    }

    public function show($AS_ASKID)
    {
//          $model = PertanyaanPublic::where(['AS_ASKID' => $AS_ASKID])->first();
//        $mPertanyaanDoc = PertanyaanPublicDoc::where(['askid' => $AS_ASKID])->get();
//        return view('pertanyaan.pertanyaan-public.check', compact('model','mPertanyaanDoc'));
    }

    public function Check($AS_ASKID)
    {
        $tanya = PertanyaanPublic::where(['AS_ASKID' => $AS_ASKID])->first();
        $mPertanyaanDoc = PertanyaanPublicDoc::where(['askid' => $AS_ASKID])->get();
        return view('pertanyaan.pertanyaan-public.check', compact('tanya', 'mPertanyaanDoc'));
    }

    public function success($id)
    {
        return view('pertanyaan.pertanyaan-public.success', compact('id'));
    }

    public function destroy(Pertanyaan $pertanyaan)
    {
        //
    }

    public function ShowSummary($ASKID)
    {
        $model = PertanyaanPublic::where(['AS_ASKID' => $ASKID])->first();
        $trnsksi = PertanyaanDetailPublic::where(['AD_ASKID' => $ASKID])->get();
        return view('pertanyaan.pertanyaan-public.show_summary_modal', compact('model', 'trnsksi'));
    }

    public function PrintSummary($ASKID)
    {
        $model = PertanyaanPublic::where(['AS_ASKID' => $ASKID])->first();
        $trnsksi = PertanyaanDetailPublic::where(['AD_ASKID' => $ASKID])->get();
//        $img = PindahAduanDoc::where(['CC_CASEID' => $CASEID])->get();
        $GeneratePdfSummary = PDF::loadView('pertanyaan.pertanyaan-public.show_summary_pdf', compact('model', 'trnsksi'), [], ['default_font_size' => 7]);
        $GeneratePdfSummary->stream();
    }
}
