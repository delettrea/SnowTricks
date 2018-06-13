<?php

namespace App\DataFixtures;

use App\Entity\Illustrations;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\Persistence\ObjectManager;

class IllustrationsFixtures extends Fixture
{
    public function load(ObjectManager $manager)
    {
        $tab = array(
            array('name' => 'riders-1939640_1920.jpg', 'trick_id' => 'trick1'),
            array('name' => 'fun-3189092_1920.jpg', 'trick_id' => 'trick1'),
            array('name' => 'man-3189091_1920.jpg', 'trick_id' => 'trick1'),
            array('name' => 'ski-103600_1920.jpg', 'trick_id' => 'trick2'),
            array('name' => 'ski-slope-3184931_1920.jpg', 'trick_id' => 'trick2'),
            array('name' => 'snow-1094695_1280.jpg', 'trick_id' => 'trick4'),
            array('name' => 'snow-1283525_1920.jpg', 'trick_id' => 'trick4'),
            array('name' => 'snow-3090087_1920.jpg', 'trick_id' => 'trick4'),
            array('name' => 'snowboard-113781_1920.jpg', 'trick_id' => 'trick4'),
            array('name' => 'snowboard-113847_1920.jpg', 'trick_id' => 'trick5'),
            array('name' => 'snowboard-227540_1920.jpg', 'trick_id' => 'trick5'),
            array('name' => 'snowboard-227541_640.jpg', 'trick_id' => 'trick6'),
            array('name' => 'snowboard-227541_1920.jpg', 'trick_id' => 'trick6'),
            array('name' => 'snowboard-599323_1920.jpg', 'trick_id' => 'trick6'),
            array('name' => 'snowboard-1107266_1920.jpg', 'trick_id' => 'trick7'),
            array('name' => 'snowboard-779255_1920.jpg', 'trick_id' => 'trick7'),
            array('name' => 'snowboard-1181999_1920.jpg', 'trick_id' => 'trick8'),
            array('name' => 'snowboard-1475536_1920.jpg', 'trick_id' => 'trick8'),
            array('name' => 'snowboard-1939636_1920.jpg', 'trick_id' => 'trick8'),
            array('name' => 'snowboard-2153554_1920.jpg', 'trick_id' => 'trick8'),
            array('name' => 'snowboard-2153557_1920.jpg', 'trick_id' => 'trick8'),
            array('name' => 'snowboard-3089238_1920.jpg', 'trick_id' => 'trick9'),
            array('name' => 'snowboarder-55099_1280.jpg', 'trick_id' => 'trick9'),
            array('name' => 'snowboarder-690779_1920.jpg', 'trick_id' => 'trick10'),
            array('name' => 'snowboarder-1261790_1920.jpg', 'trick_id' => 'trick10'),
            array('name' => 'snowboarder-2030709_1920.jpg', 'trick_id' => 'trick10'),
            array('name' => 'snowboarders-245181_1920.jpg', 'trick_id' => 'trick11'),
            array('name' => 'winter-878728_1920.jpg', 'trick_id' => 'trick12'),
            array('name' => 'winter-113799_1920.jpg', 'trick_id' => 'trick12'),
            array('name' => 'snowboarders-245182_1920.jpg', 'trick_id' => 'trick13'),
            array('name' => 'snowboarding-274732_1920.jpg', 'trick_id' => 'trick13'),
            array('name' => 'snowboarding-554048_640.jpg', 'trick_id' => 'trick14'),
            array('name' => 'snowboarding-554048_1920.jpg', 'trick_id' => 'trick14'),
            array('name' => 'snowboarding-655547_1920.jpg', 'trick_id' => 'trick15'),
            array('name' => 'snowboarding-1161799_1920.jpg', 'trick_id' => 'trick16'),
            array('name' => 'snowboarding-1734841_1920.jpg', 'trick_id' => 'trick16'),
            array('name' => 'snowboarding-1985751_1920.jpg', 'trick_id' => 'trick16'),
            array('name' => 'snowboarding-3176182_1920.jpg', 'trick_id' => 'trick16'),
        );

        foreach ($tab as $row)
        {
            $img = new Illustrations();
            $img->setName($row['name']);

            $trick = $this->getReference($row['trick_id']);
            $img->setTrick($trick);

            $manager->persist($img);
        }

        $manager->flush();
    }

}