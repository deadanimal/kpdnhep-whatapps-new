<?php

namespace App\Libraries\Feedback;

use App\Models\Feedback\FeedTemplate;

class FeedbackTemplateLibrary
{
    /**
     * @param string $message
     * @param bool $is_first_time
     * @return string
     */
    public static function checkTemplate($message, $is_first_time = false)
    {
        $template = '';

        // iff its a text or first time
        // then return this template
        if ($message || $is_first_time) {
            switch (true) {
                case $is_first_time:
                    $template = 'first';
                    break;
                case strpos($message, "[KPDNHEP]") === false && strpos($message, "#aduansenarai") !== false:
                    $template = 'aduan_senarai';
                    break;
                case strpos($message, "[KPDNHEP]") === false && strpos($message, "#aduanstatus") !== false:
                    $template = 'aduan_status';
                    break;
                case strpos($message, "[KPDNHEP]") === false && strpos($message, "#aduangambar") !== false:
                    $template = 'aduan_gambar';
                    break;
                case strpos($message, "[KPDNHEP]") === false && strpos($message, "#aduancontoh") !== false:
                    $template = 'aduan_contoh';
                    break;
                case strpos($message, "[KPDNHEP]") === false && strpos($message, "#aduantamat") !== false:
                    $template = 'aduan_tamat';
                    break;
            }

            return $template;
        }
        return '';
    }

    public static function prepareTemplate($template, $data) {
        switch ($template) {
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
            case 'aduan_cipta':
                $message = '[KPDNHEP] Informasi anda telah diterima menjadi aduan. Nombor aduan anda adalah:' . $data;
                break;
            case 'first':
            default:
                $message = '[KPDNHEP] ' . FeedTemplate::find(1)->body;
                break;
        }

        return $message;
    }
}
