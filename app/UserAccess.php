<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class UserAccess extends Model
{
    public $table = "sys_user_access";
    public $timestamps = false;
    
//    public $primaryKey = 'role_code';
//    public $primaryKey = 'user_id';
}
