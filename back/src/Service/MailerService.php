<?php
namespace App\Service;

use Symfony\Component\Mailer\MailerInterface;
use Symfony\Component\Mime\Email;

class MailerService
{
    public function __construct(private MailerInterface $mailer) {}

    public function sendResetPasswordEmail(string $to, string $token): void
    {
        $email = (new Email())
            ->from('altmatch@gmail.com')
            ->to($to)
            ->subject('Réinitialisation de votre mot de passe')
            ->html("
                <p>Voici votre mot de passe temporaire : <strong>$token</strong></p>
                <p>Il est valable 10 minutes.</p>
                <p><a href='http://localhost:5173/resetPassword'>Cliquez ici pour réinitialiser</a></p>
            ");

        $this->mailer->send($email);
    }
}
