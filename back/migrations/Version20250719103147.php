<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20250719103147 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql(<<<'SQL'
            ALTER TABLE job_offers CHANGE contract_type contract_type ENUM('Stage', 'Alternance', 'CDI', 'CDD')
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE search_preferences CHANGE contract_type contract_type ENUM('Stage', 'Alternance', 'CDI', 'CDD')
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE student_languages CHANGE proficiency proficiency ENUM('Beginner', 'Intermediate', 'Advanced', 'Fluent')
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE students ADD title VARCHAR(255) NOT NULL
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE users ADD reset_password VARCHAR(255) DEFAULT NULL, ADD reset_password_requested_at DATETIME DEFAULT NULL
        SQL);
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql(<<<'SQL'
            ALTER TABLE Job_offers CHANGE contract_type contract_type VARCHAR(0) DEFAULT NULL
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE Search_preferences CHANGE contract_type contract_type VARCHAR(0) DEFAULT NULL
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE Students DROP title
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE Student_languages CHANGE proficiency proficiency VARCHAR(0) DEFAULT NULL
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE Users DROP reset_password, DROP reset_password_requested_at
        SQL);
    }
}
