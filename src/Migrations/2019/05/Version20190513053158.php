<?php

declare(strict_types=1);

namespace Migrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

final class Version20190513053158 extends AbstractMigration
{
    public function getDescription(): string
    {
        return 'Add name to attachments, keep mimetype instead of extension';
    }

    public function up(Schema $schema): void
    {
        $this->abortIf('postgresql' !== $this->connection->getDatabasePlatform()->getName(), 'Migration can only be executed safely on \'postgresql\'.');

        $this->addSql('ALTER TABLE npd_attachment ADD name VARCHAR(255) NOT NULL');
        $this->addSql('ALTER TABLE npd_attachment ADD mime_type VARCHAR(100) NOT NULL');
        $this->addSql('ALTER TABLE npd_attachment DROP file_extension');
    }

    public function down(Schema $schema): void
    {
        $this->abortIf('postgresql' !== $this->connection->getDatabasePlatform()->getName(), 'Migration can only be executed safely on \'postgresql\'.');

        $this->addSql('CREATE SCHEMA public');
        $this->addSql('ALTER TABLE npd_attachment ADD file_extension VARCHAR(10) NOT NULL');
        $this->addSql('ALTER TABLE npd_attachment DROP name');
        $this->addSql('ALTER TABLE npd_attachment DROP mime_type');
    }
}
