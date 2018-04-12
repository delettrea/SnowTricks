<?php
/**
 * Created by PhpStorm.
 * User: aline
 * Date: 07/04/18
 * Time: 19:39
 */

namespace App\Controller;

use App\Entity\Comments;
use App\Entity\Tricks;
use App\Entity\User;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Session\Session;
use Symfony\Component\Routing\Annotation\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;

class CommentsController extends Controller
{
    /**
     * @Route("/comment/new/{id}", name="comment_new")
     * @Method({"GET", "POST"})
     */
    public function new(Request $request, Tricks $tricks)
    {
        $form = $this->createForm('App\Form\CommentsType');
        $form->handleRequest($request);

        if($form->isSubmitted() && $form->isValid())
        {
            $author = $this->getUser();
            $text = $form['comment']->getData();

            $comment = new Comments();
            $comment->setTrick($tricks);
            $comment->setAuthor($author);
            $comment->setComment($text);
            $comment->setDateTime();

            $em = $this->getDoctrine()->getManager();
            $em->persist($comment);
            $em->flush();

            return $this->redirectToRoute('trick_details', ['id' => $tricks->getId()]);
        }

        return $this->render('comments/new.html.twig', [
            'form' => $form->createView()
        ]);
    }


}