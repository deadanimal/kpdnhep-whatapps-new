<?php

namespace App\Console\Commands;

use App\Repositories\CalculateFirstActionDurationRepository;
use DB;
use Illuminate\Console\Command;

class CalculateFirstActionDuration extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'eaduan:calcfirstactionduration';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Calculate case info first action duration';

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
        $data = self::query();

        foreach ($data as $key => $v) {
            $data_final = CalculateFirstActionDurationRepository::calc($v, $v->fa_dt);

            echo $key . ' - ' . $v->CA_CASEID . ' - ' . $data_final['total_duration'] . PHP_EOL;

            DB::table('case_info')
                ->where('CA_CASEID', $v->CA_CASEID)
                ->update([
                    'CA_FA_DATE' => date('Y-m-d', strtotime($data_final['CA_COMPLETEDT'])),
                    'CA_FA_DURATION' => $data_final['total_duration'],
                    'CA_SC_FA' => 1,
                ]);
        }
    }

    public function query()
    {
        return  DB::table(DB::raw('case_dtl a'))
            ->select('case_info.CA_AGAINST_STATECD', 'case_info.CA_STATECD', 'case_info.CA_RCVDT', 'case_info.CA_CASEID'
                , 'a.cd_caseid', 'a.cd_invsts as fa_sts', 'b.cd_invsts as new_sts', 'a.cd_credt as fa_dt', 'b.cd_credt as new_dt')
            ->join(DB::raw('case_dtl b'), function ($q) {
                $q->on('a.cd_caseid', '=', 'b.cd_caseid')
                    ->where('b.cd_invsts', 1);
            })
            ->join('case_info', 'a.cd_caseid', '=', 'CA_CASEID')
            ->whereNotNull('a.cd_invsts')
            ->whereNotIn('a.cd_invsts', [10, 1])
            ->get();
    }
}
