<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20201226001442 extends AbstractMigration
{
    public function getDescription() : string
    {
        return '';
    }

    public function up(Schema $schema) : void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE commanders_decks (deck_id INT NOT NULL, commander_id INT NOT NULL, INDEX IDX_4C229BE2111948DC (deck_id), INDEX IDX_4C229BE23349A583 (commander_id), PRIMARY KEY(deck_id, commander_id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('ALTER TABLE commanders_decks ADD CONSTRAINT FK_4C229BE2111948DC FOREIGN KEY (deck_id) REFERENCES deck (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE commanders_decks ADD CONSTRAINT FK_4C229BE23349A583 FOREIGN KEY (commander_id) REFERENCES commander (id) ON DELETE CASCADE');
    }

    public function down(Schema $schema) : void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('DROP TABLE commanders_decks');
    }
}
