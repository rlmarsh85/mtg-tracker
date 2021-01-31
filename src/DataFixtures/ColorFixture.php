<?php

namespace App\DataFixtures;

use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;
use App\Entity\Color;
use Doctrine\Bundle\FixturesBundle\FixtureGroupInterface;

class ColorFixture extends Fixture implements FixtureGroupInterface
{
    public function load(ObjectManager $manager)
    {

      $color_names = array("Blue", "Black", "Green", "Red", "White");
      for($i=0; $i < count($color_names); $i++){
        $color = new Color();
        $color->setName($color_names[$i]);
        $manager->persist($color);
      }

      $manager->flush();
    }

    public static function getGroups(): array
    {
        return ['static'];
    }        
}
