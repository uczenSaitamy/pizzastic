<?php

namespace App\DataFixtures;

use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\Persistence\ObjectManager;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;
use App\Entity\User;
use App\Entity\Address;

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


        $address = new Address();

        $address->setAddress('Example Street 23');
        $address->setZipcode('22-222');
        $address->setCity('Cracow');
        $address->setCountry('Poland');
        $address->setUser($user);
        $address->setName('Work');

        $manager->persist($user);
        $manager->persist($address);

        $manager->flush();
    }
}
