<?php

namespace App\Repositories\Feedback\Email;

use App\Models\Feedback\FeedEmail;
use App\Models\Feedback\FeedEmailDetail;
use Illuminate\Support\Facades\Mail;
use Webklex\IMAP\Facades\Client;

class EmailReplierRepository
{
    /**
     * link all feed with ticket
     * @param $email
     */
    public static function send($email, $data)
    {
        Mail::send('', $data, function ($message) use ($data, $email) {
            $message->to($data->email, $data->name)
                ->subject('your message')
                ->getHeaders()
                ->addTextHeader('x-mailgun-native-send', 'true')
                ->addTextHeader('In-Reply-To', $email->parent->message_id)
                ->addTextHeader('References', $email->message_references);
        });
    }

    /**
     * @param string $since
     * @param $oFolder
     */
    public static function fetchByFolder(string $since, $oFolder)
    {
        $aMessages = $oFolder->query()
            ->since($since)
            ->get();

        foreach ($aMessages as $message) {
            // check if sender is system or not
            if ($message->from[0]->mail == config('imap.accounts.default.username')) {
                $is_input = 0;
                $sender_mail = $message->to[0]->mail;
            } else {
                $is_input = 1;
                $sender_mail = $message->from[0]->mail;
            }

            $feedEmailData = static::storeFeedEmail($message, $sender_mail);

            $feedEmailDetail = static::storeFeedEmailDetail($message, $feedEmailData, $is_input);

            if ($feedEmailDetail && $message->moveToFolder(config('imap.setting.folder.read')) == true) {
                echo 'Message has ben moved';
            } else {
                echo 'Message could not be moved';
            }
        }

        return;
    }

    /**
     * store every mail feed
     *
     * @param $message
     * @param $sender_mail
     * @return array
     */
    public static function storeFeedEmail($message, $sender_mail): array
    {
        $data = [];
        $data['feedEmail'] = FeedEmail::where('email', $sender_mail)->first();

        $data['message_reply_id'] = explode(" ", trim(preg_replace('/\s+/', ' ', $message->references)));

        if (!$data['feedEmail']) {
            $feedEmail = new FeedEmail();

            $feedEmail->fill([
                'email' => $sender_mail,
            ])->save();
        }

        return $data;
    }

    /**
     * store every mail feed detail
     * @param $message
     * @param $feedEmailData
     * @param int $is_input
     * @return bool
     */
    public static function storeFeedEmailDetail($message, $feedEmailData, $is_input = 0): bool
    {
        $feedEmailDetail = FeedEmailDetail::where('message_id', $message->message_id)->first();

        if (!$feedEmailDetail) {
            echo 'uid:' . $message->uid . "<br/>";

            $feedEmailDetail = new FeedEmailDetail();

            $feedEmailDetail->fill([
                'feed_email_id' => $feedEmailData['feedEmail']->id,
                'subject' => $message->subject,
                'body' => isset($message->bodies['text']) ? $message->bodies['text']->content : '-',
                'is_input' => $is_input,
                'message_id' => $message->message_id,
                'message_reply_id' => $feedEmailData['message_reply_id'][0] != '' ? substr($feedEmailData['message_reply_id'][0], 1, -1) : null,
            ])->save();
        } else {
            return false;
        }

        return true;
    }

    /**
     * Configure the Model
     **/
    public function model()
    {
        return FeedEmail::class;
    }
}
