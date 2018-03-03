<?php


namespace App\DataFixtures;


use App\Entity\User;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\Persistence\ObjectManager;
use Symfony\Component\DependencyInjection\ContainerAwareInterface;
use Symfony\Component\DependencyInjection\ContainerAwareTrait;

class UserFixtures extends Fixture implements ContainerAwareInterface
{
    use  ContainerAwareTrait;

    public function load(ObjectManager $manager)
    {
        $tab = array(
            array('username' => 'User1' , 'email' => 'user1@test.test', 'password' =>'user', 'role' => 'ROLE_USER' ),
            array('username' => 'User2' , 'email' => 'user2@test.test', 'password' =>'user', 'role' => 'ROLE_USER'  ),
            array('username' => 'User3' , 'email' => 'user3@test.test', 'password' =>'user', 'role' => 'ROLE_USER'  ),
        );

        $passwordEncoder = $this->container->get('security.password_encoder');

        foreach ($tab as $row)
        {
            $user = new User();
            $user->setUsername($row['username']);
            $user->setEmail($row['email']);
            $user->setRole($row['role']);
            $encodedPassword = $passwordEncoder->encodePassword($user, $row['password']);
            $user->setPassword($encodedPassword);

            $manager->persist($user);
        }

        $manager->flush();
    }
}