<?php

declare(strict_types=1);

namespace Migrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20190303200340 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->abortIf('postgresql' !== $this->connection->getDatabasePlatform()->getName(), 'Migration can only be executed safely on \'postgresql\'.');

        $this->addSql('ALTER TABLE npd_user ADD password_need_to_be_changed BOOLEAN DEFAULT NULL');
        $this->addSql('ALTER TABLE user_course DROP CONSTRAINT FK_73CC7484A76ED395');
        $this->addSql('ALTER TABLE user_course DROP CONSTRAINT FK_73CC7484591CC992');
        $this->addSql('ALTER TABLE 
          user_course 
        ADD 
          CONSTRAINT FK_73CC7484A76ED395 FOREIGN KEY (user_id) REFERENCES npd_user (uuid) NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE 
          user_course 
        ADD 
          CONSTRAINT FK_73CC7484591CC992 FOREIGN KEY (course_id) REFERENCES course (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->abortIf('postgresql' !== $this->connection->getDatabasePlatform()->getName(), 'Migration can only be executed safely on \'postgresql\'.');

        $this->addSql('ALTER TABLE user_course DROP CONSTRAINT fk_73cc7484a76ed395');
        $this->addSql('ALTER TABLE user_course DROP CONSTRAINT fk_73cc7484591cc992');
        $this->addSql('ALTER TABLE 
          user_course 
        ADD 
          CONSTRAINT fk_73cc7484a76ed395 FOREIGN KEY (user_id) REFERENCES npd_user (uuid) ON DELETE CASCADE NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE 
          user_course 
        ADD 
          CONSTRAINT fk_73cc7484591cc992 FOREIGN KEY (course_id) REFERENCES course (id) ON DELETE CASCADE NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE npd_user DROP password_need_to_be_changed');
    }
}
