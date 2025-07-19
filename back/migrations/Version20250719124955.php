<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20250719124955 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql(<<<'SQL'
            ALTER TABLE companies ADD CONSTRAINT FK_B52899A76ED395 FOREIGN KEY (user_id) REFERENCES Users (id) ON DELETE CASCADE
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE educations ADD CONSTRAINT FK_3C55757DCB944F1A FOREIGN KEY (student_id) REFERENCES Students (id_student) ON DELETE CASCADE
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE experiences ADD CONSTRAINT FK_49E81A7CB944F1A FOREIGN KEY (student_id) REFERENCES Students (id_student) ON DELETE CASCADE
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE job_offers CHANGE contract_type contract_type ENUM('Stage', 'Alternance', 'CDI', 'CDD')
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE job_offers ADD CONSTRAINT FK_C51F2A76979B1AD6 FOREIGN KEY (company_id) REFERENCES Companies (id_company) ON DELETE CASCADE
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE likes_offer ADD CONSTRAINT FK_13B6A46FCB944F1A FOREIGN KEY (student_id) REFERENCES Students (id_student) ON DELETE CASCADE
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE likes_offer ADD CONSTRAINT FK_13B6A46FBE04EA9 FOREIGN KEY (job_id) REFERENCES Job_offers (id_job) ON DELETE CASCADE
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE likes_student ADD CONSTRAINT FK_B6E48834CB944F1A FOREIGN KEY (student_id) REFERENCES Students (id_student) ON DELETE CASCADE
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE likes_student ADD CONSTRAINT FK_B6E48834979B1AD6 FOREIGN KEY (company_id) REFERENCES Companies (id_company) ON DELETE CASCADE
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE matches ADD CONSTRAINT FK_C99B2C26CB944F1A FOREIGN KEY (student_id) REFERENCES Students (id_student) ON DELETE CASCADE
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE matches ADD CONSTRAINT FK_C99B2C26979B1AD6 FOREIGN KEY (company_id) REFERENCES Companies (id_company) ON DELETE CASCADE
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE search_preferences CHANGE contract_type contract_type ENUM('Stage', 'Alternance', 'CDI', 'CDD')
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE search_preferences ADD CONSTRAINT FK_CF1E835BCB944F1A FOREIGN KEY (student_id) REFERENCES Students (id_student) ON DELETE CASCADE
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE student_languages CHANGE proficiency proficiency ENUM('Beginner', 'Intermediate', 'Advanced', 'Fluent')
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE student_languages ADD CONSTRAINT FK_E91CA003CB944F1A FOREIGN KEY (student_id) REFERENCES Students (id_student) ON DELETE CASCADE
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE student_languages ADD CONSTRAINT FK_E91CA00382F1BAF4 FOREIGN KEY (language_id) REFERENCES Languages (id_language) ON DELETE CASCADE
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE students ADD CONSTRAINT FK_5D1FEFE4A76ED395 FOREIGN KEY (user_id) REFERENCES Users (id) ON DELETE CASCADE
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE student_competencies ADD CONSTRAINT FK_F1A9ADC4CB944F1A FOREIGN KEY (student_id) REFERENCES Students (id_student) ON DELETE CASCADE
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE student_competencies ADD CONSTRAINT FK_F1A9ADC4FB9F58C FOREIGN KEY (competency_id) REFERENCES Competencies (id_competency) ON DELETE CASCADE
        SQL);
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql(<<<'SQL'
            ALTER TABLE Companies DROP FOREIGN KEY FK_B52899A76ED395
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE Job_offers DROP FOREIGN KEY FK_C51F2A76979B1AD6
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE Job_offers CHANGE contract_type contract_type VARCHAR(0) DEFAULT NULL
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE Likes_offer DROP FOREIGN KEY FK_13B6A46FCB944F1A
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE Likes_offer DROP FOREIGN KEY FK_13B6A46FBE04EA9
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE Likes_student DROP FOREIGN KEY FK_B6E48834CB944F1A
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE Likes_student DROP FOREIGN KEY FK_B6E48834979B1AD6
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE Students DROP FOREIGN KEY FK_5D1FEFE4A76ED395
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE Educations DROP FOREIGN KEY FK_3C55757DCB944F1A
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE Matches DROP FOREIGN KEY FK_C99B2C26CB944F1A
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE Matches DROP FOREIGN KEY FK_C99B2C26979B1AD6
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE Experiences DROP FOREIGN KEY FK_49E81A7CB944F1A
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE Student_languages DROP FOREIGN KEY FK_E91CA003CB944F1A
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE Student_languages DROP FOREIGN KEY FK_E91CA00382F1BAF4
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE Student_languages CHANGE proficiency proficiency VARCHAR(0) DEFAULT NULL
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE Student_competencies DROP FOREIGN KEY FK_F1A9ADC4CB944F1A
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE Student_competencies DROP FOREIGN KEY FK_F1A9ADC4FB9F58C
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE Search_preferences DROP FOREIGN KEY FK_CF1E835BCB944F1A
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE Search_preferences CHANGE contract_type contract_type VARCHAR(0) DEFAULT NULL
        SQL);
    }
}
