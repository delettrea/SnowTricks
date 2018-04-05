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

    public function registration($user)
    {
        $title = 'Activer votre compte sur Snowtricks.com';
        $render = 'emails/registration.html.twig';
        $arrayRender = array('user' => $user);
        $this->sendMail($this->mailer, $this->sendFrom, $user, $title, $render, $arrayRender);
    }

    public function forgotPasswordEmail($user)
    {
        $title = 'Modifier votre mot de passe Snowtricks.com';
        $render = 'emails/forgot_password.html.twig';
        $arrayRender = array('user' => $user);
        $this->sendMail($this->mailer,$this->sendFrom, $user, $title, $render, $arrayRender);
    }

    private function sendMail(\Swift_Mailer $mailer,$sendFrom, $user, $title, $render, $arrayRender)
    {
        $message = (new \Swift_Message($title))
            ->setFrom($sendFrom)
            ->setTo($user->getEmail())
            ->setBody(
                $this->twig->render(
                    $render, $arrayRender),
                'text/html');

        $mailer->send($message);
    }

}