<?php

namespace App\Service;


use Twig\Environment;

class MailGenerator
{

    private $mailer;
    private $twig;
    private $sendFrom = "contact@snowtricks.com";

    public function __construct(\Swift_Mailer $mailer, Environment $twig)
    {
        $this->mailer = $mailer;
        $this->twig = $twig;
    }

    public function forgotPasswordEmail($user)
    {
        $sendTo = $user->getEmail();
        $this->sendMail($this->mailer,$this->sendFrom, $sendTo);
    }

    private function sendMail(\Swift_Mailer $mailer,$sendFrom, $sendTo)
    {
        $message = (new \Swift_Message("Activer votre compte sur Snowtricks.com"))
            ->setFrom('contact@snowtricks.com')
            ->setTo($sendTo)
            ->setBody(
                $this->twig->render(
                    'emails/forgot_password.html.twig', array(
                    'user' => $sendTo
                )),
                'text/html');

        $mailer->send($message);
    }

}