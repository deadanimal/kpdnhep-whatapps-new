<?php

namespace App\Pertanyaan;

use App\EAduan;
use Illuminate\Database\Eloquent\Model;

class AskAnswerTemplate extends Model
{
	use EAduan;

    protected $fillable = [
        'category', 'code', 'title', 'body', 'body_en', 'sort', 'status',
    ];
}
