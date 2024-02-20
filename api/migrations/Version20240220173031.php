<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

final class Version20240220173031 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        $this->addSql('ALTER TABLE `admin` RENAME INDEX uniq_880e0d76e7927c74 TO email');
        $this->addSql('ALTER TABLE user RENAME INDEX uniq_8d93d649e7927c74 TO email');
    }
}
