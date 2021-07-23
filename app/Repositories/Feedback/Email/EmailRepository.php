<?php

namespace App\Repositories\Feedback\Email;

use App\Models\Feedback\FeedEmail;

class EmailRepository
{
    /**
     * Update feed whatsapp active status
     * @param $id
     * @param $is_active
     * @param null $supporter_id
     */
    public static function updateActiveStatus($id, $is_active, $supporter_id = null)
    {
        FeedEmail::where('id', $id)
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
     * @return \App\Models\Feedback\FeedEmail|\Illuminate\Database\Eloquent\Model|\Illuminate\Http\RedirectResponse|null
     */
    public static function findIsMineOrRedirect($id, $redirect)
    {
        $feedback = FeedEmail::where('id', $id)
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
        return FeedEmail::class;
    }
}