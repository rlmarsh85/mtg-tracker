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
          ["Gitrog", "https://scryfall.com/card/soi/245/the-gitrog-monster", ["Black","Green"]],
          ["Heliod, Sun-Crowned", "https://scryfall.com/card/thb/18/heliod-sun-crowned", ["White"]],
          ["Edric", "https://scryfall.com/card/c16/195/edric-spymaster-of-trest", ["Blue","Green"]],
          ["Kynaios","https://scryfall.com/card/c16/36/kynaios-and-tiro-of-meletis",["Red","Blue","Green","White"]],
          ["Teysa Karlov","https://scryfall.com/card/rna/212/teysa-karlov",["White","Black"]],
          ["Kambal","https://scryfall.com/card/klr/198/kambal-consul-of-allocation",["Black","White"]],
          ["Kaheera","https://scryfall.com/card/iko/224/kaheera-the-orphanguard",["White", "Green"]],
          ["Arahbo","https://scryfall.com/card/c17/35/arahbo-roar-of-the-world", ["White", "Green"]],
          ["Golos, Tireless Pilgrim","https://scryfall.com/card/m20/226/golos-tireless-pilgrim", ["White","Green","Blue","Black","Red"]],
          ["Sai, Master Thopterist","https://scryfall.com/card/m19/69/sai-master-thopterist",["Blue"]],
          ["Ezuri, Renegade Leader","https://scryfall.com/card/ddu/1/ezuri-renegade-leader",["Green"]],
          ["Sethron, Hurloon General", "https://scryfall.com/card/jmp/25/sethron-hurloon-general", ["Red", "Black"]],
          ["Teysa, Orzhov Scion","https://scryfall.com/card/gpt/134/teysa-orzhov-scion",["Black", "White"]],
          ["Savra, Queen of the Golgari","https://scryfall.com/card/rav/225/savra-queen-of-the-golgari",["Green","Black"]],
          ["Trostani, Selesnya's Voice","https://scryfall.com/card/c19/204/trostani-selesnyas-voice",["Green","White"]],
          ["Zaxara, the Exemplary","https://scryfall.com/card/c20/20/zaxara-the-exemplary",["Black","Green","Blue"]],
          ["Arcanis the Omnipotent","https://scryfall.com/card/c17/80/arcanis-the-omnipotent",["Blue"]],
          ["Starke of Rath","https://scryfall.com/card/tpr/162/starke-of-rath",["Red"]]
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
