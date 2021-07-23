<?php

namespace App\Libraries\Feedback\BotMan;

use BotMan\BotMan\Messages\Conversations\Conversation;
use BotMan\BotMan\Messages\Incoming\Answer;
use BotMan\BotMan\Messages\Outgoing\Actions\Button;
use BotMan\BotMan\Messages\Outgoing\Question;
use BotMan\Drivers\Telegram\Extensions\Keyboard;
use BotMan\Drivers\Telegram\Extensions\KeyboardButton;
use Log;

/**
 * Class OnboardingConversation
 * @package App\Libraries\Feedback\BotMan
 */
class OnboardingConversation extends Conversation
{
    public function askAgreementOfDataUsage()
    {
        Log::debug('Q1 start');

        $question =  Keyboard::create()
            ->type(Keyboard::TYPE_KEYBOARD)
            ->oneTimeKeyboard()
            ->addRow(
                KeyboardButton::create('Ya')->callbackData('yes'),
                KeyboardButton::create('Tidak')->callbackData('no')
                )
            ->toArray();

        return $this->ask('Untuk membuat aduan, anda bersetuju bahawa data yang dimasukkan adalah benar dan anda bersetuju data yang anda masukkan akan digunakan bagi tujuan penguatkuasaan di bawah bidang kuasa KPDNHEP?.
Sila jawab "ya" atau "tidak"', function (Answer $answer) {
            if ($answer->isInteractiveMessageReply()) {
                $selectedValue = $answer->getText(); // will be either 'yes' or 'no'
            } else {
                $selectedValue = $answer;
            }

            Log::debug(json_encode(''));

            if ($selectedValue == 'Tidak') {
                $this->say('Anda tidak bersetuju dengan syarat di atas. Aduan anda dihentikan, terima kasih.');
                return false;
            }

            $this->say('Terima kasih, anda akan diberikan beberapa soalan yang memerlukan jawapan. Sila jawab soalan tersebut.');

            $this->bot->startConversation(new ComplaintConversation());
        }, $question);
    }

    public function run()
    {
        $this->askAgreementOfDataUsage();
    }
}
