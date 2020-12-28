<?php

namespace App\DataFixtures;

use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;
use App\Repository\ColorRepository;
use App\Entity\Color;
use App\Entity\Commander;

class CommanderFixtures extends Fixture
{
    public function load(ObjectManager $manager)
    {

        $commanderDetails = [
          ["Godo","https://scryfall.com/card/2xm/128/godo-bandit-warlord", ["Red"]],
          ["Phenax", "https://scryfall.com/card/bng/152/phenax-god-of-deception", ["Blue","Black"]],
          ["Gitrog", "https://scryfall.com/card/soi/245/the-gitrog-monster", ["Black","Green"]]
        ];
        $colorRepo = $manager->getRepository(Color::class);

        for($i=0;$i < count($commanderDetails); $i++){

          $commander = new Commander();

          $commander->setName($commanderDetails[$i][0]);
          $commander->setScryfallURL($commanderDetails[$i][1]);

          for($j=0; $j < count($commanderDetails[$i][2]); $j++){
            $color = $colorRepo->findByName($commanderDetails[$i][2][$j])[0];
            $commander->addColor($color);
          }

          $manager->persist($commander);
        }

        $manager->flush();
    }
}
