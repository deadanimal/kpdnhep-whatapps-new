<?php

namespace App\Console\Commands;

use App\Repositories\CalculatePreCloseDurationRepository;
use DB;
use Illuminate\Console\Command;

class CalculatePreCloseDuration extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'eaduan:calcprecloseduration';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Calculate case info pre close duration';

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
            $data_final = CalculatePreCloseDurationRepository::calc($v);

            echo $key .' - '.$v->CA_CASEID.' - '.$data_final['total_duration'].PHP_EOL;

            DB::table('case_info')
                ->where('CA_CASEID', $v->CA_CASEID)
                ->update([
                    'CA_PRECLOSE_DATE' => date('Y-m-d', strtotime($data_final['CA_COMPLETEDT'])),
                    'CA_PRECLOSE_DURATION' => $data_final['total_duration'],
                    'CA_SC_PRECLOSE' => 1,
                ]);
        }
    }

    public function query()
    {
        return DB::table('case_info')
            ->select('BR_STATECD', 'CA_DEPTCD', 'CA_CASEID', 'CA_AGAINST_STATECD', 'CA_STATECD', 'CA_COMPLETEDT', 'CA_RCVDT',
                DB::Raw('DATEDIFF(case_info.CA_COMPLETEDT,case_info.CA_RCVDT) as date_diff')
            )
            ->join('sys_brn', 'case_info.CA_BRNCD', '=', 'sys_brn.BR_BRNCD')
            ->whereIn('CA_INVSTS', [3, 4, 5, 6, 8, 9, 11, 12])
            ->where('CA_SC_PRECLOSE', 0)
            ->whereNull('CA_PRECLOSE_DATE')
            ->groupBy('BR_STATECD', 'CA_DEPTCD', 'date_diff', 'CA_CASEID', 'CA_AGAINST_STATECD', 'CA_STATECD', 'CA_COMPLETEDT', 'CA_RCVDT')
            ->get();
    }
}
