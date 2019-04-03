<?php

declare(strict_types=1);

namespace Migrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20190317090554 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->abortIf('postgresql' !== $this->connection->getDatabasePlatform()->getName(), 'Migration can only be executed safely on \'postgresql\'.');

        $this->addSql('ALTER TABLE npd_module ALTER created TYPE TIMESTAMP(0) WITHOUT TIME ZONE');
        $this->addSql('ALTER TABLE npd_module ALTER created DROP DEFAULT');
        $this->addSql('ALTER TABLE npd_lesson ALTER created DROP DEFAULT');
        $this->addSql('ALTER TABLE course ALTER created TYPE TIMESTAMP(0) WITHOUT TIME ZONE');
        $this->addSql('ALTER TABLE course ALTER created DROP DEFAULT');
        $this->addSql('ALTER TABLE npd_user ALTER created TYPE TIMESTAMP(0) WITHOUT TIME ZONE');
        $this->addSql('ALTER TABLE npd_user ALTER created DROP DEFAULT');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->abortIf('postgresql' !== $this->connection->getDatabasePlatform()->getName(), 'Migration can only be executed safely on \'postgresql\'.');

        $this->addSql('ALTER TABLE npd_module ALTER created TYPE DATE');
        $this->addSql('ALTER TABLE npd_module ALTER created DROP DEFAULT');
        $this->addSql('ALTER TABLE course ALTER created TYPE DATE');
        $this->addSql('ALTER TABLE course ALTER created DROP DEFAULT');
        $this->addSql('ALTER TABLE npd_lesson ALTER created SET DEFAULT \'now()\'');
        $this->addSql('ALTER TABLE npd_user ALTER created TYPE DATE');
        $this->addSql('ALTER TABLE npd_user ALTER created DROP DEFAULT');
    }
}
