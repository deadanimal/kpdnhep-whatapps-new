<?php

namespace App\Libraries\Feedback\Whatsapp;

use App\Models\Feedback\FeedTemplate;
use App\Repositories\Feedback\Whatsapp\WaboxappRepository;
use Log;

class WhatsappTemplateLibrary
{
    /**
     * @param array $data
     * @param $feedback
     * @param $f_data
     * @param bool $is_first_time
     * @return mixed|string
     */
    public static function checkTemplate($data, $feedback, $f_data, $is_first_time = false)
    {
        $template = '';
        if (isset($data['message']['body']['text']) || $is_first_time) {
            switch (true) {
                case $is_first_time:
                    $template = 'first';
                    break;
                case strpos($data['message']['body']['text'], "[KPDNHEP]") === false && strpos($data['message']['body']['text'], "#aduansenarai") !== false:
                    $template = 'aduan_senarai';
                    break;
                case strpos($data['message']['body']['text'], "[KPDNHEP]") === false && strpos($data['message']['body']['text'], "#aduanstatus") !== false:
                    $template = 'aduan_status';
                    break;
                case strpos($data['message']['body']['text'], "[KPDNHEP]") === false && strpos($data['message']['body']['text'], "#aduangambar") !== false:
                    $template = 'aduan_gambar';
                    break;
                case strpos($data['message']['body']['text'], "[KPDNHEP]") === false && strpos($data['message']['body']['text'], "#aduancontoh") !== false:
                    $template = 'aduan_contoh';
                    break;
//                case strpos($data['message']['body']['text'], "[KPDNHEP]") === false && strpos($data['message']['body']['text'], "#aduanmula") !== false:
//                    $template = 'aduan_gambar';
//                    break;
                case strpos($data['message']['body']['text'], "[KPDNHEP]") === false && strpos($data['message']['body']['text'], "#aduantamat") !== false:
                    $template = 'aduan_tamat';
                    break;
            }
            return self::sendTemplate($data['contact']['uid'], $template, $f_data, $feedback, $data, '', false, $is_first_time);

        }
        return 'not enter check template function';
    }

    /**
     * @param $contact_no
     * @param $template
     * @param $f_data
     * @param $feedback
     * @param string $data
     * @param string $message
     * @param bool $is_reply
     * @param bool $is_first_time
     * @return mixed|string
     */
    public static function sendTemplate($contact_no, $template, $f_data, $feedback, $data = '', $message = '', $is_reply = false, $is_first_time = false)
    {
        if ($is_reply === false) {
            switch ($template) {
                case 'first':
                    $message = '[KPDNHEP] ' . FeedTemplate::find(1)->body;
                    break;
                case 'aduan_tamat':
                    $message = '[KPDNHEP] ' . FeedTemplate::find(2)->body;
                    break;
                case 'aduan_senarai':
                    $message = '[KPDNHEP] Berikut adalah senarai aduan yang anda pernah berikan: ' . $data . '. Taip /aduan_status diikuti nombor aduan untuk mengetahui status aduan anda.';
                    break;
                case 'aduan_status':
                    $message = '[KPDNHEP] Status bagi aduan ini adalah: ' . $data;
                    break;
                case 'aduan_contoh':
                    $message = '*#aduanmula*
a) Nama: (Nama Penuh Mengikut K/P)
b) No. K/P: (Sila Nyatakan No K/P Yang Sah)
c) Alamat Surat Menyurat:
d) Nombor Telefon Untuk Dihubungi:
e) Emel: (Sekiranya Ada)
f) Nama Premis:
g) Alamat Premis: (Sila Nyatakan Alamat Lengkap Termasuk Daerah)
h) Keterangan Aduan:
i) Gambar Bukti (Sekiranya Ada)
j) Sekiranya Aduan Berkenaan Transaksi Atas Talian Mohon Kemukakan Nama Bank Dan No Akaun Bank / No Transaksi FPX Yang Terlibat
';
                    break;
//                case 'aduan_gambar':
//                    $message = '[KPDNHEP] Jika anda mempunyai sebarang gambar sebagai bukti, sila kepilkan atau hantar /aduan_tamat untuk menamatkan aduan anda.';
//                    break;
                case 'aduan_cipta':
                    $message = '[KPDNHEP] Informasi anda telah diterima menjadi aduan. Nombor aduan anda adalah:' . $data;
                    break;
            }
        }
        return WaboxappRepository::send($contact_no, $message);
    }
}
