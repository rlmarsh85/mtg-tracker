<?php

namespace App\DataFixtures;

use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;

use App\Entity\Color;
use App\Entity\Commander;
use App\Entity\Player;
use App\Entity\GameFormat;
use App\Entity\Deck;

use App\DataFixtures\GameFormatFixtures;
use App\DataFixtures\PlayerFixture;
use App\DataFixtures\CommanderFixtures;
use Doctrine\Common\DataFixtures\DependentFixtureInterface;

use App\Repository\ColorRepository;
use App\Repository\CommanderRepository;
use App\Repository\PlayerRepository;
use App\Repository\GameFormatRepository;

class DeckFixtures extends Fixture  implements DependentFixtureInterface
{
    public function load(ObjectManager $manager)
    {
        $deck_details = [
          ["Waiting for Godo","Lee\'s Godo deck. Basically save enough mana to cast Godo and win.", "CEDH","Lee",["Godo"],["Red"]],
          ["Super Mill","Lee\'s mill deck. Try to get an infinite combo or just attrition", "EDH","Lee",["Phenax"],["Blue", "Black"]],
          ["White Weenies", "Gain life, beef creatures.","EDH","Zane",["Heliod, Sun-Crowned"],["White"]],
          ["Spies Like Us", "Cast weenies, draw cards", "EDH", "Zane",["Edric"],["Blue", "Green"]],
          ["Sysmic Swans", "Use lands for kills", "EDH", "Jesse", ["Kynaios"],["Red","Green","White","Blue"]],
          ["Death Triggers", "Sac Creatuers, double up and do things","EDH","Fed",["Teysa Karlov"],["Black","White"]],
          ["COVID-19", "Gain Life and plague everthing", "EDH", "Lee", ["Kambal"],["Black", "White"]],
          ["Cat Party Fast", "Destory lands kill, death by cats", "EDH", "Jesse",["Kaheera","Arahbo"],["White","Green"]],
          ["Golos Storm", "Dumb combos", "CEDH", "Lee", ["Golos, Tireless Pilgrim"],["White","Green","Blue","Black","Red"]],
          ["Ezuri elves", "It's Elves!", "EDH", "Mike", ["Ezuri, Renegade Leader"],["Green"]],
          ["Thopter Storm","Make a lot of thopters","EDH", "Zane", ["Sai, Master Thopterist"],["Blue"]],
          ["A Canticle for Golos", "Get 10 gates", "EDH", "Zane", ["Golos, Tireless Pilgrim"],["White","Green","Blue","Black","Red"]],
          ["Sacrifice Your Feelings", "Sac and cause paint", "EDH", "Mike", ["Savra, Queen of the Golgari"],["Green","Black"]],
          ["Minataur Maze", "Menacing minataurs", "EDH", "Lee", ["Sethron, Hurloon General"],["Red","Black"]],
          ["Orzhov Elephant", "Piss everyone off", "EDH", "Jesse",["Teysa, Orzhov Scion"],["Black","White"]],
          ["Oko Miracles", "Win with miracles", "Duel", "Jesse",[],["Blue","Green"]],
          ["Classic Burn", "Burn and wheel", "Duel", "Lee", [], ["Red"]],
          ["Red/Green Devotion", "", "Duel", "Jesse", [], ["Red","Green"]],
          ["She's Got Worms", "", "EDH", "Mike",["Trostani, Selesnya's Voice"],["Green","White"]],
          ["Super Xman Hydras", "Big X spells and hydras", "EDH", "Jesse",["Zaxara, the Exemplary"],["Black","Green","Blue"]],
          ["Turn Walk", "Counter and combo", "EDH", "Lee", ["Arcanis the Omnipotent"],["Blue"]],
          ["Backstab", "Destroy stuff?", "EDH", "Lee", ["Starke of Rath"], ["Red"]]
        ];

        $formatRepo = $manager->getRepository(GameFormat::class);
        $playerRepo = $manager->getRepository(Player::class);
        $commanderRepo = $manager->getRepository(Commander::class);
        $colorRepo = $manager->getRepository(Color::class);

        for($i=0; $i < count($deck_details);$i++){
          $deck = new Deck();

          $deck->setName($deck_details[$i][0]);
          $deck->setDescription($deck_details[$i][1]);

          $format = $formatRepo->findByName($deck_details[$i][2]);
          $deck->setPrimaryFormat($format[0]);

          $player = $playerRepo->findByName($deck_details[$i][3]);
          $deck->setPrimaryPlayer($player[0]);


          for($j=0; $j < count($deck_details[$i][4]); $j++ ){
            $commander = $commanderRepo->findByName($deck_details[$i][4][$j]);
            $deck->addCommander($commander[0]);
          }

          for($j=0; $j < count($deck_details[$i][5]); $j++ ){
            $color = $colorRepo->findByName($deck_details[$i][5][$j]);
            $deck->addColor($color[0]);
          }

          $manager->persist($deck);
        }

        $manager->flush();
    }

    public function getDependencies(){
      return array(
          CommanderFixtures::class,
          GameFormatFixtures::class,
          PlayerFixture::class
      );
    }
}
