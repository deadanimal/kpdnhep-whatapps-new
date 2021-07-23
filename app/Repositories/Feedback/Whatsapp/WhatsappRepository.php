<?php

namespace App\Repositories\Feedback\Whatsapp;

use App\Models\Feedback\FeedWhatsapp;

class WhatsappRepository
{
    /**
     * Update feed whatsapp active status
     * @param $id
     * @param $is_active
     * @param null $supporter_id
     */
    public static function updateActiveStatus($id, $is_active, $supporter_id = null)
    {
        FeedWhatsapp::where('id', $id)
            ->update([
                'is_active' => $is_active,
                'supporter_id' => $supporter_id // clean it
            ]);

        return;
    }

    /**
     * Check if the feed is mine or redirect it if something else happen.
     * @param $id
     * @param $redirect
     * @return FeedWhatsapp|\Illuminate\Database\Eloquent\Model|\Illuminate\Http\RedirectResponse
     */
    public static function findIsMineOrRedirect($id, $redirect)
    {
        $feedback = FeedWhatsapp::where('id', $id)
            ->where('supporter_id', auth()->user()->id)
            ->first();

        if (!$feedback) {
            return redirect()->action($redirect);
        }

        return $feedback;
    }

    /**
     * Configure the Model
     **/
    public function model()
    {
        return FeedWhatsapp::class;
    }
}