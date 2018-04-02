<?php

namespace App\Controller;

use Alpha\A;
use App\Entity\User;
use App\Form\ForgotPasswordType;
use App\Form\ResetPasswordType;
use App\Form\UserType;
use App\Service\MailGenerator;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\Form\Extension\Core\Type\PasswordType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
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
    public function registration(Request $request, UserPasswordEncoderInterface $passwordEncoder,MailGenerator $mailGenerator ){

        $user = new User();
        $form = $this->createForm(UserType::class, $user);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {

            // 3) Encode the password (you could also do this via Doctrine listener)
            $password = $passwordEncoder->encodePassword($user, $user->getPlainPassword());
            $user->setPassword($password);
            $user->setConfirmKey();
            $user->setPasswordKey();
            // 4) save the User!
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->persist($user);
            $entityManager->flush();

            $mailGenerator->registration($user);

            $this->addFlash(
                "registration",
                "Votre compte à bien était créé. Veuillez confirmer votre inscription via le mail qui vient de vous être envoyé."
            );

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
        $em = $this->getDoctrine()->getManager();
        $rep = $em->getRepository('App:User')->findBy(array('id' => $id, 'confirmKey' => $confirm_key));

        if (!empty($rep)) {
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

        $sendPassword = $this->createFormBuilder()
            ->add('username', TextType::class)
            ->add('submit', SubmitType::class)
            ->getForm();

        //$sendPassword = $this->createForm(ForgotPasswordType::class);

        $sendPassword->handleRequest($request);

        if ($sendPassword->isSubmitted()) {

            $username = $sendPassword->getData();

            $em = $this->getDoctrine()->getManager();
            $user = $em->getRepository('App:User')->findOneBy(array('username' => $username));

            if (!empty($user) && $user->getIsActive() == 1) {
                $mailGenerator->forgotPasswordEmail($user);

                return $this->redirectToRoute('home_page');
            } elseif (empty($user)) {
                $this->addFlash(
                    "send",
                    "Le nom d'utilisateur saisit ne correspond à aucun compte sur le site."
                );
            } else {
                $this->addFlash(
                    "send",
                    "L'utilisateur n'a pas encore validé son compte."
                );
            }
        }

        return $this->render('security/forgot_password.html.twig', array(
            'form' => $sendPassword->createView(),
        ));
    }

    /**
     * @Route("/reset_password/{id}/{password_key}", name="reset_password")
     */
    public function resetPassword(Request $request,User $user, $id, $password_key, UserPasswordEncoderInterface $passwordEncoder)
    {

        $em = $this->getDoctrine()->getManager();
        $rep = $em->getRepository('App:User')->findBy(array('id' => $id, 'passwordKey' => $password_key));

        if (!empty($rep)) {
            $form = $this->createForm(ResetPasswordType::class, $user);

            $form->handleRequest($request);

            if($form->isSubmitted() && $form->isValid()){
                $password = $passwordEncoder->encodePassword($user, $user->getPlainPassword());
                $user->setPassword($password);

                $em->flush();

                return $this->redirectToRoute('home_page');
            }

            return $this->render('security/reset_password.html.twig', array(
                'form' => $form->createView(),
            ));
        }

        return $this->redirectToRoute('home_page');
    }
}