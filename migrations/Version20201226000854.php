<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20201226000854 extends AbstractMigration
{
    public function getDescription() : string
    {
        return '';
    }

    public function up(Schema $schema) : void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE commander (id INT AUTO_INCREMENT NOT NULL, name VARCHAR(128) NOT NULL, scryfall_url VARCHAR(255) DEFAULT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE commander_color (commander_id INT NOT NULL, color_id INT NOT NULL, INDEX IDX_405007FC3349A583 (commander_id), INDEX IDX_405007FC7ADA1FB5 (color_id), PRIMARY KEY(commander_id, color_id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('ALTER TABLE commander_color ADD CONSTRAINT FK_405007FC3349A583 FOREIGN KEY (commander_id) REFERENCES commander (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE commander_color ADD CONSTRAINT FK_405007FC7ADA1FB5 FOREIGN KEY (color_id) REFERENCES color (id) ON DELETE CASCADE');
    }

    public function down(Schema $schema) : void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE commander_color DROP FOREIGN KEY FK_405007FC3349A583');
        $this->addSql('DROP TABLE commander');
        $this->addSql('DROP TABLE commander_color');
    }
}
