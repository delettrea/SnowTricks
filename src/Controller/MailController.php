<?php
namespace App\Controller;


use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\Routing\Annotation\Route;


class MailController extends Controller
{
    /**
     * @Route("/mail", name="mail")
     */
    public function testMailer(\Swift_Mailer $mailer)
    {
        $message = (new \Swift_Message('Hello Email'))
            ->setFrom('send@example.com')
            ->setTo('recipient@example.com')
            ->setBody(
                $this->renderView(
                // templates/emails/registration.html.twig
                    'emails/registration.html.twig'
                ),
                'text/html'
            )
        ;

        $mailer->send($message);

        return $this->redirectToRoute('home_page');
    }

}