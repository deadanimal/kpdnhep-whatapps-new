<?php

namespace App\Http\Controllers\Laporan;

use App\Aduan\AdminCase;
use App\Aduan\AdminCaseDetail;
use App\Aduan\Carian;
use App\Branch;
use App\Http\Controllers\Controller;
use App\Laporan\ReportLainlain;
use App\Pertanyaan\PertanyaanAdmin;
use App\Rating;
use App\Ref;
use App\User;
use Box\Spout\Common\Type;
use Box\Spout\Writer\WriterFactory;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Input;
use Illuminate\Support\Facades\Storage;
use Maatwebsite\Excel\Facades\Excel;
use PDF;
use Yajra\DataTables\Facades\DataTables;

class LaporanLainLainController extends Controller
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

    public function kategori(Request $request)
    {
        $data = $request->all();
        $mKat = DB::table('sys_ref')
            ->where(['cat' => 244])
            ->get();
        $mState = DB::table('sys_ref')
            ->where('cat', '1214')
            ->get();
        $i = 1;
        $CA_RCVDT_dri = isset($data['CA_RCVDT_dri']) 
            ? Carbon::createFromFormat('d-m-Y', $data['CA_RCVDT_dri'])->startOfDay() 
            : Carbon::now()->startOfDay();
        $CA_RCVDT_lst = isset($data['CA_RCVDT_lst']) 
            ? Carbon::createFromFormat('d-m-Y', $data['CA_RCVDT_lst'])->endOfDay() 
            : Carbon::now()->endOfDay();
        $depart = '';
        $cari = '';

        if ($request->has('cari') || $request->get('excel') == '1' || $request->has('pdf')) {
            $cari = true;
            $query = DB::table('case_info')
                ->join('sys_ref', 'sys_ref.code', '=', 'case_info.CA_CMPLCAT')
                ->join('sys_brn', 'sys_brn.BR_BRNCD', '=', 'case_info.CA_BRNCD');
            $query->select(DB::raw("
                    sys_ref.code,sys_ref.descr,COUNT(sys_brn.BR_STATECD) AS Bilangan,
                    SUM(CASE WHEN BR_STATECD='01' THEN 1 ELSE 0 END) AS countstate1,
                    SUM(CASE WHEN BR_STATECD='02' THEN 1 ELSE 0 END) AS countstate2,
                    SUM(CASE WHEN BR_STATECD='03' THEN 1 ELSE 0 END) AS countstate3,
                    SUM(CASE WHEN BR_STATECD='04' THEN 1 ELSE 0 END) AS countstate4,
                    SUM(CASE WHEN BR_STATECD='05' THEN 1 ELSE 0 END) AS countstate5,
                    SUM(CASE WHEN BR_STATECD='06' THEN 1 ELSE 0 END) AS countstate6,
                    SUM(CASE WHEN BR_STATECD='07' THEN 1 ELSE 0 END) AS countstate7,
                    SUM(CASE WHEN BR_STATECD='08' THEN 1 ELSE 0 END) AS countstate8,
                    SUM(CASE WHEN BR_STATECD='09' THEN 1 ELSE 0 END) AS countstate9,
                    SUM(CASE WHEN BR_STATECD='10' THEN 1 ELSE 0 END) AS countstate10,
                    SUM(CASE WHEN BR_STATECD='11' THEN 1 ELSE 0 END) AS countstate11,
                    SUM(CASE WHEN BR_STATECD='12' THEN 1 ELSE 0 END) AS countstate12,
                    SUM(CASE WHEN BR_STATECD='13' THEN 1 ELSE 0 END) AS countstate13,
                    SUM(CASE WHEN BR_STATECD='14' THEN 1 ELSE 0 END) AS countstate14,
                    SUM(CASE WHEN BR_STATECD='15' THEN 1 ELSE 0 END) AS countstate15,
                    SUM(CASE WHEN BR_STATECD='16' THEN 1 ELSE 0 END) AS countstate16,
                    COUNT(CA_CASEID) as countcaseid
                    "));
            $query->whereBetween('CA_RCVDT', [$CA_RCVDT_dri, $CA_RCVDT_lst]);

            if ($request->get('CA_DEPTCD') != '0') {
                $query->where('case_info.CA_DEPTCD', $request->CA_DEPTCD);
            } else {
            }

            $query->where('sys_ref.cat', '244');
            $query->whereNotIn('case_info.CA_INVSTS', [10]);
            $query->groupBy('sys_ref.code', 'sys_ref.descr', 'sys_ref.sort');
            $query->orderBy('countcaseid', 'DESC');
            $query = $query->get();
            $datas = $query;


            //Jumlah Berdasarkan Status Mengikut Negeri
            $negeri = ['01','02','03','04','05','06','07','08','09','10','11','12','13','14','15','16'];
            $status = ['BR_STATECD'=>0,'SELESAI'=>0,'DALAMSIASATAN'=>0,'TUTUP'=>0,'total'=>0];
            $ipep = ['IP'=>0,'EP'=>0];

            $base = [];
            foreach($negeri as $neg) {
                $base[$neg] = $status;
            }
            $baseipep = [];
            foreach($negeri as $neg) {
                $baseipep[$neg] = $ipep;
            }

            $q = DB::table('case_info')
                ->select('sys_brn.BR_STATECD AS BR_STATECD',
                    DB::raw("SUM(CASE WHEN CA_INVSTS IN (3,12) THEN 1 ELSE 0 END) AS SELESAI,
                    SUM(CASE WHEN CA_CASESTS = 2 AND CA_INVSTS = 2 THEN 1 ELSE 0 END) AS DALAMSIASATAN, 
                    SUM(CASE WHEN CA_CASESTS = 2 AND CA_INVSTS IN (9,11) THEN 1 ELSE 0 END) AS TUTUP,
                    SUM(CASE WHEN CA_CASESTS = 2 AND CA_INVSTS IN (2,3,9,11,12) THEN 1 ELSE 0 END) AS total"
                    )
                )
                ->join('sys_brn', 'sys_brn.BR_BRNCD', '=', 'case_info.CA_BRNCD')
                ->whereBetween('case_info.CA_RCVDT', [$CA_RCVDT_dri, $CA_RCVDT_lst])
                ->where('CA_CMPLCAT','!=','')
                ->whereNotNull('CA_CMPLCAT')
                ->groupBy('BR_STATECD')
                ->orderBy('BR_STATECD');
            $data_raw = $q->get();

            $raw = [];
            foreach($data_raw as $key => $value) {
                $raw[$value->BR_STATECD] = json_decode(json_encode($value), True);
            }
            $qstatus = array_replace_recursive($base,$raw);

            //Jumlah Kes IP & EP
            $qipep = DB::table('case_info')
                ->select('sys_brn.BR_STATECD AS BR_STATECD',
                    DB::raw("SUM(CASE WHEN (CT_IPNO <> '' OR CT_IPNO IS NOT NULL) AND (CT_EPNO <> '' OR CT_EPNO IS NOT NULL) THEN 1 ELSE 0 END) AS IP,
                    SUM(CASE WHEN (CT_IPNO = '' OR CT_IPNO IS NULL) AND (CT_EPNO <> '' OR CT_EPNO IS NOT NULL) THEN 1 ELSE 0 END) AS EP"
                    )
                )
                ->join('sys_brn', 'sys_brn.BR_BRNCD', '=', 'case_info.CA_BRNCD')
                ->join('case_act', 'case_act.CT_CASEID', '=', 'case_info.CA_CASEID')
                ->whereBetween('case_info.CA_RCVDT', [$CA_RCVDT_dri, $CA_RCVDT_lst])
                ->where('CA_CMPLCAT','!=','')
                ->whereNotNull('CA_CMPLCAT')
                ->groupBy('BR_STATECD')
                ->orderBy('BR_STATECD');
            $dataipep_raw = $qipep->get();

            $rawipep = [];
            foreach($dataipep_raw as $key => $value) {
                $rawipep[$value->BR_STATECD] = json_decode(json_encode($value), True);
            }
            $qstatusipep = array_replace_recursive($baseipep,$rawipep);

        }

        if ($request->has('cari')) {
            return view('laporan.laporanlainlain.kategori',
                compact(
                    'query','datas', 'cari', 'i', 'CA_RCVDT_dri', 'CA_RCVDT_lst', '',
                    'depart', 'mState', 'mKat', 'cari', 'request', 'qstatus', 'qstatusipep'
                )
            );
        } elseif ($request->has('excel')) {
            return view('laporan.laporanlainlain.kategori_excel', compact('query', 'datas', 'cari', 'i', 'CA_RCVDT_dri', 'CA_RCVDT_lst', '', 'depart', 'mState', 'mKat', 'cari', 'request'));
        } elseif ($request->has('pdf')) {
            $pdf = PDF::loadView('laporan.laporanlainlain.kategori_pdf', compact('query', 'datas', 'cari', 'i', 'CA_RCVDT_dri', 'CA_RCVDT_lst', '', 'depart', 'mState', 'mKat', 'cari', 'request'), [], ['default_font_size' => 7, 'title' => date('Ymd_His')]);
            return $pdf->stream('Kategori_' . date("Ymd_His") . '.pdf');
        } else {
            return view('laporan.laporanlainlain.kategori', compact('query', 'datas', 'cari', 'i', 'CA_RCVDT_dri', 'CA_RCVDT_lst', '', 'depart', 'mState', 'mKat', 'cari', 'request'));
        }
    }

    public function kategori1(Request $request, $CA_RCVDT_dri, $CA_RCVDT_lst, $dept, $cmplcat, $StateCd)
    {

        $query = Carian::select('case_info.*')
            ->join('sys_ref', 'sys_ref.code', '=', 'case_info.CA_CMPLCAT')
            ->join('sys_brn', 'sys_brn.BR_BRNCD', '=', 'case_info.CA_BRNCD');

        $query->whereDate('case_info.CA_RCVDT', '>=', date('Y-m-d ', strtotime($CA_RCVDT_dri)));
        $query->whereDate('case_info.CA_RCVDT', '<=', date('Y-m-d', strtotime($CA_RCVDT_lst)));
        $query->whereNotIn('case_info.CA_INVSTS', [10]);
        if ($dept != '0') {
            $titledept = Ref::GetDescr('315', $dept);
            $query->where('case_info.CA_DEPTCD', $dept);
        } else {
            $titledept = 'SEMUA BAHAGIAN';
        }
        if ($StateCd != '0') {
            $titlestate = 'NEGERI ' . Ref::GetDescr('17', $StateCd);
            $query->where('sys_brn.BR_STATECD', $StateCd);
        } else {
            $titlestate = 'SEMUA NEGERI';
        }
        if ($cmplcat != '0') {
            $titlecmplcat = 'KATEGORI ' . Ref::GetDescr('244', $cmplcat);
            $query->where('case_info.CA_CMPLCAT', $cmplcat);
        } else {
            $titlecmplcat = 'SEMUA KATEGORI';
        }
        $query = $query->get();
        if ($request->get('excel') == '1') {
            return view('laporan.laporanlainlain.kategori1_excel', compact('query', 'CA_RCVDT_dri', 'CA_RCVDT_lst', 'titledept', 'titlestate', 'titlecmplcat', 'dept', 'request'));
        } elseif ($request->get('pdf') == '1') {
            $pdf = PDF::loadView('laporan.laporanlainlain.kategori1_pdf', compact('query', 'CA_RCVDT_dri', 'CA_RCVDT_lst', 'titledept', 'titlestate', 'titlecmplcat', 'dept', 'request'), [], ['default_font_size' => 7, 'title' => date('Ymd_His')]);
            return $pdf->download('Laporanlainlain_' . date("Ymd_His") . '.pdf');
        } else {
            return view('laporan.laporanlainlain.kategori1', compact('query', 'CA_RCVDT_dri', 'CA_RCVDT_lst', 'titledept', 'titlestate', 'titlecmplcat', 'dept', 'request'));
        }
    }

    /**
     * Laporan Status Aduan
     * @param Request $request
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function negeristatus(Request $request)
    {
        $data = $request->all(); // put all request into single array
        $is_search = count($data) > 0 ? true : false; // iff we have some request from user then true else false | replace $cari

//        dump($data);
//        $data['CA_RCVDT_start'] = '01-01-2017';
//        $data['CA_RCVDT_end'] = '28-02-2017';

        // initialize data
        $ds = isset($data['ds']) ? Carbon::createFromFormat('d-m-Y', $data['ds'])->startOfDay() : Carbon::now()->startOfDay();
        $de = isset($data['de']) ? Carbon::createFromFormat('d-m-Y', $data['de'])->endOfDay() : Carbon::now()->endOfDay();
        $st = isset($data['st']) ? $data['st'] : []; // state
        $dp = isset($data['dp']) ? $data['dp'] : ''; // department
        $br = isset($data['br']) ? $data['br'] : 0; // branch
        $cs = isset($data['cs']) ? $data['cs'] : []; // case status
        $gen = isset($data['gen']) ? $data['gen'] : 'w'; // generate
        $uri_gen = '/laporanlainlain/laporan_negeri_status';
        $uri_dd = '/laporanlainlain/laporan_negeri_status1';
        if ($gen == 'w') {
            $uri = '?ds=' . $ds->format('d-m-Y') . '&de=' . $de->format('d-m-Y') . '&dp=' . $dp . '&br=' . $br . '&';
            foreach ($st as $datum) {
                $uri .= 'st%5B%5D=' . $datum . '&';
            }
            foreach ($cs as $datum) {
                $uri .= 'cs%5B%5D=' . $datum . '&';
            }

            $uri_gen .= $uri;
            $uri_dd .= $uri;
        }

        // data reference
        $state_list = DB::table('sys_ref')->where('cat', '17')->pluck('descr', 'code');
        $case_status_list = DB::table('sys_ref')->where('cat', '292')->where('code', '!=', '10')->pluck('descr', 'code');
        $department_list = ReportLainlain::GetRef('315', 'semua');
        $branch_list = $br == 1 ? DB::table('sys_brn')->pluck('BR_BRNNM', 'BR_BRNCD') : [];

        // prepare generate data
        $status = [
            'TERIMA' => in_array(1, $cs) ? true : false,
            'DALAMSIASATAN' => in_array(2, $cs) ? true : false,
            'SELESAI' => in_array(3, $cs) ? true : false,
            'AGENSILAIN' => in_array(4, $cs) ? true : false,
            'TRIBUNAL' => in_array(5, $cs) ? true : false,
            'PERTANYAAN' => in_array(6, $cs) ? true : false,
            'MKLUMATXLENGKAP' => in_array(7, $cs) ? true : false,
            'LUARBIDANG' => in_array(8, $cs) ? true : false,
            'TUTUP' => in_array(9, $cs) ? true : false,
        ];

        $data_final['total'] = $data_template = [
            'SELESAI' => 0, 'SELESAI_pct' => 0,
            'TERIMA' => 0, 'TERIMA_pct' => 0,
            'DALAMSIASATAN' => 0, 'DALAMSIASATAN_pct' => 0,
            'TUTUP' => 0, 'TUTUP_pct' => 0,
            'AGENSILAIN' => 0, 'AGENSILAIN_pct' => 0,
            'TRIBUNAL' => 0, 'TRIBUNAL_pct' => 0,
            'PERTANYAAN' => 0, 'PERTANYAAN_pct' => 0,
            'MKLUMATXLENGKAP' => 0, 'MKLUMATXLENGKAP_pct' => 0,
            'LUARBIDANG' => 0, 'LUARBIDANG_pct' => 0,
            'total' => 0, 'branch' => []
        ];

        foreach ($st as $datum) {
            $data_final[$datum] = $data_template;
        }

        if ($is_search) {
            // query data
            $q = DB::table('case_info')
                ->select('sys_brn.BR_STATECD AS BR_STATECD',
                    DB::raw("SUM(CASE WHEN CA_INVSTS IN (3,12) THEN 1 ELSE 0 END) AS SELESAI,
                    SUM(CASE WHEN CA_CASESTS = 1 THEN 1 ELSE 0 END) AS TERIMA, 
                    SUM(CASE WHEN CA_CASESTS = 2 AND CA_INVSTS = 2 THEN 1 ELSE 0 END) AS DALAMSIASATAN, 
                    SUM(CASE WHEN CA_CASESTS = 2 AND CA_INVSTS IN (9,11) THEN 1 ELSE 0 END) AS TUTUP, 
                    SUM(CASE WHEN CA_CASESTS = 2 AND CA_INVSTS = 4 THEN 1 ELSE 0 END) AS AGENSILAIN, 
                    SUM(CASE WHEN CA_CASESTS = 2 AND CA_INVSTS = 5 THEN 1 ELSE 0 END) AS TRIBUNAL,
                    SUM(CASE WHEN CA_CASESTS = 2 AND CA_INVSTS = 6 THEN 1 ELSE 0 END) AS PERTANYAAN, 
                    SUM(CASE WHEN CA_CASESTS = 2 AND CA_INVSTS = 7 THEN 1 ELSE 0 END) AS MKLUMATXLENGKAP, 
                    SUM(CASE WHEN CA_CASESTS = 2 AND CA_INVSTS = 8 THEN 1 ELSE 0 END) AS LUARBIDANG, 
                    COUNT(CA_CASEID)AS total "
                    )
                )
                ->join('sys_brn', 'sys_brn.BR_BRNCD', '=', 'case_info.CA_BRNCD')
                ->whereBetween('case_info.CA_RCVDT', [$ds, $de])
                ->whereNotIn('CA_INVSTS', [10])
                ->where('CA_CMPLCAT','!=','')
                ->whereNotNull('CA_CMPLCAT')
                ->groupBy('BR_STATECD');
            
            if (count($st) > 0) {
                $q = $q->whereIn('BR_STATECD', $st);
            } else { // if no data passed
                $q = $q->where('BR_STATECD', 'nodata');
            }
            
            if (!empty($dp)) {
                $q = $q->where('CA_DEPTCD', $dp);
            }

            // iff CA_BRNCD is 1 then add more select & group by
            if ($br == 1) {
                $q = $q->addSelect('CA_BRNCD')->groupBy('CA_BRNCD');
                unset($data_template['branch']);
            }

            $data_raw = $q->get();

            // populate data
            foreach ($data_raw as $raw) {
                $data_final[$raw->BR_STATECD]['total'] += $raw->total;

                foreach ($raw as $k => $v) {
                    if (in_array($k, ['SELESAI', 'TERIMA', 'DALAMSIASATAN', 'TUTUP', 'AGENSILAIN', 'TRIBUNAL', 'PERTANYAAN', 'MKLUMATXLENGKAP', 'LUARBIDANG', 'total'])) {
                        $data_final['total'][$k] += $v;
                    }
                }

                if ($br == 1) {
                    $data_final[$raw->BR_STATECD]['branch'][$raw->CA_BRNCD]['total'] = $raw->total;
                }
            }

            foreach ($data_raw as $raw) {
                $state_raw = $raw->BR_STATECD;
                foreach ($raw as $k => $v) {
                    if (!in_array($k, ['BR_STATECD', 'CA_BRNCD', 'total'])) {
                        $data_final[$state_raw][$k] += $v;
                        if (!in_array($k, ['total'])) {
                            $data_final[$state_raw][$k . '_pct'] = ($data_final['total'][$k] != 0 ? round($data_final[$state_raw][$k] / $data_final['total'][$k] * 100, 2) : 0);
                            $data_final['total'][$k . '_pct'] = round($data_final['total'][$k] / $data_final['total']['total'] * 100, 2);
                        }
                    }
                    // if have branch
                    if ($br == 1) {
                        if (!in_array($k, ['BR_STATECD', 'CA_BRNCD'])) {
                            $data_final[$state_raw]['branch'][$raw->CA_BRNCD][$k] = $v;
                            if (!in_array($k, ['total'])) {
                                $data_final[$state_raw]['branch'][$raw->CA_BRNCD][$k . '_pct'] = round($data_final[$state_raw]['branch'][$raw->CA_BRNCD][$k] / $data_final['total']['total'] * 100, 2);
                            }
                        }
                    }
                }
            }

            switch ($gen) {
                case 'e':
                    return Excel::create('Laporan Status Aduan' . date("_Ymd_His"), function ($excel) use ($is_search, $state_list, $department_list, $case_status_list, $branch_list, $ds, $de, $dp, $st, $br, $cs, $data_final, $status) {
                        $excel->sheet('Report', function ($sheet) use ($is_search, $state_list, $department_list, $case_status_list, $branch_list, $ds, $de, $dp, $st, $br, $cs, $data_final, $status) {
                            $sheet->loadView('laporan.laporanlainlain.laporan_negeri_status.excel')
                                ->with([
                                    'is_search' => $is_search,
                                    'state_list' => $state_list,
                                    'department_list' => $department_list,
                                    'case_status_list' => $case_status_list,
                                    'branch_list' => $branch_list,
                                    'ds' => $ds,
                                    'de' => $de,
                                    'dp' => $dp,
                                    'st' => $st,
                                    'br' => $br,
                                    'cs' => $cs,
                                    'data_final' => $data_final,
                                    'status' => $status,
                                ]);
                        });
                    })->export('xlsx');
                    break;
                case 'xls':
                    return view(
                        'laporan.laporanlainlain.laporan_negeri_status.excelxls', 
                        compact(
                            'is_search', 'state_list', 'department_list', 'case_status_list', 
                            'branch_list', 'ds', 'de', 'dp', 'st', 'br', 'cs', 'data_final', 'status'
                        )
                    );
                    break;
                case 'p':
                    $pdf = PDF::loadView('laporan.laporanlainlain.laporan_negeri_status.pdf',
                        compact('is_search', 'state_list', 'department_list', 'case_status_list', 'branch_list', 'ds', 'de', 'dp', 'st', 'br', 'cs', 'data_final', 'status')
                    );
                    return $pdf->stream('laporanlainlain' . date("Ymd_His") . '.pdf');
                    break;
                case 'w':
                default:
                    return view('laporan.laporanlainlain.laporan_negeri_status.index',
                        compact('is_search', 'state_list', 'department_list', 'case_status_list', 'branch_list', 'ds', 'de', 'dp', 'st', 'br', 'cs', 'data_final', 'status', 'uri_gen', 'uri_dd')
                    );
                    break;
            }
        }

        return view('laporan.laporanlainlain.laporan_negeri_status.index',
            compact('is_search', 'state_list', 'department_list', 'case_status_list', 'branch_list', 'ds', 'de', 'dp', 'st', 'br', 'cs', 'data_final', 'request', 'uri_gen')
        );
    }

    public function negeristatus1(Request $request)
    {
        $data = $request->all(); // put all request into single array
        $is_search = count($data) > 0 ? true : false; // iff we have some request from user then true else false | replace $cari

//        dump($data);
//        $data['CA_RCVDT_start'] = '01-01-2017';
//        $data['CA_RCVDT_end'] = '28-02-2017';

        // initialize data
        $ds = isset($data['ds']) ? Carbon::createFromFormat('d-m-Y', $data['ds'])->startOfDay() : Carbon::now()->startOfDay();
        $de = isset($data['de']) ? Carbon::createFromFormat('d-m-Y', $data['de'])->endOfDay() : Carbon::now()->endOfDay();
        $st = isset($data['st_datum']) ? [$data['st_datum']] : (isset($data['st']) ? $data['st'] : []); // state
        $dp = isset($data['dp']) ? $data['dp'] : ''; // department
        $br = isset($data['br_datum']) ? $data['br_datum'] : 0; // branch key
        $cs = isset($data['cs_datum']) ? [$data['cs_datum']] : (isset($data['cs']) ? $data['cs'] : []); // case
        $gen = isset($data['gen']) ? $data['gen'] : 'w'; // generate
        $uri_gen = '/laporanlainlain/laporan_negeri_status1';
        if ($gen == 'w') {
            $uri = '?ds=' . $ds->format('d-m-Y') . '&de=' . $de->format('d-m-Y') . '&dp=' . $dp . '&br=' . $br . '&';
            foreach ($st as $datum) {
                $uri .= 'st%5B%5D=' . $datum . '&';
            }
            foreach ($cs as $datum) {
                $uri .= 'cs%5B%5D=' . $datum . '&';
            }

            $uri_gen .= $uri;
        }

        // data reference
        $cmplcat_list = DB::table('sys_ref')->where('cat', 244)->pluck('descr', 'code');
        $state_list = DB::table('sys_ref')->where('cat', '17')->pluck('descr', 'code');
        $case_status_list = DB::table('sys_ref')->where('cat', '292')->pluck('descr', 'code');
        $department_list = ReportLainlain::GetRef('315', 'Semua');
        $branch_list = DB::table('sys_brn')->pluck('BR_BRNNM', 'BR_BRNCD');

        if ($is_search) {
            // query data
//            $q = DB::table('case_info')
            $q = Carian::leftJoin('sys_users', 'CA_INVBY', '=', 'sys_users.id')
                ->join('sys_brn', 'sys_brn.BR_BRNCD', '=', 'case_info.CA_BRNCD')
                ->whereBetween('case_info.CA_RCVDT', [$ds, $de])
                ->whereNotIn('CA_INVSTS', [10]);

            if (count($st) > 0) {
                $q->whereIn('BR_STATECD', $st);
            }

            if (!empty($dp)) {
                $q->where('CA_DEPTCD', $dp);
            }

            if ($br !== 0 && $br !== '0') {
                $q->where('CA_BRNCD', $br);
            }

            if (count($cs) === 1) {
                # code...
                switch ($cs[0]) {
                    case 'SELESAI':
                        $q->whereIn('CA_INVSTS', ['3','12']);
                        break;
                    case 'TERIMA':
                        $q->where('CA_CASESTS', 1);
                        break;
                    case 'DALAMSIASATAN':
                        $q->where('CA_CASESTS', 2)->where('CA_INVSTS', 2);
                        break;
                    case 'TUTUP':
                        $q->where('CA_CASESTS', 2)->whereIn('CA_INVSTS', ['9','11']);
                        break;
                    case 'AGENSILAIN':
                        $q->where('CA_CASESTS', 2)->where('CA_INVSTS', 4);
                        break;
                    case 'TRIBUNAL':
                        $q->where('CA_CASESTS', 2)->where('CA_INVSTS', 5);
                        break;
                    case 'PERTANYAAN':
                        $q->where('CA_CASESTS', 2)->where('CA_INVSTS', 6);
                        break;
                    case 'MKLUMATXLENGKAP':
                        $q->where('CA_CASESTS', 2)->where('CA_INVSTS', 7);
                        break;
                    case 'LUARBIDANG':
                        $q->where('CA_CASESTS', 2)->where('CA_INVSTS', 8);
                        break;
                    default:
                        // something
                        break;
                }
            }

            $data_final = $data_raw = $q->get();

            switch ($gen) {
                case 'e':
                    return Excel::create('Laporan Status Aduan' . date("_Ymd_His"), function ($excel) use ($is_search, $state_list, $department_list, $case_status_list, $branch_list, $ds, $de, $dp, $st, $br, $cs, $data_final, $cmplcat_list) {
                        $excel->sheet('Report', function ($sheet) use ($is_search, $state_list, $department_list, $case_status_list, $branch_list, $ds, $de, $dp, $st, $br, $cs, $data_final, $cmplcat_list) {
                            $sheet->loadView('laporan.laporanlainlain.laporan_negeri_status1.excel')
                                ->with([
                                    'is_search' => $is_search,
                                    'state_list' => $state_list,
                                    'department_list' => $department_list,
                                    'case_status_list' => $case_status_list,
                                    'branch_list' => $branch_list,
                                    'ds' => $ds,
                                    'de' => $de,
                                    'dp' => $dp,
                                    'st' => $st,
                                    'br' => $br,
                                    'cs' => $cs,
                                    'data_final' => $data_final,
                                    'cmplcat_list' => $cmplcat_list,
                                ]);
                        });
                    })->export('xlsx');
                    break;
                case 'xls':
                    return view(
                        'laporan.laporanlainlain.laporan_negeri_status1.excelxls',
                        compact(
                            'is_search', 'state_list', 'department_list', 'case_status_list', 
                            'branch_list', 'ds', 'de', 'dp', 'st', 'br', 'cs', 'data_final', 
                            'cmplcat_list'
                        )
                    );
                    break;
                case 'p':
                    $pdf = PDF::loadView('laporan.laporanlainlain.laporan_negeri_status1.pdf',
                        compact('is_search', 'state_list', 'department_list', 'case_status_list', 'branch_list', 'ds', 'de', 'dp', 'st', 'br', 'cs', 'data_final', 'cmplcat_list')
                    );
                    return $pdf->stream('laporanlainlain' . date("Ymd_His") . '.pdf');
                    break;
                case 'w':
                default:
                    return view('laporan.laporanlainlain.laporan_negeri_status1.index',
                        compact('is_search', 'state_list', 'department_list', 'case_status_list', 'branch_list', 'ds', 'de', 'dp', 'st', 'br', 'cs', 'data_final', 'cmplcat_list', 'uri_gen', 'uri_dd')
                    );
                    break;
            }
        }

        return view('laporan.laporanlainlain.laporan_negeri_status1.index',
            compact('is_search', 'state_list', 'department_list', 'case_status_list', 'branch_list', 'ds', 'de', 'dp', 'st', 'br', 'cs', 'data_final', 'request', 'uri_gen', 'cmplcat_list')
        );
    }

    public function capaianpelangan(Request $Request)
    {
        $data = [];
        $tahun = date('Y', strtotime(Carbon::now()));
        $Month_from = '01';
        $Month_to = '12';
        $cari = '';
        $mRef = DB::table('sys_ref')
            ->where(['cat' => 259])
            ->get();
        $mMonth = DB::table('sys_ref')
            ->select('descr', 'code')
            ->where('cat', '206')
            ->get();


        if (Input::has('year')) {
            $tahun = Input::get('year');
        }
        if (Input::has('Month_from')) {
            $Month_from = Input::get('Month_from');
        }
        if (Input::has('Month_to')) {
            $Month_to = Input::get('Month_to');
        }


        if (Input::has('cari')) {
            $cari = Input::get('cari');
        }

        if ($Request->has('cari') || $Request->get('excel') == '1' || $Request->has('pdf')) {
            $cari = true;
            $query = DB::table('case_info')
                ->select(DB::raw("COUNT(CA_CASEID) AS Counta"),
                    DB::raw("SUM(CASE WHEN DATEDIFF(CA_MODDT,CA_RCVDT) >=0 THEN 1 ELSE 0 END) AS Count3"),
                    DB::raw("SUM(CASE WHEN DATEDIFF(CA_MODDT,CA_RCVDT) <27 && CA_INVSTS BETWEEN 3 AND 9 THEN 1 ELSE 0 END) AS COUNT"),
                    DB::raw("SUM(CASE WHEN CA_INVSTS BETWEEN 1 AND 2 THEN 1 ELSE 0 END) AS Count1"),
                    DB::raw("SUM(CASE WHEN DATEDIFF(CA_MODDT,CA_RCVDT) >=27 THEN 1 ELSE 0 END) AS Count2"))
                ->join('sys_brn', 'case_info.CA_BRNCD', '=', 'sys_brn.BR_BRNCD');

            if ($Request->has('year')) {
                $query->whereYear('case_info.CA_RCVDT', $Request->get('year'));
            }
            if ($Request->has('Month_from')) {
                $query->whereMonth('case_info.CA_RCVDT', '>=', $Request->get('Month_from'));
            }
            if ($Request->has('Month_to')) {
                $query->whereMonth('case_info.CA_RCVDT', '<=', $Request->get('Month_to'));
            }
            $data = $query = $query->get();
        }
        $i = 1;

        if ($Request->has('cari')) {
            return view('laporan.laporanlainlain.capaian_pelanggan', compact('mRef', 'i', 'data', 'mMonth', 'Month_from', 'Month_to', 'tahun', 'cari', 'Request'));
        } elseif ($Request->has('excel')) {
            return view('laporan.laporanlainlain.capaianpelanggan_excel', compact('mRef', 'i', 'data', 'mMonth', 'Month_from', 'Month_to', 'tahun', 'cari', 'Request'));
        } elseif ($Request->has('pdf')) {
            $pdf = PDF::loadView('laporan.laporanlainlain.capaianpelanggan_pdf', compact('mRef', 'i', 'data', 'mMonth', 'Month_from', 'Month_to', 'tahun', 'cari', 'Request'), [], ['default_font_size' => 7, 'title' => date('Ymd_His')]);
            return $pdf->stream('Kategori_' . date("Ymd_His") . '.pdf');
        } else {
            return view('laporan.laporanlainlain.capaian_pelanggan', compact('mRef', 'i', 'data', 'mMonth', 'Month_from', 'Month_to', 'tahun', 'cari', 'Request'));
        }

    }

    /**
     * Laporan Aduan Mengikut Pegawai Penyiasat Bagi Semua Bahagian
     * @param Request $request
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function laporanpegawai(Request $request)
    {
        // retrive data
        $data = $request->all();
        $search = count($data) > 0 ? true : false;

        // initialization data
        $user = Auth::user();
        $role = $user->Role()->pluck('role_code', 'role_code');
        $generate = isset($data['gen']) ? $data['gen'] : 'web';
        $dateStart = isset($data['dateStart']) ? Carbon::createFromFormat('d-m-Y', $data['dateStart'])->startOfDay() : Carbon::now()->startOfDay();
        $dateEnd = isset($data['dateEnd']) ? Carbon::createFromFormat('d-m-Y', $data['dateEnd'])->endOfDay() : Carbon::now()->endOfDay();
        $state = isset($data['state']) ? $data['state'] : '';
        $branch = isset($data['branch']) ? $data['branch'] : '';
        $branchName = isset($data['branch']) ? Branch::GetBranchName($branch) : '';
        $pegawai = $data['pegawai'] ?? '';
        $isLimitByState = false;
        $isLimitByBranch = false;
        $isLimitByInvestigator = false;
        $isLimitByCreator = false;

        // user can have multiple role. need to check every role if they is cross each other
        /**
         * @todo need to know what happen to user selection & searching capabilities if role 100 & 310
         */
        foreach ($role as $datum) {
            // carian hanya kepada negeri dia sahaja
            $isLimitByState = ($isLimitByState == true || in_array($datum, [100, 120, 200, 210, 220, 230, 240, 310, 320, 340, 700])) ? true : false;
            // carian hanya kepada bahagian dia sahaja
            $isLimitByBranch = ($isLimitByBranch == true || in_array($datum, [310, 320, 340])) ? true : false;
            // carian hanya kepada aduan yang dia siasat sahaja
            $isLimitByInvestigator = ($isLimitByInvestigator == true || in_array($datum, [150, 160, 250, 350])) ? true : false;
            // carian hanya kepada aduan yang dia cipta sahaja
            $isLimitByCreator = ($isLimitByCreator == true || in_array($datum, [700])) ? true : false;
        }

        // reference data
        // state list
        $qStateList = DB::table('sys_ref')
            ->where('cat', '17');
        if ($isLimitByState) {
            $qStateList = $qStateList->where('code', $user->state_cd);
        }
        $stateList = $qStateList->pluck('descr', 'code');

        // branch list
        if ($isLimitByBranch) {
            $branchList = Branch::GetListAlternative(false, '', $user->brn_cd, '', '');
        } else if ($branch != '') {
            $branchList = Branch::GetListByState($state, true, 'Semua');
        } else {
            $branchList = Branch::GetListByState($user->state_cd, true, 'Semua');
        }

        // query data
        $qDataRaw = DB::table('case_info')
            ->select(DB::raw('count(1) as total'),
                'sys_users.name as investigator_name',
                'sys_users.id as investigator_id',
                'sys_brn.BR_BRNNM as branch_name',
                DB::raw('SUM(CASE WHEN case_info.CA_INVSTS IN (0,2,7) THEN 1 ELSE 0 END) as investigation_not_finished'),
                DB::raw('SUM(CASE WHEN case_info.CA_INVSTS IN (3,4,5,6,8,9) THEN 1 ELSE 0 END) as investigation_done')
            )
            ->join('sys_brn', 'case_info.CA_BRNCD', '=', 'sys_brn.BR_BRNCD')
            ->join('sys_users', 'case_info.CA_INVBY', '=', 'sys_users.id')
            ->whereNotIn('case_info.CA_INVSTS', [10, 1, 11, 12])
            ->whereBetween('CA_RCVDT', [$dateStart, $dateEnd]);

        if ($isLimitByState || $state != '')
            $qDataRaw = $qDataRaw->where('sys_brn.BR_STATECD', $state);

        if ($isLimitByBranch || $branch != '')
            $qDataRaw = $qDataRaw->where('sys_brn.BR_BRNCD', $branch);

        if ($isLimitByInvestigator)
            $qDataRaw = $qDataRaw->where('case_info.CA_INVBY', $user->id);

        if ($isLimitByCreator)
            $qDataRaw = $qDataRaw->where('case_info.CA_CREBY', $user->name);

        if ($pegawai != '')
           $qDataRaw = $qDataRaw->where('case_info.CA_INVBY',$pegawai);

        $dataFinal = $dataRaw = $qDataRaw->groupBy('investigator_name', 'investigator_id', 'branch_name')->get();

        

        switch ($generate) {
            case 'pdf':
                $pdf = PDF::loadView('laporan.laporanlainlain.laporan_pegawai.pdf',
                    compact('user', 'role', 'dateStart', 'dateEnd', 'stateList', 'search', 'isLimitByBranch', 'isLimitByState', 'branchList', 'state', 'branch', 'dataFinal', 'branchName'));
                return $pdf->stream('Laporan_Pegawai' . date("_Ymd_His") . '.pdf');
                break;
            case 'excel':
                return Excel::create('Laporan Aduan Mengikut Pengawai' . date("_Ymd_His"), function ($excel) use ($user, $role, $dateStart, $dateEnd, $search, $stateList, $isLimitByBranch, $isLimitByState, $branchList, $state, $branch, $dataFinal, $branchName) {
                    $excel->sheet('Report', function ($sheet) use ($user, $role, $dateStart, $dateEnd, $search, $stateList, $isLimitByBranch, $isLimitByState, $branchList, $state, $branch, $dataFinal, $branchName) {
                        $sheet->loadView('laporan.laporanlainlain.laporan_pegawai.excel')
                            ->with([
                                'user' => $user,
                                'role' => $role,
                                'dateStart' => $dateStart,
                                'dateEnd' => $dateEnd,
                                'stateList' => $stateList,
                                'search' => $search,
                                'isLimitByBranch' => $isLimitByBranch,
                                'branchList' => $branchList,
                                'state' => $state,
                                'branch' => $branch,
                                'dataFinal' => $dataFinal,
                                'branchName' => $branchName
                            ]);
                    });
                })->export('xlsx');
                break;
            case 'excelxls':
                return view('laporan.laporanlainlain.laporan_pegawai.excelxls',
                    compact('user', 'role', 'dateStart', 'dateEnd', 'stateList', 'search', 'isLimitByBranch', 'isLimitByState', 'branchList', 'state', 'branch', 'dataFinal', 'branchName'));
                break;
            case 'web':
            default:
                return view('laporan.laporanlainlain.laporan_pegawai.index',
                    compact('user', 'role', 'dateStart', 'dateEnd', 'stateList', 'search', 'isLimitByBranch', 'isLimitByState', 'branchList', 'state', 'branch', 'dataFinal', 'branchName'));
                break;
        }
    }

    /**
     * Senarai Drill Down Laporan Pegawai
     * @param Request $request
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function laporanpegawai1(Request $request)
    {
        // retrive data
        $data = $request->all();
        $dateStart = isset($data['ds']) ? Carbon::createFromFormat('d-m-Y', $data['ds'])->startOfDay() : Carbon::now()->startOfDay();
        $dateEnd = isset($data['de']) ? Carbon::createFromFormat('d-m-Y', $data['de'])->endOfDay() : Carbon::now()->endOfDay();
        $state = (isset($data['s']) && $data['s'] != null) ? $data['s'] : '';
        $stateName = 'SEMUA NEGERI';
        $department = (isset($data['d']) && $data['d'] != null) ? $data['d'] : '';
        $departmentName = 'SEMUA BAHAGIAN';
        $userId = (isset($data['i']) && $data['i'] != null) ? $data['i'] : '';
        $generate = (isset($data['g']) && $data['g'] != null) ? $data['g'] : 'w'; // w - web, p - pdf, e - excel

        $q = Carian::select('sys_users.name', 'CA_CASEID', 'CA_SUMMARY', 'CA_NAME', 'CA_AGAINSTNM',
            'CA_RCVDT', 'CA_ASGDT', 'CA_COMPLETEDT', 'CA_CLOSEDT', 'CA_INVBY', 'CA_CMPLCAT', 'BR_BRNNM',
            DB::raw("
                (SELECT
                    case_dtl.CD_CREDT
                FROM
                    case_dtl
                WHERE case_dtl.CD_CASEID = CA_CASEID
                    AND case_dtl.CD_INVSTS = '2'
                ORDER BY case_dtl.CD_CREDT DESC
                LIMIT 1 ) AS investigationdate,
                (SELECT
                    case_dtl.CD_REASON_DURATION
                FROM
                    case_dtl
                WHERE case_dtl.CD_CASEID = CA_CASEID
                    AND case_dtl.CD_CREDT > investigationdate
                ORDER BY case_dtl.CD_CREDT ASC
                LIMIT 1 ) AS duration
            "))
            ->join('sys_brn', 'case_info.CA_BRNCD', '=', 'sys_brn.BR_BRNCD')
            ->join('sys_users', 'case_info.CA_INVBY', '=', 'sys_users.id')
//            ->whereBetween('case_info.CA_INVSTS', [2, 9])
            ->whereNotIn('case_info.CA_INVSTS', [10, 1, 11, 12])
            ->where('case_info.CA_INVBY', $userId)
            ->whereBetween('case_info.CA_RCVDT', [$dateStart, $dateEnd]);

            //now get all user and services in one go without looping using eager loading
            //In your foreach() loop, if you have 1000 users you will make 1000 queries


    

        if ($state != '') {
            $q = $q->where('sys_brn.BR_STATECD', $state);
            $stateName = 'NEGERI ' . Ref::GetDescr('17', $state);
        }

        if ($department != '') {
            $q = $q->where('case_info.CA_BRNCD', $department);
            $departmentName = 'CAWANGAN ' . Branch::GetBranchName($department);
        }

        $dataFinal = $dataRaw = $q->get();

        switch ($generate) {
            case 'p':
                $pdf = PDF::loadView('laporan.laporanlainlain.pegawai1.pdf',
                    compact('dataFinal', 'dateStart', 'dateEnd', 'state', 'stateName', 'department', 'departmentName'));
                return $pdf->stream('Laporan_Pegawai' . date("_Ymd_His") . '.pdf');
                break;
            case 'e':
                return Excel::create('LAPORAN ADUAN MENGIKUT PEGAWAI PENYIASAT BAGI SEMUA BAHAGIAN' . date("_Ymd_His"), function ($excel) use ($dataFinal, $dateStart, $dateEnd, $state, $stateName, $department, $departmentName) {
                    $excel->sheet('Report', function ($sheet) use ($dataFinal, $dateStart, $dateEnd, $state, $stateName, $department, $departmentName) {
                        $sheet->loadView('laporan.laporanlainlain.pegawai1.excel')
                            ->with([
                                'dataFinal' => $dataFinal,
                                'dateStart' => $dateStart,
                                'dateEnd' => $dateEnd,
                                'state' => $state,
                                'stateName' => $stateName,
                                'department' => $department,
                                'departmentName' => $departmentName,
                            ]);
                    });
                })->export('xlsx');
                break;
            case 'w':
            default:
                return view('laporan.laporanlainlain.pegawai1.index',
                    compact('dataFinal', 'dateStart', 'dateEnd', 'state', 'stateName', 'department', 'departmentName', 'userId'));
                break;
        }
    }

    /**
     * Laporan Lanjutan
     * @param Request $request
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function laporanlanjutan(Request $request)
    {
        $statusList = [3 => 'Selesai', 4 => 'Rujuk ke Kementerian/Ajensi Lain', 5 => 'Rujuk ke Tribunal',
            1 => 'Belum Bermula', 6 => 'Pertanyaan', 7 => 'Maklumat Tidak Lengkap', 8 => 'Tidak Berasas', 9 => 'Kes Ditutup'];
        $stateList = ['08' => 'Perak', '10' => 'Selangor', '06' => 'Pahang', '03' => 'Kelantan', '01' => 'Johor',
            '02' => 'Kedah', '15' => 'Labuan', '04' => 'Melaka', '05' => 'Negeri Sembilan', '07' => 'Pulau Penang',
            '13' => 'Sarawak', '09' => 'Perlis', '12' => 'Sabah', '11' => 'Terengganu', '16' => 'Putrajaya', '14' => 'Kuala Lumpur'];
        $branchList = DB::table('sys_brn')->pluck('BR_BRNNM', 'BR_BRNCD');
        $sourceList = DB::table('sys_ref')->where('cat', '259')->pluck('descr', 'code');
        $departmentGroupList = DB::table('sys_ref')->where('cat', '315')->pluck('descr', 'code');
        $departmentList = DB::table('sys_ref')->where('cat', '244')->pluck('descr', 'code');

        $data = $request->all();
        $search = count($data) > 0 ? true : false;
//         $data['dateStart'] = '01-01-2017';
//         $data['dateEnd'] = '28-02-2017';

        $dateStart = isset($data['dateStart']) ? Carbon::createFromFormat('d-m-Y', $data['dateStart'])->startOfDay() : Carbon::now()->startOfDay();
        $dateEnd = isset($data['dateEnd']) ? Carbon::createFromFormat('d-m-Y', $data['dateEnd'])->endOfDay() : Carbon::now()->endOfDay();
        $branch = isset($data['branch']) ? $data['branch'] : [];
        $departmentGroup = isset($data['departmentGroup']) ? $data['departmentGroup'] : '';
        $department = isset($data['department']) ? $data['department'] : [];
        $reportFinal = [];
        $generate = isset($data['gen']) ? $data['gen'] : 'web';

        $reportRaw = DB::table('case_info')
            ->select(DB::Raw('substr(ca_rcvdt,1,10) as ca_rcvdt'), 'ca_brncd', 'ca_cmplcat', 'ca_caseid', 'ca_rcvtyp',
                'ca_rcvby', 'ca_name', 'ca_addr', 'ca_poscd', 'ca_distcd', 'ca_statecd', 'ca_againstnm', 'ca_result',
                'ca_casests', 'sys_users.name as name')
            ->join('sys_users', 'sys_users.id', '=', 'ca_rcvby')
            ->where(function ($qu) use ($department, $branch) {
                if (count($department) > 0 && count($branch) > 0) {
                    $qu->whereIn('ca_cmplcat', $department)
                        ->whereIn('ca_brncd', $branch);
                } else if (count($department) > 0) {
                    $qu->whereIn('ca_cmplcat', $department);
                } else if (count($branch) > 0) {
                    $qu->whereIn('ca_brncd', $branch);
                } else {
                    $qu->whereNotNull('ca_rcvdt');
                }
            })
            ->whereBetween('ca_rcvdt', [$dateStart, $dateEnd])
            ->get();

        foreach ($reportRaw as $datum) {
            if (!isset($reportFinal[$datum->ca_brncd])) {
                $reportFinal[$datum->ca_brncd] = [];
            }

            if (!isset($reportFinal[$datum->ca_brncd][$datum->ca_cmplcat])) {
                $reportFinal[$datum->ca_brncd][$datum->ca_cmplcat] = [];
            }

            $reportFinal[$datum->ca_brncd][$datum->ca_cmplcat][] = $datum;
        }

        switch ($generate) {
            case 'pdf':
                $pdf = PDF::loadView('laporan.laporanlainlain.laporan_lanjutan.pdf', compact('sourceList', 'branch', 'department', 'departmentGroup', 'statusList', 'stateList', 'branchList', 'reportFinal', 'search', 'departmentGroupList', 'departmentList', 'dateStart', 'dateEnd', 'request'), [], ['default_font_size' => 7, 'title' => date('Ymd_His')]);
                return $pdf->stream('Laporan Lanjutan_' . date("Ymd_His") . '.pdf');
                break;
            case 'excel':
                return Excel::create('Laporan Lanjutan Aduan' . date("_Ymd_His"), function ($excel) use ($sourceList, $branch, $department, $departmentGroup, $statusList, $stateList, $branchList, $reportFinal, $search, $departmentGroupList, $departmentList, $dateStart, $dateEnd, $request) {
                    $excel->sheet('Report', function ($sheet) use ($sourceList, $branch, $department, $departmentGroup, $statusList, $stateList, $branchList, $reportFinal, $search, $departmentGroupList, $departmentList, $dateStart, $dateEnd, $request) {
                        $sheet->loadView('laporan.laporanlainlain.laporan_lanjutan.excel')
                            ->with([
                                'sourceList' => $sourceList,
                                'branch' => $branch,
                                'department' => $department,
                                'departmentGroup' => $departmentGroup,
                                'statusList' => $statusList,
                                'stateList' => $stateList,
                                'branchList' => $branchList,
                                'reportFinal' => $reportFinal,
                                'search' => $search,
                                'departmentGroupList' => $departmentGroupList,
                                'departmentList' => $departmentList,
                                'dateStart' => $dateStart,
                                'dateEnd' => $dateEnd,
                                'request' => $request,
                            ]);
                    });
                })->export('xlsx');
                break;
            case 'web':
            default:
                return view('laporan.laporanlainlain.laporan_lanjutan.index',
                    compact('sourceList', 'branch', 'department', 'departmentGroup', 'statusList', 'stateList', 'branchList', 'reportFinal', 'search', 'departmentGroupList', 'departmentList', 'dateStart', 'dateEnd', 'request'));
                break;
        }
    }

    public function laporanjantina(Request $request)
    {

        $CA_RCVDT_dri = Carbon::now();
        $CA_RCVDT_lst = Carbon::now();
        $CA_DEPTCD = '';
        $cari = '';
//        $mState = [];
        $CA_CMPLCAT = [];
        $i = 1;

        $mRef = DB::table('sys_ref')
            ->where(['cat' => 17])
            ->get();
        $mState = DB::table('sys_ref')
            ->where('cat', '18')
            ->get();
        $listcats = DB::table('sys_ref')
            ->select('descr', 'code')
            ->where('cat', '244')->get();


        if ($request->CA_CMPLCAT) {
            $CA_CMPLCAT = $request->CA_CMPLCAT;
            $listcat = DB::table('sys_ref')
                ->select('descr', 'code')
                ->whereIn('code', $CA_CMPLCAT)
                ->where('cat', '244')->get();
        }
        if ($request->CA_DEPTCD) {
            $CA_DEPTCD = $request->CA_DEPTCD;
            if (!empty($request->CA_CMPLCAT)) {
                $CA_CMPLCAT = $request->CA_CMPLCAT;
                $listcat = DB::table('sys_ref')
                    ->select('descr', 'code')
                    ->whereIn('code', $CA_CMPLCAT)
                    ->where(['cat' => '244', 'status' => 1])->get();
            } else {
                $listcat = DB::table('sys_ref')
                    ->select('descr', 'code')
                    ->where([['code', 'LIKE', "$CA_DEPTCD%"], ['cat', '=', '244'], ['status' => 1]])->get();
            }
        }


        if (Input::has('CA_RCVDT_dri')) {
            $CA_RCVDT_dri = Input::get('CA_RCVDT_dri');
        }
        if (Input::has('CA_RCVDT_lst')) {
            $CA_RCVDT_lst = Input::get('CA_RCVDT_lst');
        }
        if (Input::has('CA_DEPTCD')) {
            $CA_DEPTCD = Input::get('CA_DEPTCD');
        }
        if (Input::has('BR_STATECD')) {
            $BR_STATECD = $request->BR_STATECD;
        }
        if (Input::has('CA_CMPLCAT')) {
            $CA_CMPLCAT = $request->CA_CMPLCAT;
        }
        if (Input::has('cari')) {
            $cari = 1;
        }
        return view('laporan.laporanlainlain.laporanjantina', compact('mRef', 'i', 'mState', 'listcats', 'listcat', 'CA_CMPLCAT', 'CA_DEPTCD', 'CA_RCVDT_dri', 'CA_RCVDT_lst', 'CA_CMPLCAT', 'cari'));
    }

    public function laporanwarganegara(Request $request)
    {

        $CA_RCVDT_dri = Carbon::now();
        $CA_RCVDT_lst = Carbon::now();
        $CA_DEPTCD = '';
        $cari = '';
//        $mState = [];
        $CA_CMPLCAT = [];
        $i = 1;

        $mRef = DB::table('sys_ref')
            ->where(['cat' => 17])
            ->get();
        $mState = DB::table('sys_ref')
            ->where('cat', '18')
            ->get();
        $listcats = DB::table('sys_ref')
            ->select('descr', 'code')
            ->where('cat', '244')->get();
        if ($request->CA_CMPLCAT) {
            $CA_CMPLCAT = $request->CA_CMPLCAT;
            $listcat = DB::table('sys_ref')
                ->select('descr', 'code')
                ->whereIn('code', $CA_CMPLCAT)
                ->where('cat', '244')->get();
        }
        if ($request->CA_DEPTCD) {
            $CA_DEPTCD = $request->CA_DEPTCD;
            if (!empty($request->CA_CMPLCAT)) {
                $CA_CMPLCAT = $request->CA_CMPLCAT;
                $listcat = DB::table('sys_ref')
                    ->select('descr', 'code')
                    ->whereIn('code', $CA_CMPLCAT)
                    ->where(['cat' => '244', 'status' => 1])->get();
            } else {
                $listcat = DB::table('sys_ref')
                    ->select('descr', 'code')
                    ->where([['code', 'LIKE', "$CA_DEPTCD%"], ['cat', '=', '244'], ['status' => 1]])->get();
            }
        }


        if (Input::has('CA_RCVDT_dri')) {
            $CA_RCVDT_dri = Input::get('CA_RCVDT_dri');
        }
        if (Input::has('CA_RCVDT_lst')) {
            $CA_RCVDT_lst = Input::get('CA_RCVDT_lst');
        }
        if (Input::has('CA_DEPTCD')) {
            $CA_DEPTCD = Input::get('CA_DEPTCD');
        }
        if (Input::has('BR_STATECD')) {
            $BR_STATECD = $request->BR_STATECD;
        }
        if (Input::has('CA_CMPLCAT')) {
            $CA_CMPLCAT = $request->CA_CMPLCAT;
        }
        if (Input::has('cari')) {
            $cari = 1;
        }
        return view('laporan.laporanlainlain.laporanwarganegara', compact('mRef', 'i', 'mState', 'listcats', 'listcat', 'CA_CMPLCAT', 'CA_DEPTCD', 'CA_RCVDT_dri', 'CA_RCVDT_lst', 'CA_CMPLCAT', 'cari'));
    }

    /**
     * Matrix report generator.
     * @param Request $request
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function matrix(Request $request)
    {
        // retrive data from request
        $data = $request->all();
        $is_search = count($data) > 0 ? true : false;
//        dump($data);
//        $data['ds'] = '01-01-2017';
//        $data['de'] = '28-02-2017';

        // reference data
        $category_list = DB::table('sys_ref')->where(['cat' => '244', 'status' => '1'])->pluck('descr', 'code');
        $matrix_type_list = [
            "" => "Sila Pilih...",
            "CA_BRNCD" => "Cawangan",
            "CA_INVSTS" => "Status Siasatan",
            "CA_RCVTYP" => "Cara Penerimaan",
            "CA_AGAINST_STATECD" => "Negeri",
            "BR_STATECD" => "Negeri( Cawangan )",
            "CA_SEXCD" => "Jantina",
        ];

        /**
         * Initialization data.
         *
         * @param $ds string date_start
         * @param $de string date_end
         * @param $ca string category
         * @param $row string row type
         * @param $row_data array row selection
         * @param $col string col type
         * @param $col_data array col selection
         * @param $g string generate
         */
        $ds = isset($data['ds']) ? Carbon::createFromFormat('d-m-Y', $data['ds'])->startOfDay() : Carbon::now()->startOfDay();
        $de = isset($data['de']) ? Carbon::createFromFormat('d-m-Y', $data['de'])->endOfDay() : Carbon::now()->endOfDay();
        $ca = isset($data['ca']) ? $data['ca'] : '';
        $row = isset($data['row']) ? $data['row'] : '';
        $row_data = isset($data['row_data']) ? $data['row_data'] : [];
        $row_list = $this->matrixRowColList($row);
        $col = isset($data['col']) ? $data['col'] : '';
        $col_data = isset($data['col_data']) ? $data['col_data'] : [];
        $col_list = $this->matrixRowColList($col);
        $g = isset($data['g']) ? $data['g'] : 'w';

        // START: URI
        $uri_array = '';
        foreach ($col_data as $datum) {
            $uri_array .= 'col_data%5B%5D=' . $datum . '&';
        }
        foreach ($row_data as $datum) {
            $uri_array .= 'row_data%5B%5D=' . $datum . '&';
        }
        $uri_share = 'ds=' . $ds->format('d-m-Y') . '&de=' . $de->format('d-m-Y');
        $uri = '/laporanlainlain/matrix?' . $uri_share . '&ca=' . $ca . '&row=' . $row . '&col=' . $col . '&' . $uri_array;
        $uri_dd = '/laporanlainlain/matrix1?ds=' . $ds->format('d-m-Y') . '&de=' . $de->format('d-m-Y') . '&ca=' . $ca . '&row=' . $row . '&col=' . $col . '&gen=w&';
        // END: URI

        // START: Prepare data array
        $data_template = ['total' => 0];
        $data_final = ['total' => $data_template];

        foreach ($row_data as $v) {
            $data_final[$v] = $data_template;
            foreach ($col_data as $k) {
                $data_final[$v][$k] = 0;
                $data_final['total'][$k] = 0;
            }
        }

        $data_final['total']['total'] = 0;
        // END: Prepare data array

        if ($is_search) {
            $data_raw = $this->matrixQuery(false, $ds, $de, $ca, $row, $row_data, $col, $col_data);

            // START: Populate data
            if (count($data_raw) > 0 && $data_raw[0]->counter != 0) {
                foreach ($data_raw as $datum_raw) {
                    $data_final[trim($datum_raw->$row)][$datum_raw->$col] += $datum_raw->counter;
                    $data_final[trim($datum_raw->$row)]['total'] += $datum_raw->counter;
                    $data_final['total'][$datum_raw->$col] += $datum_raw->counter;
                    $data_final['total']['total'] += $datum_raw->counter;
                }
            }
            // END: Populate data

            switch ($g) {
                case 'e':
                    return Excel::create('Laporan Matriks' . date("_Ymd_His"), function ($excel) use (
                        $ds, $de, $category_list, $matrix_type_list, $is_search, $col_data, $col_list, $row_data,
                        $row_list, $uri_dd, $uri, $data_final
                    ) {
                        $excel->sheet('Report', function ($sheet) use (
                            $ds, $de, $category_list, $matrix_type_list, $is_search, $col_data, $col_list, $row_data,
                            $row_list, $uri_dd, $uri, $data_final
                        ) {
                            $sheet->loadView('laporan.laporanlainlain.matrix.excel')
                                ->with([
                                    'ds' => $ds,
                                    'de' => $de,
                                    'category_list' => $category_list,
                                    'matrix_type_list' => $matrix_type_list,
                                    'is_search' => $is_search,
                                    'col_data' => $col_data,
                                    'col_list' => $col_list,
                                    'row_data' => $row_data,
                                    'row_list' => $row_list,
                                    'uri_dd' => $uri_dd,
                                    'uri' => $uri,
                                    'data_final' => $data_final
                                ]);
                        });
                    })->export('xlsx');
                    break;
                case 'p':
                    $pdf = PDF::loadView('laporan.laporanlainlain.matrix.pdf',
                        compact('ds', 'de', 'category_list', 'matrix_type_list', 'is_search', 'col_data', 'col_list',
                            'row_data', 'row_list', 'uri_dd', 'uri', 'data_final')
                    );
                    return $pdf->stream('laporanlainlain' . date("Ymd_His") . '.pdf');
                    break;
                case 'w':
                default:
                    return view('laporan.laporanlainlain.matrix.index',
                        compact('ds', 'de', 'category_list', 'matrix_type_list', 'is_search', 'col_data', 'col_list',
                            'row_data', 'row_list', 'uri_dd', 'uri', 'data_final')
                    );
                    break;
            }
        }

        // default return
        return view('laporan.laporanlainlain.matrix.index',
            compact('ds', 'de', 'category_list', 'matrix_type_list', 'is_search', 'col_data', 'col_list',
                'row_data', 'row_list', 'uri_dd', 'uri', 'data_final')
        );
    }

    public function matrix1(Request $request)
    {
        // retrive data from request
        $data = $request->all();
//        dump($data);
//        $data['ds'] = '01-01-2017';
//        $data['de'] = '28-02-2017';
        $is_search = count($data) > 0 ? true : false;

        // reference data
        $category_list = DB::table('sys_ref')->where('cat', 244)->pluck('descr', 'code');

        /**
         * Initialization data.
         *
         * @param $ds string date_start
         * @param $de string date_end
         * @param $ca string category
         * @param $row string row type
         * @param $row_data array row selection
         * @param $col string col type
         * @param $col_data array col selection
         * @param $g string generate
         */
        $ds = isset($data['ds']) ? Carbon::createFromFormat('d-m-Y', $data['ds'])->startOfDay() : Carbon::now()->startOfDay();
        $de = isset($data['de']) ? Carbon::createFromFormat('d-m-Y', $data['de'])->endOfDay() : Carbon::now()->endOfDay();
        $ca = isset($data['ca']) ? $data['ca'] : '';
        $col = isset($data['col']) ? $data['col'] : '';
        $col_data = isset($data['col_datum']) ? $data['col_datum'] : [];
        $row = isset($data['row']) ? $data['row'] : '';
        $row_data = isset($data['row_datum']) ? $data['row_datum'] : [];
        $g = isset($data['g']) ? $data['g'] : 'w';

        // START: URI
        $uri = '/laporanlainlain/matrix1?ds=' . $ds->format('d-m-Y') . '&de=' . $de->format('d-m-Y')
            . '&ca=' . $ca . '&row=' . $row . '&col=' . $col . '&col_datum%5B%5D=' . (isset($col_data[0]) ? $col_data[0] : '')
            . '&row_datum%5B%5D=' . (isset($row_data[0]) ? $row_data[0] : '');
        // END: URI

        // query data
        if ($is_search) {
            $data_final = $data_raw = $this->matrixQuery(true, $ds, $de, $ca, $row, $row_data, $col, $col_data);
            switch ($g) {
                case 'e':
                    return Excel::create('Senarai-Aduan_Laporan-Matriks' . date("_Ymd_His"), function ($excel) use ($ds, $de, $uri, $data_final, $category_list) {
                        $excel->sheet('Report', function ($sheet) use ($ds, $de, $uri, $data_final, $category_list) {
                            $sheet->loadView('laporan.laporanlainlain.matrix1.excel')
                                ->with([
                                    'ds' => $ds,
                                    'de' => $de,
                                    'uri' => $uri,
                                    'data_final' => $data_final,
                                    'category_list' => $category_list,
                                ]);
                        });
                    })->export('xlsx');
                    break;
                case 'p':
                    $pdf = PDF::loadView('laporan.laporanlainlain.matrix1.pdf',
                        compact('ds', 'de', 'uri', 'data_final', 'category_list')
                    );
                    return $pdf->stream('laporanlainlain' . date("Ymd_His") . '.pdf');
                    break;
                case 'w':
                default:
                    return view('laporan.laporanlainlain.matrix1.index',
                        compact('ds', 'de', 'uri', 'data_final', 'category_list')
                    );
                    break;
            }
        }
    }

    /**
     * @param $row_col
     * @return array
     */
    public function matrixRowColList($row_col)
    {
        switch ($row_col) {
            case "CA_BRNCD":
                $row_col_list = DB::table('sys_brn')
                    ->where('BR_STATUS', 1)
                    ->pluck('BR_BRNNM', 'BR_BRNCD');
                break;
            case "CA_INVSTS":
                $row_col_list = [
                    3 => 'Selesai',
                    4 => 'Rujuk ke Kementerian/Ajensi Lain',
                    5 => 'Rujuk ke Tribunal',
                    1 => 'Belum Bermula',
                    6 => 'Pertanyaan',
                    7 => 'Maklumat Tidak Lengkap',
                    8 => 'Tidak Berasas',
                    9 => 'Kes Ditutup'
                ];
                break;
            case "CA_RCVTYP":
                $row_col_list = DB::table('sys_ref')
                    ->where('cat', '259')
                    ->pluck('descr', 'code');
                break;
            case "CA_AGAINST_STATECD":
            case "BR_STATECD":
                $row_col_list = DB::table('sys_ref')
                    ->where('cat', '17')
                    ->pluck('descr', 'code');
                break;
            case "CA_SEXCD":
                $row_col_list = [
                    'M' => 'Lelaki',
                    'F' => 'Perempuan',
                    'U' => 'Tidak Dinyatakan'
                ];
                break;
            default:
                $row_col_list = [];
                break;
        }

        return $row_col_list;
    }

    /**
     * @param $is_drill_down boolean
     * @param $ds string date_start
     * @param $de string date_end
     * @param $ca string category
     * @param $row string row type
     * @param $row_data array row selection
     * @param $col string col type
     * @param $col_data array col selection
     * @return mixed
     */
    public function matrixQuery($is_drill_down, $ds, $de, $ca, $row, $row_data, $col, $col_data)
    {
        if ($is_drill_down) {
            $q = DB::table('case_info')
                ->leftJoin('sys_users', 'CA_INVBY', '=', 'sys_users.id');
        } else {
            $q = DB::table('case_info')
                ->select(DB::Raw('count(1) as counter'));
        }

        $q->where('CA_CMPLCAT', $ca);
        $q->whereRaw('CA_CASEID IS NOT NULL');

        if ($row == 'CA_BRNCD' || $col == 'CA_BRNCD' || $row == 'CA_AGAINST_STATECD' || $col == 'CA_AGAINST_STATECD' || $row == 'BR_STATECD' || $col == 'BR_STATECD') {
            $q->join('sys_brn', 'ca_brncd', '=', 'br_brncd');
        }

        if ($row == 'CA_BRNCD' || $col == 'CA_BRNCD') {
//            $q->join('sys_brn', 'ca_brncd', '=', 'br_brncd');
            if ($row == 'CA_BRNCD' && !empty($row_data)) $q->whereIn('CA_BRNCD', $row_data);
            if ($col == 'CA_BRNCD' && !empty($col_data)) $q->whereIn('CA_BRNCD', $col_data);
            if (!$is_drill_down) $q->addSelect('CA_BRNCD')->groupBy('CA_BRNCD');
        }

        if ($row == 'CA_INVSTS' || $col == 'CA_INVSTS') {
            if ($row == 'CA_INVSTS' && !empty($row_data)) $q->whereIn('CA_INVSTS', $row_data);
            if ($col == 'CA_INVSTS' && !empty($col_data)) $q->whereIn('CA_INVSTS', $col_data);
            if (!$is_drill_down) $q->addSelect('CA_INVSTS')->groupBy('CA_INVSTS');
        }

        if ($row == 'CA_RCVTYP' || $col == 'CA_RCVTYP') {
            if ($row == 'CA_RCVTYP' && !empty($row_data)) $q->whereIn('CA_RCVTYP', $row_data);
            if ($col == 'CA_RCVTYP' && !empty($col_data)) $q->whereIn('CA_RCVTYP', $col_data);
            if (!$is_drill_down) $q->addSelect('CA_RCVTYP')->groupBy('CA_RCVTYP');
        }

        if ($row == 'CA_AGAINST_STATECD' || $col == 'CA_AGAINST_STATECD') {
//            $q->join('sys_brn', 'ca_brncd', '=', 'br_brncd');
            if ($row == 'CA_AGAINST_STATECD' && !empty($row_data)) $q->whereIn('CA_AGAINST_STATECD', $row_data);
            if ($col == 'CA_AGAINST_STATECD' && !empty($col_data)) $q->whereIn('CA_AGAINST_STATECD', $col_data);
            if (!$is_drill_down) $q->addSelect('CA_AGAINST_STATECD')->groupBy('CA_AGAINST_STATECD');
        }

        if ($row == 'BR_STATECD' || $col == 'BR_STATECD') {
//            $q->join('sys_brn', 'ca_brncd', '=', 'br_brncd');
            if ($row == 'BR_STATECD' && !empty($row_data)) $q->whereIn('BR_STATECD', $row_data);
            if ($col == 'BR_STATECD' && !empty($col_data)) $q->whereIn('BR_STATECD', $col_data);
            if (!$is_drill_down) $q->addSelect('BR_STATECD')->groupBy('BR_STATECD');
        }

        if ($row == 'CA_SEXCD' || $col == 'CA_SEXCD') {
            if ($row == 'CA_SEXCD' && !empty($row_data)) $q->whereIn('CA_SEXCD', $row_data);
            if ($col == 'CA_SEXCD' && !empty($col_data)) $q->whereIn('CA_SEXCD', $col_data);
            if (!$is_drill_down) $q->addSelect('CA_SEXCD')->groupBy('CA_SEXCD');
        }

        return $q->whereBetween('ca_rcvdt', [$ds, $de])->get();
    }

    /**
     * Akta report generator
     * @param Request $request
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function akta(Request $request)
    {
        // retrive data from request
        $data = $request->all();
        $is_search = count($data) > 0 ? true : false;

        // reference data
        $state_list = DB::table('sys_ref')
            ->where('cat', '17')
            ->pluck('descr', 'code');
        $akta_list = DB::table('sys_ref')
            ->where('cat', '713')
            ->pluck('descr', 'code');
        $branch_list = [];
        $department_list = ReportLainlain::GetRef('315', 'semua');

        /**
         * Initialization data.
         *
         * @param $ds string date_start
         * @param $de string date_end
         * @param $dp string department
         * @param $st array states
         * @param $is_branch integer is_branch
         * @param $g string generate
         */
        $ds = isset($data['ds']) ? Carbon::createFromFormat('d-m-Y', $data['ds'])->startOfDay() : Carbon::now()->startOfDay();
        $de = isset($data['de']) ? Carbon::createFromFormat('d-m-Y', $data['de'])->endOfDay() : Carbon::now()->endOfDay();
        $dp = isset($data['dp']) ? $data['dp'] : '';
        $dp_desc = $dp != '' ? Ref::GetDescr('315', $dp, 'ms') : 'SEMUA BAHAGIAN';
        $st = isset($data['st']) ? $data['st'] : [];
        $is_branch = isset($data['is_branch']) ? $data['is_branch'] : '';
        $g = isset($data['g']) ? $data['g'] : 'web';
        $data_template = [
            'total' => 0,
            'branch' => []
        ];

        // START: prepare data template

        foreach ($akta_list as $k => $v) {
            $data_template[$k] = 0;
        }

        $data_final['total'] = $data_template;
        $data_final['total']['total'] = 0;

        foreach ($state_list as $k => $state) {
            $data_final[$k] = $data_template;
        }

        // END: prepare data template
        // START: Data preparation

        if ($is_search) {
            // START: URI preparation
            $uri_array = '';
            foreach ($st as $datum) {
                $uri_array .= 'st%5B%5D=' . $datum . '&';
            }
            $uri_share = '?ds=' . $ds->format('d-m-Y') . '&de=' . $de->format('d-m-Y');
            $uri = '/laporanlainlain/akta' . $uri_share . '&is_branch=' . $is_branch . '&' . $uri_array;
            $uri_dd = '/laporanlainlain/akta1' . $uri_share . '&g=w&';
            // END: URI preparation

            // START: Data query
            $q = DB::table('case_act')
                ->select(DB::Raw('count(1) as counter'), 'ct_akta', 'br_statecd')
                ->join('case_info', 'case_info.ca_caseid', '=', 'case_act.ct_caseid')
                ->join('sys_brn', 'case_info.ca_brncd', '=', 'sys_brn.br_brncd')
                ->whereBetween('ct_credt', [$ds, $de]);

            // START: prepare branch data
            if ($is_branch == 1) {
                $q->addSelect('br_brncd')
                    ->groupBy('br_brncd');

                $branches = DB::table('sys_brn')
                    ->select('BR_BRNCD', 'BR_BRNNM', 'BR_STATECD')
                    ->get();

                // retrive branch data
                $branch_list = DB::table('sys_brn')
                    ->pluck('BR_BRNNM', 'BR_BRNCD');

                unset($data_template['branch']);

                foreach ($branches as $branch) {
                    // FORMAT: $data_final[%selangor%]['branch'][%hulu_langat%] = [...]
                    $data_final[$branch->BR_STATECD]['branch'][$branch->BR_BRNCD] = $data_template;
                }
            }
            // END: prepare branch data

            if (is_array($st) && count($st) > 0) {
                $q->whereIn('br_statecd', $st);
            }

            $data_raw = $q->groupBy('br_statecd', 'ct_akta')->get();
            // END: Data query

            // START: populate raw data to final array
            foreach ($data_raw as $datum) {
                // START: populate branch data to branch array
                if ($is_branch == 1) {
                    $data_final[$datum->br_statecd]['branch'][$datum->br_brncd][$datum->ct_akta] += $datum->counter;
                    $data_final[$datum->br_statecd]['branch'][$datum->br_brncd]['total'] += $datum->counter;
                }
                // END: populate branch data to branch array
                // START: populate stat data to stat array
                $data_final[$datum->br_statecd][$datum->ct_akta] += $datum->counter;
                $data_final[$datum->br_statecd]['total'] += $datum->counter;
                $data_final["total"][$datum->ct_akta] += $datum->counter;
                $data_final["total"]["total"] += $datum->counter;
                // END: populate branch data to branch array
            }
            // END: populate raw data to final array

            $doc_name = 'Laporan_Akta_Aduan_Mengikut_Negeri' . date("_Ymd_His");
            switch ($g) {
                case 'p':
                    $pdf = PDF::loadView('laporan.laporanlainlain.akta.pdf',
                        compact('ds', 'de', 'dp', 'dp_desc', 'st', 'data_final', 'state_list', 'akta_list',
                            'department_list', 'branch_list', 'is_branch', 'is_search')
                    );
                    return $pdf->stream($doc_name . '.pdf');
                    break;
                case 'e':
                    return Excel::create($doc_name, function ($excel) use (
                        $ds, $de, $dp, $dp_desc, $st, $data_final, $state_list, $akta_list, $department_list,
                        $branch_list, $is_branch, $is_search
                    ) {
                        $excel->sheet('Report', function ($sheet) use (
                            $ds, $de, $dp, $dp_desc, $st, $data_final, $state_list, $akta_list, $department_list,
                            $branch_list, $is_branch, $is_search
                        ) {
                            $sheet->loadView('laporan.laporanlainlain.akta.excel')
                                ->with([
                                    'ds' => $ds,
                                    'de' => $de,
                                    'dp' => $dp,
                                    'dp_desc' => $dp_desc,
                                    'st' => $st,
                                    'data_final' => $data_final,
                                    'state_list' => $state_list,
                                    'akta_list' => $akta_list,
                                    'department_list' => $department_list,
                                    'branch_list' => $branch_list,
                                    'is_branch' => $is_branch,
                                    'is_search' => $is_search,
                                ]);
                        });
                    })->export('xlsx');
                    break;
                case 'w':
                default:
                    return view('laporan.laporanlainlain.akta.index',
                        compact('ds', 'de', 'dp', 'dp_desc', 'st', 'data_final', 'state_list', 'akta_list',
                            'department_list', 'branch_list', 'is_branch', 'is_search', 'uri', 'uri_dd')
                    );
                    break;
            }
        }

        // END: Data preparation
        // Default return
        return view('laporan.laporanlainlain.akta.index',
            compact('ds', 'de', 'dp', 'st', 'data_final', 'state_list', 'akta_list', 'department_list',
                'is_branch', 'is_search')
        );
    }

    /**
     * Akta drill down report generator
     *
     * @param Request $request
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function akta1(Request $request)
    {
        // retrive data from request
        $data = $request->all();
        $is_search = count($data) > 0 ? true : false;

        /**
         * Initialization data.
         *
         * @param $ds string date_start
         * @param $de string date_end
         * @param $dp string department
         * @param $st string single states id
         * @param $br string single branch id
         * @param $ac string single act id
         * @param $g string generate
         */
        $ds = isset($data['ds']) ? Carbon::createFromFormat('d-m-Y', $data['ds'])->startOfDay()
            : Carbon::now()->startOfDay();
        $de = isset($data['de']) ? Carbon::createFromFormat('d-m-Y', $data['de'])->endOfDay()
            : Carbon::now()->endOfDay();
        $dp = isset($data['dp']) ? $data['dp'] : '';
        $dp_desc = $dp != '' ? Ref::GetDescr('315', $dp) : 'SEMUA BAHAGIAN';
        $st = isset($data['st']) ? $data['st'] : '';
        $st_desc = $st != '' ? Ref::GetDescr('17', $st) : 'SEMUA NEGERI';
        $br = isset($data['br']) ? $data['br'] : '';
        $br_desc = $br != '' ? Branch::GetBranchName($br) : 'SEMUA CAWANGAN';
        $ac = isset($data['ac']) ? $data['ac'] : '';
        $ac_desc = $br != '' ? Ref::GetDescr('713', $ac) : 'SEMUA AKTA';
        $g = isset($data['g']) ? $data['g'] : 'web';
        $uri = '/laporanlainlain/akta1?ds=' . $ds->format('d-m-Y') . '&de=' . $de->format('d-m-Y')
            . '&dp=' . $dp . '&st=' . $st . '&br=' . $br . '&ac=' . $ac . '&g=';

        // START: Data query
        $q = DB::table('case_act')
            ->join('case_info', 'case_info.ca_caseid', '=', 'case_act.ct_caseid')
            ->join('sys_brn', 'case_info.ca_brncd', '=', 'sys_brn.br_brncd')
            ->join('sys_users', 'CA_INVBY', '=', 'sys_users.id')
            ->whereBetween('ct_credt', [$ds, $de]);

        if ($dp != '') {
            $q->where('case_info.CA_DEPTCD', $dp);
        }

        if ($st != '') {
            $q->where('sys_brn.BR_STATECD', $st);
        }

        if ($br != '') {
            $q->where('sys_brn.BR_BRNCD', $br);
        }

        if ($ac != '') {
            $q->where('case_act.CT_AKTA', $ac);
        }

        $data_final = $data_raw = $q->get();
        // END: Data query

        $category_list = DB::table('sys_ref')->where('cat', 244)->pluck('descr', 'code');

        switch ($g) {
            case 'e':
                return Excel::create('Laporan Akta Aduan' . date("_Ymd_His"), function ($excel) use (
                    $data_final, $ds, $de, $dp_desc, $ac_desc, $st_desc, $br_desc, $uri, $category_list
                ) {
                    $excel->sheet('Report', function ($sheet) use (
                        $data_final, $ds, $de, $dp_desc, $ac_desc, $st_desc, $br_desc, $uri, $category_list
                    ) {
                        $sheet->loadView('laporan.laporanlainlain.akta1.excel')
                            ->with([
                                'data_final' => $data_final,
                                'ds' => $ds,
                                'de' => $de,
                                'dp_desc' => $dp_desc,
                                'ac_desc' => $ac_desc,
                                'st_desc' => $st_desc,
                                'br_desc' => $br_desc,
                                'uri' => $uri,
                                'category_list' => $category_list,
                            ]);
                    });
                })->export('xlsx');
                break;
            case 'p':
                $pdf = PDF::loadView('laporan.laporanlainlain.akta1.pdf',
                    compact('data_final', 'ds', 'de', 'dp_desc', 'ac_desc', 'st_desc', 'br_desc',
                        'uri', 'category_list')
                );
                return $pdf->stream('laporanlainlain' . date("Ymd_His") . '.pdf');
                break;
            case 'w':
            default:
                return view('laporan.laporanlainlain.akta1.index',
                    compact('data_final', 'ds', 'de', 'dp_desc', 'ac_desc', 'st_desc', 'br_desc',
                        'uri', 'category_list')
                );
                break;
        }
    }

    public function PembekalPerkhidmatan(Request $Request)
    {
        $datas = [];
        $search = $Request->get('search');
        $ListState = DB::table('sys_ref')
            ->where('cat', '1214')
            ->orderBy('sort')
            ->get();
        $CA_RCVDT_FROM = $Request->get('CA_RCVDT_FROM');
        $CA_RCVDT_TO = $Request->get('CA_RCVDT_TO');
        $option = $Request->get('option');

        if ($Request->has('search') || $Request->has('excel') || $Request->has('pdf')) {
            $query = DB::table('case_info')
                ->join('sys_brn', 'sys_brn.BR_BRNCD', '=', 'case_info.CA_BRNCD');
            if ($option == '1') {
                $query->leftJoin('sys_ref', function ($leftjoin) use ($CA_RCVDT_FROM, $CA_RCVDT_TO) {
                    $leftjoin->on('sys_ref.code', '=', 'case_info.CA_ONLINECMPL_PROVIDER')
                        ->where('case_info.CA_RCVDT', '>=', date('Y-m-d 00:00:01', strtotime($CA_RCVDT_FROM)))
                        ->where('case_info.CA_RCVDT', '<=', date('Y-m-d 23:59:59', strtotime($CA_RCVDT_TO)));
                });
            } else {
                $query->rightJoin('sys_ref', function ($rightjoin) use ($CA_RCVDT_FROM, $CA_RCVDT_TO) {
                    $rightjoin->on('sys_ref.code', '=', 'case_info.CA_ONLINECMPL_PROVIDER')
//                        ->whereYear('case_info.CA_RCVDT',date('Y'));
//                        ->whereRaw("(case_info.CA_RCVDT BETWEEN '?' AND '?')",[date('Y-m-d', strtotime($CA_RCVDT_FROM)), date('Y-m-d', strtotime($CA_RCVDT_TO))]);
                        ->where('case_info.CA_RCVDT', '>=', date('Y-m-d 00:00:01', strtotime($CA_RCVDT_FROM)))
                        ->where('case_info.CA_RCVDT', '<=', date('Y-m-d 23:59:59', strtotime($CA_RCVDT_TO)));

//                ->whereBetween('case_info.CA_RCVDT',['"'.date('Y-m-d', strtotime($CA_RCVDT_FROM)).'"', '"'.date('Y-m-d', strtotime($CA_RCVDT_TO))]);
                });
            }
            $query->where('sys_ref.cat', '=', '1091');
            $query->where([
                ['CA_CASEID', '<>', null],
                ['CA_RCVTYP', '<>', null],
                ['CA_RCVTYP', '<>', ''],
                ['CA_CMPLCAT', '<>', ''],
                ['CA_INVSTS', '!=', '10'],
            ]);
            $query->select(DB::raw("
                    sys_ref.code,sys_ref.descr,COUNT(sys_brn.BR_STATECD) AS Bilangan,
                    SUM(CASE WHEN sys_brn.BR_STATECD = '01' THEN 1 ELSE 0 END) AS 'kod_01',
                    SUM(CASE WHEN sys_brn.BR_STATECD = '02' THEN 1 ELSE 0 END) AS 'kod_02',
                    SUM(CASE WHEN sys_brn.BR_STATECD = '03' THEN 1 ELSE 0 END) AS 'kod_03',
                    SUM(CASE WHEN sys_brn.BR_STATECD = '04' THEN 1 ELSE 0 END) AS 'kod_04',
                    SUM(CASE WHEN sys_brn.BR_STATECD = '05' THEN 1 ELSE 0 END) AS 'kod_05',
                    SUM(CASE WHEN sys_brn.BR_STATECD = '06' THEN 1 ELSE 0 END) AS 'kod_06',
                    SUM(CASE WHEN sys_brn.BR_STATECD = '07' THEN 1 ELSE 0 END) AS 'kod_07',
                    SUM(CASE WHEN sys_brn.BR_STATECD = '08' THEN 1 ELSE 0 END) AS 'kod_08',
                    SUM(CASE WHEN sys_brn.BR_STATECD = '09' THEN 1 ELSE 0 END) AS 'kod_09',
                    SUM(CASE WHEN sys_brn.BR_STATECD = '10' THEN 1 ELSE 0 END) AS 'kod_10',
                    SUM(CASE WHEN sys_brn.BR_STATECD = '11' THEN 1 ELSE 0 END) AS 'kod_11',
                    SUM(CASE WHEN sys_brn.BR_STATECD = '12' THEN 1 ELSE 0 END) AS 'kod_12',
                    SUM(CASE WHEN sys_brn.BR_STATECD = '13' THEN 1 ELSE 0 END) AS 'kod_13',
                    SUM(CASE WHEN sys_brn.BR_STATECD = '14' THEN 1 ELSE 0 END) AS 'kod_14',
                    SUM(CASE WHEN sys_brn.BR_STATECD = '15' THEN 1 ELSE 0 END) AS 'kod_15',
                    SUM(CASE WHEN sys_brn.BR_STATECD = '16' THEN 1 ELSE 0 END) AS 'kod_16'
                    "));
            $query->groupBy('sys_ref.code', 'sys_ref.descr', 'sys_ref.sort');
            $query->orderBy('sys_ref.sort');
            $query = $query->get();
            $datas = $query;
//            dd($datas);
        }

        if ($Request->has('search')) {
            return view('laporan.laporanlainlain.pembekal_perkhidmatan', compact('datas', 'search', 'ListState', 'option', 'Request'));
        } elseif ($Request->has('excel')) {
            return view('laporan.laporanlainlain.pembekal_perkhidmatan_excel', compact('datas', 'search', 'ListState', 'Request'));
        } elseif ($Request->has('pdf')) {
            $pdf = PDF::loadView('laporan.laporanlainlain.pembekal_perkhidmatan_pdf', compact('datas', 'search', 'ListState', 'Request'), [], ['default_font_size' => 7, 'title' => date('Ymd_His')]);
            return $pdf->stream('LaporanPembekalPerkhidmatan_' . date("Ymd_His") . '.pdf');
        } else {
            return view('laporan.laporanlainlain.pembekal_perkhidmatan', compact('datas', 'search', 'ListState', 'option', 'Request'));
        }
    }

    public function PembekalPerkhidmatan1(Request $Request, $DateFrom, $DateTo, $ProviderCd, $StateCd)
    {
        $query = Carian::select('case_info.*')
            ->leftJoin('sys_brn', 'sys_brn.BR_BRNCD', '=', 'case_info.CA_BRNCD');

        $query->where('case_info.CA_ONLINECMPL_PROVIDER', '<>', NULL);
        $query->where('case_info.CA_RCVDT', '>=', date('Y-m-d 00:00:01', strtotime($DateFrom)));
        $query->where('case_info.CA_RCVDT', '<=', date('Y-m-d 23:59:59', strtotime($DateTo)));

        if ($ProviderCd != '0') {
            $titleprovider = Ref::GetDescr('1091', $ProviderCd);
            $query->where(['case_info.CA_ONLINECMPL_PROVIDER' => $ProviderCd]);
        } else {
            $titleprovider = 'SEMUA PEMBEKAL PERKHIDMATAN';
        }
        if ($StateCd != '0') {
            $titlestate = Ref::GetDescr('17', $StateCd);
            $query->where(['sys_brn.BR_STATECD' => $StateCd]);
        } else {
            $titlestate = 'SEMUA NEGERI';
        }

        $query = $query->get();

        if ($Request->has('excel')) {
            return view('laporan.laporanlainlain.pembekal_perkhidmatan_excel1', compact('query', 'DateFrom', 'DateTo', 'titleprovider', 'titlestate'));
        } elseif ($Request->has('pdf')) {
            $pdf = PDF::loadView('laporan.laporanlainlain.pembekal_perkhidmatan_pdf1', compact('query', 'DateFrom', 'DateTo', 'titleprovider', 'titlestate'), [], ['default_font_size' => 7, 'title' => date('Ymd_His')]);
            return $pdf->stream('LaporanPembekalPerkhidmatan_' . date("Ymd_His") . '.pdf');
        } else {
            return view('laporan.laporanlainlain.pembekal_perkhidmatan1', compact('query', 'DateFrom', 'DateTo', 'titleprovider', 'titlestate'));
        }
    }


    public function listCat()
    {

//                $listcse = DB::table('case_info')
//                    ->whereYear('CA_RCVDT',$year)
//                    ->where(['CA_DEPTCD' => $sdepart,'BR_STATECD' => $sbrn])
//                    ->get();
        return view('laporan.laporanlainlain.senaraikes', compact('mList', 'CA_RCVDT_dri', 'CA_RCVDT_lst', 'depart'));
    }

    public function getDataTable($CA_RCVDT_dri, $CA_RCVDT_lst, $depart, $mKat, $mState)
    {

        $mList = ReportLainlain::orderBy('CA_RCVDT')
            ->join('sys_brn', 'case_info.CA_BRNCD', '=', 'sys_brn.BR_BRNCD')
            ->whereBetween('CA_RCVDT', [date('Y-m-d', strtotime($CA_RCVDT_dri)), date('Y-m-d', strtotime($CA_RCVDT_lst))])
            ->where(['CA_DEPTCD' => $depart, 'CA_CMPLCAT' => $mKat, 'BR_STATECD' => $mState])
            ->get();

        $datatables = app('datatables')->of($mList)
            ->addIndexColumn();


//        dd($mList);
        return $datatables->make(true);
    }

    public function DwnloadExcel()
    {
        $writer = WriterFactory::create(Type::XLSX); // for XLSX files
        $senaraikat = ReportLainlain::SenaraiAduanByKat(); // for CSV files
        //$writer = WriterFactory::create(Type::ODS); // for ODS files
//        $writer->openToFile($filePath); // write data to a file or to a PHP stream
        $time = date('YmdHis');
        $writer->openToBrowser("Laporan{$time}.xlsx"); // stream data directly to the browser 
        dd($writer->openToBrowser("Laporan{$time}.xlsx"));
        $writer->addRow(array('Bil', 'Kategori', 'Negeri', 'Jumlah', '%')); // add a row at a time
//        $writer->addRows(""); // add multiple rows at a time

        $writer->close();

        return view('laporan.laporanlainlain.kategori', compact('senaraikat'));

    }

    /**
     * Get branch list by state id
     * @depreacted please use XrefsController.branch instead
     * @param $state_cd
     * @param int $need_placeholder
     * @return string
     */
    public function Getcawangan($state_cd, $need_placeholder = 1)
    {
        $mBList = DB::table('sys_brn')
            ->where(['BR_STATECD' => $state_cd, 'BR_STATUS' => 1])
            ->pluck('BR_BRNNM', 'BR_BRNCD');
        if ($need_placeholder == 1) {
            $mBList->prepend('-- SILA PILIH --', '');
        }
        return json_encode($mBList);
    }

    /**
     * @deprecated please use XrefsController/category
     * @param $catlist
     * @return string
     */
    public function GetCatList($catlist)
    {
        if ($catlist != '') {
            $mKategoriList = DB::table('sys_ref')
                ->where('code', 'LIKE', $catlist . '%')
                ->where('cat', '244')
                ->pluck('descr', 'code');
        } else {
            $mKategoriList = DB::table('sys_ref')
                ->where('cat', '244')
                ->pluck('descr', 'code');

        }
        return json_encode($mKategoriList);
    }

    public function CallCenter(Request $Request)
    {
        $datas = [];
        $titleyear = 'LAPORAN CALL CENTER';
        $cari = false;
        if ($Request->has('cari') || $Request->get('excel') == '1' || $Request->get('pdf') == '1') {
            $cari = true;
            $query = DB::table('case_info')
                    ->join('sys_users', 'sys_users.id', '=', 'case_info.CA_CREBY');
            $query->join('sys_user_access', function ($join) {
                $join->on('sys_user_access.user_id', '=', 'sys_users.id')
                    ->where('sys_user_access.role_code', '700');
//                    ->where(function ($query) {
//                        $query->where('sys_user_access.role_code', '700')
//                            ->orWhere('sys_user_access.role_code', '800')
//                            ->orWhere('sys_user_access.role_code', '120');
//                    });
            });
//            });
            $query->select(DB::raw("
                    sys_users.name,sys_users.id,COUNT(1) AS Total,
                    SUM(CASE WHEN MONTH(case_info.CA_RCVDT) = '01' THEN 1 ELSE 0 END) AS 'JAN',
                    SUM(CASE WHEN MONTH(case_info.CA_RCVDT) = '02' THEN 1 ELSE 0 END) AS 'FEB',
                    SUM(CASE WHEN MONTH(case_info.CA_RCVDT) = '03' THEN 1 ELSE 0 END) AS 'MAC',
                    SUM(CASE WHEN MONTH(case_info.CA_RCVDT) = '04' THEN 1 ELSE 0 END) AS 'APR',
                    SUM(CASE WHEN MONTH(case_info.CA_RCVDT) = '05' THEN 1 ELSE 0 END) AS 'MEI',
                    SUM(CASE WHEN MONTH(case_info.CA_RCVDT) = '06' THEN 1 ELSE 0 END) AS 'JUN',
                    SUM(CASE WHEN MONTH(case_info.CA_RCVDT) = '07' THEN 1 ELSE 0 END) AS 'JUL',
                    SUM(CASE WHEN MONTH(case_info.CA_RCVDT) = '08' THEN 1 ELSE 0 END) AS 'OGO',
                    SUM(CASE WHEN MONTH(case_info.CA_RCVDT) = '09' THEN 1 ELSE 0 END) AS 'SEP',
                    SUM(CASE WHEN MONTH(case_info.CA_RCVDT) = '10' THEN 1 ELSE 0 END) AS 'OKT',
                    SUM(CASE WHEN MONTH(case_info.CA_RCVDT) = '11' THEN 1 ELSE 0 END) AS 'NOV',
                    SUM(CASE WHEN MONTH(case_info.CA_RCVDT) = '12' THEN 1 ELSE 0 END) AS 'DIS'
                    "));
            if(Auth::user()->Role->role_code == '700'){
                $query->where('case_info.CA_CREBY', Auth::user()->id);
            }
            if ($Request->has('year')) {
                $titleyear = 'LAPORAN CALL CENTER BAGI TAHUN ' . $Request->get('year');
                $query->whereYear('case_info.CA_RCVDT', $Request->get('year'));
            } else {
                $titleyear = 'LAPORAN CALL CENTER';
            }
            $query->groupBy('case_info.CA_CREBY', 'sys_users.name', 'sys_users.id');
            $query = $query->get();
            $datas = $query;
        }
        
        
        if ($Request->has('excel')) {
            return view('laporan.laporanlainlain.callcenter.excel', compact('Request', 'datas', 'titleyear'));
        } elseif ($Request->has('pdf')) {
            $pdf = PDF::loadView('laporan.laporanlainlain.callcenter.pdf', compact('Request', 'datas', 'titleyear'), [], ['default_font_size' => 7, 'title' => date('Ymd_His')]);
            return $pdf->stream('LaporanPembekalPerkhidmatan_' . date("Ymd_His") . '.pdf');
        } else {
            return view('laporan.laporanlainlain.callcenter.index', compact('Request', 'datas', 'titleyear'));
        }
    }

    public function CallCenter1(Request $Request, $year, $month, $userid)
    {
        $query = Carian::select('case_info.*');
        $query->where('case_info.CA_CREBY', $userid);
        $query->whereYear('case_info.CA_RCVDT', $year);
        if ($month != '0') {
            $titlemonth = 'BULAN ' . Ref::GetDescr('206', $month);
            $query->whereMonth('case_info.CA_RCVDT', $month);
        } else {
            $titlemonth = 'SEMUA BULAN';
        }
        $query = $query->get();
        $mUser = User::find($userid);
        if ($Request->has('excel')) {
            return view('laporan.laporanlainlain.callcenter1.excel',
                compact('query', 'titlercvtyp', 'titlemonth', 'year', 'month', 'userid', 'mUser', 'Request'));
        } elseif ($Request->has('pdf')) {
            $pdf = PDF::loadView('laporan.laporanlainlain.callcenter1.pdf',
                compact('query', 'titlercvtyp', 'titlemonth', 'year', 'month', 'userid', 'mUser', 'Request'),
                [], ['title' => 'Laporan Call Center ' . date('d-m-Y h-i A')]);
            return $pdf->stream('Laporan Call Center ' . date('d-m-Y h-i A') . '.pdf');
        } else {
            return view('laporan.laporanlainlain.callcenter1.index',
                compact('query', 'titlercvtyp', 'titlemonth', 'year', 'month', 'userid', 'mUser', 'Request'));
        }
    }

    public function EzStar(Request $Request)
    {
        $datas = [];
        $titleyear = 'LAPORAN EZSTAR';
        $cari = false;

        if ($Request->has('cari') || $Request->get('excel') == '1' || $Request->get('pdf') == '1') {
            $cari = true;
            $query = DB::table('case_dtl')
                ->join('sys_users', 'sys_users.id', '=', 'case_dtl.CD_CREBY')
                ->join('sys_brn', 'sys_brn.BR_BRNCD', '=', 'sys_users.brn_cd');
            $query->select(DB::raw("
                sys_users.id,sys_users.name,sys_brn.BR_BRNNM,sys_brn.BR_STATECD,
                SUM(CASE WHEN case_dtl.CD_INVSTS = '2' THEN 1 ELSE 0 END) AS 'code2',
                SUM(CASE WHEN case_dtl.CD_INVSTS = '3' THEN 1 ELSE 0 END) AS 'code3',
                SUM(CASE WHEN case_dtl.CD_INVSTS = '4' THEN 1 ELSE 0 END) AS 'code4',
                SUM(CASE WHEN case_dtl.CD_INVSTS = '5' THEN 1 ELSE 0 END) AS 'code5',
                SUM(CASE WHEN case_dtl.CD_INVSTS = '7' THEN 1 ELSE 0 END) AS 'code7',
                SUM(CASE WHEN case_dtl.CD_INVSTS = '8' THEN 1 ELSE 0 END) AS 'code8',
                SUM(CASE WHEN case_dtl.CD_INVSTS = '9' THEN 1 ELSE 0 END) AS 'code9',
                SUM(CASE WHEN case_dtl.CD_INVSTS = '0' THEN 1 ELSE 0 END) AS 'code0',
                SUM(CASE WHEN case_dtl.CD_INVSTS = '11' THEN 1 ELSE 0 END) AS 'code11'
            "));
            $query->where('case_dtl.CD_TYPE', 'S');

            if ($Request->has('year')) {
                $titleyear = 'LAPORAN EZSTAR BAGI TAHUN ' . $Request->get('year');
                $query->whereYear('case_dtl.CD_CREDT', $Request->get('year'));
            } else {
                $titleyear = 'LAPORAN EZSTAR';
            }
            if ($Request->has('month')) {
                $titlemonth = 'BULAN ' . Ref::GetDescr('206', $Request->get('month'));
                $query->whereMonth('case_dtl.CD_CREDT', $Request->get('month'));
            } else {
                $titlemonth = 'SEMUA BULAN';
            }
            if ($Request->has('state')) {
                $titlestate = 'NEGERI ' . Ref::GetDescr('17', $Request->get('state'));
                $query->where('sys_brn.BR_STATECD', $Request->get('state'));
            } else {
                $titlestate = 'SEMUA NEGERI';
            }
            if ($Request->has('brn')) {
                $titlebrn = 'CAWANGAN ' . Branch::GetBranchName($Request->get('brn'));
                $query->where('sys_brn.BR_BRNCD', $Request->get('brn'));
            } else {
                $titlebrn = 'SEMUA CAWANGAN';
            }

//            $query->groupBy('case_dtl.CD_CREBY', 'sys_users.name');
            $query->groupBy('case_dtl.CD_CREBY', 'sys_users.name', 'sys_users.id', 'sys_brn.BR_BRNNM', 'sys_brn.BR_STATECD');
            $query = $query->get();
            $datas = $query;
//            dd($datas);
        }

        if ($Request->has('excel')) {
            return view('laporan.laporanlainlain.ezstar.excel', compact('Request', 'datas', 'titleyear', 'titlemonth', 'titlestate', 'titlebrn'));
        } elseif ($Request->has('pdf')) {
            $pdf = PDF::loadView('laporan.laporanlainlain.ezstar.pdf', compact('Request', 'datas', 'titleyear', 'titlemonth', 'titlestate', 'titlebrn'), [], ['default_font_size' => 7, 'title' => date('Ymd_His'), 'orientation' => 'L', 'format' => 'A4-L']);
            return $pdf->stream('LaporanEzStar_' . date("Ymd_His") . '.pdf');
        } else {
            return view('laporan.laporanlainlain.ezstar.index', compact('Request', 'datas', 'titleyear', 'titlemonth', 'titlestate', 'titlebrn'));
        }
    }

    public function EzStar1(Request $Request, $year, $month, $state, $brn_cd, $userid, $status)
    {
        $query = AdminCaseDetail::select('case_info.*')
            ->join('case_info', 'case_info.CA_CASEID', '=', 'case_dtl.CD_CASEID')
            ->join('sys_users', 'sys_users.id', '=', 'case_dtl.CD_CREBY')
            ->join('sys_brn', 'sys_brn.BR_BRNCD', '=', 'sys_users.brn_cd');

        if ($year != '0') {
            $titleyear = 'LAPORAN EZSTAR BAGI TAHUN ' . $year;
            $query->whereYear('case_dtl.CD_CREDT', $year);
        } else {
            $titleyear = 'LAPORAN EZSTAR';
        }

        if ($month != '0') {
            $titlemonth = 'BULAN ' . Ref::GetDescr('206', $month);
            $query->whereMonth('case_dtl.CD_CREDT', $month);
        } else {
            $titlemonth = 'SEMUA BULAN';
        }

        if ($state != '0') {
            $titlestate = 'NEGERI ' . Ref::GetDescr('17', $state);
            $query->where('sys_brn.BR_STATECD', $state);
        } else {
            $user = User::getDetails($userid);
            $titlestate = 'NEGERI ' . Ref::GetDescr('17', $user->state_cd);
//            $titlestate = 'SEMUA NEGERI';
        }

        if ($brn_cd != '0') {
            $titlebrn = 'CAWANGAN ' . Branch::GetBranchName($brn_cd);
            $query->where('sys_brn.BR_BRNCD', $brn_cd);
        } else {
            $brn = User::getDetails($userid);
            $titlebrn = 'CAWANGAN ' . Branch::GetBranchName($brn->brn_cd);
//            $titlebrn = 'SEMUA CAWANGAN';
        }

        if ($status != '99') {
            $titlestatus = 'STATUS ' . Ref::GetDescr('292', $status);
            $query->where('case_dtl.CD_INVSTS', '=', $status);
        } else {
            $titlestatus = 'SEMUA STATUS';
        }

        $name = User::getDetails($userid);
        $titlename = $name->name;

        $query->where('case_dtl.CD_CREBY', '=', $userid);
        $query->where('case_dtl.CD_TYPE', 'S');
        $query = $query->get();
        // dd($query);

        if ($Request->get('excel') == '1') {
            return view('laporan.laporanlainlain.ezstar1.excel', compact('query', 'titleyear', 'titlemonth', 'titlestate', 'titlebrn', 'titlestatus', 'titlename'));
        } elseif ($Request->get('pdf') == '1') {
            $pdf = PDF::loadView('laporan.laporanlainlain.ezstar1.pdf', compact('query', 'titleyear', 'titlemonth', 'titlestate', 'titlebrn', 'titlestatus', 'titlename'), [], ['default_font_size' => 7, 'title' => date('Ymd_His')]);
            return $pdf->stream('LaporanEzStar_' . date("Ymd_His") . '.pdf');
        } else {
            return view('laporan.laporanlainlain.ezstar1.index', compact('query', 'titleyear', 'titlemonth', 'titlestate', 'titlebrn', 'titlestatus', 'titlename'));
        }
    }

    public function Rating(Request $Request)
    {
        $datas = [];
        $titleyear = 'LAPORAN RATING';
        $cari = false;
        $option = $Request->get('RA_TYP');

        if ($Request->has('cari') || $Request->get('excel') == '1' || $Request->get('pdf') == '1') {
            $cari = true;
            $type = $Request->RA_TYP;
            if ($type == 1) {
                $query = DB::table('rating')
                    ->join('case_info', 'case_info.CA_CASEID', '=', 'rating.caseid')
                    ->join('sys_brn', 'sys_brn.BR_BRNCD', '=', 'case_info.CA_BRNCD')
                    ->whereNotNull('caseid')
                    ->whereNotNull('rate');

                $column = 'caseid';
            } else {
                $query = DB::table('rating')
                    ->join('ask_info', 'ask_info.AS_ASKID', '=', 'rating.askid')
                    ->whereNotNull('askid')
                    ->whereNotNull('rate');

                $column = 'askid';
            }

            $query->select(DB::raw('
                rate,COUNT(' . $column . ') AS Total,
                SUM(CASE WHEN MONTH(rating.created_at) = "01" THEN 1 ELSE 0 END) AS jan,
                SUM(CASE WHEN MONTH(rating.created_at) = "02" THEN 1 ELSE 0 END) AS feb,
                SUM(CASE WHEN MONTH(rating.created_at) = "03" THEN 1 ELSE 0 END) AS mac,
                SUM(CASE WHEN MONTH(rating.created_at) = "04" THEN 1 ELSE 0 END) AS apr,
                SUM(CASE WHEN MONTH(rating.created_at) = "05" THEN 1 ELSE 0 END) AS mei,
                SUM(CASE WHEN MONTH(rating.created_at) = "06" THEN 1 ELSE 0 END) AS jun,
                SUM(CASE WHEN MONTH(rating.created_at) = "07" THEN 1 ELSE 0 END) AS jul,
                SUM(CASE WHEN MONTH(rating.created_at) = "08" THEN 1 ELSE 0 END) AS ogo,
                SUM(CASE WHEN MONTH(rating.created_at) = "09" THEN 1 ELSE 0 END) AS sep,
                SUM(CASE WHEN MONTH(rating.created_at) = "10" THEN 1 ELSE 0 END) AS okt,
                SUM(CASE WHEN MONTH(rating.created_at) = "11" THEN 1 ELSE 0 END) AS nov,
                SUM(CASE WHEN MONTH(rating.created_at) = "12" THEN 1 ELSE 0 END) AS dis
            '));

            if ($Request->has('year')) {
                $titleyear = 'LAPORAN RATING BAGI TAHUN ' . $Request->get('year');
                $query->whereYear('rating.created_at', $Request->get('year'));
            } else {
                $titleyear = 'LAPORAN RATING';
            }

            if ($column == 'caseid') {
                if ($Request->has('state')) {
                    $titlestate = 'NEGERI ' . Ref::GetDescr('17', $Request->get('state'));
                    $query->where('sys_brn.BR_STATECD', $Request->get('state'));
                } else {
                    $titlestate = 'SEMUA NEGERI';
                }

                if ($Request->has('brn')) {
                    $titlebrn = 'CAWANGAN ' . Branch::GetBranchName($Request->get('brn'));
                    $query->where('case_info.CA_BRNCD', $Request->get('brn'));
                } else {
                    $titlebrn = 'SEMUA CAWANGAN';
                }
            }

            $query->groupBy('rating.rate');
            // $query = $query->toSql();
            $query = $query->get();
            $datas = $query;
            // dd($datas);
        }

        if ($Request->has('excel')) {
            return view('laporan.laporanlainlain.rating.excel', compact('Request', 'datas', 'titleyear', 'titlestate', 'titlebrn'));
        } elseif ($Request->has('pdf')) {
            $pdf = PDF::loadView('laporan.laporanlainlain.rating.pdf', compact('Request', 'datas', 'titleyear', 'titlestate', 'titlebrn'), [], ['default_font_size' => 7, 'title' => date('Ymd_His')]);
            return $pdf->stream('LaporanRating_' . date("Ymd_His") . '.pdf');
        } else {
            return view('laporan.laporanlainlain.rating.index', compact('Request', 'option', 'datas', 'titleyear', 'titlestate', 'titlebrn'));
        }
    }

    public function Rating1(Request $Request, $year, $month, $state, $brn, $type, $rate)
    {
        if ($type == 1) // Aduan
        {
            $query = Rating::select('case_info.*')
                ->join('case_info', 'case_info.CA_CASEID', '=', 'rating.caseid')
                ->join('sys_brn', 'sys_brn.BR_BRNCD', '=', 'case_info.CA_BRNCD');
        } else // Pertanyaan / Cadangan
        {
            $query = Rating::select('ask_info.*')
                ->join('ask_info', 'ask_info.AS_ASKID', '=', 'rating.askid');
        }
        $query->whereYear('rating.created_at', $year);
        $query->where('rating.rate', '=', $rate);

        if ($type == 1) {
            if ($state != '0') {
                $titlestate = 'NEGERI ' . Ref::GetDescr('17', $state);
                $query->where('sys_brn.BR_STATECD', $state);
            } else {
                $titlestate = 'SEMUA NEGERI';
            }
            if ($brn != '0') {
                $titlebrn = 'CAWANGAN ' . Branch::GetBranchName($brn);
                $query->where('sys_brn.BR_BRNCD', $brn);
            } else {
                $titlebrn = 'SEMUA CAWANGAN';
            }
        }
        if ($month != '0') {
            $titlemonth = 'BULAN ' . Ref::GetDescr('206', $month);
            $query->whereMonth('rating.created_at', $month);
        } else {
            $titlemonth = 'SEMUA BULAN';
        }
        $query = $query->get();

        if ($type == 1) {
            if ($Request->get('excel') == '1') {
                return view('laporan.laporanlainlain.rating1.excel', compact('query', 'year', 'rate', 'titlemonth', 'titlestate', 'titlebrn'));
            } elseif ($Request->get('pdf') == '1') {
                $pdf = PDF::loadView('laporan.laporanlainlain.rating1.pdf', compact('query', 'year', 'rate', 'titlemonth', 'titlestate', 'titlebrn'), [], ['default_font_size' => 7, 'title' => date('Ymd_His')]);
                return $pdf->stream('LaporanRating_' . date("Ymd_His") . '.pdf');
            } else {
                return view('laporan.laporanlainlain.rating1.index', compact('query', 'year', 'rate', 'titlemonth', 'titlestate', 'titlebrn'));
            }
        } else {
            if ($Request->get('excel') == '1') {
                return view('laporan.laporanlainlain.rating2.excel', compact('query', 'year', 'rate', 'titlemonth'));
            } elseif ($Request->get('pdf') == '1') {
                $pdf = PDF::loadView('laporan.laporanlainlain.rating2.pdf', compact('query', 'year', 'rate', 'titlemonth'), [], ['default_font_size' => 7, 'title' => date('Ymd_His')]);
                return $pdf->stream('LaporanRating_' . date("Ymd_His") . '.pdf');
            } else {
                return view('laporan.laporanlainlain.rating2.index', compact('query', 'year', 'rate', 'titlemonth'));
            }
        }
    }
    
    public function AduanMenghasilkanKes(Request $Request)
    {
        $data = $Request->all();
        // dump($data);
        $search = count($data) > 0 ? true : false;

        // initialize data
        $user = Auth::user();
        // $generate = isset($data['gen']) ? $data['gen'] : 'web';
        $year = isset($Request->year) ? $Request->year : '';
        
        $dataTemplate = [
            'diterima' => 0,
            'hasil' => 0,
            'disiasatsas' => 0,
            'disiasatlain' => 0,
        ];
        $dataFinal = [];
        $dataCounter = $dataTemplate;

        // cara penerimaan selain SAS

        $ref1249 = \App\Ref::select('code')
            ->where('cat', '1249')
            ->get()->toArray();

        $refCategory = DB::table('sys_ref')
            ->where('cat', '259')
            ->whereNotIn('code', $ref1249)
            ->get()
            ->pluck('descr', 'code');
        
        if ($Request->has('cari') || $Request->get('excel') == '1' || $Request->has('pdf')) {
            // query data
            $q = DB::table('case_info')
                ->select(DB::raw('sys_ref.code, '
                    . 'SUM(CASE WHEN CA_CASEID IS NOT NULL AND CA_INVSTS != 10 THEN 1 ELSE 0 END) AS DITERIMA, '
                    . 'SUM(CASE WHEN CA_CASEID = CT_CASEID THEN 1 ELSE 0 END) AS HASIL, '
                    . 'SUM(CASE WHEN role_code = 160 AND CA_CASEID = CT_CASEID THEN 1 ELSE 0 END) AS DISIASATSAS, '
                    . 'SUM(CASE WHEN role_code != 160 AND CA_CASEID = CT_CASEID THEN 1 ELSE 0 END) AS DISIASATLAIN ' ))
                ->leftJoin('sys_ref', 'sys_ref.code', '=', 'case_info.CA_RCVTYP')
                ->leftJoin(DB::raw('(SELECT CT_CASEID, MIN(CT_CASEID) FROM case_act GROUP BY CT_CASEID) a'), 'a.CT_CASEID', '=', 'case_info.CA_CASEID')
                ->leftJoin('sys_user_access', 'sys_user_access.user_id', '=', 'case_info.CA_INVBY')
                ->where('sys_ref.cat', '259')
                ->whereNotIn('sys_ref.code', $ref1249)
                ->whereYear('case_info.CA_RCVDT', $year);

            $dataRaw = $q->groupBy('sys_ref.code')->get();

            foreach ($refCategory as $key => $datum) {
                $dataFinal[$key] = $dataTemplate;
            }
            
            // populate data to dataFinal & dataCounter
            foreach ($dataRaw as $datum) {
                $dataFinal[$datum->code] = [
                    'diterima' => $datum->DITERIMA,
                    'hasil' => $datum->HASIL,
                    'disiasatsas' => $datum->DISIASATSAS,
                    'disiasatlain' => $datum->DISIASATLAIN,
                ];
                $dataCounter['diterima'] += $datum->DITERIMA;
                $dataCounter['hasil'] += $datum->HASIL;
                $dataCounter['disiasatsas'] += $datum->DISIASATSAS;
                $dataCounter['disiasatlain'] += $datum->DISIASATLAIN;
            }
        }

        if ($Request->has('excel')) {
            return view('laporan.laporanlainlain.aduanmenghasilkankes.menghasilkan_kes_excel', compact('Request', 'search', 'year', 'dataFinal', 'dataCounter', 'refCategory'));
        } else if ($Request->has('pdf')) {
            $pdf = PDF::loadView('laporan.laporanlainlain.aduanmenghasilkankes.menghasilkan_kes_pdf', compact('Request', 'search', 'year', 'dataFinal', 'dataCounter', 'refCategory'), [], ['default_font_size' => 7, 'title' => date('Ymd_His')]);
            return $pdf->stream('LaporanAduanMenghasilkanKes_'.date("Ymd_His").'.pdf');
        } else {
            return view('laporan.laporanlainlain.aduanmenghasilkankes.menghasilkan_kes', compact('Request', 'search', 'year', 'dataFinal', 'dataCounter', 'refCategory'));
        }        
    }
    
    public function AduanMenghasilkanKes1(Request $Request,$year,$rcvtyp,$column)
    {
        $ref1249 = \App\Ref::select('code')
            ->where('cat', '1249')
            ->get()->toArray();
        
        $query = Carian::select('case_info.*')
            ->leftJoin('sys_ref', 'sys_ref.code', '=', 'case_info.CA_RCVTYP')
            ->leftJoin(DB::raw('(SELECT CT_CASEID, MIN(CT_CASEID) FROM case_act GROUP BY CT_CASEID) a'), 'a.CT_CASEID', '=', 'case_info.CA_CASEID')
            ->leftJoin('sys_user_access', 'sys_user_access.user_id', '=', 'case_info.CA_INVBY')
            ->where('sys_ref.cat', '259')
            ->whereNotIn('sys_ref.code', $ref1249)
            ->whereYear('case_info.CA_RCVDT', $year);

        if ($rcvtyp && $column == 1) {
            if ($rcvtyp != 'total') {
                $query->where('sys_ref.code', $rcvtyp);
            }
            if ($column == 1) {
                $query->whereNotNull('CA_CASEID');
                $query->where('CA_INVSTS','<>','10');
            }
        }
        elseif ($rcvtyp && $column == 2) {
            if ($rcvtyp != 'total') {
                $query->where('sys_ref.code', $rcvtyp);
            }
            if ($column == 2) {
                $query->whereRaw('CA_CASEID = CT_CASEID');
            }
        }
        elseif ($rcvtyp && $column == 3) {
            if ($rcvtyp != 'total') {
                $query->where('sys_ref.code', $rcvtyp);
            }
            if ($column == 3) {
                $query->where('role_code','=','160');
                $query->whereRaw('CA_CASEID = CT_CASEID');
            }
        }
        elseif ($rcvtyp && $column == 4) {
            if ($rcvtyp != 'total') {
                $query->where('sys_ref.code', $rcvtyp);
            }
            if ($column == 4) {
                $query->where('role_code','<>','160');
                $query->whereRaw('CA_CASEID = CT_CASEID');
            }
        }

        $query = $query->get();

        if($Request->has('excel')) {
            return view('laporan.laporanlainlain.aduanmenghasilkankes1.menghasilkan_kes_excel1', 
                compact('Request','query','year','rcvtyp','column')
            );
        }elseif($Request->has('pdf')) {
            $pdf = PDF::loadView('laporan.laporanlainlain.aduanmenghasilkankes1.menghasilkan_kes_pdf1', 
                compact('Request','query','year','rcvtyp','column'), [], ['default_font_size' => 7, 'title' => date('Ymd_His')]
            );
            return $pdf->stream('LaporanSasMenghasilkanKes_'.date("Ymd_His").'.pdf');
        }else{
            return view('laporan.laporanlainlain.aduanmenghasilkankes1.menghasilkan_kes1', 
                compact('Request','query','year','rcvtyp','column')
            );
        }
    }
}
