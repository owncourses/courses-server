<?php

declare(strict_types=1);

namespace Migrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20190316131700 extends AbstractMigration
{
    public function getDescription() : string
    {
        return '';
    }

    public function up(Schema $schema) : void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'postgresql', 'Migration can only be executed safely on \'postgresql\'.');

        $this->addSql('CREATE TABLE npd_lesson (
          uuid UUID NOT NULL, 
          module_id UUID NOT NULL, 
          title VARCHAR(255) NOT NULL, 
          description TEXT NOT NULL, 
          embed_code TEXT NOT NULL, 
          created TIMESTAMP DEFAULT CURRENT_TIMESTAMP NOT NULL, 
          updated TIMESTAMP(0) WITHOUT TIME ZONE DEFAULT NULL, 
          position INT DEFAULT 0 NOT NULL, 
          PRIMARY KEY(uuid)
        )');
        $this->addSql('CREATE INDEX IDX_148D7D2FAFC2B591 ON npd_lesson (module_id)');
        $this->addSql('COMMENT ON COLUMN npd_lesson.uuid IS \'(DC2Type:uuid)\'');
        $this->addSql('COMMENT ON COLUMN npd_lesson.module_id IS \'(DC2Type:uuid)\'');
        $this->addSql('ALTER TABLE 
          npd_lesson 
        ADD 
          CONSTRAINT FK_148D7D2FAFC2B591 FOREIGN KEY (module_id) REFERENCES npd_module (uuid) NOT DEFERRABLE INITIALLY IMMEDIATE');
    }

    public function down(Schema $schema) : void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'postgresql', 'Migration can only be executed safely on \'postgresql\'.');

        $this->addSql('CREATE SCHEMA public');
        $this->addSql('DROP TABLE npd_lesson');
    }
}
