<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

final class Version20231008165029 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        $this->addSql('CREATE TABLE project_vote (
    id INT UNSIGNED AUTO_INCREMENT NOT NULL,
    user_id INT UNSIGNED DEFAULT NULL,
    project_id INT UNSIGNED DEFAULT NULL,
    ip VARCHAR(15) DEFAULT NULL,
    user_agent VARCHAR(244) NOT NULL,
    browser VARCHAR(244) DEFAULT NULL,
    update_date DATETIME NOT NULL,
    create_date DATETIME NOT NULL,
    INDEX IDX_63814D7CA76ED395 (user_id),
    INDEX IDX_63814D7C166D1F9C (project_id),
    UNIQUE INDEX project_id_user_id (project_id, user_id),
    PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('ALTER TABLE project_vote ADD CONSTRAINT FK_63814D7CA76ED395 FOREIGN KEY (user_id) REFERENCES user (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE project_vote ADD CONSTRAINT FK_63814D7C166D1F9C FOREIGN KEY (project_id) REFERENCES project (id) ON DELETE CASCADE');
    }

    public function down(Schema $schema): void
    {
        $this->addSql('ALTER TABLE project_vote DROP FOREIGN KEY FK_63814D7CA76ED395');
        $this->addSql('ALTER TABLE project_vote DROP FOREIGN KEY FK_63814D7C166D1F9C');
        $this->addSql('DROP TABLE project_vote');
    }
}
