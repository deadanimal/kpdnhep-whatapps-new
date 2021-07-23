<?php

namespace App\Http\Controllers\Aduan;

use App\Aduan\DataEntry;
use App\Aduan\DataEntryDetail;
use App\Http\Controllers\Controller;
use App\Repositories\ConsumerComplaint\CaseInfoRepository;
use App\Repositories\RunnerRepository;
use App\User;
use Auth;
use DB;
use Illuminate\Http\Request;

class DataEntryController extends Controller
{
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
        return view('aduan.dataentry.create');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        if($request->CA_CMPLCAT != 'BPGK 19') {
            $request->merge([
                'CA_ONLINECMPL_PROVIDER' => NULL,
                'CA_ONLINECMPL_URL' => NULL,
                'CA_ONLINECMPL_ACCNO' => NULL,
                'CA_ONLINECMPL_IND' => NULL,
                'CA_ONLINECMPL_CASENO' => NULL,
                'CA_ONLINEADD_IND' => NULL
            ]);
            $this->validate($request, [
                'CA_RCVDT' => 'required',
                'CA_INVBY' => 'required',
                'CA_RCVTYP' => 'required',
                'CA_INVSTS' => 'required',
                'CA_MAGNCD' => 'required_if:CA_INVSTS,4',
                'CA_DOCNO' => 'required',
                'CA_EMAIL' => 'required_without_all:CA_MOBILENO,CA_TELNO',
                'CA_MOBILENO' => 'required_without_all:CA_TELNO,CA_EMAIL',
                'CA_TELNO' => 'required_without_all:CA_MOBILENO,CA_EMAIL',
                'CA_NAME' => 'required',
                'CA_STATECD' => 'required',
                'CA_DISTCD' => 'required',
                'CA_COUNTRYCD' => 'required_if:CA_NATCD,0',
                'CA_CMPLCAT' => 'required',
                'CA_CMPLCD' => 'required',
                'CA_ONLINECMPL_AMOUNT' => 'required',
                'CA_CMPLKEYWORD' => 'required_if:CA_CMPLCAT,BPGK 01|required_if:CA_CMPLCAT,BPGK 03',
                'CA_AGAINST_PREMISE' => 'required',
                'CA_TTPMTYP' => 'required_if:CA_CMPLCAT,BPGK 08',
                'CA_TTPMNO' => 'required_if:CA_CMPLCAT,BPGK 08',
                'CA_AGAINSTNM' => 'required',
                'CA_AGAINSTADD' => 'required',
                'CA_AGAINST_STATECD' => 'required',
                'CA_AGAINST_DISTCD' => 'required',
                'CA_SUMMARY' => 'required',
                'CA_RESULT' => 'required',
                'CA_ANSWER' => 'required',
            ],
            [
                'CA_RCVDT.required' => 'Ruangan Tarikh Terima Aduan diperlukan',
                'CA_INVBY.required' => 'Ruangan Pegawai Penyiasat/Serbuan diperlukan',
                'CA_RCVTYP.required' => 'Ruangan Cara Penerimaan diperlukan.',
                'CA_INVSTS.required' => 'Ruangan Status Aduan diperlukan',
                'CA_MAGNCD.required_if' => 'Ruangan Agensi diperlukan.',
                'CA_DOCNO.required' => 'Ruangan No. Kad Pengenalan/Pasport diperlukan.',
                'CA_EMAIL.required_without_all' => 'Sila isikan salah satu maklumat berikut: Emel / No. Telefon (Bimbit) / No. Telefon (Rumah)',
                'CA_MOBILENO.required_without_all' => 'Sila isikan salah satu maklumat berikut: Emel / No. Telefon (Bimbit) / No. Telefon (Rumah)',
                'CA_TELNO.required_without_all' => 'Sila isikan salah satu maklumat berikut: Emel / No. Telefon (Bimbit) / No. Telefon (Rumah)',
                'CA_NAME.required' => 'Ruangan Nama diperlukan.',
                'CA_STATECD.required' => 'Ruangan Negeri diperlukan.',
                'CA_DISTCD.required' => 'Ruangan Daerah diperlukan.',
                'CA_COUNTRYCD.required_if' => 'Ruangan Negara Asal diperlukan.',
                'CA_CMPLCAT.required' => 'Ruangan Kategori diperlukan.',
                'CA_CMPLCD.required' => 'Ruangan Subkategori diperlukan.',
                'CA_ONLINECMPL_AMOUNT.required' => 'Ruangan Jumlah Kerugian (RM) diperlukan.',
                'CA_CMPLKEYWORD.required_if' => 'Ruangan Jenis Barangan diperlukan.',
                'CA_AGAINST_PREMISE.required' => 'Ruangan Jenis Premis diperlukan.',
                'CA_TTPMTYP.required_if' => 'Ruangan Penuntut/Penentang diperlukan.',
                'CA_TTPMNO.required_if' => 'Ruangan No. TTPM diperlukan.',
                'CA_AGAINSTNM.required' => 'Ruangan Nama (Syarikat/Premis/Penjual) diperlukan.',
                'CA_AGAINSTADD.required' => 'Ruangan Alamat diperlukan.',
                'CA_AGAINST_STATECD.required' => 'Ruangan Negeri diperlukan.',
                'CA_AGAINST_DISTCD.required' => 'Ruangan Daerah diperlukan.',
                'CA_SUMMARY.required' => 'Ruangan Keterangan Aduan diperlukan.',
                'CA_RESULT.required' => 'Ruangan Hasil Siasatan diperlukan',
                'CA_ANSWER.required' => 'Ruangan Jawapan Kepada Pengadu diperlukan',
            ]);
        }
        $this->validate($request, [
            'CA_RCVDT' => 'required',
            'CA_INVBY' => 'required',
            'CA_RCVTYP' => 'required',
            'CA_INVSTS' => 'required',
            'CA_MAGNCD' => 'required_if:CA_INVSTS,4',
            'CA_DOCNO' => 'required',
            'CA_EMAIL' => 'required_without_all:CA_MOBILENO,CA_TELNO',
            'CA_MOBILENO' => 'required_without_all:CA_TELNO,CA_EMAIL',
            'CA_TELNO' => 'required_without_all:CA_MOBILENO,CA_EMAIL',
            'CA_NAME' => 'required',
            'CA_CMPLCAT' => 'required',
            'CA_CMPLCD' => 'required',
            'CA_NATCD' => 'required',
            'CA_STATECD' => 'required',
            'CA_DISTCD' => 'required',
            'CA_COUNTRYCD' => 'required_if:CA_NATCD,0',
            'CA_CMPLKEYWORD' => 'required_if:CA_CMPLCAT,BPGK 01|required_if:CA_CMPLCAT,BPGK 03',
            'CA_ONLINECMPL_PROVIDER' => 'required_if:CA_CMPLCAT,BPGK 19',
            'CA_ONLINECMPL_URL' => 'required_if:CA_ONLINECMPL_PROVIDER,999',
            'CA_ONLINECMPL_AMOUNT' => 'required',
            'CA_ONLINECMPL_PYMNTTYP' => 'required_if:CA_CMPLCAT,BPGK 19',
            'CA_ONLINECMPL_BANKCD' => 'required_unless:CA_ONLINECMPL_PYMNTTYP,COD,CA_ONLINECMPL_PYMNTTYP,,CA_CMPLCAT,BPGK 19',
            'CA_ONLINECMPL_ACCNO' => 'required_unless:CA_ONLINECMPL_PYMNTTYP,COD,CA_ONLINECMPL_PYMNTTYP,,CA_CMPLCAT,BPGK 19',
            'CA_AGAINST_PREMISE' => 'required_unless:CA_CMPLCAT,BPGK 19',
            'CA_AGAINSTNM' => 'required',
            'CA_AGAINSTADD' => 'required_if:CA_ONLINEADD_IND,on|required_unless:CA_CMPLCAT,BPGK 19',
            'CA_AGAINST_STATECD' => 'required_if:CA_ONLINEADD_IND,on|required_unless:CA_CMPLCAT,BPGK 19',
            'CA_AGAINST_DISTCD' => 'required_if:CA_ONLINEADD_IND,on|required_unless:CA_CMPLCAT,BPGK 19',
            'CA_SUMMARY' => 'required',
            'CA_RESULT' => 'required',
            'CA_ANSWER' => 'required',
        ],
        [
            'CA_RCVDT.required' => 'Ruangan Tarikh Terima Aduan diperlukan',
            'CA_INVBY.required' => 'Ruangan Pegawai Penyiasat/Serbuan diperlukan',
            'CA_RCVTYP.required' => 'Ruangan Cara Penerimaan diperlukan.',
            'CA_INVSTS.required' => 'Ruangan Status Aduan diperlukan.',
            'CA_MAGNCD.required_if' => 'Ruangan Agensi diperlukan.',
            'CA_DOCNO.required' => 'Ruangan No. Kad Pengenalan/Pasport diperlukan.',
            'CA_EMAIL.required_without_all' => 'Sila isikan salah satu maklumat berikut: Emel / No. Telefon (Bimbit) / No. Telefon (Rumah)',
            'CA_MOBILENO.required_without_all' => 'Sila isikan salah satu maklumat berikut: Emel / No. Telefon (Bimbit) / No. Telefon (Rumah)',
            'CA_TELNO.required_without_all' => 'Sila isikan salah satu maklumat berikut: Emel / No. Telefon (Bimbit) / No. Telefon (Rumah)',
            'CA_NAME.required' => 'Ruangan Nama diperlukan.',
            'CA_CMPLCAT.required' => 'Ruangan Kategori diperlukan.',
            'CA_CMPLCD.required' => 'Ruangan Subkategori diperlukan.',
            'CA_AGAINST_PREMISE.required_unless' => 'Ruangan Jenis Premis diperlukan.',
            'CA_AGAINSTNM.required' => 'Ruangan Nama (Syarikat/Premis/Penjual) diperlukan.',
            'CA_AGAINST_STATECD.required_if' => 'Ruangan Negeri diperlukan.',
            'CA_AGAINST_STATECD.required_unless' => 'Ruangan Negeri diperlukan.',
            'CA_AGAINST_DISTCD.required_if' => 'Ruangan Daerah diperlukan.',
            'CA_AGAINST_DISTCD.required_unless' => 'Ruangan Daerah diperlukan.',
            'CA_AGAINSTADD.required_unless' => 'Ruangan Alamat diperlukan.',
            'CA_AGAINSTADD.required_if' => 'Ruangan Alamat diperlukan.',
            'CA_AGAINST_POSTCD.required_if' => 'Ruangan Poskod diperlukan.',
            'CA_AGAINST_POSTCD.required' => 'Ruangan Poskod diperlukan.',
            'CA_AGAINST_POSTCD.required_unless' => 'Ruangan Poskod diperlukan.',
            'CA_AGAINST_POSTCD.min' => 'Poskod mesti sekurang-kurangnya 5 angka.',
            'CA_NATCD.required' => 'Ruangan Warganegara diperlukan.',
            'CA_POSCD.min' => 'Poskod mesti sekurang-kurangnya 5 angka.',
            'CA_STATECD.required' => 'Ruangan Negeri diperlukan.',
            'CA_DISTCD.required' => 'Ruangan Daerah diperlukan.',
            'CA_COUNTRYCD.required_if' => 'Ruangan Negara Asal diperlukan.',
            'CA_CMPLKEYWORD.required_if' => 'Ruangan Jenis Barangan diperlukan.',
            'CA_ONLINECMPL_PROVIDER.required_if' => 'Ruangan Pembekal Perkhidmatan diperlukan.',
            'CA_ONLINECMPL_URL.required_if' => 'Ruangan Laman Web / URL / ID diperlukan.',
            'CA_ONLINECMPL_AMOUNT.required' => 'Ruangan Jumlah Kerugian (RM) diperlukan.',
            'CA_ONLINECMPL_PYMNTTYP.required_if' => 'Ruangan Cara Pembayaran diperlukan.',
            'CA_ONLINECMPL_BANKCD.required_unless' => 'Ruangan Nama Bank diperlukan.',
            'CA_ONLINECMPL_ACCNO.required_unless' => 'Ruangan No. Akaun Bank diperlukan.',
            'CA_SUMMARY.required' => 'Ruangan Keterangan Aduan diperlukan.',
            'CA_RESULT.required' => 'Ruangan Hasil Siasatan diperlukan',
            'CA_ANSWER.required' => 'Ruangan Jawapan Kepada Pengadu diperlukan',
        ]);
        
