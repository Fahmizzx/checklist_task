<?php namespace App\Libraries;

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\SMTP;
use PHPMailer\PHPMailer\Exception;

class EmailService
{
    protected $mail;

    public function __construct()
    {
        $this->mail = new PHPMailer(true);

        // Konfigurasi SMTP
        $this->mail->isSMTP();
        $this->mail->Host = 'smtp.gmail.com';
        $this->mail->SMTPAuth = true;
        $this->mail->Username = 'fahmi.demir1@gmail.com'; // <--- UBAH INI
        $this->mail->Password = 'oryo wegq ismo eakg';     // <--- UBAH INI
        $this->mail->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS; // <--- PASTIKAN INI STARTTLS
        $this->mail->Port = 587; // <--- PASTIKAN INI 587
        $this->mail->CharSet = 'UTF-8';

        // Set pengirim default
        $this->mail->setFrom('fahmi.demir1@gmail.com', 'PT INTI'); // <--- UBAH INI
    }

    public function sendEmail($to, $subject, $message)
    {
        try {
            // Clear all addresses and attachments for a fresh email
            $this->mail->clearAddresses();
            $this->mail->clearAttachments();

            $this->mail->addAddress($to);
            $this->mail->isHTML(true);
            $this->mail->Subject = $subject;
            $this->mail->Body = $message;
            $this->mail->send();
            return true;
        } catch (Exception $e) {
            // Log error lebih detail dari PHPMailer
            log_message('error', "Error mengirim email: {$this->mail->ErrorInfo}");
            return false;
        }
    }
}