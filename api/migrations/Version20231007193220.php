<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

final class Version20231007193220 extends AbstractMigration
{
    public function getDescription(): string
    {
        return 'change project address table';
    }

    public function up(Schema $schema): void
    {
        $this->addSql(<<<SQL
ALTER TABLE project_address 
    ADD country VARCHAR(120) DEFAULT NULL AFTER id,
    ADD country_code VARCHAR(120) DEFAULT NULL AFTER country,
    ADD district VARCHAR(120) DEFAULT NULL AFTER country_code,
    ADD building VARCHAR(120) DEFAULT NULL AFTER district,
    ADD apartment VARCHAR(120) DEFAULT NULL AFTER building,
    ADD postcode VARCHAR(120) DEFAULT NULL AFTER apartment,
    CHANGE region region VARCHAR(120) DEFAULT NULL,
    CHANGE city city VARCHAR(120) DEFAULT NULL,
    CHANGE street street VARCHAR(120) DEFAULT NULL,
    CHANGE lat lat DOUBLE PRECISION DEFAULT NULL,
    CHANGE lon lon DOUBLE PRECISION DEFAULT NULL
SQL);
    }

    public function down(Schema $schema): void
    {
        $this->addSql('ALTER TABLE project_address DROP country, DROP country_code, DROP district, DROP building, DROP apartment, DROP postcode, CHANGE region region VARCHAR(120) NOT NULL, CHANGE city city VARCHAR(120) NOT NULL, CHANGE street street VARCHAR(120) NOT NULL, CHANGE lat lat DOUBLE PRECISION NOT NULL, CHANGE lon lon DOUBLE PRECISION NOT NULL');
    }
}
