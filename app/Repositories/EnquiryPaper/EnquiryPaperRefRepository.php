<?php

namespace App\Repositories\EnquiryPaper;

use App\Ref;

/**
 * Class EnquiryPaperRefRepository
 *
 * @package App\Repositories\EnquiryPaper
 */
class EnquiryPaperRefRepository
{
    /**
     * refStates
     *
     * @return array
     */
    public static function refStates()
    {
        return Ref::where(['cat' => '17', 'status' => '1'])
            ->orderBy('sort', 'asc')->pluck('descr', 'code')->toArray();
    }

    /**
     * refActs
     *
     * @return array
     */
    public static function refActs()
    {
        return Ref::where(['cat' => '713', 'status' => '1'])
            ->orderBy('sort', 'asc')->pluck('descr', 'code')->toArray();
    }

    /**
     * senaraiAsasTindakan
     *
     * @return array
     */
    public static function senaraiAsasTindakan()
    {
        return [
            'OPS BARANG TIRUAN' => 'OPS BARANG TIRUAN',
            'ADUAN' => 'ADUAN',
            'JUALAN MURAH' => 'JUALAN MURAH',
            'OPS BERSEPADU' => 'OPS BERSEPADU',
            'RAMPASAN TANPA TUAN' => 'RAMPASAN TANPA TUAN',
        ];
    }

    /**
     * senaraiSerahanAgensi
     *
     * @return array
     */
    public static function senaraiSerahanAgensi()
    {
        return [
            'AKSEM' => 'AKSEM',
            'APMM' => 'APMM',
            'ATM' => 'ATM',
            'FRANCAIS' => 'FRANCAIS',
            'KASTAM' => 'KASTAM',
            'NRRET' => 'NRRET',
            'PDRM' => 'PDRM',
            'PGA' => 'PGA',
            'PPM' => 'PPM',
            'SPRM' => 'SPRM',
            'NA' => 'TIDAK BERKENAAN (NA)',
        ];
    }

    /**
     * senaraiPengelasanKes
     *
     * @return array
     */
    public static function senaraiPengelasanKes()
    {
        return [
            'A1' => 'A1 - PREMIS TERBUKA-KOTS',
            'A2' => 'A2 - PREMIS TETAP-KOTS',
            'B3' => 'B3 - RTT-DGN NOTIS',
            'B4' => 'B4 - RTT-TANPA NOTIS',
            'B5' => 'B5 - RTT',
            'C6' => 'C6 - KOMPAUN DIBAYAR DLM TEMPOH DITETAPKAN',
            'D7' => 'D7 - SIASATAN SUKAR',
            'D8' => 'D8 - RAYUAN KOMPAUN ATAU KOMPAUN TIDAK DIBAYAR',
        ];
    }

    /**
     * senaraiKategoriKesalahan
     *
     * @return array
     */
    public static function senaraiKategoriKesalahan()
    {
        return [
            'BARANG TIRUAN' => 'BARANG TIRUAN',
            'JUALAN MURAH' => 'JUALAN MURAH',
            'LABEL CAKERA OPTIK' => 'LABEL CAKERA OPTIK',
        ];
    }

    /**
     * senaraiKesalahan
     *
     * @return array
     */
    public static function senaraiKesalahan()
    {
        return [
            'SEK 8 (2) (b) APD 2011' => 'SEK 8 (2) (b) APD 2011',
            'PER 3 (1) PPPD (HJM) 1997' => 'PER 3 (1) PPPD (HJM) 1997',
            'PER 9 (1) PPPD (HJM) (PINDAAN) 2016' => 'PER 9 (1) PPPD (HJM) (PINDAAN) 2016',
        ];
    }

