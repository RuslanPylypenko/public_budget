<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

final class Version20230621201937 extends AbstractMigration
{
    public function getDescription(): string
    {
        return 'Add city entity';
    }

    public function up(Schema $schema): void
    {
        $this->addSql('CREATE TABLE city (
            id INT UNSIGNED AUTO_INCREMENT NOT NULL, 
            name VARCHAR(255) NOT NULL, 
            tech_name VARCHAR(140) NOT NULL, 
            main_title VARCHAR(255) NOT NULL, 
            main_text TEXT NOT NULL, 
            lat DOUBLE PRECISION NOT NULL, 
            lon DOUBLE PRECISION NOT NULL, 
            update_date DATETIME NOT NULL, 
            create_date DATETIME NOT NULL, 
            PRIMARY KEY(id)
        ) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
    }

    public function down(Schema $schema): void
    {
        $this->addSql('DROP TABLE city');
    }
}
