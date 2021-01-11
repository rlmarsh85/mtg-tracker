<?php

namespace App\DataFixtures;

use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;
use App\Entity\Player;

class PlayerFixture extends Fixture
{
    public function load(ObjectManager $manager)
    {
      $players = array(
        array("Jesse", "Blue Mage"),
        array("Zane", "Mud"),
        array("Mike", "Green Mage"),
        array("Lee", "BurgerDude"),
        array("Fed", "Fedarino")
      );
      for($i=0; $i < count($players); $i++){
        $player = new Player();
        $player->setName($players[$i][0]);
        $player->setNickname($players[$i][1]);
        $manager->persist($player);
      }

        $manager->flush();
  }
}
