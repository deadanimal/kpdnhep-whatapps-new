<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Tip extends Model
{
    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'tip';

    /**
     * The primary key associated with the model.
     *
     * @var string
     */
    public $primaryKey = 'NO_KES';

    /**
     * Use of a non-incrementing or a non-numeric primary key associated with the model.
     *
     * @var bool
     */
    public $incrementing = false;

    /**
     * Indicates if the model should be timestamped.
     *
     * @var bool
     */
    public $timestamps = false;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'NO_KES','KOD_NEGERI','KOD_CAWANGAN','AKTA',
        'ASAS_TINDAKAN','SERAHAN_AGENSI','TKH_KEJADIAN','TKH_SERAHAN',
        'PENGELASAN_KES','KATEGORI_KESALAHAN','KESALAHAN','PEGAWAI_SERBUAN_RO',
        'PEGAWAI_PENYIASAT_AIO','PEGAWAI_PENYIASAT_IO','NO_REPORT_POLIS','NO_SSM',
        'NAMA_PREMIS_SYARIKAT','ALAMAT','JENAMA_PREMIS','KAWASAN',
        'JENIS_PERNIAGAAN','KATEGORI_PREMIS','JENIS_PREMIS','STATUS_OKT'
    ];

    /**
     * Validation rules
     *
     * @var array
     */
    public static $rules = [
        // 'NO_KES' => 'required',
        'KOD_NEGERI' => 'required',
        'KOD_CAWANGAN' => 'required',
        'AKTA' => 'required',
        'ASAS_TINDAKAN' => 'required',
        'SERAHAN_AGENSI' => 'required',
        'TKH_KEJADIAN' => 'required',
        'TKH_SERAHAN' => 'required',
        'PENGELASAN_KES' => 'required',
        'KATEGORI_KESALAHAN' => 'required',
        'KESALAHAN' => 'required',
        'PEGAWAI_SERBUAN_RO' => 'required',
        'PEGAWAI_PENYIASAT_AIO' => 'required',
        'PEGAWAI_PENYIASAT_IO' => 'required',
        'NO_REPORT_POLIS' => 'required',
        'NO_SSM' => 'required',
        'NAMA_PREMIS_SYARIKAT' => 'required',
        'ALAMAT' => 'required',
        'JENAMA_PREMIS' => 'required',
        'KAWASAN' => 'required',
        'JENIS_PERNIAGAAN' => 'required',
        'KATEGORI_PREMIS' => 'required',
        'JENIS_PREMIS' => 'required',
        'STATUS_OKT' => 'required',
    ];
}
