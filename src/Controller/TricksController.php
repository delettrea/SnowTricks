<?php

namespace App\Controller;

use App\Entity\Tricks;
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

}
