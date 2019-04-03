<?php

declare(strict_types=1);

namespace Migrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20190223101249 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->abortIf('postgresql' !== $this->connection->getDatabasePlatform()->getName(), 'Migration can only be executed safely on \'postgresql\'.');

        $this->addSql('CREATE TABLE user_course (
          user_id UUID NOT NULL, 
          course_id INT NOT NULL, 
          PRIMARY KEY(user_id, course_id)
        )');
        $this->addSql('CREATE INDEX IDX_73CC7484A76ED395 ON user_course (user_id)');
        $this->addSql('CREATE INDEX IDX_73CC7484591CC992 ON user_course (course_id)');
        $this->addSql('COMMENT ON COLUMN user_course.user_id IS \'(DC2Type:uuid)\'');
        $this->addSql('ALTER TABLE 
          user_course 
        ADD 
          CONSTRAINT FK_73CC7484A76ED395 FOREIGN KEY (user_id) REFERENCES npd_user (uuid) ON DELETE CASCADE NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE 
          user_course 
        ADD 
          CONSTRAINT FK_73CC7484591CC992 FOREIGN KEY (course_id) REFERENCES course (id) ON DELETE CASCADE NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE npd_user ADD first_name VARCHAR(255) NOT NULL');
        $this->addSql('ALTER TABLE npd_user ADD last_name VARCHAR(255) NOT NULL');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->abortIf('postgresql' !== $this->connection->getDatabasePlatform()->getName(), 'Migration can only be executed safely on \'postgresql\'.');

        $this->addSql('DROP TABLE user_course');
        $this->addSql('ALTER TABLE npd_user DROP first_name');
        $this->addSql('ALTER TABLE npd_user DROP last_name');
    }
}
