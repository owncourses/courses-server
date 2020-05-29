<?php

declare(strict_types=1);

namespace Migrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

final class Version20200529034907 extends AbstractMigration
{
    public function getDescription(): string
    {
        return 'Allow removing already read notification';
    }

    public function up(Schema $schema): void
    {
        $this->abortIf('postgresql' !== $this->connection->getDatabasePlatform()->getName(), 'Migration can only be executed safely on \'postgresql\'.');

        $this->addSql('ALTER TABLE npd_user_read_notifications DROP CONSTRAINT FK_62224523A76ED395');
        $this->addSql('ALTER TABLE npd_user_read_notifications DROP CONSTRAINT FK_62224523EF1A9D84');
        $this->addSql('ALTER TABLE 
          npd_user_read_notifications 
        ADD 
          CONSTRAINT FK_62224523A76ED395 FOREIGN KEY (user_id) REFERENCES npd_user (uuid) ON DELETE CASCADE NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE 
          npd_user_read_notifications 
        ADD 
          CONSTRAINT FK_62224523EF1A9D84 FOREIGN KEY (notification_id) REFERENCES npd_notification (uuid) ON DELETE CASCADE NOT DEFERRABLE INITIALLY IMMEDIATE');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->abortIf('postgresql' !== $this->connection->getDatabasePlatform()->getName(), 'Migration can only be executed safely on \'postgresql\'.');

        $this->addSql('ALTER TABLE npd_user_read_notifications DROP CONSTRAINT fk_62224523a76ed395');
        $this->addSql('ALTER TABLE npd_user_read_notifications DROP CONSTRAINT fk_62224523ef1a9d84');
        $this->addSql('ALTER TABLE 
          npd_user_read_notifications 
        ADD 
          CONSTRAINT fk_62224523a76ed395 FOREIGN KEY (user_id) REFERENCES npd_user (uuid) NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE 
          npd_user_read_notifications 
        ADD 
          CONSTRAINT fk_62224523ef1a9d84 FOREIGN KEY (notification_id) REFERENCES npd_notification (uuid) NOT DEFERRABLE INITIALLY IMMEDIATE');
    }
}
