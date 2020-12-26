<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20201226001839 extends AbstractMigration
{
    public function getDescription() : string
    {
        return '';
    }

    public function up(Schema $schema) : void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE deck CHANGE format_id primary_format_id INT DEFAULT NULL');
        $this->addSql('ALTER TABLE deck ADD CONSTRAINT FK_4FAC36371CB24BE2 FOREIGN KEY (primary_format_id) REFERENCES game_format (id)');
        $this->addSql('CREATE INDEX IDX_4FAC36371CB24BE2 ON deck (primary_format_id)');
    }

    public function down(Schema $schema) : void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE deck DROP FOREIGN KEY FK_4FAC36371CB24BE2');
        $this->addSql('DROP INDEX IDX_4FAC36371CB24BE2 ON deck');
        $this->addSql('ALTER TABLE deck CHANGE primary_format_id format_id INT DEFAULT NULL');
    }
}
