<?php

namespace App\Controller;

use App\Entity\User;
use App\Form\ForgotPasswordType;
use App\Form\ResetPasswordType;
use App\Form\UserType;
use App\Service\FileUploader;
use App\Service\MailGenerator;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;
use Symfony\Component\Security\Http\Authentication\AuthenticationUtils;

class SecurityController extends Controller
{

    public $defaultAvatar = 'default.jpg';

    /**
     * @Route("/login", name="login")
     */
    public function login(Request $request, AuthenticationUtils $authUtils)
    {
        $error = $authUtils->getLastAuthenticationError();

        $lastUsername = $authUtils->getLastUsername();

        return $this->render('security/login.html.twig', array(
            'error'         => $error,
            'last_username' => $lastUsername,
        ));
    }

    /**
     * @Route("/registration", name="registration")
     * @Method({"GET", "POST"})
     */
    public function registration(Request $request, UserPasswordEncoderInterface $passwordEncoder,MailGenerator $mailGenerator, FileUploader $fileUploader){

        $user = new User();
        $form = $this->createForm(UserType::class, $user);
        $form->handleRequest($request);

        if ($form->isSubmitted()) {

            $username = $form['username']->getData();

            $em = $this->getDoctrine()->getManager();
            $resp = $em->getRepository('App:User')->findByUsername($username);

            if ($form->isValid() && empty($resp)) {

                if(!empty($form['avatar']->getData()))
                {
                    $file = $user->getAvatar();
                    $fileName = $fileUploader->upload($file, 'avatar');
                    $user->setAvatar($fileName);
                }
                else{
                    $user->setAvatar($this->defaultAvatar);
                }

                $password = $passwordEncoder->encodePassword($user, $user->getPlainPassword());
                $user->setPassword($password);
                $user->setConfirmKey();
                $user->setPasswordKey();

                $em = $this->getDoctrine()->getManager();
                $em->persist($user);
                $em->flush();

                $mailGenerator->registration($user);

                $this->addFlash(
                    "message-succes",
                    "Votre compte à bien était créé. Veuillez confirmer votre inscription via le mail qui vient de vous être envoyé."
                );

                return $this->redirectToRoute('home_page');
            }
            elseif (!empty($resp)){
                $this->addFlash(
                    "message-error",
                    "Le nom d'utilisateur existe déjà, veuillez en choisir un nouveau."
                );
            }
            else {
                $this->addFlash(
                    "message-error",
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
                $em->persist($user);
                $em->flush();
                $this->addFlash(
                    "message-succes",
                    "Votre compte est bien activé, vous pouvez vous connecter."
                );
            } else {
                $this->addFlash(
                    "message-succes",
                    "Le compte est déjà activé, vous pouvez vous connecter."
                );
            };
        } else {
            $this->addFlash(
                "message-error",
                "Cet email ne permet pas d'activer un compte."
            );
        }
        return $this->redirectToRoute('home_page');
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
                    "message-succes",
                    "Vous venez de faire une demande de changement votre mot de passe.
                    Un email vient de vous être envoyé pour que vous changiez votre mot de passe."
                );
            } elseif(empty($user)) {
                $this->addFlash(
                    "message-error",
                    "Le nom d'utilisateur saisit ne correspond à aucun compte sur le site."
                );
            } else {
                $this->addFlash(
                    "message-error",
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
                "message-error",
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

                    $this->addFlash(
                        'message-succes',
                        "Votre changement de mot de passe vient d'être effectué.
                        Vous pouvez vous connecter maintenant à votre compte avec votre nouveau mot de passe."
                    );

                    return $this->redirectToRoute('home_page');
                }
                else {
                    $this->addFlash(
                        'message-error',
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
                "message-error",
                "La page demandée ne permet pas de changer le mot de passe d'un utilisateur. 
                Veuillez vérifier le lien dans l'email qui vous à été envoyer pour le changement de mot de passe."
            );
        }

        return $this->redirectToRoute('login');
    }
}