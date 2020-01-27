<?php


namespace App\Services\Common;

use Symfony\Component\Config\Definition\Exception\Exception;
use Symfony\Component\Mailer\Exception\TransportExceptionInterface;
use Symfony\Component\Mailer\MailerInterface;
use Symfony\Component\Mime\Email;
use Symfony\Contracts\Translation\TranslatorInterface;

class MailInformer
{
    private $mailer;
    private $email_admin;
    private $email_transfer;
    private $translator;

    public function __construct(TranslatorInterface $translator, MailerInterface $mailer, $email_admin, $email_transfer)
    {
        $this->mailer = $mailer;
        $this->email_admin = $email_admin;
        $this->email_transfer = $email_transfer;
        $this->translator = $translator;
    }

    public function messageToEmail($msg, $subject = null)
    {
        if ($subject == null) {
            $subject = $this->translator->trans('Уведомление');
        }

        $email = (new Email())
            ->from($this->email_transfer)
            ->to($this->email_admin)
            ->subject($subject)
            ->text($msg);
        //  ->html('');

        try {
            $this->mailer->send($email);
        } catch (TransportExceptionInterface $e) {
            throw new Exception($e->getMessage());
        }
    }
}
