<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

final class Version20231014072010 extends AbstractMigration
{
    public function getDescription(): string
    {
        return 'Add project category';
    }

    public function up(Schema $schema): void
    {
        $this->addSql('ALTER TABLE project ADD category VARCHAR(255) NOT NULL AFTER number');
    }

    public function down(Schema $schema): void
    {
        $this->addSql('ALTER TABLE project DROP category');
    }
}