    /**
     * senaraiJenamaPremis
     *
     * @return array
     */
    public static function senaraiJenamaPremis()
    {
        return [
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
    }

    /**
     * senaraiKawasan
     *
     * @return array
     */
    public static function senaraiKawasan()
    {
        return [
            'B' => 'BANDAR',
            'LB' => 'LUAR BANDAR',
        ];
    }

    /**
     * senaraiJenisPerniagaan
     *
     * @return array
     */
    public static function senaraiJenisPerniagaan()
    {
        return [
            'BENGKEL' => 'BENGKEL',
            'BORONG' => 'BORONG',
            'BORONG & RUNCIT' => 'BORONG & RUNCIT',
            'RUNCIT' => 'RUNCIT',
            'LAIN-LAIN' => 'LAIN-LAIN',
        ];
    }

    /**
     * senaraiKategoriPremis
     *
     * @return array
     */
    public static function senaraiKategoriPremis()
    {
        return [
            'GERAI MAKAN' => 'GERAI MAKAN',
            'PREMIS TERBUKA' => 'PREMIS TERBUKA',
            'PREMIS TETAP' => 'PREMIS TETAP',
            'RUNCIT' => 'RUNCIT',
            'LAIN-LAIN' => 'LAIN-LAIN',
        ];
    }

    /**
     * senaraiJenisPremis
     *
     * @return array
     */
    public static function senaraiJenisPremis()
    {
        return [
            'AKSESORI' => 'AKSESORI',
            'ARKED NIAGA' => 'ARKED NIAGA',
            'BADAN KERAJAAN' => 'BADAN KERAJAAN',
            'BALAI POLIS' => 'BALAI POLIS',
            'BANK' => 'BANK',
            'BARANGAN' => 'BARANGAN',
            'LAIN-LAIN' => 'LAIN-LAIN',
        ];
    }

    /**
     * senaraiStatusOkt
     *
     * @return array
     */
    public static function senaraiStatusOkt()
    {
        return [
            'IMIGRESEN' => 'IMIGRESEN',
            'JAMINAN POLIS' => 'JAMINAN POLIS',
            'REMAN' => 'REMAN',
            'RTT' => 'RTT',
        ];
    }

    /**
     * senaraiJantina
     *
     * @return array
     */
    public static function senaraiJantina()
    {
        return [
            'L' => 'LELAKI',
            'P' => 'PEREMPUAN',
        ];
    }

    /**
     * senaraiTarafKerakyatan
     *
     * @return array
     */
    public static function senaraiTarafKerakyatan()
    {
        return [
            'W' => 'WARGANEGARA',
            // 'WA' => 'WARGA ASING',
            'BW' => 'BUKAN WARGANEGARA',
            'PT' => 'PERMASTAUTIN TETAP',
        ];
    }

    /**
     * refCountries
     *
     * @return array
     */
    public static function refCountries()
    {
        return Ref::where(['cat' => '334', 'status' => '1'])
            ->orderBy('sort', 'asc')->pluck('descr', 'code')->toArray();
    }

    /**
     * senaraiKategoriBarangRampasan
     *
     * @return array
     */
    public static function senaraiKategoriBarangRampasan()
    {
        return [
            'AKSESORI' => 'AKSESORI',
            'KASUT' => 'KASUT',
            'KENDERAAN' => 'KENDERAAN',
            'MAKANAN' => 'MAKANAN',
            'PAKAIAN' => 'PAKAIAN',
        ];
    }

    /**
     * senaraiJenisBarangRampasan
     *
     * @return array
     */
    public static function senaraiJenisBarangRampasan()
    {
        return [
            'BAJU' => 'BAJU',
            'KASUT SUKAN' => 'KASUT SUKAN',
            'SELIPAR' => 'SELIPAR',
            'SELUAR' => 'SELUAR',
            'STOKIN' => 'STOKIN',
        ];
    }

    /**
     * senaraiJenisBarangRampasan2
     *
     * @return array
     */
    public static function senaraiJenisBarangRampasan2()
    {
        return [
            'BAJU' => 'BAJU',
            'KASUT SUKAN' => 'KASUT SUKAN',
            'SELIPAR' => 'SELIPAR',
            'SELUAR' => 'SELUAR',
            'STOKIN' => 'STOKIN',
        ];
    }

    /**
     * senaraiKaryaTempatanAntarabangsa
     *
     * @return array
     */
    public static function senaraiKaryaTempatanAntarabangsa()
    {
        return [
            '1 KG' => '1 KG',
            '1 LITER' => '1 LITER',
            '14 KG' => '14 KG',
            'ACROBAT X PRO' => 'ACROBAT X PRO',
            'AUTOCAD 2012' => 'AUTOCAD 2012',
            'AUTOCAD 2013' => 'AUTOCAD 2013',
        ];
    }

    /**
     * senaraiUnit
     *
     * @return array
     */
    public static function senaraiUnit()
    {
        return [
            'KG' => 'KG',
            'LITER' => 'LITER',
            'TANGKI' => 'TANGKI',
            'TONG' => 'TONG',
        ];
    }

    /**
     * statusGroups
     *
     * @return array
     */
    public static function statusGroups()
    {
        return [
            'BS' => 'BS - BELUM SELESAI',
            'S' => 'S - SELESAI',
            'T' => 'T - DITUTUP',
        ];
    }

    /**
     * statusCases
     *
     * @return array
     */
    public static function statusCases()
    {
        return [
            'BELUM SELESAI' => [
                'BS' => 'BS - BELUM SELESAI',
                'M' => 'M - MAHKAMAH',
                'E' => 'E - EKSIBIT',
            ],
            'SELESAI' => [
                'S' => 'S - SELESAI'
            ],
            'DITUTUP' => [
                'T' => 'T - DITUTUP'
            ],
        ];
    }

    /**
     * statusKesDets
     *
     * @return array
     */
    public static function statusKesDets()
    {
        return [
            'BELUM SELESAI' => [
                'BS01' => 'BS01 - DALAM SIASATAN',
                'BS02' => 'BS02 - LAPORAN PAKAR/KIMIA',
                'BS03' => 'BS03 - TPR',
                'BS04' => 'BS04 - TAWARAN KOMPAUN',
                'BS05' => 'BS05 - RAYUAN KOMPAUN',
                'BS06' => 'BS06 - DNAA',
            ],
            'MAHKAMAH' => [
                'M01' => 'M01 - DAFTAR MAHKAMAH',
                'M02' => 'M02 - SEBUT KES',
                'M03' => 'M03 - BICARA',
            ],
            'EKSIBIT' => [
                'E01' => 'E01 - PEMULANGAN',
                'E02' => 'E02 - MOHON PELUPUSAN',
                'E03' => 'E03 - PELUPUSAN',
            ],
            'SELESAI' => [
                'S01' => 'S01 - NFA',
                'S02' => 'S02 - KOMPAUN DIBAYAR',
                'S03' => 'S03 - DENDA',
                'S04' => 'S04 - PENJARA',
                'S05' => 'S05 - DENDA & PENJARA',
                'S06' => 'S06 - A&D (KALAH)',
            ],
        ];
    }

    /**
     * statusCaseCodes
     *
     * @return array
     */
    public static function statusCaseCodes()
    {
        return [
            '1' => 'Deraf',
            '2' => 'Aduan Baru',
            '3' => 'Pindah Cawangan',
            '4' => 'Dalam Siasatan',
            '5' => 'Selesai',
            '6' => 'Ditutup',
        ];
    }

    /**
     * Configure the Model
     **/
    public function model()
    {
        return Ref::class;
    }
}
