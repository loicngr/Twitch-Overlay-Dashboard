<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

final class Version20230304104907 extends AbstractMigration
{
    public function getDescription(): string
    {
        return 'Add ManagerSettingsFeature entity';
    }

    public function up(Schema $schema): void
    {
        $this->addSql(
            'CREATE TABLE manager_settings_feature (
                    manager_id INT NOT NULL,
                    twitch_oauth LONGTEXT DEFAULT \'[]\' NOT NULL COMMENT \'(DC2Type:json)\',
                    PRIMARY KEY(manager_id)
                ) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB',
        );
        $this->addSql(
            'ALTER TABLE manager_settings_feature ADD CONSTRAINT FK_3E2E37FA783E3463 FOREIGN KEY (manager_id) REFERENCES manager (id)',
        );
    }

    public function down(Schema $schema): void
    {
        $this->addSql('ALTER TABLE manager_settings_feature DROP FOREIGN KEY FK_3E2E37FA783E3463');
        $this->addSql('DROP TABLE manager_settings_feature');
    }
}
