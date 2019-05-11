<?php

declare(strict_types=1);

namespace Migrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20190511183642 extends AbstractMigration
{
    public function getDescription(): string
    {
        return 'Add user lessons table with info about lessons progress';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->abortIf('postgresql' !== $this->connection->getDatabasePlatform()->getName(), 'Migration can only be executed safely on \'postgresql\'.');

        $this->addSql('CREATE TABLE npd_user_lesson (
          uuid UUID NOT NULL, 
          user_uuid UUID NOT NULL, 
          lesson_uuid UUID NOT NULL, 
          completed BOOLEAN DEFAULT NULL, 
          created TIMESTAMP(0) WITHOUT TIME ZONE NOT NULL, 
          updated TIMESTAMP(0) WITHOUT TIME ZONE DEFAULT NULL, 
          PRIMARY KEY(uuid)
        )');
        $this->addSql('CREATE INDEX IDX_E4F546A3ABFE1C6F ON npd_user_lesson (user_uuid)');
        $this->addSql('CREATE INDEX IDX_E4F546A3E80E96C2 ON npd_user_lesson (lesson_uuid)');
        $this->addSql('COMMENT ON COLUMN npd_user_lesson.uuid IS \'(DC2Type:uuid)\'');
        $this->addSql('COMMENT ON COLUMN npd_user_lesson.user_uuid IS \'(DC2Type:uuid)\'');
        $this->addSql('COMMENT ON COLUMN npd_user_lesson.lesson_uuid IS \'(DC2Type:uuid)\'');
        $this->addSql('ALTER TABLE 
          npd_user_lesson 
        ADD 
          CONSTRAINT FK_E4F546A3ABFE1C6F FOREIGN KEY (user_uuid) REFERENCES npd_user (uuid) NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE 
          npd_user_lesson 
        ADD 
          CONSTRAINT FK_E4F546A3E80E96C2 FOREIGN KEY (lesson_uuid) REFERENCES npd_lesson (uuid) NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE npd_lesson DROP completed');
        $this->addSql('ALTER INDEX idx_73cc7484a76ed395 RENAME TO IDX_A1F5DE9A76ED395');
        $this->addSql('ALTER INDEX idx_73cc7484591cc992 RENAME TO IDX_A1F5DE9591CC992');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->abortIf('postgresql' !== $this->connection->getDatabasePlatform()->getName(), 'Migration can only be executed safely on \'postgresql\'.');

        $this->addSql('DROP TABLE npd_user_lesson');
        $this->addSql('ALTER INDEX idx_a1f5de9a76ed395 RENAME TO idx_73cc7484a76ed395');
        $this->addSql('ALTER INDEX idx_a1f5de9591cc992 RENAME TO idx_73cc7484591cc992');
        $this->addSql('ALTER TABLE npd_lesson ADD completed BOOLEAN DEFAULT NULL');
    }
}
