<?php

namespace App\Console\Commands;

use App\Models\Cases\CaseInfo;
use Carbon\Carbon;
use DB;
use Excel;
use Exception;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Storage;

class GenerateExcelCaseInfo extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'eaduan:generateexcelcaseinfo';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'To generate excel case info for integration with ECC.';

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
        self::info('Total data need to be run : '.$case_infos->count().PHP_EOL);

        // initialize daily file name
        $count = 1;
        $filename = date('Ymd-'.$count);
        $extension = 'csv';

        // check if daily file exists
        $dailyFileExist = Storage::disk('exports')->exists($filename.'.'.$extension);
        if($dailyFileExist) {
            $count++;
            // set daily file name
            $filename = date('Ymd-'.$count);
        }

        // excel file headers
        $headers = ['NO ADUAN', 'STATUS ADUAN', 'CARA PENERIMAAN', 'TARIKH PENERIMAAN',
            'TARIKH DIBERI PENUGASAN', 'TARIKH MULA SIASATAN', 'TARIKH SELESAI SIASATAN',
            'TARIKH TUTUP ADUAN', 'JAWAPAN KEPADA PENGADU', 'HASIL SIASATAN', 'SARANAN',
            'KETERANGAN ADUAN', 'CAWANGAN', 'KATEGORI ADUAN', 'SUBKATEGORI ADUAN',
            'PEMBEKAL PERKHIDMATAN', 'LAMAN WEB / URL PIHAK DIADU', 'NO AKAUN BANK / TRANSAKSI FPX',
            'JUMLAH KERUGIAN (RM)', 'CARA PEMBAYARAN', 'RUJUKAN KE AGENSI', 'NAMA PENGADU',
            'NO KAD PENGENALAN / PASPORT PENGADU', 'ALAMAT MYIDENTITY PENGADU',
            'POSKOD MYIDENTITY PENGADU', 'DAERAH MYIDENTITY PENGADU', 'NEGERI MYIDENTITY PENGADU',
            'ALAMAT PENGADU', 'POSKOD PENGADU', 'DAERAH PENGADU', 'NEGERI PENGADU',
            'NO TELEFON PENGADU', 'NO TELEFON FAKS PENGADU', 'EMEL PENGADU',
            'NO TELEFON BIMBIT PENGADU', 'NAMA PIHAK DIADU', 'ALAMAT PIHAK DIADU',
            'POSKOD PIHAK DIADU', 'DAERAH PIHAK DIADU', 'NEGERI PIHAK DIADU', 'KOORDINAT PIHAK DIADU',
            'KAWASAN KES', 'NO TELEFON PIHAK DIADU', 'NO FAKS PIHAK DIADU',
            'NO TELEFON BIMBIT PIHAK DIADU', 'EMEL PIHAK DIADU', 'JENIS PREMIS',
        ];

        // create excel file from blade view file, and then store in storage
        $excel = Excel::create($filename, function ($excel) use ($headers, $case_infos) {
            $excel->sheet('Report', function ($sheet) use ($headers, $case_infos) {
                $sheet->loadView('exports.table')->with(compact('headers','case_infos'));
            });
        })
        ->store($extension, public_path('storage/exports'));
        self::info('Excel file successfully uploaded to storage server.'.PHP_EOL);

        // check if file in storage exists
        $fileLocal = Storage::disk('exports')->get($filename.'.'.$extension);
        if($fileLocal) {
            try {
                // upload to FTP server
                $file_ftp = Storage::disk('ftp')->put($filename.'.'.$extension, $fileLocal);
                self::info('Excel file successfully uploaded to FTP server.');
            } catch (Exception $e) {
                // error exception
                self::error('Something went wrong! ' . $e->getMessage());
            }
        }
        // return true;
    }

    /**
     * Query for get data from case_info.
     *
     * @return CaseInfo[]|\Illuminate\Database\Eloquent\Collection
     */
    public function query()
    {
        return CaseInfo::leftJoin('sys_users', function ($leftjoin) {
                $leftjoin->on('case_info.CA_DOCNO', '=', 'sys_users.icnew')
                    ->whereNotNull('sys_users.myidentity_address');
            })
            ->select(DB::raw("
                CONCAT("."'`'".", case_info.CA_CASEID) AS 'NO ADUAN',
                case_info.CA_INVSTS as 'STATUS ADUAN',
                case_info.CA_RCVTYP AS 'CARA PENERIMAAN',
                case_info.CA_RCVDT AS 'TARIKH PENERIMAAN',
                case_info.CA_ASGDT AS 'TARIKH DIBERI PENUGASAN',
                case_info.CA_INVDT AS 'TARIKH MULA SIASATAN',
                case_info.CA_COMPLETEDT AS 'TARIKH SELESAI SIASATAN',
                case_info.CA_CLOSEDT AS 'TARIKH TUTUP ADUAN',
                case_info.CA_ANSWER AS 'JAWAPAN KEPADA PENGADU',
                case_info.CA_RESULT AS 'HASIL SIASATAN',
                case_info.CA_RECOMMEND AS 'SARANAN',
                case_info.CA_SUMMARY AS 'KETERANGAN ADUAN',
                case_info.CA_BRNCD AS 'CAWANGAN',
                case_info.CA_CMPLCAT AS 'KATEGORI ADUAN',
                case_info.CA_CMPLCD AS 'SUBKATEGORI ADUAN',
                case_info.CA_ONLINECMPL_PROVIDER AS 'PEMBEKAL PERKHIDMATAN',
                case_info.CA_ONLINECMPL_URL AS 'LAMAN WEB / URL PIHAK DIADU',
                CONCAT("."'`'".", case_info.CA_ONLINECMPL_ACCNO) AS 'NO AKAUN BANK / TRANSAKSI FPX',
                CONCAT("."'`'".", case_info.CA_ONLINECMPL_AMOUNT) AS 'JUMLAH KERUGIAN (RM)',
                case_info.CA_ONLINECMPL_PYMNTTYP AS 'CARA PEMBAYARAN',
                case_info.CA_MAGNCD AS 'RUJUKAN KE AGENSI',
                case_info.CA_NAME AS 'NAMA PENGADU',
                CONCAT("."'`'".", case_info.CA_DOCNO) AS 'NO KAD PENGENALAN / PASPORT PENGADU',
                sys_users.myidentity_address AS 'ALAMAT MYIDENTITY PENGADU',
                CONCAT("."'`'".", sys_users.myidentity_postcode) AS 'POSKOD MYIDENTITY PENGADU',
                CONCAT("."'`'".", sys_users.myidentity_distrinct_cd) AS 'DAERAH MYIDENTITY PENGADU',
                CONCAT("."'`'".", sys_users.myidentity_state_cd) AS 'NEGERI MYIDENTITY PENGADU',
                case_info.CA_ADDR AS 'ALAMAT PENGADU',
                CONCAT("."'`'".", case_info.CA_POSCD) AS 'POSKOD PENGADU',
                CONCAT("."'`'".", case_info.CA_DISTCD) AS 'DAERAH PENGADU',
                CONCAT("."'`'".", case_info.CA_STATECD) AS 'NEGERI PENGADU',
                CONCAT("."'`'".", case_info.CA_TELNO) AS 'NO TELEFON PENGADU',
                CONCAT("."'`'".", case_info.CA_FAXNO) AS 'NO TELEFON FAKS PENGADU',
                case_info.CA_EMAIL AS 'EMEL PENGADU',
                CONCAT("."'`'".", case_info.CA_MOBILENO) AS 'NO TELEFON BIMBIT PENGADU',
                case_info.CA_AGAINSTNM AS 'NAMA PIHAK DIADU',
                case_info.CA_AGAINSTADD AS 'ALAMAT PIHAK DIADU',
                CONCAT("."'`'".", case_info.CA_AGAINST_POSTCD) AS 'POSKOD PIHAK DIADU',
                CONCAT("."'`'".", case_info.CA_DISTCD) AS 'DAERAH PIHAK DIADU',
                CONCAT("."'`'".", case_info.CA_STATECD) AS 'NEGERI PIHAK DIADU',
                case_info.CA_CRDNT AS 'KOORDINAT PIHAK DIADU',
                case_info.CA_AREACD AS 'KAWASAN KES',
                CONCAT("."'`'".", case_info.CA_AGAINST_TELNO) AS 'NO TELEFON PIHAK DIADU',
                CONCAT("."'`'".", case_info.CA_AGAINST_FAXNO) AS 'NO FAKS PIHAK DIADU',
                CONCAT("."'`'".", case_info.CA_AGAINST_MOBILENO) AS 'NO TELEFON BIMBIT PIHAK DIADU',
                case_info.CA_AGAINST_EMAIL AS 'EMEL PIHAK DIADU',
                case_info.CA_AGAINST_PREMISE AS 'JENIS PREMIS'
            "))
            ->where([
                ['case_info.CA_CASEID', '<>', null],
                ['case_info.CA_RCVTYP', '<>', null],
                ['case_info.CA_RCVTYP', '<>', ''],
                ['case_info.CA_CMPLCAT', '<>', ''],
                ['case_info.CA_INVSTS', '<>', '10']
            ])
            ->whereBetween('case_info.CA_MODDT', [
                Carbon::now()->subDays(30)->startOfDay()->toDateTimeString(),
                Carbon::now()->endOfDay()->toDateTimeString()
            ])
            ->orderBy('case_info.CA_RCVDT')
            ->get();
    }
}
