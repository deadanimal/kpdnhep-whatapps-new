<?php

namespace App\Repositories\Feedback\Email;

use App\Models\Feedback\FeedEmail;
use App\Models\Feedback\FeedEmailDetail;
use Webklex\IMAP\Facades\Client;

class EmailFetcherRepository
{
    /**
     * link all feed with ticket
     * @param string $since
     * @param array $folders
     */
    public static function fetch($since = '', $folders = [])
    {
        if (count($folders) === 0) {
            $folders = [
                config('imap.setting.folder.inbox'),
                config('imap.setting.folder.read')
            ];
        }

        if ($since === '') {
            $since = FeedEmailDetail::orderBy('id', 'desc')
                ->first();

            if ($since != null) {
                $since = $since->created_at->format('d.m.Y');
            } else {
                $since = '01.08.2019';
            }
        }

        $oClient = Client::account('default');

        try {
            $oClient->connect();
        } catch (Exception $e) {
            $old = file_get_contents('storage/logs/imap.log');
            $file = fopen('storage/logs/imap.log', 'w');
            fwrite($file, date('Y-m-d H:i:s') . " - " . $e . "\n" . $old);
            fclose($file);
            echo 'error';
        }

        foreach ($folders as $folder) {
            echo $folder;
            $oFolder = $oClient->getFolder($folder);
            static::fetchByFolder($since, $oFolder);
        }
    }

    /**
     * @param \App\Repositories\Feedback\Email\string $since
     * @param $oFolder
     * @param bool $is_moved
     * @param bool $is_single
     * @param bool $is_fetch_flags
     * @param bool $is_fetch_body
     * @param bool $is_fetch_attachment
     */
    public static function fetchByFolder(string $since, $oFolder, $is_moved = true, $is_single = false, $is_fetch_flags = false, $is_fetch_body = false, $is_fetch_attachment = false)
    {
        $count = $oFolder->query(null)->since($since)->leaveUnread()->count();
        if ($count > 5) {
            $page = floor($count / 5) + ceil($count % 5);

            for ($i = 1; $i <= $page; $i++) {
                $aMessages = $oFolder
                    ->messages(null)
                    ->since($since)
                    ->limit(5, $i)
//            ->setFetchFlags($is_fetch_flags)
//            ->setFetchBody($is_fetch_body)
//            ->setFetchAttachment($is_fetch_attachment)
                    ->get();

                foreach ($aMessages as $message) {
                    // fetch real data from id
                    if ($is_single) {
                        $message = $message->getMessage($message->id);
                    }

                    $message_reply_id = null;
                    $references = [];
                    if ($message->getReferences()) {
                        $references = explode(' ', $message->getReferences());
                        $message_reply_id = substr($references[0], 1, -1);
                    }

                    // check if sender is send from the system or not
                    if ($message->from[0]->mail != 'MicrosoftExchange329e71ec88ae4615bbc36ab6ce41109e@1GOVUC.GOV.MY') {
                        if ($message->from[0]->mail == config('imap.accounts.default.username')) {
                            $is_input = 0;
                            $sender_mail = $message->to[0]->mail;
                        } else {
                            $is_input = 1;
                            $sender_mail = $message->from[0]->mail;
                        }

                        $feedEmailData = static::storeFeedEmail($sender_mail);
//                    $feedEmailData['message_reply_id'] = explode(" ", trim(preg_replace('/\s+/', ' ', $message->references)));
                        $feedEmailDetail = static::storeFeedEmailDetail($message, $feedEmailData, $is_input, $message_reply_id);

                        //if ($is_moved && $feedEmailDetail && $message->moveToFolder(config('imap.setting.folder.read')) == true) {
                        //  echo 'Message has ben moved';
                        //} else {
                        echo 'Message could not be moved';
                        //    }
                    } else {
                        echo ' rejected ';
                    }
                }
            }
        } else
            if ($count > 0) {
                echo 'less then 5';
                $aMessages = $oFolder
                    ->messages(null)
                    ->since($since)
//            ->setFetchFlags($is_fetch_flags)
//            ->setFetchBody($is_fetch_body)
//            ->setFetchAttachment($is_fetch_attachment)
                    ->get();

                foreach ($aMessages as $message) {
                    // fetch real data from id
                    if ($is_single) {
                        $message = $message->getMessage($message->id);
                    }

                    $message_reply_id = null;
                    $references = [];
                    if ($message->getReferences()) {
                        $references = explode(' ', $message->getReferences());
                        $message_reply_id = substr($references[0], 1, -1);
                    }

                    // check if sender is send from the system or not
                    if ($message->from[0]->mail != 'MicrosoftExchange329e71ec88ae4615bbc36ab6ce41109e@1GOVUC.GOV.MY') {
                        if ($message->from[0]->mail == config('imap.accounts.default.username')) {
                            $is_input = 0;
                            $sender_mail = $message->to[0]->mail;
                        } else {
                            $is_input = 1;
                            $sender_mail = $message->from[0]->mail;
                        }

                        $feedEmailData = static::storeFeedEmail($sender_mail);
//                    $feedEmailData['message_reply_id'] = explode(" ", trim(preg_replace('/\s+/', ' ', $message->references)));
                        $feedEmailDetail = static::storeFeedEmailDetail($message, $feedEmailData, $is_input, $message_reply_id);

                        //if ($is_moved && $feedEmailDetail && $message->moveToFolder(config('imap.setting.folder.read')) == true) {
                        //  echo 'Message has ben moved';
                        //} else {
                        echo 'Message could not be moved';
                        //    }
                    } else {
                        echo 'x';
                    }
                }
            } else {
                echo 'empty';
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
    public
    static function storeFeedEmail($sender_mail): array
    {
        $data = [];
        $data['feedEmail'] = FeedEmail::firstOrCreate(['email' => $sender_mail], ['email' => $sender_mail]);

        return $data;
    }

    /**
     * store every mail feed detail
     * @param $message
     * @param $feedEmailData
     * @param int $is_input
     * @param string $message_reply_id
     * @return bool
     */
    public
    static function storeFeedEmailDetail($message, $feedEmailData, $is_input = 0, $message_reply_id = null): bool
    {
        $feedEmailDetail = FeedEmailDetail::where('message_id', $message->message_id)->first();

        if (!$feedEmailDetail) {
            echo 'uid:' . $message->uid . "<br/>";

            $feedEmailDetail = new FeedEmailDetail();

            if ($message->hasTextBody()) {
                $body = $message->getTextBody();
            } else if ($message->hasHTMLBody()) {
                $body = iconv('', 'UTF-8//IGNORE', $message->getHTMLBody());
            } else {
                $body = '-';
            }

            $feedEmailDetail->fill([
                'feed_email_id' => $feedEmailData['feedEmail']->id,
                'subject' => $message->subject,
                'body' => $body,
                'is_input' => $is_input,
                'message_id' => $message->message_id,
                'message_reply_id' => $message_reply_id,
                'created_at' => $message->getDate()->toDateTimeString(),
            ])->save();

        } else {
            return false;
        }

        return true;
    }

    /**
     * Configure the Model
     **/
    public
    function model()
    {
        return FeedEmail::class;
    }
}
