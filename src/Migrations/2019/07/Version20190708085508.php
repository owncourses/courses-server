<?php

declare(strict_types=1);

namespace Migrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

final class Version20190708085508 extends AbstractMigration
{
    public function getDescription(): string
    {
        return 'add settings bundle tables';
    }

    public function up(Schema $schema): void
    {
        $this->abortIf('postgresql' !== $this->connection->getDatabasePlatform()->getName(), 'Migration can only be executed safely on \'postgresql\'.');

        $this->addSql('CREATE SEQUENCE npd_settings_id_seq INCREMENT BY 1 MINVALUE 1 START 1');
        $this->addSql('CREATE TABLE npd_settings (
          id INT NOT NULL, 
          created_at TIMESTAMP(0) WITHOUT TIME ZONE NOT NULL, 
          updated_at TIMESTAMP(0) WITHOUT TIME ZONE DEFAULT NULL, 
          scope VARCHAR(255) NOT NULL, 
          value TEXT NOT NULL, 
          name VARCHAR(255) NOT NULL, 
          owner INT DEFAULT NULL, 
          PRIMARY KEY(id)
        )');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->abortIf('postgresql' !== $this->connection->getDatabasePlatform()->getName(), 'Migration can only be executed safely on \'postgresql\'.');

        $this->addSql('DROP SEQUENCE npd_settings_id_seq CASCADE');
        $this->addSql('DROP TABLE npd_settings');
    }
}
