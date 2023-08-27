<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

final class Version20230827174221 extends AbstractMigration
{
    public function getDescription(): string
    {
        return 'Added stages';
    }

    public function up(Schema $schema): void
    {
        $this->addSql('CREATE TABLE session_stage (
            id INT UNSIGNED AUTO_INCREMENT NOT NULL, 
            session_id INT UNSIGNED NOT NULL, 
            name VARCHAR(255) NOT NULL, 
            is_enable TINYINT(1) NOT NULL, 
            start_date DATETIME DEFAULT NULL, 
            end_date DATETIME DEFAULT NULL, 
            INDEX IDX_19012BBF613FECDF (session_id), 
            PRIMARY KEY(id)
        ) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('ALTER TABLE session_stage ADD CONSTRAINT FK_19012BBF613FECDF FOREIGN KEY (session_id) REFERENCES session (id) ON DELETE CASCADE');
    }

    public function down(Schema $schema): void
    {
        $this->addSql('ALTER TABLE session_stage DROP FOREIGN KEY FK_19012BBF613FECDF');
        $this->addSql('DROP TABLE session_stage');
    }
}
