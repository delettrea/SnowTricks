<?php
namespace App\Controller;


use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\Routing\Annotation\Route;


class MailController extends Controller
{
    /**
     * @Route("/mail", name="mail")
     */
    public function registrationMailer(\Swift_Mailer $mailer)
    {

        $message = (new \Swift_Message("Activer votre compte sur Snowtricks.com"))
            ->setFrom('contact@snowtricks.com')
            ->setTo('send@example.com')
            ->setBody(
                $this->renderView(
                // templates/emails/registration.html.twig
                    'emails/forgot_password.html.twig'
                ),
                'text/html')
        ;

        $mailer->send($message);

        return $this->redirectToRoute('home_page');
    }

}