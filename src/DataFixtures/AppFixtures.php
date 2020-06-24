<?php

namespace App\DataFixtures;

use App\Entity\Player;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\Persistence\ObjectManager;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;


class AppFixtures extends Fixture
{
    private $encoder;

    public function __construct(UserPasswordEncoderInterface $encoder)
    {
        $this->encoder = $encoder;
    }

    public function load(ObjectManager $manager)
    {
        for ($i = 0; $i < 20; $i++) {
            $player = new Player();
            $player->setFirstname('firstname '.$i);
            $player->setLastname('lastname '.$i);
            $player->setEmail('email'.$i.'@gmail.com');
            $player->setNickname('nickname '.$i);
            $password = $this->encoder->encodePassword($player, '0000');
            $player->setPassword($password);

            $manager->persist($player);
        }


        $manager->flush();
    }
}
