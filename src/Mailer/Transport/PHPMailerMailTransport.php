<?php

namespace App\Mailer\Transport;

use Cake\Mailer\AbstractTransport;
use Cake\Mailer\Email;
use PHPMailer\PHPMailer\Exception;
use PHPMailer\PHPMailer\PHPMailer;

class PHPMailerMailTransport extends AbstractTransport
{
    /**
     * Send mail
     *
     * @param \Cake\Mailer\Email $email Cake Email
     * @return void
     * @throws Exception
     */
    public function send(Email $email)
    {
        $from = $email->getFrom();
        $replyTo = $email->getReplyTo();
        $to = $email->getTo();
        $cc = $email->getCc();
        $bcc = $email->getBcc();

        // Create a new PHPMailer instance
        $mail = new PHPMailer;

        $mail->isMail();

        $mail->setFrom(key($from), reset($from));
        $mail->addReplyTo(key($replyTo), reset($replyTo));
        $mail->addAddress(key($to), reset($to)); //Set who the message is to be sent to
        $mail->addCC(key($cc), reset($cc));
        $mail->addBCC(key($bcc), reset($bcc));
        $mail->Subject = $email->getSubject();

        $mail->msgHTML($email->message('html'));
        $mail->AltBody = $email->message('text');
        if (!$mail->send()) {
            $msg = 'Could not send email: ' . $mail->ErrorInfo;
            throw new Exception($msg);
        }
    }
}
