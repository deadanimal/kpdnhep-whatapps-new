<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use App\EAduan;

class Log extends Model
{
    use EAduan;
    protected $table = 'sys_log';
    public $primaryKey = 'id';
    
    public function AdminUser() {
        return $this->hasOne('App\User', 'id', 'created_by');
    }
}
