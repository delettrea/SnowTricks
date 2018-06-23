<?php

namespace App\DataFixtures;

use App\Entity\Videos;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\Persistence\ObjectManager;

class VideosFixtures extends Fixture
{
    public function load(ObjectManager $manager)
    {
        $tab = array(
            array('name' => '<iframe width="560" height="315" src="https://www.youtube.com/embed/_hxLS2ErMiY" frameborder="0" allow="autoplay; encrypted-media" allowfullscreen></iframe>', 'trick_id' => 'trick1'),
            array('name' => '<iframe width="560" height="315" src="https://www.youtube.com/embed/Opg5g4zsiGY" frameborder="0" allow="autoplay; encrypted-media" allowfullscreen></iframe>', 'trick_id' => 'trick14'),
            array('name' => '<iframe width="560" height="315" src="https://www.youtube.com/embed/k-CoAquRSwY" frameborder="0" allow="autoplay; encrypted-media" allowfullscreen></iframe>', 'trick_id' => 'trick6'),
            array('name' => '<iframe width="560" height="315" src="https://www.youtube.com/embed/M-W7Pmo-YMY" frameborder="0" allow="autoplay; encrypted-media" allowfullscreen></iframe>', 'trick_id' => 'trick9'),
            array('name' => '<iframe width="560" height="315" src="https://www.youtube.com/embed/6tgjY8baFT0" frameborder="0" allow="autoplay; encrypted-media" allowfullscreen></iframe>', 'trick_id' => 'trick16'),
        );

        foreach ($tab as $row)
        {
            $video = new Videos();
            $video->setName($row['name']);

            $trick = $this->getReference($row['trick_id']);
            $video->setTrick($trick);

            $manager->persist($video);
        }

        $manager->flush();
    }

}