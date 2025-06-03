<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20250602154643 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql(<<<'SQL'
            CREATE TABLE booking (id INT AUTO_INCREMENT NOT NULL, user_id INT NOT NULL, schedule_id INT NOT NULL, INDEX IDX_E00CEDDEA76ED395 (user_id), INDEX IDX_E00CEDDEA40BC2D5 (schedule_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB
        SQL);
        $this->addSql(<<<'SQL'
            CREATE TABLE schedules (id INT AUTO_INCREMENT NOT NULL, trainer_id INT NOT NULL, datetime DATETIME NOT NULL, type ENUM('group', 'individual'), INDEX IDX_313BDC8EFB08EDF6 (trainer_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB
        SQL);
        $this->addSql(<<<'SQL'
            CREATE TABLE trainers (id INT AUTO_INCREMENT NOT NULL, photo VARCHAR(255) NOT NULL, last_name VARCHAR(128) NOT NULL, first_name VARCHAR(128) NOT NULL, surname VARCHAR(128) DEFAULT NULL, phone VARCHAR(24) NOT NULL, specialization VARCHAR(255) NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB
        SQL);
        $this->addSql(<<<'SQL'
            CREATE TABLE users (id INT AUTO_INCREMENT NOT NULL, last_name VARCHAR(128) NOT NULL, first_name VARCHAR(128) NOT NULL, surname VARCHAR(128) DEFAULT NULL, phone VARCHAR(24) NOT NULL, password VARCHAR(128) NOT NULL, role ENUM('admin', 'client'), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE booking ADD CONSTRAINT FK_E00CEDDEA76ED395 FOREIGN KEY (user_id) REFERENCES users (id)
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE booking ADD CONSTRAINT FK_E00CEDDEA40BC2D5 FOREIGN KEY (schedule_id) REFERENCES schedules (id)
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE schedules ADD CONSTRAINT FK_313BDC8EFB08EDF6 FOREIGN KEY (trainer_id) REFERENCES trainers (id)
        SQL);
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql(<<<'SQL'
            ALTER TABLE booking DROP FOREIGN KEY FK_E00CEDDEA76ED395
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE booking DROP FOREIGN KEY FK_E00CEDDEA40BC2D5
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE schedules DROP FOREIGN KEY FK_313BDC8EFB08EDF6
        SQL);
        $this->addSql(<<<'SQL'
            DROP TABLE booking
        SQL);
        $this->addSql(<<<'SQL'
            DROP TABLE schedules
        SQL);
        $this->addSql(<<<'SQL'
            DROP TABLE trainers
        SQL);
        $this->addSql(<<<'SQL'
            DROP TABLE users
        SQL);
    }
}
