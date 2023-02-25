<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

final class Version20230225144606 extends AbstractMigration
{
    public function getDescription(): string
    {
        return 'Initial migration';
    }

    public function up(Schema $schema): void
    {
        $this->addSql(
            'CREATE TABLE game (
                    id INT AUTO_INCREMENT NOT NULL,
                    name VARCHAR(255) NOT NULL,
                    picture VARCHAR(300) NOT NULL,
                    igdb_id INT DEFAULT NULL,
                    PRIMARY KEY(id)
                  ) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB',
        );

        $this->addSql(
            'CREATE TABLE stream (
                    id INT AUTO_INCREMENT NOT NULL,
                    user_id_id INT NOT NULL,
                    type INT NOT NULL,
                    title VARCHAR(255) NOT NULL,
                    start_at DATETIME NOT NULL COMMENT \'(DC2Type:datetime_immutable)\',
                    INDEX IDX_F0E9BE1C9D86650F (user_id_id),
                    PRIMARY KEY(id)
                 ) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB',
        );

        $this->addSql(
            'CREATE TABLE stream_game (
                    stream_id INT NOT NULL,
                    game_id INT NOT NULL,
                    INDEX IDX_943C082FD0ED463E (stream_id),
                    INDEX IDX_943C082FE48FD905 (game_id),
                    PRIMARY KEY(stream_id, game_id)
                ) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB',
        );

        $this->addSql(
            'CREATE TABLE user (
                    id INT AUTO_INCREMENT NOT NULL,
                    login VARCHAR(255) NOT NULL,
                    display_name VARCHAR(255) NOT NULL,
                    description LONGTEXT DEFAULT NULL,
                    email VARCHAR(255) NOT NULL,
                    created_at DATETIME NOT NULL COMMENT \'(DC2Type:datetime_immutable)\',
                    view_count INT DEFAULT NULL,
                    profile_picture VARCHAR(300) DEFAULT NULL,
                    PRIMARY KEY(id)
                ) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB',
        );

        $this->addSql(
            'CREATE TABLE manager (
                    id INT AUTO_INCREMENT NOT NULL,
                    email VARCHAR(180) NOT NULL,
                    roles LONGTEXT NOT NULL COMMENT \'(DC2Type:json)\',
                    password VARCHAR(255) NOT NULL,
                    UNIQUE INDEX UNIQ_FA2425B9E7927C74 (email),
                    PRIMARY KEY(id)
                 ) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB',
        );

        $this->addSql(
            'ALTER TABLE stream ADD CONSTRAINT FK_F0E9BE1C9D86650F FOREIGN KEY (user_id_id) REFERENCES user (id)',
        );

        $this->addSql(
            'ALTER TABLE stream_game ADD CONSTRAINT FK_943C082FD0ED463E FOREIGN KEY (stream_id) REFERENCES stream (id) ON DELETE CASCADE',
        );

        $this->addSql(
            'ALTER TABLE stream_game ADD CONSTRAINT FK_943C082FE48FD905 FOREIGN KEY (game_id) REFERENCES game (id) ON DELETE CASCADE',
        );

        $this->addSql(
            'CREATE TABLE refresh_tokens (
                    id INT AUTO_INCREMENT NOT NULL,
                    refresh_token VARCHAR(128) NOT NULL,
                    username VARCHAR(255) NOT NULL,
                    valid DATETIME NOT NULL,
                    UNIQUE INDEX UNIQ_9BACE7E1C74F2195 (refresh_token),
                    PRIMARY KEY(id)
                ) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB',
        );
    }

    public function down(Schema $schema): void
    {
        $this->addSql('ALTER TABLE stream DROP FOREIGN KEY FK_F0E9BE1C9D86650F');
        $this->addSql('ALTER TABLE stream_game DROP FOREIGN KEY FK_943C082FD0ED463E');
        $this->addSql('ALTER TABLE stream_game DROP FOREIGN KEY FK_943C082FE48FD905');
        $this->addSql('DROP TABLE game');
        $this->addSql('DROP TABLE stream');
        $this->addSql('DROP TABLE stream_game');
        $this->addSql('DROP TABLE user');
        $this->addSql('DROP TABLE manager');
        $this->addSql('DROP TABLE refresh_tokens');
    }
}
