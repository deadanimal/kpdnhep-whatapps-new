<?php

namespace App\Libraries\Feedback\Telegram;

use App\Models\Feedback\FeedTemplate;
use App\Repositories\Feedback\Whatsapp\WhatsappRepository;

class TelegramMessageLibrary
{
    /**
     * Separate string and massage data
     * @param $data
     * @return array
     */
    public static function separateMessage($data)
    {
        $key = [
            'a) Nama' => 'name',
            'a)' => 'name',
            'b) No.K/P' => 'ic',
            'b)' => 'ic',
            'c) Alamat surat menyurat' => 'addr',
            'c) Alamat' => 'addr',
            'c)' => 'addr',
            'd) No telefon untuk dihubungi' => 'phone',
            'd) No telefon' => 'phone',
            'd)' => 'phone',
            'e) Email' => 'email',
            'e)' => 'email',
            'f) Nama Premis' => 'shop_name',
            'f)' => 'shop_name',
            'g) Alamat Premis' => 'shop_address',
            'g)' => 'shop_address',
            'h) Keterangan Aduan' => 'report',
            'h)' => 'report',
        ];

        $data_massage = explode(PHP_EOL, $data);

        $data_massage_final = [];
        foreach ($data_massage as $k => $v) {
            foreach ($key as $h => $i) {
                if (strpos($v, $h) !== false) {
                    echo 'h: ' . $h . ' - ' . 'v :', $v . '</br>';
                    if (!isset($data_massage_final[$i])) {
                        $data_massage_final[$i] = str_replace($h, '', $v);
                    }
                }
            }

        }

        return $data_massage_final;
    }

    /**
     * fill message data to prepare before insert it into db
     * @param $feedback_id
     * @param $request
     * @param $feedback
     * @param null $supporter_id
     * @return array
     * @throws \Exception
     */
    public static function fillMessage($feedback_id, $request, $feedback, $supporter_id = null)
    {
        $template_id = null;

        if (isset($request['message']['body']['text'])) {
            $text_array = explode("\n", substr($request['message']['body']['text'], -50));

            foreach ($text_array as $data) {
                if (substr($data, 0, 1) == '#') {
                    $template_id = FeedTemplate::where('code', substr($data, 1))->first()->id;
                    $supporter_id = $template_id != null ? $feedback->supporter_id : $supporter_id;

                    if ($template_id != '') {
                        WhatsappRepository::updateActiveStatus($feedback_id, 0, null);
                    }
                }
            }
        }

        return $f_data = [
            'feed_whatsapp_id' => $feedback_id,
            'supporter_id' => $supporter_id,
            'message' => $request['message']['body']['text'] ?? $request['message']['body']['caption'] ?? '-',
            'message_uid' => $request['message']['uid'] ?? 'msg-' . bin2hex(random_bytes(4)),
            'message_cuid' => $request['message']['cuid'] ?? bin2hex(random_bytes(10)),
            'message_type' => $request['message']['body']['type'] ?? null,
            'message_caption' => isset($request['message']['body']['caption']) ? strlen($request['message']['body']['caption']) > 100 ? mb_substr($request['message']['body']['caption'], 0, 100) . "..." : $request['message']['body']['caption'] : null,
            'message_mimetype' => $request['message']['body']['mimetype'] ?? null,
            'is_have_attachment' => isset($request['message']['body']['mimetype']) ? 1 : 0,
            'message_size' => $request['message']['body']['size'] ?? null,
            'message_duration' => $request['message']['body']['duration'] ?? null,
            'message_thumb' => isset($request['message']['body']['thumb']) ? 'thumb' : null,
            'message_url' => $request['message']['body']['url'] ?? null,
            'message_lng' => $request['message']['body']['lng'] ?? null,
            'message_lat' => $request['message']['body']['lat'] ?? null,
            'is_input' => (isset($request['message']['dir']) && $request['message']['dir'] === 'i') ? 1 : 0,
            'template_id' => $template_id,
        ];
    }
}
