<?php

namespace App\Controller;

use App\Entity\Comments;
use App\Entity\Illustrations;
use App\Entity\Tricks;
use App\Service\FileUploader;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;

class TricksController extends Controller
{

    /**
     * @Route("/", name="home_page")
     */
    public function index()
    {
        $em = $this->getDoctrine()->getManager();

        $tricks = $em->getRepository('App:Tricks')->findAll();

        return $this->render('tricks/index.html.twig', [
            'tricks' => $tricks,
        ]);
    }

    /**
     * @Route("/tricks/details/{id}", name="trick_details")
     * @Method({"GET", "POST"})
     */
    public function details(Request $request, Tricks $tricks)
    {
        $em = $this->getDoctrine()->getManager();
        $comments = $em->getRepository('App:Comments')->findBy(['trick' => $tricks]);
        $illustrations = $em->getRepository('App:Illustrations')->findBy(['trick' => $tricks]);

        $form = $this->createForm('App\Form\CommentsType');
        $form->handleRequest($request);

        $author = $this->getUser();

        if($form->isSubmitted() && $form->isValid())
        {
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

        return $this->render('tricks/details.html.twig', [
            'tricks' => $tricks,
            'comments' => $comments,
            'illustrations' => $illustrations,
            'form' => $form->createView(),
            'author' => $author
        ]);
    }

    /**
     * @param Comments $comments The trick entity
     *
     * @return \Symfony\Component\Form\FormInterface The form
     */
    private function createDeleteFormComment(Comments $comments)
    {
        return $this->createFormBuilder()
            ->setAction($this->generateUrl('comment_delete', array('id' => $comments->getId())))
            ->setMethod('DELETE')
            ->getForm()
            ;
    }

    /**
     * @Route("tricks/{id}/edit", name="trick_edit")
     * @Method({"GET", "POST"})
     */
    public function edit(Request $request, Tricks $trick)
    {
        $deleteForm = $this->createDeleteForm($trick);
        $editForm = $this->createForm('App\Form\TricksType', $trick);
        $editForm->handleRequest($request);

        if ($editForm->isSubmitted() && $editForm->isValid()) {
            $this->getDoctrine()->getManager()->flush();

            return $this->redirectToRoute('home_page', array('id' => $trick->getId()));
        }

        return $this->render('tricks/edit.html.twig', array(
            'trick' => $trick,
            'edit_form' => $editForm->createView(),
            'delete_form' => $deleteForm->createView(),
        ));
    }

    /**
     * Deletes a trick entity.
     *
     * @Route("/{id}", name="trick_delete")
     * @Method("DELETE")
     */
    public function delete(Request $request, Tricks $trick)
    {
        $form = $this->createDeleteForm($trick);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->remove($trick);
            $em->flush();
        }

        return $this->redirectToRoute('home_page');
    }

    /**
     * @param Tricks $trick The trick entity
     *
     * @return \Symfony\Component\Form\FormInterface The form
     */
    private function createDeleteForm(Tricks $trick)
    {
        return $this->createFormBuilder()
            ->setAction($this->generateUrl('trick_delete', array('id' => $trick->getId())))
            ->setMethod('DELETE')
            ->getForm()
            ;
    }

    /**
     * @Route("/trick/new", name="trick_new")
     * @Method({"GET", "POST"})
     */
    public function new(Request $request, FileUploader $fileUploader)
    {
        $trick = new Tricks();
        $form = $this->createForm('App\Form\TricksType', $trick);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $trick->setFiles(null);
            $em->persist($trick);

            foreach ($form['files']->getData() as $file)
            {
                $illustration = new Illustrations();
                $nameFile = $file;
                $fileName = $fileUploader->upload($nameFile, 'illustrations');
                $illustration->setName($fileName);
                $illustration->setTrick($trick);
                $em->persist($illustration);
            }

            $em->flush();

            return $this->redirectToRoute('trick_details', array('id' => $trick->getId()));
        }

        return $this->render('tricks/new.html.twig', array(
            'trick' => $trick,
            'form' => $form->createView(),
        ));
    }
}
