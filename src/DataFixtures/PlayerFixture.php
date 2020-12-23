<?php

namespace App\DataFixtures;

use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;
use App\Entity\Player;

class PlayerFixture extends Fixture
{
    public function load(ObjectManager $manager)
    {
        for($i=0; $i < 10; $i++){
          $player = new Player();
          $player->setName(sprintf('lol%d', $i));
          $manager->persist($player);
        }
        $manager->flush();
    }
}
