<?php

declare(strict_types=1);

namespace Migrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

final class Version20200520135254 extends AbstractMigration
{
    public function getDescription(): string
    {
        return 'Introduce demo courses and demo lessons';
    }

    public function up(Schema $schema): void
    {
        $this->abortIf('postgresql' !== $this->connection->getDatabasePlatform()->getName(), 'Migration can only be executed safely on \'postgresql\'.');

        $this->addSql('CREATE TABLE npd_demo_lesson (
          uuid UUID NOT NULL, 
          lesson_uuid UUID NOT NULL, 
          created TIMESTAMP DEFAULT CURRENT_TIMESTAMP NOT NULL, 
          updated TIMESTAMP(0) WITHOUT TIME ZONE DEFAULT NULL, 
          PRIMARY KEY(uuid)
        )');
        $this->addSql('CREATE UNIQUE INDEX UNIQ_FD5B2AE5E80E96C2 ON npd_demo_lesson (lesson_uuid)');
        $this->addSql('COMMENT ON COLUMN npd_demo_lesson.uuid IS \'(DC2Type:uuid)\'');
        $this->addSql('COMMENT ON COLUMN npd_demo_lesson.lesson_uuid IS \'(DC2Type:uuid)\'');
        $this->addSql('ALTER TABLE 
          npd_demo_lesson 
        ADD 
          CONSTRAINT FK_FD5B2AE5E80E96C2 FOREIGN KEY (lesson_uuid) REFERENCES npd_lesson (uuid) NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE course ADD parent_id INT DEFAULT NULL');
        $this->addSql('ALTER TABLE course ADD type VARCHAR(255) DEFAULT \'standard\' NOT NULL');
        $this->addSql('ALTER TABLE course ADD purchase_url VARCHAR(255) DEFAULT NULL');
        $this->addSql('ALTER TABLE 
          course 
        ADD 
          CONSTRAINT FK_169E6FB9727ACA70 FOREIGN KEY (parent_id) REFERENCES course (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('CREATE INDEX IDX_169E6FB9727ACA70 ON course (parent_id)');
    }

    public function down(Schema $schema): void
    {
        $this->abortIf('postgresql' !== $this->connection->getDatabasePlatform()->getName(), 'Migration can only be executed safely on \'postgresql\'.');

        $this->addSql('DROP TABLE npd_demo_lesson');
        $this->addSql('ALTER TABLE course DROP CONSTRAINT FK_169E6FB9727ACA70');
        $this->addSql('DROP INDEX IDX_169E6FB9727ACA70');
        $this->addSql('ALTER TABLE course DROP parent_id');
        $this->addSql('ALTER TABLE course DROP type');
        $this->addSql('ALTER TABLE course DROP purchase_url');
    }
}
