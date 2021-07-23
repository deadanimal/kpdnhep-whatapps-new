<?php

namespace App\Integriti;

use Illuminate\Database\Eloquent\Model;
use App\EAduanOld;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Facades\Auth;

class IntegritiPublicDoc extends Model
{
    use EAduanOld;
    use SoftDeletes;
    
    protected $table = 'integriti_case_doc';
    
    const CREATED_AT = 'IC_CREATED_AT';
    const UPDATED_AT = 'IC_UPDATED_AT';
    const CREATED_BY = 'IC_CREATED_BY';
    const UPDATED_BY = 'IC_UPDATED_BY';
    const DELETED_AT = 'IC_DELETED_AT';
    const DELETED_BY = 'IC_DELETED_BY';
    
    protected $fillable = ['IC_REMARKS'];
    
    /**
     * The attributes that should be mutated to dates.
     *
     * @var array
     */
    protected $dates = ['IC_CREATED_AT', 'IC_UPDATED_AT', 'IC_DELETED_AT'];

    public static function boot()
    {
        parent::boot();

        static::creating(function($model)
        {
            if(Auth::guest())
            {
                $model->{static::CREATED_BY} = '1';
                $model->{static::UPDATED_BY} = '1';
            } else {
                $model->{static::CREATED_BY} = Auth::user()->id;
                $model->{static::UPDATED_BY} = Auth::user()->id;
            }
        });
        
        static::updating(function($model)
        {
            // do some logging
            // override some property like $model->something = transform($something);
            $model->{static::UPDATED_BY} = Auth::user()->id;
        });

        /*
         * Deleting a model is slightly different than creating or deleting. For
         * deletes we need to save the model first with the deleted_by field
         * */
        static::deleting(function($model)
        {
            // $model->deleted_by = Auth::user()->id;
            $model->{static::DELETED_BY} = Auth::user()->id;
            $model->save();
        });
    }
}
