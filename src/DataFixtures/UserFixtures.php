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
            array('ref' => 'user1', 'username' => 'User1' , 'email' => 'user1@test.test', 'password' =>'user', 'role' => 'ROLE_USER', 'isActive' => true),
            array('ref' => 'user2', 'username' => 'User2' , 'email' => 'user2@test.test', 'password' =>'user', 'role' => 'ROLE_USER', 'isActive' => true),
            array('ref' => 'user3', 'username' => 'User3' , 'email' => 'user3@test.test', 'password' =>'user', 'role' => 'ROLE_USER','isActive' => true),
        );

        $passwordEncoder = $this->container->get('security.password_encoder');

        foreach ($tab as $row)
        {
            $user = new User();
            $user->setUsername($row['username']);
            $user->setEmail($row['email']);
            $user->setRole($row['role']);
            $user->setConfirmKey();
            $user->setPasswordKey();
            $user->setIsActive($row['isActive']);
            $encodedPassword = $passwordEncoder->encodePassword($user, $row['password']);
            $user->setPassword($encodedPassword);

            $this->addReference($row['ref'], $user);

            $manager->persist($user);
        }

        $manager->flush();
    }
}