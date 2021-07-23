<?php

namespace App\Libraries\Feedback\BotMan;

use BotMan\BotMan\BotMan;
use BotMan\BotMan\BotManFactory;
use BotMan\BotMan\Cache\LaravelCache;
use BotMan\BotMan\Drivers\DriverManager;
use BotMan\Drivers\Telegram\TelegramDriver;
use Log;

class BotManInitialLibrary
{
    public static function startBotMan($driver = 'telegram')
    {
        switch ($driver) {
            case 'telegram':
            default:
                DriverManager::loadDriver(TelegramDriver::class);
                break;
        }

        // Create an instance
        $botman = BotManFactory::create(config('botman'), new LaravelCache());

        // make simple one
        $question = <<<EOT
Tuan/Puan

Terima kasih kerana menghubungi KPDNHEP. Sila lengkapkan butiran aduan seperti berikut :

a) Nama (Nama Penuh Mengikut K/P)
b) No.K/P (Sila Nyatakan No K/P Yang Sah)
c) Alamat surat menyurat
d) No telefon untuk dihubungi
e) Email (Sekiranya ada)
f) Nama Premis
g) Alamat Premis (Sila Nyatakan Alamat Lengkap Termasuk Daerah)
h) Keterangan Aduan
i) Gambar Bukti (Sekiranya Ada)
j) Sekiranya Aduan Berkenaan Transaksi Atas Talian Mohon Kemukakan Nama Bank Dan No Akaun Bank / No Transaksi FPX Yang Terlibat

Setelah selesai, sila taip TAMAT. 
Terima kasih.

Hanya aduan melalui pesanan teks sahaja yang akan diproses.
Sekiranya Tuan/Puan gagal untuk melengkapkan maklumat yang diperlukan dalam tempoh 3 hari bekerja, pihak kami menganggap Tuan/Puan tidak berminat untuk meneruskan aduan ini dan seterusnya aduan ini akan DITUTUP

#maklumattidaklengkap
EOT;

        $botman->reply($question);
//
//        Log::debug('BotManInitialLibrary.startBotMan', [json_encode($botman)]);
//
//        // Give the bot something to listen for.
//        $botman->hears('/mula', function (BotMan $bot) {
//            $bot->startConversation(new OnboardingConversation);
//        });
//
        $botman->hears('/mula', function(BotMan $bot) use ($question) {
            $bot->reply($question);
        });

        $botman->hears('/henti', function(BotMan $bot) use ($question) {
            $bot->reply($question);
        });

        // Start listening
        $botman->listen();

        return;
    }
}
