<?php

namespace App\Controller;

use App\Entity\Comments;
use App\Entity\Illustrations;
use App\Entity\Tricks;
use App\Entity\Videos;
use App\Form\VideosType;
use App\Service\CollectionTypeEditor;
use App\Service\FileUploader;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;

class TricksController extends Controller
{

    private $em;

    public function __construct(EntityManagerInterface $entityManager)
    {
        $this->em = $entityManager;
    }

    /**
     * @Route("/", name="home_page")
     */
    public function index()
    {
        $tricks = $this->em->getRepository('App:Tricks')->TricksWithOneIllustration();

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
        $comments = $this->em->getRepository('App:Comments')->findBy(['trick' => $tricks]);
        $illustrations = $this->em->getRepository('App:Illustrations')->findBy(['trick' => $tricks]);
        $videos = $this->em->getRepository('App:Videos')->findBy(['trick' => $tricks]);

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

            $this->em = $this->getDoctrine()->getManager();
            $this->em->persist($comment);
            $this->em->flush();

            return $this->redirectToRoute('trick_details', ['id' => $tricks->getId()]);
        }

        return $this->render('tricks/details.html.twig', [
            'tricks' => $tricks,
            'comments' => $comments,
            'illustrations' => $illustrations,
            'videos' => $videos,
            'form' => $form->createView(),
            'author' => $author
        ]);
    }

    /**
     * @Route("tricks/{id}/edit", name="trick_edit")
     * @Method({"GET", "POST"})
     */
    public function edit(Request $request, Tricks $trick)
    {
        $deleteForm = $this->createDeleteForm($trick);
        $editForm = $this->createForm('App\Form\EditTrickType', $trick);
        $editForm->handleRequest($request);

        if ($editForm->isSubmitted() && $editForm->isValid()) {
            $this->em->flush();

            return $this->redirectToRoute('trick_details', array('id' => $trick->getId()));
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
            $this->em->remove($trick);
            $this->em->flush();
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
     * @Route("/delete/trick/{id}", name="trick_delete_url")
     * @Method({"GET"})
     */
    public function deleteTrick(Tricks $tricks)
    {
        $em = $this->getDoctrine()->getManager();
        $em->remove($tricks);
        $em->flush();

        return $this->redirectToRoute('home_page');
    }

    /**
     * @Route("/trick/new", name="trick_new")
     * @Method({"GET", "POST"})
     */
    public function new(Request $request, FileUploader $fileUploader, CollectionTypeEditor $collectionTypeEditor)
    {
        $trick = new Tricks();

        $video1 = new Videos();
        $trick->getVideos()->add($video1);

        $file1 = new Illustrations();
        $trick->getIllustrations()->add($file1);

        $form = $this->createForm('App\Form\TricksType', $trick);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $trick->setDateCreation();
            $this->em->persist($trick);

            $collectionTypeEditor->sendCollectionTypeForm($trick, $fileUploader, $form);

            $this->em->flush();

            return $this->redirectToRoute('trick_details', array('id' => $trick->getId()));
        }

        return $this->render('tricks/new.html.twig', array(
            'trick' => $trick,
            'form' => $form->createView(),
        ));
    }
}
