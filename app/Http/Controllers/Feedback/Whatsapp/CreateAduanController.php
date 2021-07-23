<?php

namespace App\Http\Controllers\Feedback\Whatsapp;

use App\Http\Controllers\Controller;
use App\Library\Feedback\Whatsapp\WhatsappMessageLibrary;
use App\Repositories\Feedback\Whatsapp\WhatsappRepository;
use Illuminate\Http\Request;

class CreateAduanController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    /**
     * Prepare to create new aduan
     * @param Request $request
     * @param $id
     * @return \Illuminate\Http\RedirectResponse
     */
    public function __invoke(Request $request, $id)
    {
        $feedback = WhatsappRepository::findIsMineOrRedirect($id, 'Feedback\Whatsapp\WhatsappController@index');

        $feedback_data = [
            'info' => [],
            'image' => '',
            'raw' => '',
            'type' => 'ws',
            'id' => '',
        ];

        $feedback_details = [];

        if($request->get('ws_detail')) {
            $feedback_details = $feedback->detail()->whereIn('id', $request->get('ws_detail'))->get();
        }

        foreach ($feedback_details as $feedback_detail) {
            $feedback_data['id'] .= $feedback_detail->id . ';';
            if ($feedback_detail['is_have_attachment'] == 1) {
                $feedback_data['image'] .= $feedback_detail->message_url . ';';
            } elseif (strpos($feedback_detail['message'], '/aduan_mula') == 0) {
                $feedback_data['info'] = WhatsappMessageLibrary::separateMessage($feedback_detail['message']);
            }
            $feedback_data['raw'] .= ' | ' . $feedback_detail['message'];
        }

        $feedback_data['id'] = trim($feedback_data['id'], ";");
        $feedback_data['image'] = trim($feedback_data['image'], ";");
        $feedback_data['info']['phone'] = $feedback->phone;

        return redirect()->action('Aduan\AdminCaseController@create', ['feedback' => $feedback_data, 'sender' => $feedback->phone]);
    }
}
