<?php

namespace App\DataFixtures;

use App\Entity\Tricks;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\Persistence\ObjectManager;

class TricksFixtures extends Fixture
{
    public function load(ObjectManager $manager)
    {
        $tab = array(
            array('ref' => 'trick1' ,'name' => 'Method Air' , 'group' => 'Old school', 'description' =>'Le figure consiste à attraper sa planche d\'une main et le tourner perpendiculairement au sol' ),
            array('ref' => 'trick2', 'name' => 'Mute' , 'group' => 'Grab', 'description' =>'Saisie de la carre frontside de la planche entre les deux pieds avec la main avant.' ),
            array('ref' => 'trick3', 'name' => 'Stalefish' , 'group' => 'Grab', 'description' =>'Saisie de la carre backside de la planche entre les deux pieds avec la main arrière.' ),
            array('ref' => 'trick4' ,'name' => '900' , 'group' => 'Rotation', 'description' =>'Deux tours et demi.' ),
        );

        foreach ($tab as $row)
        {
            $trick = new Tricks();
            $trick->setName($row['name']);
            $trick->setDescription($row['description']);

            $reference = $this->getReference($row['group']);
            $trick->setGroup($reference);

            $this->addReference($row['ref'], $trick);

            $manager->persist($trick);
        }

        $manager->flush();
    }
}
