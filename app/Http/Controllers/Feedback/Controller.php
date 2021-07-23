<?php

namespace App\Http\Controllers\Feedback;

use App\Http\Controllers\Controller as BaseController;
use App\Models\Feedback\FeedTemplate;

class Controller extends BaseController
{
    public function __construct()
    {
        $this->middleware('auth')->only('index');
    }

    /**
     * get DT data for new whatsapp feedback
     * @return mixed
     * @throws \Exception
     */
    public function index()
    {
        return view('feedback.index');
    }


}
