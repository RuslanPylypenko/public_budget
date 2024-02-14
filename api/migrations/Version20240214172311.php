<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

final class Version20240214172311 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        $this->addSql('ALTER TABLE session CHANGE start_date start_date DATETIME NOT NULL, CHANGE end_date end_date DATETIME NOT NULL');
        $this->addSql('ALTER TABLE user ADD ban_reason VARCHAR(255) DEFAULT NULL AFTER status');
    }
}
