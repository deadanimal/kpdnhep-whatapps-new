<?php

namespace App;

use Illuminate\Support\Facades\Auth;

trait EAduanOld {
    
    protected static function boot() {
        
        parent::boot();
        
//        static::creating(function($model) {
//            $model->BR_CREBY = Auth::user()->id;
//            $model->BR_MODBY = Auth::user()->id;
//        });
        
        static::creating(function($model) {
            if(Auth::guest()) {
                $model->{static::CREATED_BY} = '1';
                $model->{static::UPDATED_BY} = '1';
            }else{
                $model->{static::CREATED_BY} = Auth::user()->id;
                $model->{static::UPDATED_BY} = Auth::user()->id;
            }
        });
        
//        static::updating(function($model) {
//            $model->BR_MODBY = Auth::user()->id;
//        });
        
        static::updating(function($model) {
            $model->{static::UPDATED_BY} = Auth::user()->id;
        });
        
    }
    
}
