<?php

namespace App\Controller;

use App\Entity\Tricks;
use App\Entity\Videos;
use App\Form\VideoType;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;

class VideoController extends Controller
{
    /**
     * @Route("/video/{id}/delete", name="illustration_new")
     * @Method({"GET", "POST"})
     */
    public function delete(Videos $videos)
    {
        $em = $this->getDoctrine()->getManager();
        $em->remove($videos);
        $em->flush();

        return $this->redirectToRoute('trick_details', ['id' => $videos->getTrick()->getId()]);
    }

    /**
     * @Route("/video/{id}/edit", name="illustration_new")
     * @Method({"GET", "POST"})
     */
    public function edit(Request $request, Videos $videos)
    {
        $form = $this->createForm('App\Form\VideosType');
        $form->handleRequest($request);

        if($form->isSubmitted() && $form->isValid())
        {
            $em = $this->getDoctrine()->getManager();
            $videos->setName($form['name']->getData());
            $em->persist($videos);
            $em->flush();

            return $this->redirectToRoute('trick_details', ['id' => $videos->getTrick()->getId()]);
        }

        return $this->render('video/edit.html.twig', [
            'tricks' => $videos->getTrick(),
            'form' => $form->createView()
        ]);
    }

}
