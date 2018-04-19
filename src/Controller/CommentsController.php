<?php

namespace App\Controller;

use App\Entity\Comments;
use App\Entity\User;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;


class CommentsController extends Controller
{

    /**
     * @Route("/comment/delete/{id}", name="comment_delete")
     * @Method({"GET"})
     */
    public function delete(Request $request, Comments $comments)
    {

        if($this->getUser() == $comments->getAuthor()){
            $em = $this->getDoctrine()->getManager();
            $em->remove($comments);
            $em->flush();

            return $this->redirectToRoute('trick_details', ['id' => $comments->getTrick()->getId()]);
        }
        else{
            $this->addFlash(
                "message",
                "Vous ne pouvez supprimer que les commentaires dont vous êtes l'auteur."
            );
            return $this->redirectToRoute('trick_details', ['id' => $comments->getTrick()->getId()]);
        }
    }


}