<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20230717192454 extends AbstractMigration
{
    public function getDescription(): string
    {
        return 'Add session and project';
    }

    public function up(Schema $schema): void
    {

        $this->addSql('CREATE TABLE project (
            id INT UNSIGNED AUTO_INCREMENT NOT NULL,
            author_id INT UNSIGNED DEFAULT NULL, 
            session_id INT UNSIGNED DEFAULT NULL, 
            number INT UNSIGNED NOT NULL, 
            status VARCHAR(32) NOT NULL, 
            budget DOUBLE PRECISION NOT NULL, 
            name VARCHAR(255) NOT NULL, 
            short TEXT NOT NULL, 
            description LONGTEXT NOT NULL, 
            update_date DATETIME NOT NULL, 
            create_date DATETIME NOT NULL, 
            INDEX IDX_2FB3D0EEF675F31B (author_id),
            INDEX IDX_2FB3D0EE613FECDF (session_id), 
            PRIMARY KEY(id)
        ) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE session (
            id INT UNSIGNED AUTO_INCREMENT NOT NULL, 
            city_id INT UNSIGNED NOT NULL, 
            name VARCHAR(255) NOT NULL, 
            update_date DATETIME NOT NULL, 
            create_date DATETIME NOT NULL, 
            INDEX IDX_D044D5D48BAC62AF (city_id), 
            PRIMARY KEY(id)
        ) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE project_address (
            id INT UNSIGNED AUTO_INCREMENT NOT NULL, 
            project_id INT UNSIGNED DEFAULT NULL, 
            region VARCHAR(120) NOT NULL, 
            city VARCHAR(120) NOT NULL, 
            street VARCHAR(120) NOT NULL, 
            lat DOUBLE PRECISION NOT NULL, 
            lon DOUBLE PRECISION NOT NULL, 
            UNIQUE INDEX UNIQ_9B5063E4166D1F9C (project_id), 
            PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('ALTER TABLE project ADD CONSTRAINT FK_2FB3D0EEF675F31B FOREIGN KEY (author_id) REFERENCES user (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE project ADD CONSTRAINT FK_2FB3D0EE613FECDF FOREIGN KEY (session_id) REFERENCES session (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE session ADD CONSTRAINT FK_D044D5D48BAC62AF FOREIGN KEY (city_id) REFERENCES city (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE project_address ADD CONSTRAINT FK_9B5063E4166D1F9C FOREIGN KEY (project_id) REFERENCES project (id) ON DELETE CASCADE');
    }

    public function down(Schema $schema): void
    {
        $this->addSql('ALTER TABLE project DROP FOREIGN KEY FK_2FB3D0EEF675F31B');
        $this->addSql('ALTER TABLE project DROP FOREIGN KEY FK_2FB3D0EE613FECDF');
        $this->addSql('ALTER TABLE session DROP FOREIGN KEY FK_D044D5D48BAC62AF');
        $this->addSql('ALTER TABLE project_address DROP FOREIGN KEY FK_9B5063E4166D1F9C');
        $this->addSql('DROP TABLE project');
        $this->addSql('DROP TABLE session');
        $this->addSql('DROP TABLE project_address');
    }
}
