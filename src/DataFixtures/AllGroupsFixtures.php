<?php

namespace App\DataFixtures;

use App\Entity\TricksGroups;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\Persistence\ObjectManager;

class AllGroupsFixtures extends Fixture
{
    public function load(ObjectManager $manager)

    {
        $tab = array(
            array('name' => 'Grab','ref' => 'Grab', 'description' => 'Un grab consiste à attraper la planche avec la main pendant le saut. Le verbe anglais to grab signifie « attraper. » Il existe plusieurs types de grabs selon la position de la saisie et la main choisie pour l\'effectuer, avec des difficultés variables.' ),
            array('name' => 'Rotation','ref' => 'Rotation','description' => 'On désigne par le mot « rotation » uniquement des rotations horizontales. Le principe est d\'effectuer une rotation horizontale pendant le saut, puis d\'attérir en position switch ou normal. La nomenclature se base sur le nombre de degrés de rotation effectués.' ),
            array('name' => 'Flip','ref' => 'Flip', 'description' => 'Un flip est une rotation verticale. On distingue les front flips, rotations en avant, et les back flips, rotations en arrière. Il est possible de faire plusieurs flips à la suite, et d\'ajouter un grab à la rotation.'),
            array('name' => 'Rotations désaxées','ref' => 'Rotations desaxées', 'description' => 'Une rotation désaxée est une rotation initialement horizontale mais lancée avec un mouvement des épaules particulier qui désaxe la rotation. Certaines de ces rotations, bien qu\'initialement horizontales, font passer la tête en bas .'),
            array('name' => 'Slides','ref' => 'Slides', 'description' => 'Un slide consiste à glisser sur une barre de slide. Le slide se fait soit avec la planche dans l\'axe de la barre, soit perpendiculaire, soit plus ou moins désaxé.'),
            array('name' => 'One foot tricks','ref' => 'One foot tricks', 'description' => 'Figures réalisée avec un pied décroché de la fixation, afin de tendre la jambe correspondante pour mettre en évidence le fait que le pied n\'est pas fixé. Ce type de figure est extrêmement dangereuse pour les ligaments du genou en cas de mauvaise réception.'),
            array('name' => 'Old school', 'ref' => 'Old school','description' => 'Le terme old school désigne un style de freestyle caractérisée par en ensemble de figure et une manière de réaliser des figures passée de mode, qui fait penser au freestyle des années 1980 - début 1990 (par opposition à new school).')
        );

        foreach ($tab as $row)
        {
            $groups = new TricksGroups();
            $groups->setName($row['name']);
            $groups->setDescription($row['description']);

            $this->addReference($row['ref'], $groups);

            $manager->persist($groups);
        }

        $manager->flush();
    }

}
