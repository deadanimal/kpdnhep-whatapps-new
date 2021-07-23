<?php

namespace App;

use Illuminate\Support\Facades\Auth;
// /laravel/app/Updater.php 

trait EAduan {
    
    protected static function boot() {
        
        parent::boot(); /* * During a model create Eloquent will also update the updated_at field so * need to have the updated_by field here as well * */ 
        
        static::creating(function($model) {
            if(Auth::guest()) {
                $model->created_by = '1';
                $model->updated_by = '1';
            }else{
                $model->created_by = Auth::user()->id;
                $model->updated_by = Auth::user()->id;
            }
        });
 
        static::updating(function($model)  {
            if(Auth::guest()) {
                $model->updated_by = '1';
            }else{
                $model->updated_by = Auth::user()->id;
            }
        });
        /*
         * Deleting a model is slightly different than creating or deleting. For
         * deletes we need to save the model first with the deleted_by field
         * */
//        static::deleting(function($model)  {
//            $model->deleted_by = Auth::user()->id;
//            $model->save();
//        });
    }
}