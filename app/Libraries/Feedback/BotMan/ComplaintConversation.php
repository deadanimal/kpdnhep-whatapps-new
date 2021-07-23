<?php

namespace App\Libraries\Feedback\BotMan;

use App\Helpers\EmailValidationHelper;
use App\Models\Feedback\FeedTelegram;
use App\Models\Feedback\FeedTelegramDetail;
use BotMan\BotMan\Messages\Conversations\Conversation;
use BotMan\BotMan\Messages\Incoming\Answer;
use Carbon\Carbon;
use Request;

/**
 * Class ComplaintConversation
 * @package App\Libraries\Feedback\BotMan
 */
class ComplaintConversation extends Conversation
{
    /**
     * @var string
     */
    public $name;
    /**
     * @var string
     */
    public $identity;
    /**
     * @var string
     */
    public $phoneNumber;
    /**
     * @var string
     */
    public $email;
    /**
     * @var string
     */
    public $complaintDescription;
    /**
     * @var string
     */
    public $userAddress;
    /**
     * @var string
     */
    public $premiseAddress;
    /**
     * @var string
     */
    public $complaintImages;
    /**
     * @var string
     */
    public $onlineBankName;
    /**
     * @var string
     */
    public $onlineAttachment;

    public function askName()
    {
        $this->ask('Sila berikan nama penuh anda. Seperti di dalam MyKad.', function (Answer $answer) {
            // Save result
            $this->name = $answer->getText();
            self::askIdentity();
        });
    }

    public function askIdentity()
    {
        $this->ask('Kemudian, berikan pula nombor MyKad anda.', function (Answer $answer) {
            // Save result
            $this->identity = $answer->getText();
            self::askUserAddress();
        });
    }

    public function askUserAddress()
    {
        $this->ask('Terima kasih, masukkan alamat surat menyurat anda.', function (Answer $answer) {
            // Save result
            $this->userAddress = $answer->getText();
            self::askPhoneNumber();
        });
    }

    public function askPhoneNumber()
    {
        $this->ask('Sila masukkan nombor telefon yang boleh untuk dihubungi.', function (Answer $answer) {
            // Save result
            $this->phoneNumber = $answer->getText();
            self::askEmail();
        });
    }

    public function askEmail()
    {
        $this->ask('Sila masukkan emel (jika ada). Masukkan "-" jika tiada', function (Answer $answer) {
            // Save result
            $this->email = EmailValidationHelper::validEmail($answer->getText()) ? $answer->getText() : null;
            self::askPremiseAddress();
        });
    }

    public function askPremiseAddress()
    {
        $this->ask('Sila masukkan alamat premis yang lengkap termasuk daerah dan negeri.', function (Answer $answer) {
            // Save result
            $this->premiseAddress = $answer->getText();
            self::askComplaintDescription();
        });
    }

    public function askComplaintDescription()
    {
        $this->ask('Sila masukkan keterangan aduan.', function (Answer $answer) {
            // Save result
            $this->complaintDescription = $answer->getText();
            self::askComplaintImages();
        });
    }

    public function askComplaintImages()
    {
        $this->ask('Sila masukkan gambar jika ada.', function (Answer $answer) {
            // Save result
            $this->complaintImages = $answer->getText();
            self::askIfOnline();
        });

//        $this->askForImages('Sila masukkan gambar jika ada.', function ($images) {
//            // $images contains an array with all images.
//
////            $this->complaintImages = $answer->getText();
//            return $this->askIfOnline();
//        });
    }

    public function askIfOnline()
    {
        $this->ask('Adakah ini merupakan transaksi secara atas talian?.', function (Answer $answer) {
            // Save result
            return $this->askOnlineBankName();
        });
    }

    public function askOnlineBankName()
    {
        $this->ask('Apakah nama bank dan nombor akaun atau nombor transaksi FPX yang terlibat.', function (Answer $answer) {
            // Save result
            $this->onlineBankName = $answer->getText();
            return $this->askOnlineAttachment();
        });
    }

    public function askOnlineAttachment()
    {
        $this->ask('Berikan bukti transaksi tersebut.', function (Answer $answer) {
            // Save result
            $this->onlineAttachment = $answer->getText();

            $this->say('Maklumat anda telah diterima. Berikut adalah maklumat yang anda masukkan.');
            self::storeComplaintData();
        });
    }

    public function storeComplaintData()
    {
        $data = [
            'name' => $this->name,
            'identity' => $this->identity,
            'phoneNumber' => $this->phoneNumber,
            'email' => $this->email,
            'complaintDescription' => $this->complaintDescription,
            'userAddress' => $this->userAddress,
            'premiseAddress' => $this->premiseAddress,
            'complaintImages' => $this->complaintImages,
            'onlineBankName' => $this->onlineBankName,
            'onlineAttachment' => $this->onlineAttachment,
        ];

        $text = '[NAMA] ' . $data['name'] . ' 
[NO KP] ' . $data['identity'] . ' 
[NO TEL] ' . $data['phoneNumber'] . ' 
[EMEL] ' . $data['email'] . ' 
[KETERANGAN ADUAN] ' . $data['complaintDescription'] . ' 
[ALAMAT PREMIS] ' . $data['premiseAddress'];

        $data = Request::all();

        $message = isset($data['message']) ? $data['message'] : $data['edited_message'];

        // store telegram main data
        $telegramUser = FeedTelegram::where('user_id', $message['from']['id'])->first();

        FeedTelegramDetail::create([
            'feed_telegram_id' => $telegramUser->id,
            'update_id' => uniqid(),
            'message_id' => uniqid(),
            'message_text' => $text,
            'message_date' => Carbon::parse(),
            'is_input' => 0,
        ]);

        $this->say($text);
    }

    public function run()
    {
        $this->askName();
    }
}
