<?php

namespace App\Console\Commands;

use App\Aduan\CaseEmail;
use App\Email;
use App\Mail\EmelNotifikasiAduanDalamSiasatan;
use App\Penugasan;
use App\Repositories\CalculatePreCloseDurationRepository;
use DB;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Mail;
use Exception;

class emailnotification extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'eaduan:emailnotification';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Send email notification to investigator if complaint is on 16th day';

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle()
    {
        // get case_info data
        $case_infos = self::query();
        echo "Total data need to be run : ".$case_infos->count().PHP_EOL;
        foreach ($case_infos as $key => $v) {
            // complaint days calculation
            $data_final = CalculatePreCloseDurationRepository::calc($v);
            // send email to investigator
            if($data_final['total_duration'] == 16){
                if($v->namapenyiasat->email){
                    try {
                        // $cc = 'sitizakiah@kpdnhep.gov.my';
                        $cc = ['sitizakiah@kpdnhep.gov.my'];
                        $ccadminbranch = DB::table('sys_users')
                            ->join('sys_user_access', 'sys_users.id', '=', 'sys_user_access.user_id')
                            ->where('sys_users.user_cat', '1')
                            ->where('sys_users.status', '1')
                            ->where('sys_users.brn_cd', $v->CA_BRNCD)
                            ->whereIn('sys_user_access.role_code', [
                                // '120', '220', 
                                '320'
                            ])
                            ->pluck('sys_users.email');
                        $ccadminstate = DB::table('sys_users')
                            ->join('sys_user_access', 'sys_users.id', '=', 'sys_user_access.user_id')
                            // ->join('sys_brn', 'sys_users.brn_cd', '=', 'sys_brn.BR_BRNCD')
                            ->join('sys_brn', 'sys_users.state_cd', '=', 'sys_brn.BR_STATECD')
                            ->where('sys_users.user_cat', '1')
                            ->where('sys_users.status', '1')
                            ->where('sys_brn.BR_BRNCD', $v->CA_BRNCD)
                            ->whereIn('sys_user_access.role_code', [
                                '120', '125', '220', '225'
                                // , '320'
                            ])
                            ->pluck('sys_users.email');
                        $ccconcat = collect($cc)->concat($ccadminbranch)->concat($ccadminstate);
                        // Send pakai queue
                        // Mail::to($mAdminCase->CA_EMAIL)->queue(new AduanTerimaAdmin($mAdminCase));
                        // Send biasa
                        Mail::to($v->namapenyiasat->email)
                            ->cc($ccconcat)
                            ->send(new EmelNotifikasiAduanDalamSiasatan($v));
                        $TemplateEmail = 
                            Email::where(['email_type'=>'02','email_code'=>'2','status'=>'1'])
                            ->first();
                        $model = new CaseEmail();
                        $model->ce_title = $TemplateEmail->title;
                        $model->ce_to = $v->namapenyiasat->email;
                        // $model->ce_cc = $cc;
                        $model->ce_cc = $ccconcat->implode(';');
                        $model->ce_caseid = $v->CA_CASEID;
                        $model->save();
                        DB::table('case_info')
                            ->where('CA_CASEID', $v->CA_CASEID)
                            ->update([
                                'CA_EMAIL_NOTIFY' => 1,
                            ]);
                    } catch (Exception $ex) {
                        
                    }
                }
            }
            // echo $key .' - ' . $v->CA_CASEID . ' - ' . $v->duration . PHP_EOL;
            echo $key+1 .' - '.$v->CA_CASEID.' - '.$data_final['total_duration']
            // .PHP_EOL
            // .'</br></br> - Penyiasat : '.$v->namapenyiasat ? $v->namapenyiasat->email : $v->CA_INVBY
            .PHP_EOL;
        }
    }

    public function query()
    {
        return 
            Penugasan::
            leftJoin('case_dtl', 'case_info.CA_CASEID', '=', 'case_dtl.CD_CASEID')
            ->whereIn('case_info.CA_INVSTS', ['2'])
            ->whereRaw('CD_INVSTS = CA_INVSTS')
            ->where('CD_CURSTS', 1)
            ->whereYear('CD_CREDT', '>=', '2015')
            ->where('case_info.CA_EMAIL_NOTIFY', 0)
            ->get();
    }
}
