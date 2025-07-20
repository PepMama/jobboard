<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20250720095736 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql(<<<'SQL'
            ALTER TABLE Job_offers CHANGE contract_type contract_type ENUM('Stage', 'Alternance', 'CDI', 'CDD')
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE Matches ADD job_id INT DEFAULT NULL
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE Matches ADD CONSTRAINT FK_C99B2C26BE04EA9 FOREIGN KEY (job_id) REFERENCES Job_offers (id_job) ON DELETE CASCADE
        SQL);
        $this->addSql(<<<'SQL'
            CREATE INDEX IDX_C99B2C26BE04EA9 ON Matches (job_id)
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE Search_preferences CHANGE contract_type contract_type ENUM('Stage', 'Alternance', 'CDI', 'CDD')
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE Student_languages CHANGE proficiency proficiency ENUM('Beginner', 'Intermediate', 'Advanced', 'Fluent')
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
            ALTER TABLE Student_languages CHANGE proficiency proficiency VARCHAR(0) DEFAULT NULL
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE Matches DROP FOREIGN KEY FK_C99B2C26BE04EA9
        SQL);
        $this->addSql(<<<'SQL'
            DROP INDEX IDX_C99B2C26BE04EA9 ON Matches
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE Matches DROP job_id
        SQL);
    }
}
