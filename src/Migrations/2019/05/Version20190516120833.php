<?php

declare(strict_types=1);

namespace Migrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

final class Version20190516120833 extends AbstractMigration
{
    public function getDescription(): string
    {
        return 'Add authors table and course <=> author many to many relation table';
    }

    public function up(Schema $schema): void
    {
        $this->abortIf('postgresql' !== $this->connection->getDatabasePlatform()->getName(), 'Migration can only be executed safely on \'postgresql\'.');

        $this->addSql('CREATE TABLE npd_author (
          uuid UUID NOT NULL, 
          name VARCHAR(255) NOT NULL, 
          bio TEXT DEFAULT NULL, 
          picture VARCHAR(255) DEFAULT NULL, 
          created TIMESTAMP(0) WITHOUT TIME ZONE NOT NULL, 
          updated TIMESTAMP(0) WITHOUT TIME ZONE DEFAULT NULL, 
          PRIMARY KEY(uuid)
        )');
        $this->addSql('COMMENT ON COLUMN npd_author.uuid IS \'(DC2Type:uuid)\'');
        $this->addSql('CREATE TABLE npd_author_course (
          author_id UUID NOT NULL, 
          course_id INT NOT NULL, 
          PRIMARY KEY(author_id, course_id)
        )');
        $this->addSql('CREATE INDEX IDX_61BCADC1F675F31B ON npd_author_course (author_id)');
        $this->addSql('CREATE INDEX IDX_61BCADC1591CC992 ON npd_author_course (course_id)');
        $this->addSql('COMMENT ON COLUMN npd_author_course.author_id IS \'(DC2Type:uuid)\'');
        $this->addSql('ALTER TABLE 
          npd_author_course 
        ADD 
          CONSTRAINT FK_61BCADC1F675F31B FOREIGN KEY (author_id) REFERENCES npd_author (uuid) NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE 
          npd_author_course 
        ADD 
          CONSTRAINT FK_61BCADC1591CC992 FOREIGN KEY (course_id) REFERENCES course (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
    }

    public function down(Schema $schema): void
    {
        $this->abortIf('postgresql' !== $this->connection->getDatabasePlatform()->getName(), 'Migration can only be executed safely on \'postgresql\'.');

        $this->addSql('ALTER TABLE npd_author_course DROP CONSTRAINT FK_61BCADC1F675F31B');
        $this->addSql('DROP TABLE npd_author');
        $this->addSql('DROP TABLE npd_author_course');
    }
}
