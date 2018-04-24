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
     * @Route("/video/{id}/new", name="illustration_new")
     * @Method({"GET", "POST"})
     */
    public function new(Request $request, Tricks $tricks)
    {
        $trick = new Tricks();

        // dummy code - this is here just so that the Task has some tags
        // otherwise, this isn't an interesting example
        $video1 = new Videos();
        $video1->setName('video1');
        $trick->getVideos()->add($video1);
        $video2 = new Videos();
        $video2->setName('video2');
        $trick->getVideos()->add($video2);
        // end dummy code

        $form = $this->createForm(VideoType::class);
        dump($form);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            dump($form);
        }

        return $this->render('video/new.html.twig', array(
            'form' => $form->createView(),
        ));
    }
}
