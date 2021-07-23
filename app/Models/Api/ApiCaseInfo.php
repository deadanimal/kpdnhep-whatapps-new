<?php

namespace App\Models\Api;

use Illuminate\Database\Eloquent\Model;

class ApiCaseInfo extends Model
{
    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'case_info';

    /**
     * Constants to customize the names of the columns used to store the timestamps.
     */
    const CREATED_AT = 'CA_CREDT';
    const UPDATED_AT = 'CA_MODDT';
}
