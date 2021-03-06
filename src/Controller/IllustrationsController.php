<?php

namespace App\Controller;


use App\Entity\Illustrations;
use App\Entity\Tricks;
use App\Entity\Videos;
use App\Form\IllustrationsType;
use App\Service\FileUploader;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;


class IllustrationsController extends Controller
{
    /**
     * @Route("/illustration/new/{id}", name="illustration_new")
     * @Method({"GET", "POST"})
     */
    public function new(Request $request, Tricks $tricks, FileUploader $fileUploader)
    {
        $em = $this->getDoctrine()->getManager();
        $form = $this->createForm('App\Form\IllustrationsType');
        $form->handleRequest($request);

        if($form->isSubmitted() && $form->isValid())
        {
                $illustration = new Illustrations();
                $nameFile = $form['name']->getData();
                $fileName = $fileUploader->upload($nameFile, 'illustrations');
                $illustration->setName($fileName);
                $illustration->setTrick($tricks);
                $em->persist($illustration);

            $em->flush();

            $this->addFlash(
                "message-succes",
                "La nouvelle illustration a bien été ajoutée."
            );

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

        $this->addFlash(
            "message-error",
            "L'illustration vient d'être supprimée."
        );

            return $this->redirectToRoute('trick_details', ['id' => $illustrations->getTrick()->getId()]);
    }

    /**
     * @Route("/illustration/delete_mobile/{id}", name="illustration_delete_mobile")
     * @Method({"GET"})
     */
    public function deleteMobile(Illustrations $illustrations)
    {
        return $this->render('illustrations/delete.html.twig', [
            'illustration' => $illustrations
        ]);
    }

    /**
     * @Route("/illustration/edit/{id}", name="illustration_edit")
     * @Method({"GET", "POST"})
     */
    public function edit(Request $request, Illustrations $illustrations, FileUploader $fileUploader)
    {
        $em = $this->getDoctrine()->getManager();
        $form = $this->createForm('App\Form\IllustrationsType');
        $form->handleRequest($request);

        if($form->isSubmitted() && $form->isValid())
        {
            $nameFile = $form['name']->getData();
            $fileName = $fileUploader->upload($nameFile, 'illustrations');
            $illustrations->setName($fileName);
            $em->persist($illustrations);
            $em->flush();

            $trickId = $illustrations->getTrick()->getId();

            $this->addFlash(
                "message-succes",
                "L'illustration a bien été modifiée."
            );

            return $this->redirectToRoute('trick_details', ['id' => $trickId]);
        }

        return $this->render('illustrations/edit.html.twig', [
            'tricks' => $illustrations->getTrick(),
            'form' => $form->createView()
        ]);
    }

}