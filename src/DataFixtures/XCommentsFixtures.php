<?php

namespace App\DataFixtures;

use App\Entity\Comments;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\Persistence\ObjectManager;

class XCommentsFixtures extends Fixture
{
    public function load(ObjectManager $manager)
    {
        $tab = array(
            array('author' => 'user1', 'trick' => 'trick16', 'comment' => "J'adore cette figure je la conseille à tout le monde.", 'dateTime' => '2018-04-24 00:00:00'),
            array('author' => 'user3', 'trick' => 'trick16', 'comment' => "C'est vrai qu'elle a l'air vraiment bien mais j'ai un peu peur de la ratée, elle a l'air assez technique.", 'dateTime' => '2018-04-24 00:00:00'),
            array('author' => 'user1', 'trick' => 'trick16', 'comment' => "Oui au premier abord elle a l'air compliquée mais en fait ça va, je te la conseille.", 'dateTime' => '2018-04-24 00:00:00'),
            array('author' => 'user2', 'trick' => 'trick16', 'comment' => "Super figure.", 'dateTime' => '2018-04-24 00:00:00'),
            array('author' => 'user3', 'trick' => 'trick3', 'comment' => "Très bonne figure, la meilleure que j'ai pu faire.", 'dateTime' => '2018-04-24 00:00:00'),
            array('author' => 'user2', 'trick' => 'trick3', 'comment' => "Je suis d'accord", 'dateTime' => '2018-04-24 00:00:00'),
            array('author' => 'user1', 'trick' => 'trick15', 'comment' => "A essayer.", 'dateTime' => '2018-04-24 00:00:00'),
        );

        foreach ($tab as $row)
        {
            $comment = new Comments();
            $comment->setTrick($row['trick']);
            $comment->setComment($row['comment']);
            $comment->setDateTime();

            $author = $this->getReference($row['author']);
            $comment->setAuthor($author);

            $trick = $this->getReference($row['trick']);
            $comment->setTrick($trick);

            $manager->persist($comment);
        }

        $manager->flush();
    }

}
