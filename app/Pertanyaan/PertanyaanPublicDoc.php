<?php

namespace App\Pertanyaan;

use Illuminate\Database\Eloquent\Model;
use App\EAduan;

class PertanyaanPublicDoc extends Model
{
    protected $table = 'ask_doc';
    use EAduan;
}
