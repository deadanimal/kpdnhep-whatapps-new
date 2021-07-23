<?php

namespace App\Console\Commands;

// use App\Repositories\CalculatePreCloseDurationRepository;
use DB;
use Illuminate\Console\Command;

class ComplaintDocnoFokCheck extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'eaduan:complaintdocnofokcheck';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Check if complaint ic number is a fok user';

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
        echo "total data need to be run:".$case_infos->count().PHP_EOL;
        foreach ($case_infos as $key => $v) {
            // compare with fed_user_fok
            $fok = DB::table('fed_user_fok')
                ->where('ic_no', $v->CA_DOCNO)
                ->first();

            // if have data then 1
            // else then 0
            DB::table('case_info')
                ->where('CA_CASEID', $v->CA_CASEID)
                ->update([
                    'CA_FOK_IND' => $fok ? 1 : 0,
                    'CA_FOK_IND_STATUS' => 1,
                ]);
                
            echo $key .' - '.$v->CA_CASEID.' - ' .$v->CA_DOCNO .'-'.$v->CA_FOK_IND.PHP_EOL;
        }
    }

    public function query()
    {
        $date = \Carbon\Carbon::parse();
        return DB::table('case_info')
            ->select('CA_CASEID', 'CA_DOCNO', 'CA_RCVDT', 'CA_FOK_IND')
            ->whereNotIn('case_info.CA_DOCNO', ['-', '000000000000', '111111111111', '', '00000000000'])
            ->where(function($q) use ($date) {
                $q->where('case_info.CA_FOK_IND_STATUS', 0)
                    ->orWhereBetween('case_info.CA_RCVDT', [$date->startOfMonth()->toDateString(), $date->endOfMonth()->toDateString()]);
            })
            ->where('case_info.CA_FOK_IND', 0)
            ->whereNotNull('case_info.CA_RCVDT')
            // ->whereBetween('case_info.CA_RCVDT', ['2018-01-01', '2019-01-28'])
            // ->limit(1000)
            ->whereNotIn('case_info.CA_INVSTS', ['10'])
            ->get();
            // ->toSql();
    }
}
