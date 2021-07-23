<?php

namespace App\Aduan;

use Illuminate\Database\Eloquent\Model;
use App\EAduan;

class DataEntryDoc extends Model
{
    public $table = 'case_doc';
    use EAduan;
}
