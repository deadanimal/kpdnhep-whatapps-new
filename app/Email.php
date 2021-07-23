<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use DB;
use App\EAduan;

class Email extends Model
{
    use EAduan;
    protected $table = 'template_email';
//    public $primaryKey = 'id';
    
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'title', 'header', 'body', 'footer', 'email_type', 'email_cat', 'email_code', 'status'
    ];
    
    public function EmailType() {
        return $this->hasOne('App\Ref', 'code', 'email_type')->where('cat', '149');
    }
    
//    public static function ShowEmailType($email_type) {
//        if ($email_type == '01') {
//            return 'EMEL PENERIMAAN';
//        }
//        else if ($email_type == '02') {
//            return 'EMEL HASIL SIASATAN';
//        }
//    }
    
    public static function ShowEmailType($email_type) {
        $mEmail = DB::table('sys_ref')->where(['code' => $email_type, 'cat' => '149'])->first();
        return $mEmail->descr;
    }
    
    public static function ShowStatus($status) {
        if ($status == '1') {
            return 'AKTIF';
        }
        else if ($status == '0') {
            return 'TIDAK AKTIF';
        }
    }
}
