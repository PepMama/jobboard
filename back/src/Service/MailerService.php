<?php
namespace App\Service;

use Symfony\Component\Mailer\MailerInterface;
use Symfony\Component\Mime\Email;

class MailerService
{
    public function __construct(private MailerInterface $mailer, private string $frontUrl) {}

    public function sendResetPasswordEmail(string $to, string $token): void
    {
        $resetUrl = $this->frontUrl . '/resetPassword';
        $email = (new Email())
            ->from('altmatch@gmail.com')
            ->to($to)
            ->subject('Réinitialisation de votre mot de passe')
            ->html("
                <p>Voici votre mot de passe temporaire : <strong>$token</strong></p>
                <p>Il est valable 10 minutes.</p>
                <p><a href='$resetUrl'>Cliquez ici pour réinitialiser</a></p>
            ");

        $this->mailer->send($email);
    }
}
