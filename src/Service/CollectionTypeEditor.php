<?php

namespace App\Service;


use App\Entity\Tricks;
use Doctrine\ORM\EntityManagerInterface;

class CollectionTypeEditor
{

    private $em;

    public function __construct(EntityManagerInterface $entityManager)
    {
        $this->em = $entityManager;
    }

    public function sendCollectionTypeForm(Tricks $trick, FileUploader $fileUploader, $form)
    {
        $countIllustrationSend = count($form['illustrations']) - 1;

        for($i = 0; $i <= $countIllustrationSend; $i ++)
        {
            if(!empty($form['illustrations'][$i]['name']->getData()))
            {
                $illustration = $form['illustrations'][$i]->getData();
                $nameFile = $form['illustrations'][$i]['name']->getData();
                $fileName = $fileUploader->upload($nameFile, 'illustrations');
                $illustration->setName($fileName);
                $illustration->setTrick($trick);
                $this->em->persist($illustration);
            }
        }

        $countVideoSend = count($form['videos']) - 1;

        for($i = 0; $i <= $countVideoSend; $i ++)
        {
            if(!empty($form['videos'][$i]['name']->getData()))
            {
                $video = $form['videos'][$i]->getData();
                $video->setTrick($trick);
                $this->em->persist($video);
            }
        }
    }
}