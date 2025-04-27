<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20250427092207 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql(<<<'SQL'
            ALTER TABLE Companies CHANGE website website VARCHAR(255) DEFAULT NULL, CHANGE linkedin linkedin VARCHAR(255) DEFAULT NULL, CHANGE logo logo VARCHAR(255) DEFAULT NULL, CHANGE industry industry VARCHAR(255) DEFAULT NULL, CHANGE city city VARCHAR(255) DEFAULT NULL, CHANGE postal_code postal_code VARCHAR(10) DEFAULT NULL
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE Educations CHANGE start_date start_date DATETIME DEFAULT NULL, CHANGE end_date end_date DATETIME DEFAULT NULL
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE Experiences CHANGE start_date start_date DATETIME DEFAULT NULL, CHANGE end_date end_date DATETIME DEFAULT NULL
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE Job_offers CHANGE contract_type contract_type ENUM('Stage', 'Alternance', 'CDI', 'CDD'), CHANGE city city VARCHAR(255) DEFAULT NULL
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE Search_preferences CHANGE contract_type contract_type ENUM('Stage', 'Alternance', 'CDI', 'CDD'), CHANGE availability availability DATE DEFAULT NULL
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE Student_languages CHANGE proficiency proficiency ENUM('Beginner', 'Intermediate', 'Advanced', 'Fluent')
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE Students CHANGE city city VARCHAR(255) DEFAULT NULL, CHANGE postal_code postal_code VARCHAR(10) DEFAULT NULL, CHANGE photo photo VARCHAR(255) DEFAULT NULL, CHANGE linkedin linkedin VARCHAR(255) DEFAULT NULL, CHANGE github github VARCHAR(255) DEFAULT NULL, CHANGE cv cv VARCHAR(255) DEFAULT NULL
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE Users DROP username
        SQL);
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql(<<<'SQL'
            ALTER TABLE Companies CHANGE website website VARCHAR(255) DEFAULT 'NULL', CHANGE linkedin linkedin VARCHAR(255) DEFAULT 'NULL', CHANGE logo logo VARCHAR(255) DEFAULT 'NULL', CHANGE industry industry VARCHAR(255) DEFAULT 'NULL', CHANGE city city VARCHAR(255) DEFAULT 'NULL', CHANGE postal_code postal_code VARCHAR(10) DEFAULT 'NULL'
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE Search_preferences CHANGE contract_type contract_type VARCHAR(0) DEFAULT 'NULL', CHANGE availability availability DATE DEFAULT 'NULL'
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE Users ADD username VARCHAR(255) NOT NULL
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE Student_languages CHANGE proficiency proficiency VARCHAR(0) DEFAULT 'NULL'
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE Students CHANGE city city VARCHAR(255) DEFAULT 'NULL', CHANGE postal_code postal_code VARCHAR(10) DEFAULT 'NULL', CHANGE photo photo VARCHAR(255) DEFAULT 'NULL', CHANGE linkedin linkedin VARCHAR(255) DEFAULT 'NULL', CHANGE github github VARCHAR(255) DEFAULT 'NULL', CHANGE cv cv VARCHAR(255) DEFAULT 'NULL'
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE Job_offers CHANGE contract_type contract_type VARCHAR(0) DEFAULT 'NULL', CHANGE city city VARCHAR(255) DEFAULT 'NULL'
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE Experiences CHANGE start_date start_date DATETIME DEFAULT 'NULL', CHANGE end_date end_date DATETIME DEFAULT 'NULL'
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE Educations CHANGE start_date start_date DATETIME DEFAULT 'NULL', CHANGE end_date end_date DATETIME DEFAULT 'NULL'
        SQL);
    }
}
