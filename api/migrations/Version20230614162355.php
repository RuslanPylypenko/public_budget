<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

final class Version20230614162355 extends AbstractMigration
{
    public function getDescription(): string
    {
        return 'Create user table';
    }

    public function up(Schema $schema): void
    {
        $this->addSql('CREATE TABLE user (
        id INT UNSIGNED AUTO_INCREMENT NOT NULL, 
        name VARCHAR(255) NOT NULL, 
        email VARCHAR(255) NOT NULL, 
        status VARCHAR(30) NOT NULL, 
        birthday DATETIME NOT NULL, 
        password_hash VARCHAR(100) NOT NULL, 
        update_date DATETIME NOT NULL, 
        create_date DATETIME NOT NULL, 
        confirm_token_token VARCHAR(255) DEFAULT NULL, 
        confirm_token_expired_at DATETIME DEFAULT NULL, 
        UNIQUE INDEX UNIQ_8D93D649E7927C74 (email), 
        PRIMARY KEY(id)
        ) 
      DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
    }

    public function down(Schema $schema): void
    {
        $this->addSql('DROP TABLE user');
    }
}