        $model = new DataEntry();
        $model->fill($request->all());
        if(in_array($request->CA_CMPLCAT,['BPGK 01','BPGK 03'])) {
            $model->CA_CMPLKEYWORD = $request->CA_CMPLKEYWORD;
        }else{
            $model->CA_CMPLKEYWORD = NULL;
        }
        if($request->CA_CMPLCAT == 'BPGK 19') {
            if($request->CA_ONLINECMPL_IND) {
                $model->CA_ONLINECMPL_IND = '1';
                $model->CA_ONLINECMPL_CASENO = $request->CA_ONLINECMPL_CASENO;
            }else{
                $model->CA_ONLINECMPL_IND = '0';
                $model->CA_ONLINECMPL_CASENO = NULL;
            }
            if($request->CA_ONLINEADD_IND) {
                $model->CA_ONLINEADD_IND = '1';
            }else{
                $model->CA_ONLINEADD_IND = '0';
            }
            $model->CA_ONLINECMPL_URL = $request->CA_ONLINECMPL_URL;
        }else{
            $model->CA_ONLINECMPL_URL = NULL;
        }
        $DeptCd = explode(' ', $model->CA_CMPLCAT)[0];
        $model->CA_DEPTCD = $DeptCd;
        $model->CA_CASEID = RunnerRepository::generateAppNumber('X', date('y'), '0');
        $model->CA_RCVDT = date('Y-m-d H:i:s', strtotime($request->CA_RCVDT));
        if($request->CA_COMPLETEDT){
            $model->CA_COMPLETEDT = date('Y-m-d H:i:s', strtotime($request->CA_COMPLETEDT));
        }
        else
        {
            $model->CA_COMPLETEDT = NULL;
        }
        $model->CA_ONLINECMPL_AMOUNT = str_replace(',', '', $request->CA_ONLINECMPL_AMOUNT);
        if($request->CA_ONLINEADD_IND == 'on' || $request->CA_CMPLCAT != 'BPGK 19')
        {
            $StateCd = $request->CA_AGAINST_STATECD;
            $DistCd = $request->CA_AGAINST_DISTCD;
        }else{
            $StateCd = $model->CA_STATECD;
            $DistCd = $model->CA_DISTCD;
            $model->CA_AGAINSTADD = NULL;
            $model->CA_AGAINST_POSTCD = NULL;
            $model->CA_AGAINST_STATECD = NULL;
            $model->CA_AGAINST_DISTCD = NULL;
        }
        if($request->CA_ROUTETOHQIND && $request->CA_ROUTETOHQIND == 'on') {
            $model->CA_ROUTETOHQIND = '1';
            $model->CA_BRNCD = 'WHQR5';
        }else{
            $model->CA_ROUTETOHQIND = '0';
            // $model->CA_BRNCD = $this->AduanRoute($StateCd, $DistCd, $DeptCd, false);
            $model->CA_BRNCD = CaseInfoRepository::routeBranch($StateCd, $DistCd, $DeptCd, false);
        }
//        $model->CA_INVSTS = '9'; // Pengesahan Penutupan
        if($request->CA_INVSTS == '4') // Penutupan (Rujuk Agensi Di Bawah KPDNKK)
        {
            $model->CA_MAGNCD = $request->CA_MAGNCD;
        }
        $model->CA_CASESTS = '2'; // Telah Diberi Penugasan
        if($model->save()) {
            $mCaseDtl = new DataEntryDetail;
            $mCaseDtl->CD_CASEID = $model->CA_CASEID;
            $mCaseDtl->CD_TYPE = 'D';
            $mCaseDtl->CD_ACTTYPE = 'CLS';
            $mCaseDtl->CD_INVSTS = '1';
            $mCaseDtl->CD_CASESTS = '1';
            $mCaseDtl->CD_CURSTS = '0';
            $mCaseDtlTutup = new DataEntryDetail;
            $mCaseDtlTutup->CD_CASEID = $model->CA_CASEID;
            $mCaseDtlTutup->CD_TYPE = 'D';
            $mCaseDtlTutup->CD_ACTTYPE = 'CLS';
            $mCaseDtlTutup->CD_INVSTS = $model->CA_INVSTS;
            $mCaseDtlTutup->CD_CASESTS = $model->CA_CASESTS;
            $mCaseDtlTutup->CD_CURSTS = '1';
            if($mCaseDtl->save() && $mCaseDtlTutup->save()) {
                return redirect()->route('dataentry.edit', $model->CA_CASEID)->with('success', 
                    'Aduan Baru (Data Entry) telah berjaya ditambah.'
                    .'<br />No. Aduan: ' . $model->CA_CASEID
                );
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
        $model = DataEntry::where(['CA_CASEID' => $id])->first();
        $mUser = User::find(Auth::User()->id);
        if(!empty($model->CA_RCVBY)) {
            $mUserRcvBy = User::find($model->CA_RCVBY);
            $RcvBy = $mUserRcvBy->name;
        }else{
            $RcvBy = '';
        }
        if(!empty($model->CA_INVBY)) {
            $mUserInvBy = User::find($model->CA_INVBY);
            $InvBy = $mUserInvBy->name;
        }else{
            $InvBy = '';
        }
        $countDocAduan = DB::table('case_doc')
            ->where(['CC_CASEID' => $id, 'CC_IMG_CAT' => 1])
            ->count('CC_CASEID');
        $countDocSiasat = DB::table('case_doc')
            ->where(['CC_CASEID' => $id, 'CC_IMG_CAT' => 2])
            ->count('CC_CASEID');
        $countAkta = DB::table('case_act')
            ->where(['CT_CASEID' => $id])
            ->count('CT_CASEID');
        return view('aduan.dataentry.edit', compact('model', 'mUser', 'RcvBy', 'InvBy', 'countDocAduan', 'countDocSiasat', 'countAkta'));
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
        if($request->CA_CMPLCAT != 'BPGK 19'){
            $request->merge([
                'CA_ONLINECMPL_PROVIDER' => NULL,
                'CA_ONLINECMPL_URL' => NULL,
//                'CA_ONLINECMPL_AMOUNT' => NULL,
                'CA_ONLINECMPL_ACCNO' => NULL,
                'CA_ONLINECMPL_IND' => NULL,
                'CA_ONLINECMPL_CASENO' => NULL,
                'CA_ONLINEADD_IND' => NULL
            ]);
            $this->validate($request, [
                'CA_RCVTYP' => 'required',
                'CA_DOCNO' => 'required',
                'CA_EMAIL' => 'required_without_all:CA_MOBILENO,CA_TELNO',
                'CA_MOBILENO' => 'required_without_all:CA_TELNO,CA_EMAIL',
                'CA_TELNO' => 'required_without_all:CA_MOBILENO,CA_EMAIL',
                'CA_NAME' => 'required',
                'CA_STATECD' => 'required',
                'CA_DISTCD' => 'required',
                'CA_COUNTRYCD' => 'required_if:CA_NATCD,0',
                'CA_CMPLCAT' => 'required',
                'CA_CMPLCD' => 'required',
                'CA_ONLINECMPL_AMOUNT' => 'required',
                'CA_CMPLKEYWORD' => 'required_if:CA_CMPLCAT,BPGK 01|required_if:CA_CMPLCAT,BPGK 03',
                'CA_AGAINST_PREMISE' => 'required',
                'CA_AGAINSTNM' => 'required',
                'CA_AGAINSTADD' => 'required',
//                'CA_AGAINST_POSTCD' => 'min:5|max:5'
                'CA_AGAINST_STATECD' => 'required',
                'CA_AGAINST_DISTCD' => 'required',
                'CA_SUMMARY' => 'required',
                'CA_RESULT' => 'required',
                'CA_ANSWER' => 'required',
                'CA_SSP' => 'required',
            ],
            [
                'CA_RCVTYP.required' => 'Ruangan Cara Penerimaan diperlukan.',
                'CA_DOCNO.required' => 'Ruangan No. Kad Pengenalan/Pasport diperlukan.',
                'CA_EMAIL.required_without_all' => 'Sila isikan salah satu maklumat berikut: Emel / No. Telefon (Bimbit) / No. Telefon (Rumah)',
                'CA_MOBILENO.required_without_all' => 'Sila isikan salah satu maklumat berikut: Emel / No. Telefon (Bimbit) / No. Telefon (Rumah)',
                'CA_TELNO.required_without_all' => 'Sila isikan salah satu maklumat berikut: Emel / No. Telefon (Bimbit) / No. Telefon (Rumah)',
                'CA_NAME.required' => 'Ruangan Nama diperlukan.',
                'CA_STATECD.required' => 'Ruangan Negeri diperlukan.',
                'CA_DISTCD.required' => 'Ruangan Daerah diperlukan.',
                'CA_COUNTRYCD.required_if' => 'Ruangan Negara diperlukan.',
                'CA_CMPLCAT.required' => 'Ruangan Kategori diperlukan.',
                'CA_CMPLCD.required' => 'Ruangan Subkategori diperlukan.',
                'CA_ONLINECMPL_AMOUNT.required' => 'Ruangan Jumlah Kerugian (RM) diperlukan.',
                'CA_CMPLKEYWORD.required_if' => 'Ruangan Jenis Barangan diperlukan.',
                'CA_AGAINST_PREMISE.required' => 'Ruangan Jenis Premis diperlukan.',
                'CA_AGAINSTNM.required' => 'Ruangan Nama (Syarikat/Premis) diperlukan.',
                'CA_AGAINSTADD.required' => 'Ruangan Alamat diperlukan.',
                'CA_AGAINST_STATECD.required' => 'Ruangan Negeri diperlukan.',
                'CA_AGAINST_DISTCD.required' => 'Ruangan Daerah diperlukan.',
                'CA_SUMMARY.required' => 'Ruangan Aduan diperlukan.',
                'CA_RESULT.required' => 'Ruangan Hasil Siasatan diperlukan.',
                'CA_ANSWER.required' => 'Ruangan Jawapan Kepada Pengadu diperlukan.',
                'CA_SSP.required' => 'Ruangan Wujud Kes diperlukan.',
            ]);
        } else {
            $this->validate($request, [
                'CA_RCVTYP' => 'required',
                'CA_DOCNO' => 'required',
                'CA_EMAIL' => 'required_without_all:CA_MOBILENO,CA_TELNO',
                'CA_MOBILENO' => 'required_without_all:CA_TELNO,CA_EMAIL',
                'CA_TELNO' => 'required_without_all:CA_MOBILENO,CA_EMAIL',
                'CA_NAME' => 'required',
                'CA_STATECD' => 'required',
                'CA_DISTCD' => 'required',
                'CA_COUNTRYCD' => 'required_if:CA_NATCD,0',
                'CA_CMPLCAT' => 'required',
                'CA_CMPLCD' => 'required',
                'CA_ONLINECMPL_AMOUNT' => 'required',
                'CA_ONLINECMPL_PROVIDER' => 'required_if:CA_CMPLCAT,BPGK 19',
                'CA_ONLINECMPL_URL' => 'required_if:CA_ONLINECMPL_PROVIDER,999',
                'CA_ONLINECMPL_PYMNTTYP' => 'required_if:CA_CMPLCAT,BPGK 19',
                'CA_ONLINECMPL_BANKCD' => 'required_unless:CA_ONLINECMPL_PYMNTTYP,COD,CA_ONLINECMPL_PYMNTTYP,,CA_CMPLCAT,BPGK 19',
                'CA_ONLINECMPL_ACCNO' => 'required_unless:CA_ONLINECMPL_PYMNTTYP,COD,CA_ONLINECMPL_PYMNTTYP,,CA_CMPLCAT,BPGK 19',
//                'CA_ONLINECMPL_CASENO' => 'required_if:CA_ONLINECMPL_IND,on|required_if:CA_ONLINECMPL_IND,1',
//                'CA_AGAINST_PREMISE' => 'required_unless:CA_CMPLCAT,BPGK 19',
                'CA_AGAINSTNM' => 'required',
                'CA_AGAINSTADD' => 'required_if:CA_ONLINEADD_IND,on',
//                'CA_AGAINST_POSTCD' => 'required_if:CA_ONLINEADD_IND,on'
                'CA_AGAINST_STATECD' => 'required_if:CA_ONLINEADD_IND,on',
                'CA_AGAINST_DISTCD' => 'required_if:CA_ONLINEADD_IND,on',
                'CA_SUMMARY' => 'required',
                'CA_RESULT' => 'required',
                'CA_ANSWER' => 'required',
                'CA_SSP' => 'required',
            ],
            [
                'CA_RCVTYP.required' => 'Ruangan Cara Penerimaan diperlukan.',
                'CA_DOCNO.required' => 'Ruangan No. Kad Pengenalan/Pasport diperlukan.',
                'CA_EMAIL.required_without_all' => 'Sila isikan salah satu maklumat berikut: Emel / No. Telefon (Bimbit) / No. Telefon (Rumah)',
                'CA_MOBILENO.required_without_all' => 'Sila isikan salah satu maklumat berikut: Emel / No. Telefon (Bimbit) / No. Telefon (Rumah)',
                'CA_TELNO.required_without_all' => 'Sila isikan salah satu maklumat berikut: Emel / No. Telefon (Bimbit) / No. Telefon (Rumah)',
                'CA_NAME.required' => 'Ruangan Nama diperlukan.',
                'CA_STATECD.required' => 'Ruangan Negeri diperlukan.',
                'CA_DISTCD.required' => 'Ruangan Daerah diperlukan.',
                'CA_COUNTRYCD.required_if' => 'Ruangan Negara diperlukan.',
                'CA_CMPLCAT.required' => 'Ruangan Kategori diperlukan.',
                'CA_CMPLCD.required' => 'Ruangan Subkategori diperlukan.',
                'CA_ONLINECMPL_AMOUNT.required' => 'Ruangan Jumlah Kerugian (RM) diperlukan.',
                'CA_ONLINECMPL_PROVIDER.required_if' => 'Ruangan Pembekal Perkhidmatan diperlukan.',
                'CA_ONLINECMPL_URL.required_if' => 'Ruangan Laman Web / URL / ID diperlukan.',
                'CA_ONLINECMPL_PYMNTTYP.required_if' => 'Ruangan Cara Pembayaran diperlukan.',
                'CA_ONLINECMPL_BANKCD.required_unless' => 'Ruangan Nama Bank diperlukan.',
                'CA_ONLINECMPL_ACCNO.required_unless' => 'Ruangan No. Akaun Bank diperlukan.',
                'CA_ONLINECMPL_CASENO.required_if' => 'Ruangan No. Aduan Rujukan diperlukan.',
                'CA_AGAINSTNM.required' => 'Ruangan Nama (Syarikat / Premis) diperlukan.',
                'CA_AGAINSTADD.required_if' => 'Ruangan Alamat diperlukan.',
                'CA_AGAINST_STATECD.required_if' => 'Ruangan Negeri diperlukan.',
                'CA_AGAINST_DISTCD.required_if' => 'Ruangan Daerah diperlukan.',
                'CA_SUMMARY.required' => 'Ruangan Aduan diperlukan.',
                'CA_RESULT.required' => 'Ruangan Hasil Siasatan diperlukan.',
                'CA_ANSWER.required' => 'Ruangan Jawapan Kepada Pengadu diperlukan.',
                'CA_SSP.required' => 'Ruangan Wujud Kes diperlukan.',
            ]);
        }
        
        $model = DataEntry::where(['CA_CASEID' => $id])->first();
        $model->fill($request->all());
        $DeptCd = explode(' ', $request->CA_CMPLCAT)[0];
        $model->CA_DEPTCD = $DeptCd;
        $model->CA_RCVDT = date('Y-m-d H:i:s', strtotime($request->CA_RCVDT));
        if($request->CA_COMPLETEDT){
            $model->CA_COMPLETEDT = date('Y-m-d H:i:s', strtotime($request->CA_COMPLETEDT));
        }
        else
        {
            $model->CA_COMPLETEDT = NULL;
        }
        if($request->CA_ONLINECMPL_AMOUNT == NULL){
            $model->CA_ONLINECMPL_AMOUNT = 0.00;
        } else {
            $model->CA_ONLINECMPL_AMOUNT = str_replace(',', '', $request->CA_ONLINECMPL_AMOUNT);
        }
        if ($model->CA_NATCD == '1') {
            $model->CA_COUNTRYCD = NULL;
        } 
        if(in_array($request->CA_CMPLCAT,['BPGK 01','BPGK 03'])) {
            $model->CA_CMPLKEYWORD = $request->CA_CMPLKEYWORD;
            $model->CA_ONLINECMPL_IND = NULL;
            $model->CA_ONLINECMPL_CASENO = NULL;
            $model->CA_ONLINECMPL_URL = NULL;
        }else{
            $model->CA_CMPLKEYWORD = NULL;
        }
        if($request->CA_CMPLCAT == 'BPGK 19') {
            if($request->CA_ONLINECMPL_IND) {
                $model->CA_ONLINECMPL_IND = '1';
                $model->CA_ONLINECMPL_CASENO = $request->CA_ONLINECMPL_CASENO;
            }else{
                $model->CA_ONLINECMPL_IND = '0';
                $model->CA_ONLINECMPL_CASENO = NULL;
            }
            if($request->CA_ONLINEADD_IND) {
                $model->CA_ONLINEADD_IND = '1';
            }else{
                $model->CA_ONLINEADD_IND = '0';
                $model->CA_AGAINSTADD = NULL;
                $model->CA_AGAINST_STATECD = NULL;
                $model->CA_AGAINST_DISTCD = NULL;
                $model->CA_AGAINST_POSTCD = NULL;
            }
            $model->CA_ONLINECMPL_URL = $request->CA_ONLINECMPL_URL;
            $model->CA_ONLINECMPL_PYMNTTYP = $request->CA_ONLINECMPL_PYMNTTYP;
            $model->CA_AGAINST_PREMISE = NULL;
        }else{
            $model->CA_ONLINECMPL_URL = NULL;
        }
        if($request->CA_ONLINEADD_IND == 'on' || $request->CA_CMPLCAT != 'BPGK 19'){
            $StateCd = $request->CA_AGAINST_STATECD;
            $DistCd = $request->CA_AGAINST_DISTCD;
        }else{
            $StateCd = $model->CA_STATECD;
            $DistCd = $model->CA_DISTCD;
            $model->CA_AGAINSTADD = NULL;
            $model->CA_AGAINST_POSTCD = NULL;
            $model->CA_AGAINST_STATECD = NULL;
            $model->CA_AGAINST_DISTCD = NULL;
        }
        if($request->CA_ROUTETOHQIND && $request->CA_ROUTETOHQIND == 'on') {
            $model->CA_ROUTETOHQIND = '1';
            $model->CA_BRNCD = 'WHQR5';
        }else{
            $model->CA_ROUTETOHQIND = '0';
            // $model->CA_BRNCD = $this->AduanRoute($StateCd, $DistCd, $DeptCd, false);
            $model->CA_BRNCD = CaseInfoRepository::routeBranch($StateCd, $DistCd, $DeptCd, false);
        }
        if ($model->save()) {
            $mCaseDtl = new DataEntryDetail;
            $mCaseDtl->CD_CASEID = $model->CA_CASEID;
            $mCaseDtl->CD_INVSTS = $model->CA_INVSTS;
            $mCaseDtl->CD_CASESTS = $model->CA_CASESTS;
            $mCaseDtl->CD_DESC = 'Kemaskini Aduan';
            if($mCaseDtl->save()) {
                return redirect()->route('dataentry.edit', $model->CA_CASEID)->with('success', 
                    'Aduan Baru (Data Entry) telah berjaya dikemaskini.'
                );
            }
        }
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
    
    public function __construct()
    {
        $this->middleware('auth');
    }
    
    public function AduanRoute($StateCd, $DistCd, $DeptCd, $RouteToHQ = false) {
        if ($DeptCd == 'BPGK') {
            if ($StateCd == '16') {
                $FindBrn = DB::table('sys_brn')
                    ->select('BR_BRNCD', 'BR_BRNNM', 'BR_OTHDIST')
                    ->where('BR_STATECD', $StateCd)
                    ->where(DB::raw("LOCATE(CONCAT(',', '$DistCd' ,','),CONCAT(',',BR_OTHDIST,','))"), ">", 0)
                    ->where('BR_DEPTCD', 'BGK')
                    ->where('BR_STATUS', 1)
                    ->first();
            } else {
                $FindBrn = DB::table('sys_brn')
                    ->select('BR_BRNCD', 'BR_BRNNM', 'BR_OTHDIST')
                    ->where('BR_STATECD', $StateCd)
                    ->where(DB::raw("LOCATE(CONCAT(',', '$DistCd' ,','),CONCAT(',',BR_OTHDIST,','))"), ">", 0)
                    ->where('BR_DEPTCD', $DeptCd)
                    ->where('BR_STATUS', 1)
                    ->first();
            }
            if($RouteToHQ) {
                return 'WHQR5';
            }else{
                return $FindBrn->BR_BRNCD;
            }
        } else {
            $FindBrn = DB::table('sys_brn')
                ->select('BR_BRNCD', 'BR_BRNNM', 'BR_OTHDIST')
                ->where('BR_STATECD', 16)
                ->where(DB::raw("LOCATE(CONCAT(',', '1601' ,','),CONCAT(',',BR_OTHDIST,','))"), ">", 0)
                ->where('BR_DEPTCD', $DeptCd)
                ->where('BR_STATUS', 1)
                ->first();
            return $FindBrn->BR_BRNCD;
        }
    }
}
