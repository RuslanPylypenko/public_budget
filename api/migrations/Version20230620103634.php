<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

final class Version20230620103634 extends AbstractMigration
{
    public function getDescription(): string
    {
        return 'Add user fields';
    }

    public function up(Schema $schema): void
    {

        $this->addSql('ALTER TABLE user 
            ADD surname VARCHAR(255) NOT NULL AFTER hash_session, 
            ADD patronymic VARCHAR(255) NOT NULL AFTER surname, 
            ADD passport VARCHAR(255) DEFAULT NULL AFTER patronymic, 
            ADD phone VARCHAR(12) DEFAULT NULL AFTER passport
        ');
    }

    public function down(Schema $schema): void
    {
        $this->addSql('ALTER TABLE user DROP surname, DROP patronymic, DROP passport, DROP phone');
    }
}
