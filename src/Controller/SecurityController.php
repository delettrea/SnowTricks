<?php

namespace App\Controller;

use App\Entity\User;
use App\Form\ForgotPasswordType;
use App\Form\LoginType;
use App\Form\ResetPasswordType;
use App\Form\UserType;
use App\Service\MailGenerator;
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

        $form = $this->createForm(LoginType::class);
        $form->handleRequest($request);

        return $this->render('security/login.html.twig', array(
            'last_username' => $lastUsername,
            'error'         => $error,
            'form'          => $form->createView()
        ));
    }

    /**
     * @Route("/registration", name="registration")
     * @Method({"GET", "POST"})
     */
    public function registration(Request $request, UserPasswordEncoderInterface $passwordEncoder,MailGenerator $mailGenerator ){

        $user = new User();
        $form = $this->createForm(UserType::class, $user);
        $form->handleRequest($request);

        if ($form->isSubmitted()) {

            $username = $form['username']->getData();

            $em = $this->getDoctrine()->getManager();
            $resp = $em->getRepository('App:User')->findByUsername($username);

            if ($form->isValid() && empty($resp)) {

                $password = $passwordEncoder->encodePassword($user, $user->getPlainPassword());
                $user->setPassword($password);
                $user->setConfirmKey();
                $user->setPasswordKey();

                $em = $this->getDoctrine()->getManager();
                $em->persist($user);
                $em->flush();

                $mailGenerator->registration($user);

                $this->addFlash(
                    "message",
                    "Votre compte à bien était créé. Veuillez confirmer votre inscription via le mail qui vient de vous être envoyé."
                );

                return $this->redirectToRoute('home_page');
            }
            elseif (!empty($resp)){
                $this->addFlash(
                    "message",
                    "Le nom d'utilisateur existe déjà, veuillez en choisir un nouveau."
                );
            }
            else {
                $this->addFlash(
                    "message",
                    "Le formulaire n'est pas valide, veuillez remplir correctement tous les champs."
                );
            }
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
        $em = $this->getDoctrine()->getManager();
        $resp = $em->getRepository('App:User')->findBy(array('id' => $id, 'confirmKey' => $confirm_key));

        if (!empty($resp)) {
            if ($user->getisActive() == false) {
                $user->setIsActive(true);
                $em->flush();
                $message = "Votre compte à bien été activé.";
            } else {
                $this->addFlash(
                    "active",
                    "Le compte est déjà activé, vous pouvez vous connecter."
                );
            };
        } else {
            $this->addFlash(
                "active",
                "Cet email ne permet pas d'activer un compte."
            );
        }
        return $this->render('security/activeAccount.html.twig', array(
            'id' => $id,
            'key' => $confirm_key,
        ));
    }

    /**
    * @Route("/forgot_password", name="forgot_password")
     * @Method({"GET", "POST"})
    */
    public function forgotPassword(Request $request, MailGenerator $mailGenerator)
    {
        $sendPassword = $this->createForm(ForgotPasswordType::class);

        $sendPassword->handleRequest($request);

        if ($sendPassword->isSubmitted()) {

            $username = $sendPassword['username']->getData();

            $em = $this->getDoctrine()->getManager();
            $user = $em->getRepository('App:User')->findOneBy(array('username' => $username));

            if (!empty($user) && $user->getIsActive() == true) {
                $mailGenerator->forgotPasswordEmail($user);
                $this->addFlash(
                    "message",
                    "Votre inscription a bien été prise en compte.
                    Un email vient de vous être envoyé pour que vous activiez votre compte."
                );
            } elseif(empty($user)) {
                $this->addFlash(
                    "message",
                    "Le nom d'utilisateur saisit ne correspond à aucun compte sur le site."
                );
            } else {
                $this->addFlash(
                    "message",
                    "L'utilisateur n'a pas encore validé son compte."
                );
            }

            return $this->redirectToRoute('home_page');

        }

        return $this->render('security/forgot_password.html.twig', array(
            'form' => $sendPassword->createView(),
        ));
    }


    /**
     * @Route("/reset_password/{id}/{password_key}", name="reset_password")
     */
    public function resetPassword(Request $request, User $user, $id, $password_key, UserPasswordEncoderInterface $passwordEncoder)
    {
        $idUsername = $user->getUsername();

        $em = $this->getDoctrine()->getManager();
        $resp = $em->getRepository('App:User')->findBy(array('id' => $id, 'passwordKey' => $password_key));

        if (!empty($resp) && ($user->getisActive() === false)) {
            $this->addFlash(
                "error",
                "La page demandée ne permet pas de changer le mot de passe d'un utilisateur qui n'a pas encore activé son compte. 
                Veuillez vous reporter à l'email de validation de votre compte envoyé lors de votre inscription."
            );
        }
        elseif (!empty($resp)) {
            $form = $this->createForm(ResetPasswordType::class, $user);

            $form->handleRequest($request);

            if ($form->isSubmitted() && $form->isValid()) {

                if ($idUsername === $user->getUsername()) {

                    $password = $passwordEncoder->encodePassword($user, $user->getPlainPassword());
                    $user->setPassword($password);

                    $em->flush();

                    return $this->redirectToRoute('home_page');
                }
                else {
                    $this->addFlash(
                        'message',
                        "Le nom d'utilisateur est incorrect.
                        Veuillez renseigner le nom d'utilisateur correspondant à l'adresse email renseignée."
                    );
                }
            }

            return $this->render('security/reset_password.html.twig', array(
                'form' => $form->createView(),
            ));
        }
        else {
            $this->addFlash(
                "message",
                "La page demandée ne permet pas de changer le mot de passe d'un utilisateur. 
                Veuillez vérifier le lien dans l'email qui vous à été envoyer pour le changement de mot de passe."
            );
        }

        return $this->redirectToRoute('home_page');
    }
}