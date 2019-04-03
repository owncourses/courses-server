<?php

declare(strict_types=1);

namespace Migrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20190223095430 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->abortIf('postgresql' !== $this->connection->getDatabasePlatform()->getName(), 'Migration can only be executed safely on \'postgresql\'.');

        $this->addSql('CREATE TABLE npd_user (
          uuid UUID NOT NULL, 
          email VARCHAR(180) NOT NULL, 
          roles JSON NOT NULL, 
          password VARCHAR(255) NOT NULL, 
          PRIMARY KEY(uuid)
        )');
        $this->addSql('CREATE UNIQUE INDEX UNIQ_C9DAA1C8E7927C74 ON npd_user (email)');
        $this->addSql('COMMENT ON COLUMN npd_user.uuid IS \'(DC2Type:uuid)\'');
        $this->addSql('DROP TABLE "user"');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->abortIf('postgresql' !== $this->connection->getDatabasePlatform()->getName(), 'Migration can only be executed safely on \'postgresql\'.');

        $this->addSql('CREATE TABLE "user" (
          uuid UUID NOT NULL, 
          email VARCHAR(180) NOT NULL, 
          roles JSON NOT NULL, 
          password VARCHAR(255) NOT NULL, 
          PRIMARY KEY(uuid)
        )');
        $this->addSql('CREATE UNIQUE INDEX uniq_8d93d649e7927c74 ON "user" (email)');
        $this->addSql('COMMENT ON COLUMN "user".uuid IS \'(DC2Type:uuid)\'');
        $this->addSql('DROP TABLE npd_user');
    }
}
