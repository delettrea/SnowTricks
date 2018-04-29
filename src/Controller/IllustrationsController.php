<?php

namespace App\Controller;


use App\Entity\Illustrations;
use App\Entity\Tricks;
use App\Service\FileUploader;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;


class IllustrationsController extends Controller
{
    /**
     * @Route("/illustration/{id}/new", name="illustration_new")
     * @Method({"GET", "POST"})
     */
    public function new(Request $request, Tricks $tricks, FileUploader $fileUploader)
    {
        $em = $this->getDoctrine()->getManager();
        $form = $this->createForm('App\Form\NewIllustrationsType', $tricks);
        $form->handleRequest($request);

        if($form->isSubmitted() && $form->isValid())
        {
            $tricks->setFiles(null);

            foreach ($form['files']->getData() as $file)
            {
                $illustration = new Illustrations();
                $nameFile = $file;
                $fileName = $fileUploader->upload($nameFile, 'illustrations');
                $illustration->setName($fileName);
                $illustration->setTrick($tricks);
                $em->persist($illustration);
            }
            $em->flush();

            return $this->redirectToRoute('trick_details', ['id' => $tricks->getId()]);
        }

        return $this->render('illustrations/new.html.twig', [
            'form' => $form->createView()
        ]);
    }

    /**
     * @Route("/illustration/delete/{id}", name="illustration_delete")
     * @Method({"GET"})
     */
    public function delete(Illustrations $illustrations)
    {
            $em = $this->getDoctrine()->getManager();
            $em->remove($illustrations);
            $em->flush();

            return $this->redirectToRoute('trick_details', ['id' => $illustrations->getTrick()->getId()]);
    }

}