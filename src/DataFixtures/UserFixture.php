<?php

namespace App\DataFixtures;

use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\Persistence\ObjectManager;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;
use App\Entity\User;

class UserFixture extends Fixture
{

    private $encoder;

    public function __construct(UserPasswordEncoderInterface $encoder)
    {
        $this->encoder = $encoder;
    }

    public function load(ObjectManager $manager)
    {
        /**
         * Seed admin
         */
        $user = new User();

        $roles = $user->getRoles();
        $roles[] = 'ROLE_ADMIN';
        $roles[] = 'ROLE_SUPER_ADMIN';
        
        $user->setEmail('a@a.pl');
        $user->setRoles($roles);
        $user->setPassword($this->encoder->encodePassword($user, 'aaa'));
        $user->setTimestamps();

        $manager->persist($user);
        $manager->flush();
    }
}
