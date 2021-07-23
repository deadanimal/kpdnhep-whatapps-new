<?php

namespace App;

use App;
use App\Aduan\AdminCase;
use App\EAduan;
use App\Notifications\ResetPasswordNotification;
use App\UserAccess;
use DB;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Notifications\Notifiable;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Lab404\Impersonate\Models\Impersonate;
use Lab404\Impersonate\Services\ImpersonateManager;

class User extends Authenticatable
{
    use EAduan;
    use Notifiable;
    use Impersonate;
    protected $table = 'sys_users';
    public $primaryKey = 'id';
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'name', 'email', 'username', 'password', 'email_token', 'user_cat', 'status', 'gender', 'age', 'address', 'ctry_cd',
    ];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
        'password', 'remember_token',
    ];
    
    public static function ShowStatus($status) {
        if($status == '1') {
            return 'AKTIF';
        }else{
            return 'TIDAK AKTIF';
        }
    }
    public function Negeri() {
        return $this->hasOne('App\Ref', 'code', 'state_cd')->where('cat','17');
    }
    public function Daerah() {
        return $this->hasOne('App\Ref', 'code', 'distrinct_cd')->where('cat','18');
    }
    public function Cawangan() {
        return $this->hasOne('App\Branch', 'BR_BRNCD', 'brn_cd');
    }
    public function Role() {
        return $this->hasOne(UserAccess::class, 'user_id', 'id');
    }
    public function CtryCd() {
        return $this->hasOne('App\Ref', 'code', 'ctry_cd')->where('cat','334');
    }
    
    public static function ShowRoleName($role_code) {
        $mRef = DB::table('sys_ref')->where(['cat' => '152', 'code' => $role_code])->value('descr');
        return $mRef;
    }
    
    public static function GetDstrtList($state_cd, $placeholder = true) {
        $mDstrtList = DB::table('sys_ref')
                ->where('code','LIKE', $state_cd.'%')->where('code', '!=', $state_cd)->where('cat', '18')
                ->pluck('descr','code');
        if($placeholder == true) {
            $mDstrtList->prepend('-- SILA PILIH --', '');
            return $mDstrtList;
        } else{
            return $mDstrtList;
        }
    }
    
    public function routeNotificationForMail() {
        return $this->email;
    }
    
    public function GetTotalComplaint($userId)
    {
        $mUser = User::where(['id' => $userId])->first();
//        dd($mUser);
        $CountAduan = AdminCase::
//            where(['CA_DOCNO' => $mUser->icnew])
            where(function ($query) use ($mUser) {
                $query->where('CA_CREBY', '=', $mUser->id)
                    ->orWhere('CA_EMAIL', $mUser->email)
                    ->orWhere('CA_DOCNO', $mUser->icnew)
                    ;
            })
            ->whereNotNull('CA_CASEID')
            ->whereNotIn('CA_INVSTS', [10])
            ->count();
//        $CountAduan = count($mAduan);
        return $CountAduan;
    }
    
    public function genderdescr() {
        return $this->hasOne('App\Ref', 'code', 'gender')->where('cat', '202');
    }
    
    public function sendPasswordResetNotification($token)
    {
//        $locale = App::getLocale();
        $this->notify(new ResetPasswordNotification($token));
    }
    
    public function generateToken()
    {
        $this->api_token = str_random(60);
        $this->save();

        return $this->api_token;
    }
    
    public static function getDetails($userid) 
    {
        $mUser = User::where(['id' => $userid])->first();
        return $mUser;
    }
    
    public function NegeriMyidentity() {
        return $this->hasOne('App\Ref', 'code', 'myidentity_state_cd')->where('cat','17');
    }
    
    public function DaerahMyidentity() {
        return $this->hasOne('App\Ref', 'code', 'myidentity_distrinct_cd')->where('cat','18');
    }

    // public static function GetListYear($PlsSlct = true)
    public static function GetListYearPublicUser()
    {
        $mYear = DB::table('sys_users')
            ->select(DB::raw('DISTINCT YEAR(created_at) AS year'))
            ->whereNotNull('created_at')
            ->where('user_cat', '2')
            ->orderBy('year', 'asc')
            ->pluck('year', 'year');
        
        // if ($PlsSlct == true) {
            // $mYear->prepend('-- SILA PILIH --', '');
            // return $mYear;
        // } else {
        return $mYear;
        // }
    }

    public function whatsappResponder() {
        return $this->hasMany('App\Models\Feedback\FeedWhatsapp', 'responder_id');
    }

    // jawatan
    public function jobdest() {
        return $this->hasOne('App\Ref', 'code', 'job_dest')->where('cat', '164');
    }

    // dicipta oleh
    public function createdby() {
        return $this->hasOne('App\User', 'id', 'created_by');
    }

    /**
     * Impersonate the given user.
     *
     * @param Model $user
     * @return  bool
     */
    public function impersonate(Model $user)
    {
        return app(ImpersonateManager::class)->take($this, $user);
    }
}
