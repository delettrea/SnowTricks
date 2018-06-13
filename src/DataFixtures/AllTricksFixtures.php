<?php

namespace App\DataFixtures;

use App\Entity\Tricks;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\Persistence\ObjectManager;

class AllTricksFixtures extends Fixture
{
    public function load(ObjectManager $manager)
    {
        $tab = array(
            array('ref' => 'trick1' ,'name' => 'Method Air' , 'group' => 'Old school', 'description' =>'Le figure consiste à attraper sa planche d\'une main et le tourner perpendiculairement au sol' ),
            array('ref' => 'trick2', 'name' => 'Mute' , 'group' => 'Grab', 'description' =>'Saisie de la carre frontside de la planche entre les deux pieds avec la main avant.' ),
            array('ref' => 'trick3', 'name' => 'Stalefish' , 'group' => 'Grab', 'description' =>'Saisie de la carre backside de la planche entre les deux pieds avec la main arrière.' ),
            array('ref' => 'trick4' ,'name' => '900' , 'group' => 'Rotation', 'description' =>'Deux tours et demi.' ),
            array('ref' => 'trick5' ,'name' => 'Double Backflip One Foot' , 'group' => 'One foot tricks', 'description' =>'Figure qui se fait avec un seul pied encore fixé sur la planche. Le pied non fixé doit rester tendu pendant la figure. ' ),
            array('ref' => 'trick6' ,'name' => 'Mc Twist' , 'group' => 'Flip', 'description' =>'La figure consiste en une rotation verticale lors de laquelle on effectue aussi une vrille.' ),
            array('ref' => 'trick7' ,'name' => 'Tail grab' , 'group' => 'Grab', 'description' =>'Lors de la figure il faut saisir la partie arrière de la planche de snowboard avec la main arrière.' ),
            array('ref' => 'trick8' ,'name' => 'Nose slide' , 'group' => 'Slides', 'description' =>'Le nose slide est une figure qui consiste à glisser sur une barre de slide, peu importe sa forme, avec le haut de la planche sur le slide.' ),
            array('ref' => 'trick9' ,'name' => 'Nose grab' , 'group' => 'Grab', 'description' =>'Cette figure est un peu l\'inverse de la Tail grab. En effet le Nose grab consiste à prendre le haut de planche cette fois-ci et avec la main avant.'),
            array('ref' => 'trick10' ,'name' => '1080 ou big foot' , 'group' => 'Rotation', 'description' =>'1080 ou big foot : cette figure décrit une rotation de trois tours avec le snowboard.' ),
            array('ref' => 'trick11' ,'name' => 'Seat belt' , 'group' => 'Grab', 'description' =>'Cette figure  grab consiste à prendre le carre frontside de l\'arrière avec la main avant.' ),
            array('ref' => 'trick12' ,'name' => 'Front side' , 'group' => 'Rotation', 'description' =>'Une rotation front side est une rotation réalisée vers le carré backside.' ),
            array('ref' => 'trick13' ,'name' => 'Indy' , 'group' => 'Grab', 'description' =>'Cette figure consiste à prendre la carre frontside de la planche avec  la mains arrière entre les deux jambes.' ),
            array('ref' => 'trick14' ,'name' => '360' , 'group' => 'Slides', 'description' =>'Un trois six est une rotation sur un tour complet.' ),
            array('ref' => 'trick15' ,'name' => 'style week' , 'group' => 'Grab', 'description' =>'Proche du indy il s\'agit de faire la même chose mais d\'attraper le carre backside de la planche cette fois-ci.' ),
            array('ref' => 'trick16' ,'name' => 'Truck driver' , 'group' => 'Grab', 'description' =>'Lors de la figure il faut saisir le carre avant et le carre arrière avec chaque main. Cette figure est semblable au fait de tenir un volant, d\'où son nom truck driver.' ),
            );

        foreach ($tab as $row)
        {
            $trick = new Tricks();
            $trick->setName($row['name']);
            $trick->setDescription($row['description']);
            $trick->setDateCreation('2018-04-24 18:34:20');

            $reference = $this->getReference($row['group']);
            $trick->setGroup($reference);

            $this->addReference($row['ref'], $trick);

            $manager->persist($trick);
        }

        $manager->flush();
    }
}
