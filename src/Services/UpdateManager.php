<?php


namespace App\Services;

use App\Services\Common\MailInformer;
use App\Services\Common\TelegramInformer;

class UpdateManager
{
    private $mailer;
    private $telegram;

    public function __construct(MailInformer $mailer, TelegramInformer $telegram)
    {
        $this->mailer = $mailer;
        $this->telegram = $telegram;
    }

    public function notifyOfUpdate($message, $array = ['mail', 'telegram', 'sms'])
    {
        if (in_array('mail', $array)) {
            $this->mailer->messageToEmail($message);
        }

        if (in_array('telegram', $array)) {
            $this->telegram->messageToTelegram($message);
        }

        if (in_array('sms', $array)) {
            return;
        }
    }
}