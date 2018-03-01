<?php

namespace App\Controller;

use App\Entity\Tricks;
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

        $tricks = $em->getRepository('App:Tricks')->tricksIndex();

        return $this->render('tricks/index.html.twig', [
            'tricks' => $tricks,
        ]);
    }

    /**
     * @Route("/tricks/details/{id}", name="trick_details")
     * @Method("GET")
     */
    public function details(Tricks $tricks)
    {
        return $this->render('tricks/details.html.twig', [
            'tricks' => $tricks,
        ]);
    }

    /**
     * Displays a form to edit an existing trick entity.
     *
     * @Route("/{id}/edit", name="trick_edit")
     * @Method({"GET", "POST"})
     */
    public function editAction(Request $request, Tricks $trick)
    {
        $editForm = $this->createForm('App\Form\TricksType', $trick);
        $editForm->handleRequest($request);

        if ($editForm->isSubmitted() && $editForm->isValid()) {
            $this->getDoctrine()->getManager()->flush();

            return $this->redirectToRoute('home_page', array('id' => $trick->getId()));
        }

        return $this->render('tricks/edit.html.twig', array(
            'trick' => $trick,
            'edit_form' => $editForm->createView(),
        ));
    }
}
