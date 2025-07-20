<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20250720100427 extends AbstractMigration
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
            ALTER TABLE Likes_student ADD job_id INT DEFAULT NULL
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE Likes_student ADD CONSTRAINT FK_B6E48834BE04EA9 FOREIGN KEY (job_id) REFERENCES Job_offers (id_job) ON DELETE CASCADE
        SQL);
        $this->addSql(<<<'SQL'
            CREATE INDEX IDX_B6E48834BE04EA9 ON Likes_student (job_id)
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
            ALTER TABLE Likes_student DROP FOREIGN KEY FK_B6E48834BE04EA9
        SQL);
        $this->addSql(<<<'SQL'
            DROP INDEX IDX_B6E48834BE04EA9 ON Likes_student
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE Likes_student DROP job_id
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE Student_languages CHANGE proficiency proficiency VARCHAR(0) DEFAULT NULL
        SQL);
    }
}
