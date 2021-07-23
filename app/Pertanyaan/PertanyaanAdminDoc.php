<?php

namespace App\Pertanyaan;

use Illuminate\Database\Eloquent\Model;
use App\EAduan;

class PertanyaanAdminDoc extends Model
{
    protected $table = 'ask_doc';
    use EAduan;
}