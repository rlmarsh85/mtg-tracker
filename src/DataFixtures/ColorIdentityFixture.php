<?php

namespace App\DataFixtures;

use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;
use App\Entity\ColorIdentity;
use Doctrine\Bundle\FixturesBundle\FixtureGroupInterface;

class ColorIdentityFixture extends Fixture implements FixtureGroupInterface
{
    public function load(ObjectManager $manager)
    {

        $identities = [
            ["Blue/White", "Azorius"],
            ["Black/Blue", "Dimir"],
            ["Black/Red", "Rakdos"],
            ["Green/Red", "Gruul"],
            ["Green/White", "Selesnya"],
            ["Black/White", "Orzhov"],
            ["Blue/Red", "Izzet"],
            ["Black/Green", "Golgari"],
            ["Red/White", "Boros"],
            ["Blue/Green", "Simic"],
            ["Black/Green/Red", "Jund"],
            ["Blue/Green/White", "Bant"],
            ["Black/Blue/Red", "Grixis"],
            ["Green/Red/White", "Naya"],
            ["Black/Blue/White", "Esper"],
            ["Blue/Red/White", "Jeskai"],
            ["Black/Red/White", "Mardu"],
            ["Black/Blue/Green", "Sultai"],
            ["Blue/Green/Red", "Ketria"],
            ["Black/Green/White", "Abzan"],
            ["Black/Blue/Green/Red", "Non-white"],
            ["Black/Green/Red/White", "Non-blue"],
            ["Blue/Green/Red/White", "Non-black"],
            ["Black/Blue/Green/White", "Non-red"],
            ["Black/Blue/Red/White", "Non-green"],
            ["Black/Blue/Green/Red/White", "5-color"]
        ];

          for($i=0; $i < count($identities); $i++){
            $ci = new ColorIdentity();
            $ci->setColorCombo($identities[$i][0]);
            $ci->setComboName($identities[$i][1]);
            $manager->persist($ci);
          }
    
            $manager->flush();
    }

    public static function getGroups(): array
    {
        return ['static','new1'];
    }    
}
