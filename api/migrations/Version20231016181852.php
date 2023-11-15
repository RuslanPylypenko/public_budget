<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

final class Version20231016181852 extends AbstractMigration
{
    public function getDescription(): string
    {
        return 'Add projectStatusUpdateLastRunDate';
    }

    public function up(Schema $schema): void
    {
        $this->addSql('ALTER TABLE session ADD project_status_update_last_run_date DATETIME DEFAULT NULL AFTER city_id');
    }

    public function down(Schema $schema): void
    {
        $this->addSql('ALTER TABLE session DROP project_status_update_last_run_date');
    }
}
