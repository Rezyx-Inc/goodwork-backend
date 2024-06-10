<?php

namespace App\Services;

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\SMTP;
use PHPMailer\PHPMailer\Exception;

class CustomMailer {

    protected $mailer;

    public function __construct() {
        $this->mailer = new PHPMailer(true);
//$this->mailer->SMTPDebug = SMTP::DEBUG_SERVER;                      // Enable verbose debug output
        if (env('MAIL_MAILER') == 'smtp') {
            $this->mailer->isSMTP();
            $this->Mailer = "SMTP";
        } else {
            $this->mailer->isMail();
        }                                          // Send using SMTP
        $this->mailer->Host = env('MAIL_HOST') ?? 'smtp.gmail.com';
        $this->mailer->SMTPAuth = true;                                   // Enable SMTP authentication
        $this->mailer->Username = env('MAIL_USERNAME');                     // SMTP username
        $this->mailer->Password = env('MAIL_PASSWORD');                               // SMTP password
        $this->mailer->SMTPSecure = env('MAIL_ENCRYPTION');         // Enable TLS encryption; `PHPMailer::ENCRYPTION_SMTPS` also accepted
        $this->mailer->Port = env('MAIL_PORT');                                    // TCP port to connect to
        //Recipients

        $this->mailer->isHTML(true);
    }

    function sendSmtpMail($data) {
        $appname = str_replace(' ', '', strtolower(env('APP_NAME')));
        try {
            $this->mailer->setFrom(env('MAIL_FROM_ADDRESS'), env('APP_NAME'));
            $this->mailer->addReplyTo(env('MAIL_FROM_ADDRESS'), env('APP_NAME'));
            // $this->mailer->setFrom('noreply@' . $appname . '.com', env('APP_NAME'));
            // $this->mailer->addReplyTo('noreply@' . $appname . '.com', env('APP_NAME'));
            // Attachments
            #$mail->addAttachment('/var/tmp/file.tar.gz');         // Add attachments
            #$mail->addAttachment('/tmp/image.jpg', 'new.jpg');    // Optional name
            $this->mailer->addAddress($data['to']);
            $this->mailer->Subject = $data['subject'];
            $this->mailer->Body = $data['content'];
            $this->mailer->AltBody = 'This is the body in plain text for non-HTML mail clients';

            $this->mailer->send();
        //    echo 'Message has been sent';
        } catch (Exception $e) {
           echo "Message could not be sent. Mailer Error: {$this->mailer->ErrorInfo}";
        }
    }

}
