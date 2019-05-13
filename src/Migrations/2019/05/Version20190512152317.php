<?php

declare(strict_types=1);

namespace Migrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20190512152317 extends AbstractMigration
{
    public function getDescription(): string
    {
        return 'Add attachments table';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->abortIf('postgresql' !== $this->connection->getDatabasePlatform()->getName(), 'Migration can only be executed safely on \'postgresql\'.');

        $this->addSql('CREATE TABLE npd_attachment (
          uuid UUID NOT NULL, 
          lesson_uuid UUID NOT NULL, 
          file_name VARCHAR(255) NOT NULL, 
          file_extension VARCHAR(10) NOT NULL, 
          PRIMARY KEY(uuid)
        )');
        $this->addSql('CREATE INDEX IDX_76CE10B2E80E96C2 ON npd_attachment (lesson_uuid)');
        $this->addSql('COMMENT ON COLUMN npd_attachment.uuid IS \'(DC2Type:uuid)\'');
        $this->addSql('COMMENT ON COLUMN npd_attachment.lesson_uuid IS \'(DC2Type:uuid)\'');
        $this->addSql('ALTER TABLE 
          npd_attachment 
        ADD 
          CONSTRAINT FK_76CE10B2E80E96C2 FOREIGN KEY (lesson_uuid) REFERENCES npd_lesson (uuid) NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE npd_lesson ALTER duration_in_minutes DROP DEFAULT');
        $this->addSql('ALTER TABLE npd_lesson ALTER duration_in_minutes SET NOT NULL');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->abortIf('postgresql' !== $this->connection->getDatabasePlatform()->getName(), 'Migration can only be executed safely on \'postgresql\'.');

        $this->addSql('DROP TABLE npd_attachment');
        $this->addSql('ALTER TABLE npd_lesson ALTER duration_in_minutes SET DEFAULT 0');
        $this->addSql('ALTER TABLE npd_lesson ALTER duration_in_minutes DROP NOT NULL');
    }
}
