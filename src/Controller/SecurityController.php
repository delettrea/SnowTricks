<?php

namespace App\Controller;

use App\Entity\User;
use App\Form\UserType;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;
use Symfony\Component\Security\Http\Authentication\AuthenticationUtils;

class SecurityController extends Controller
{
    /**
     * @Route("/login", name="login")
     */
    public function login(Request $request, AuthenticationUtils $authUtils)
    {
        $error = $authUtils->getLastAuthenticationError();

        $lastUsername = $authUtils->getLastUsername();

        return $this->render('security/login.html.twig', array(
            'last_username' => $lastUsername,
            'error'         => $error,
        ));
    }

    /**
     * @Route("/registration", name="registration")
     * @Method({"GET", "POST"})
     */
    public function registration(Request $request, UserPasswordEncoderInterface $passwordEncoder, MailController $email,\Swift_Mailer $mailer){

        $user = new User();
        $form = $this->createForm(UserType::class, $user);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {

            // 3) Encode the password (you could also do this via Doctrine listener)
            $password = $passwordEncoder->encodePassword($user, $user->getPlainPassword());
            $user->setPassword($password);
            $user->setConfirmKey();
            // 4) save the User!
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->persist($user);
            $entityManager->flush();

            $message = (new \Swift_Message("Activer votre compte sur Snowtricks.com"))
                ->setFrom('send@example.com')
                ->setTo($user->getEmail())
                ->setBody(
                    $this->renderView(
                    // templates/emails/registration.html.twig
                        'emails/registration.html.twig', array(
                            'user' => $user
                        )
                    ),
                    'text/html');

            $mailer->send($message);

            return $this->redirectToRoute('home_page');
        }

        return $this->render('security/registration.html.twig', array(
            'user' => $user,
            'form' => $form->createView(),
        ));

    }

    /**
     * @Route("/activeAccount/{id}/{confirm_key}", name="active_account")
     */
    public function activeAccount(User $user, $id, $confirm_key)
    {
        $message = null;
        $messageError = null;

        $em = $this->getDoctrine()->getManager();
        $rep = $em->getRepository('App:User')->findBy(array('id' => $id, 'confirmKey' => $confirm_key));

        if (!empty($rep)) {
            if ($user->getisActive() == false) {
                $user->setIsActive(true);
                $em->flush();
                $message = "Votre compte à bien été activé.";
            } else {

                $message = "Le compte est déjà activé, vous pouvez vous connecter.";
            };
        } else {
            $messageError = "Cet email ne permet pas d'activer un compte.";
        }

        return $this->render('security/activeAccount.html.twig', array(
            'id' => $id,
            'key' => $confirm_key,
            'message' => $message,
            'messageError' => $messageError
        ));

    }

}