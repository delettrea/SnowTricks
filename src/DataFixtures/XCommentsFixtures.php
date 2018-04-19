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
            array('author' => 'user1', 'trick' => 'trick1', 'comment' => 'Commentaire n°1, Autheur : User1.' ),
            array('author' => 'user1', 'trick' => 'trick1', 'comment' => 'Commentaire n°2, Autheur : User1.' ),
            array('author' => 'user2', 'trick' => 'trick1', 'comment' => 'Commentaire n°3, Autheur : User2.' ),
            array('author' => 'user3', 'trick' => 'trick1', 'comment' => 'Commentaire n°4, Autheur : User3.' ),
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
