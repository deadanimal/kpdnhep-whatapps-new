<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use DB;
use App\EAduan;

class Letter extends Model
{
    use EAduan;
    protected $table = 'template_letter';
//    public $primaryKey = 'id';
    protected $fillable = [
        'title', 'header', 'body', 'footer', 'letter_type', 'letter_cat', 'letter_code', 'status'
    ];
    
//    public static function ShowType($letter_type) {
//        if ($letter_type == '01') {
//            return 'SURAT PENERIMAAN';
//        }
//        else if ($letter_type == '02') {
//            return 'SURAT PENUGASAN';
//        }
//    }
    
    public static function ShowType($letter_type) {
        $mLetter = DB::table('sys_ref')->where(['code' => $letter_type, 'cat' => '143'])->first();
        return $mLetter->descr;
    }
    
    public static function ShowStatus($status) {
        if ($status == '1') {
            return 'AKTIF';
        }
        else if ($status == '0') {
            return 'TIDAK AKTIF';
        }
    }
    
    public static function StatusAduan($letter_code) {
        $mLetter = DB::table('sys_ref')->where(['code' => $letter_code, 'cat' => '292'])->first();
        return $mLetter->descr;
//        return $this->hasOne('App\Ref', 'code', 'letter_code')->where('cat','292');
    }

    // kod status aduan pengguna
    public function cainvsts() {
        return $this->hasOne('App\Ref', 'code', 'letter_code')->where('cat','292');
    }

    // kod status aduan integriti
    public function ininvsts() {
        return $this->hasOne('App\Ref', 'code', 'letter_code')->where('cat','1334');
    }
}
