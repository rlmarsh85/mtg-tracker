<?php

namespace App\DataFixtures;

use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;
use App\Entity\GameFormat;
use Doctrine\Bundle\FixturesBundle\FixtureGroupInterface;

class GameFormatFixtures extends Fixture implements FixtureGroupInterface
{
    public function load(ObjectManager $manager)
    {
      $formats = array(
        array("60 Card", -1),
        array("Duel", 2),
        array("EDH", -1),
        array("CEDH", -1),
        array("Five Star", 5),
        array("Five Star Colors", 5)
      );
      for($i=0; $i < count($formats); $i++){
        $format = new GameFormat();
        $format->setName($formats[$i][0]);
        $format->setNumberPlayers($formats[$i][1]);
        $manager->persist($format);
      }

        $manager->flush();
    }

    public static function getGroups(): array
    {
        return ['static'];
    }        
}
