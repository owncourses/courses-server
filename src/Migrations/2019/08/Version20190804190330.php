<?php

declare(strict_types=1);

namespace Migrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

final class Version20190804190330 extends AbstractMigration
{
    public function getDescription(): string
    {
        return 'Add bookmarks';
    }

    public function up(Schema $schema): void
    {
        $this->abortIf('postgresql' !== $this->connection->getDatabasePlatform()->getName(), 'Migration can only be executed safely on \'postgresql\'.');

        $this->addSql('CREATE TABLE npd_bookmark (
          uuid UUID NOT NULL, 
          user_uuid UUID DEFAULT NULL, 
          lesson_uuid UUID NOT NULL, 
          title VARCHAR(255) NOT NULL, 
          time_in_seconds INT NOT NULL, 
          created TIMESTAMP(0) WITHOUT TIME ZONE NOT NULL, 
          updated TIMESTAMP(0) WITHOUT TIME ZONE DEFAULT NULL, 
          PRIMARY KEY(uuid)
        )');
        $this->addSql('CREATE INDEX IDX_8E334C53ABFE1C6F ON npd_bookmark (user_uuid)');
        $this->addSql('CREATE INDEX IDX_8E334C53E80E96C2 ON npd_bookmark (lesson_uuid)');
        $this->addSql('COMMENT ON COLUMN npd_bookmark.uuid IS \'(DC2Type:uuid)\'');
        $this->addSql('COMMENT ON COLUMN npd_bookmark.user_uuid IS \'(DC2Type:uuid)\'');
        $this->addSql('COMMENT ON COLUMN npd_bookmark.lesson_uuid IS \'(DC2Type:uuid)\'');
        $this->addSql('ALTER TABLE 
          npd_bookmark 
        ADD 
          CONSTRAINT FK_8E334C53ABFE1C6F FOREIGN KEY (user_uuid) REFERENCES npd_user (uuid) NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE 
          npd_bookmark 
        ADD 
          CONSTRAINT FK_8E334C53E80E96C2 FOREIGN KEY (lesson_uuid) REFERENCES npd_lesson (uuid) NOT DEFERRABLE INITIALLY IMMEDIATE');
    }

    public function down(Schema $schema): void
    {
        $this->abortIf('postgresql' !== $this->connection->getDatabasePlatform()->getName(), 'Migration can only be executed safely on \'postgresql\'.');

        $this->addSql('DROP TABLE npd_bookmark');
    }
}
