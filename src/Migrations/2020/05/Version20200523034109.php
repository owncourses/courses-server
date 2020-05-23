<?php

declare(strict_types=1);

namespace Migrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

final class Version20200523034109 extends AbstractMigration
{
    public function getDescription(): string
    {
        return 'Add notifications table';
    }

    public function up(Schema $schema): void
    {
        $this->abortIf('postgresql' !== $this->connection->getDatabasePlatform()->getName(), 'Migration can only be executed safely on \'postgresql\'.');

        $this->addSql('CREATE TABLE npd_notification (
          uuid UUID NOT NULL, 
          title VARCHAR(255) NOT NULL, 
          text TEXT NOT NULL, 
          url VARCHAR(255) NOT NULL, 
          label VARCHAR(255) NOT NULL, 
          created TIMESTAMP DEFAULT CURRENT_TIMESTAMP NOT NULL, 
          updated TIMESTAMP(0) WITHOUT TIME ZONE DEFAULT NULL, 
          PRIMARY KEY(uuid)
        )');
        $this->addSql('COMMENT ON COLUMN npd_notification.uuid IS \'(DC2Type:uuid)\'');
        $this->addSql('CREATE TABLE npd_user_read_notifications (
          user_id UUID NOT NULL, 
          notification_id UUID NOT NULL, 
          PRIMARY KEY(user_id, notification_id)
        )');
        $this->addSql('CREATE INDEX IDX_62224523A76ED395 ON npd_user_read_notifications (user_id)');
        $this->addSql('CREATE INDEX IDX_62224523EF1A9D84 ON npd_user_read_notifications (notification_id)');
        $this->addSql('COMMENT ON COLUMN npd_user_read_notifications.user_id IS \'(DC2Type:uuid)\'');
        $this->addSql('COMMENT ON COLUMN npd_user_read_notifications.notification_id IS \'(DC2Type:uuid)\'');
        $this->addSql('ALTER TABLE 
          npd_user_read_notifications 
        ADD 
          CONSTRAINT FK_62224523A76ED395 FOREIGN KEY (user_id) REFERENCES npd_user (uuid) NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE 
          npd_user_read_notifications 
        ADD 
          CONSTRAINT FK_62224523EF1A9D84 FOREIGN KEY (notification_id) REFERENCES npd_notification (uuid) NOT DEFERRABLE INITIALLY IMMEDIATE');
    }

    public function down(Schema $schema): void
    {
        $this->abortIf('postgresql' !== $this->connection->getDatabasePlatform()->getName(), 'Migration can only be executed safely on \'postgresql\'.');

        $this->addSql('ALTER TABLE npd_user_read_notifications DROP CONSTRAINT FK_62224523EF1A9D84');
        $this->addSql('DROP TABLE npd_notification');
        $this->addSql('DROP TABLE npd_user_read_notifications');
    }
}
