<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20201225234452 extends AbstractMigration
{
    public function getDescription() : string
    {
        return '';
    }

    public function up(Schema $schema) : void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE deck CHANGE primary_player_id primary_player_id INT DEFAULT NULL');
        $this->addSql('ALTER TABLE deck ADD CONSTRAINT FK_4FAC3637537D4838 FOREIGN KEY (primary_player_id) REFERENCES player (id)');
        $this->addSql('CREATE INDEX IDX_4FAC3637537D4838 ON deck (primary_player_id)');
    }

    public function down(Schema $schema) : void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE deck DROP FOREIGN KEY FK_4FAC3637537D4838');
        $this->addSql('DROP INDEX IDX_4FAC3637537D4838 ON deck');
        $this->addSql('ALTER TABLE deck CHANGE primary_player_id primary_player_id INT NOT NULL');
    }
}
