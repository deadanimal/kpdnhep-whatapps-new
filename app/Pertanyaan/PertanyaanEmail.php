<?php

namespace App\Pertanyaan;

use Illuminate\Database\Eloquent\Model;

class PertanyaanEmail extends Model
{
    protected $table = 'ask_email';
    
    const CREATED_AT = 'AE_CREDT';
    const UPDATED_AT = 'AE_MODDT';
    const CREATED_BY = 'AE_CREBY';
    const UPDATED_BY = 'AE_MODBY';

    protected $fillable = ['AE_ID','AE_ASKID','AE_TITLE','AE_TO','AE_CC','AE_BCC','AE_MESSAGE'];

    public function CreBy() {
        return $this->hasOne('App\User', 'id', 'AE_CREBY');
    }
}
