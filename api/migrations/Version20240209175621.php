<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

final class Version20240209175621 extends AbstractMigration
{
    public function getDescription(): string
    {
        return 'Session start end dates';
    }

    public function up(Schema $schema): void
    {
        $this->addSql(<<<SQL
ALTER TABLE session 
    ADD start_date DATETIME NOT NULL DEFAULT NOW() AFTER name,
    ADD end_date DATETIME NOT NULL DEFAULT NOW() AFTER start_date
SQL);
    }
}
