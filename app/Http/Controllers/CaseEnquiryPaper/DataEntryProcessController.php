<?php

namespace App\Http\Controllers\CaseEnquiryPaper;

use App\Http\Controllers\Controller;
use App\Models\Cases\CaseEnquiryPaper;
use App\Models\EnquiryPaper\EnquiryPaperCaseDetail;
use App\Ref;
use App\Repositories\EnquiryPaper\EnquiryPaperRefRepository;
use Auth;
use Illuminate\Http\Request;

/**
 * Daftar Proses Fail Kes EP
 *
 * Class DataEntryProcessController
 * @package App\Http\Controllers\CaseEnquiryPaper
 */
class DataEntryProcessController extends Controller
{
    /**
     * Instantiate a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('auth');
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        //
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
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        //
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
    public function edit($id, $process)
    {
        $caseEp = CaseEnquiryPaper::where('id', $id)->first();
        if (empty($caseEp)
            || empty($caseEp->case_status_code)
            || !in_array($caseEp->case_status_code, [1])
        ) {
            return redirect()->route('caseenquirypaper.dataentries.index')
                ->with('alert', 'Maklumat Fail Kes EP tidak dijumpai.');
        }
        $refStates = Ref::where(['cat' => '17', 'status' => '1'])
            ->orderBy('sort', 'asc')->pluck('descr', 'code')->toArray();
        $refActs = Ref::where(['cat' => '713', 'status' => '1'])
            ->orderBy('sort', 'asc')->pluck('descr', 'code')->toArray();
        $collections = self::parameters();
        $collections['tarafKerakyatan'] = EnquiryPaperRefRepository::senaraiTarafKerakyatan();
        switch ($process) {
            case 2:
            // case 3:
                return view('caseenquirypaper.dataentryprocess.edit',
                    compact('refStates', 'refActs', 'caseEp', 'process', 'collections')
                );
                break;
            default:
                return redirect()->route('caseenquirypaper.dataentries.index')
                    ->with('alert', 'Maklumat Fail Kes EP tidak dijumpai.');
                break;
        }
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id, $process)
    {
        $input = $request->all();

        $user = Auth::user();

        $caseEnquiryPaper = CaseEnquiryPaper::where('id', $id)->first();

        if (empty($caseEnquiryPaper)
            || empty($caseEnquiryPaper->case_status_code)
            || !in_array($caseEnquiryPaper->case_status_code, [1])
        ) {
            return redirect()->route('caseenquirypaper.dataentries.index')
                ->with('alert', 'Maklumat Fail Kes EP tidak dijumpai.');
        }

        $caseEnquiryPaper->fill($request->all());

        if (in_array($caseEnquiryPaper->case_status_code, [1])
            && in_array($caseEnquiryPaper->asas_tindakan, ['ADUAN'])
        ) {
            $caseEnquiryPaper->case_status_code = 2;

            /** @var EnquiryPaperCaseDetail $enquiryPaperCaseDetail */
            $enquiryPaperCaseDetail = new EnquiryPaperCaseDetail;
            $enquiryPaperCaseDetail->fill($input);
            $enquiryPaperCaseDetail->case_number = $caseEnquiryPaper->no_kes;
            $enquiryPaperCaseDetail->case_status_code = $caseEnquiryPaper->case_status_code;
            $enquiryPaperCaseDetail->is_current_status = 1;
            if (!empty($user)) {
                $enquiryPaperCaseDetail->action_from_user_id = $user->id ? $user->id : null;
                $enquiryPaperCaseDetail->action_from_branch_code = $user->brn_cd ? $user->brn_cd : null;
            }
            $enquiryPaperCaseDetail->save();
        }

        $caseEnquiryPaper->save();

        switch ($caseEnquiryPaper->case_status_code) {
            case 1:
                return redirect(route('enquirypaper.dataentry.loots.index', [$caseEnquiryPaper->id]))
                    ->with('success', 'Maklumat Fail Kes EP telah <b>Berjaya</b> disimpan.');
            case 2:
                return redirect()->route('caseenquirypaper.dataentries.index')
                    ->with('success', 'Maklumat Fail Kes EP telah berjaya diterima.');
                break;
            default:
                return redirect()->route('caseenquirypaper.dataentries.index')
                    ->with('alert', 'Maklumat Fail Kes EP tidak dijumpai.');
                break;
        }
        // return redirect()->route('caseenquirypaper.dataentries.index')
        //     ->with('success', 'Maklumat Fail Kes EP telah berjaya disimpan.');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
    }

    public function parameters()
    {
        $collections['jenamaPremis'] = [
            'BHP' => 'BHP',
            'CALTEX' => 'CALTEX',
            'PETRON' => 'PETRON',
            'PETRONAS' => 'PETRONAS',
            'SHELL' => 'SHELL',
            'GERAI' => 'GERAI',
            'KEDAI MAKAN' => 'KEDAI MAKAN',
            'KEDAI RUNCIT' => 'KEDAI RUNCIT',
            'PASAR' => 'PASAR',
            'PASAR BASAH' => 'PASAR BASAH',
            'RESTORAN' => 'RESTORAN',
            'RUNCIT' => 'RUNCIT',
            'TETAP' => 'TETAP',
            'LAIN-LAIN' => 'LAIN-LAIN',
        ];
        $collections['kawasan'] = [
            'B' => 'BANDAR',
            'LB' => 'LUAR BANDAR',
        ];
        $collections['jenisPerniagaan'] = [
            'BENGKEL' => 'BENGKEL',
            'BORONG' => 'BORONG',
            'BORONG & RUNCIT' => 'BORONG & RUNCIT',
            'RUNCIT' => 'RUNCIT',
            'LAIN-LAIN' => 'LAIN-LAIN',
        ];
        $collections['kategoriPremis'] = [
            'GERAI MAKAN' => 'GERAI MAKAN',
            'PREMIS TERBUKA' => 'PREMIS TERBUKA',
            'PREMIS TETAP' => 'PREMIS TETAP',
            'RUNCIT' => 'RUNCIT',
            'LAIN-LAIN' => 'LAIN-LAIN',
        ];
        $collections['jenisPremis'] = [
            'AKSESORI' => 'AKSESORI',
            'ARKED NIAGA' => 'ARKED NIAGA',
            'BADAN KERAJAAN' => 'BADAN KERAJAAN',
            'BALAI POLIS' => 'BALAI POLIS',
            'BANK' => 'BANK',
            'BARANGAN' => 'BARANGAN',
            'LAIN-LAIN' => 'LAIN-LAIN',
        ];
        $collections['statusOkt'] = [
            'IMIGRESEN' => 'IMIGRESEN',
            'JAMINAN POLIS' => 'JAMINAN POLIS',
            'REMAN' => 'REMAN',
            'RTT' => 'RTT',
        ];
        $collections['jantina'] = [
            'L' => 'LELAKI',
            'P' => 'PEREMPUAN',
        ];
        $collections['tarafKerakyatan'] = [
            'W' => 'WARGANEGARA',
            'WA' => 'WARGA ASING',
            'PT' => 'PERMASTAUTIN TETAP',
        ];
        $collections['catatanKerakyatan'] = Ref::where(['cat' => '334', 'status' => '1'])
            ->orderBy('sort', 'asc')->pluck('descr', 'code')->toArray();
        $collections['kategoriBarangRampasan'] = [
            'AKSESORI' => 'AKSESORI',
            'KASUT' => 'KASUT',
            'KENDERAAN' => 'KENDERAAN',
            'MAKANAN' => 'MAKANAN',
            'PAKAIAN' => 'PAKAIAN',
        ];
        $collections['jenisBarangRampasan'] = [
            'BAJU' => 'BAJU',
            'KASUT SUKAN' => 'KASUT SUKAN',
            'SELIPAR' => 'SELIPAR',
            'SELUAR' => 'SELUAR',
            'STOKIN' => 'STOKIN',
        ];
        $collections['jenisBarangRampasan2'] = [
            'BAJU' => 'BAJU',
            'KASUT SUKAN' => 'KASUT SUKAN',
            'SELIPAR' => 'SELIPAR',
            'SELUAR' => 'SELUAR',
            'STOKIN' => 'STOKIN',
        ];
        $collections['karyaTempatanAntarabangsa'] = [
            '1 KG' => '1 KG',
            '1 LITER' => '1 LITER',
            '14 KG' => '14 KG',
            'ACROBAT X PRO' => 'ACROBAT X PRO',
            'AUTOCAD 2012' => 'AUTOCAD 2012',
            'AUTOCAD 2013' => 'AUTOCAD 2013',
        ];
        $collections['unit'] = [
            'KG' => 'KG',
            'LITER' => 'LITER',
            'TANGKI' => 'TANGKI',
            'TONG' => 'TONG',
        ];
        return $collections;
    }
}
