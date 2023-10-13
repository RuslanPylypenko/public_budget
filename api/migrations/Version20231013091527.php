<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

final class Version20231013091527 extends AbstractMigration
{
    public function getDescription(): string
    {
        return 'Add images to project';
    }

    public function up(Schema $schema): void
    {
        $this->addSql('ALTER TABLE project ADD images JSON NOT NULL AFTER description');
    }
}
