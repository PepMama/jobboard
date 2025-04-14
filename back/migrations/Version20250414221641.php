<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20250414221641 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql(<<<'SQL'
            CREATE TABLE Companies (id_company INT AUTO_INCREMENT NOT NULL, name VARCHAR(255) NOT NULL, phone_number VARCHAR(20) NOT NULL, website VARCHAR(255) DEFAULT NULL, linkedin VARCHAR(255) DEFAULT NULL, logo VARCHAR(255) DEFAULT NULL, industry VARCHAR(255) DEFAULT NULL, city VARCHAR(255) DEFAULT NULL, address LONGTEXT DEFAULT NULL, postal_code VARCHAR(10) DEFAULT NULL, description LONGTEXT DEFAULT NULL, created_at DATETIME NOT NULL, updated_at DATETIME NOT NULL, user_id INT NOT NULL, UNIQUE INDEX UNIQ_B52899A76ED395 (user_id), PRIMARY KEY(id_company)) DEFAULT CHARACTER SET utf8mb4
        SQL);
        $this->addSql(<<<'SQL'
            CREATE TABLE Competencies (id_competency INT AUTO_INCREMENT NOT NULL, name VARCHAR(255) NOT NULL, PRIMARY KEY(id_competency)) DEFAULT CHARACTER SET utf8mb4
        SQL);
        $this->addSql(<<<'SQL'
            CREATE TABLE Educations (id_education INT AUTO_INCREMENT NOT NULL, school_name VARCHAR(255) NOT NULL, degree VARCHAR(255) NOT NULL, field_of_study VARCHAR(255) NOT NULL, start_date DATETIME DEFAULT NULL, end_date DATETIME DEFAULT NULL, student_id INT DEFAULT NULL, INDEX IDX_3C55757DCB944F1A (student_id), PRIMARY KEY(id_education)) DEFAULT CHARACTER SET utf8mb4
        SQL);
        $this->addSql(<<<'SQL'
            CREATE TABLE Experiences (id_experience INT AUTO_INCREMENT NOT NULL, company_name VARCHAR(255) NOT NULL, job_title VARCHAR(255) NOT NULL, description LONGTEXT DEFAULT NULL, start_date DATETIME DEFAULT NULL, end_date DATETIME DEFAULT NULL, student_id INT DEFAULT NULL, INDEX IDX_49E81A7CB944F1A (student_id), PRIMARY KEY(id_experience)) DEFAULT CHARACTER SET utf8mb4
        SQL);
        $this->addSql(<<<'SQL'
            CREATE TABLE Job_offers (id_job INT AUTO_INCREMENT NOT NULL, state VARCHAR(50) NOT NULL, title VARCHAR(255) NOT NULL, description LONGTEXT NOT NULL, contract_type ENUM('Stage', 'Alternance', 'CDI', 'CDD'), salary INT DEFAULT NULL, city VARCHAR(255) DEFAULT NULL, remote TINYINT(1) NOT NULL, start_date DATE NOT NULL, created_at DATETIME NOT NULL, company_id INT DEFAULT NULL, INDEX IDX_C51F2A76979B1AD6 (company_id), PRIMARY KEY(id_job)) DEFAULT CHARACTER SET utf8mb4
        SQL);
        $this->addSql(<<<'SQL'
            CREATE TABLE Languages (id_language INT AUTO_INCREMENT NOT NULL, name VARCHAR(255) NOT NULL, PRIMARY KEY(id_language)) DEFAULT CHARACTER SET utf8mb4
        SQL);
        $this->addSql(<<<'SQL'
            CREATE TABLE Likes_offer (id_like_offer INT AUTO_INCREMENT NOT NULL, liked_at DATETIME NOT NULL, student_id INT DEFAULT NULL, job_id INT DEFAULT NULL, INDEX IDX_13B6A46FCB944F1A (student_id), INDEX IDX_13B6A46FBE04EA9 (job_id), PRIMARY KEY(id_like_offer)) DEFAULT CHARACTER SET utf8mb4
        SQL);
        $this->addSql(<<<'SQL'
            CREATE TABLE Likes_student (id_likes_student INT AUTO_INCREMENT NOT NULL, liked_at DATETIME NOT NULL, student_id INT DEFAULT NULL, company_id INT DEFAULT NULL, INDEX IDX_B6E48834CB944F1A (student_id), INDEX IDX_B6E48834979B1AD6 (company_id), PRIMARY KEY(id_likes_student)) DEFAULT CHARACTER SET utf8mb4
        SQL);
        $this->addSql(<<<'SQL'
            CREATE TABLE Matches (id_matches INT AUTO_INCREMENT NOT NULL, matched_at DATETIME NOT NULL, is_valid TINYINT(1) NOT NULL, is_contacted TINYINT(1) NOT NULL, student_id INT DEFAULT NULL, company_id INT DEFAULT NULL, INDEX IDX_C99B2C26CB944F1A (student_id), INDEX IDX_C99B2C26979B1AD6 (company_id), PRIMARY KEY(id_matches)) DEFAULT CHARACTER SET utf8mb4
        SQL);
        $this->addSql(<<<'SQL'
            CREATE TABLE Search_preferences (id_preferences INT AUTO_INCREMENT NOT NULL, field VARCHAR(255) NOT NULL, contract_type ENUM('Stage', 'Alternance', 'CDI', 'CDD'), min_salary INT DEFAULT NULL, preferred_city VARCHAR(255) NOT NULL, remote TINYINT(1) DEFAULT NULL, availability DATE DEFAULT NULL, student_id INT DEFAULT NULL, UNIQUE INDEX UNIQ_CF1E835BCB944F1A (student_id), PRIMARY KEY(id_preferences)) DEFAULT CHARACTER SET utf8mb4
        SQL);
        $this->addSql(<<<'SQL'
            CREATE TABLE Student_languages (proficiency ENUM('Beginner', 'Intermediate', 'Advanced', 'Fluent'), student_id INT NOT NULL, language_id INT NOT NULL, INDEX IDX_E91CA003CB944F1A (student_id), INDEX IDX_E91CA00382F1BAF4 (language_id), PRIMARY KEY(student_id, language_id)) DEFAULT CHARACTER SET utf8mb4
        SQL);
        $this->addSql(<<<'SQL'
            CREATE TABLE Students (id_student INT AUTO_INCREMENT NOT NULL, name VARCHAR(255) NOT NULL, firstname VARCHAR(255) NOT NULL, phone_number VARCHAR(255) NOT NULL, age INT DEFAULT NULL, city VARCHAR(255) DEFAULT NULL, address LONGTEXT DEFAULT NULL, postal_code VARCHAR(10) DEFAULT NULL, photo VARCHAR(255) DEFAULT NULL, description LONGTEXT NOT NULL, created_at DATETIME NOT NULL, updated_at DATETIME NOT NULL, linkedin VARCHAR(255) DEFAULT NULL, github VARCHAR(255) DEFAULT NULL, cv VARCHAR(255) DEFAULT NULL, user_id INT NOT NULL, UNIQUE INDEX UNIQ_5D1FEFE4A76ED395 (user_id), PRIMARY KEY(id_student)) DEFAULT CHARACTER SET utf8mb4
        SQL);
        $this->addSql(<<<'SQL'
            CREATE TABLE Student_competencies (student_id INT NOT NULL, competency_id INT NOT NULL, INDEX IDX_F1A9ADC4CB944F1A (student_id), INDEX IDX_F1A9ADC4FB9F58C (competency_id), PRIMARY KEY(student_id, competency_id)) DEFAULT CHARACTER SET utf8mb4
        SQL);
        $this->addSql(<<<'SQL'
            CREATE TABLE Users (id INT AUTO_INCREMENT NOT NULL, username VARCHAR(255) NOT NULL, email VARCHAR(255) NOT NULL, password VARCHAR(255) NOT NULL, role VARCHAR(255) NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE Companies ADD CONSTRAINT FK_B52899A76ED395 FOREIGN KEY (user_id) REFERENCES Users (id) ON DELETE CASCADE
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE Educations ADD CONSTRAINT FK_3C55757DCB944F1A FOREIGN KEY (student_id) REFERENCES Students (id_student) ON DELETE CASCADE
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE Experiences ADD CONSTRAINT FK_49E81A7CB944F1A FOREIGN KEY (student_id) REFERENCES Students (id_student) ON DELETE CASCADE
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE Job_offers ADD CONSTRAINT FK_C51F2A76979B1AD6 FOREIGN KEY (company_id) REFERENCES Companies (id_company) ON DELETE CASCADE
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE Likes_offer ADD CONSTRAINT FK_13B6A46FCB944F1A FOREIGN KEY (student_id) REFERENCES Students (id_student) ON DELETE CASCADE
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE Likes_offer ADD CONSTRAINT FK_13B6A46FBE04EA9 FOREIGN KEY (job_id) REFERENCES Job_offers (id_job) ON DELETE CASCADE
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE Likes_student ADD CONSTRAINT FK_B6E48834CB944F1A FOREIGN KEY (student_id) REFERENCES Students (id_student) ON DELETE CASCADE
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE Likes_student ADD CONSTRAINT FK_B6E48834979B1AD6 FOREIGN KEY (company_id) REFERENCES Companies (id_company) ON DELETE CASCADE
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE Matches ADD CONSTRAINT FK_C99B2C26CB944F1A FOREIGN KEY (student_id) REFERENCES Students (id_student) ON DELETE CASCADE
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE Matches ADD CONSTRAINT FK_C99B2C26979B1AD6 FOREIGN KEY (company_id) REFERENCES Companies (id_company) ON DELETE CASCADE
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE Search_preferences ADD CONSTRAINT FK_CF1E835BCB944F1A FOREIGN KEY (student_id) REFERENCES Students (id_student) ON DELETE CASCADE
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE Student_languages ADD CONSTRAINT FK_E91CA003CB944F1A FOREIGN KEY (student_id) REFERENCES Students (id_student) ON DELETE CASCADE
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE Student_languages ADD CONSTRAINT FK_E91CA00382F1BAF4 FOREIGN KEY (language_id) REFERENCES Languages (id_language) ON DELETE CASCADE
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE Students ADD CONSTRAINT FK_5D1FEFE4A76ED395 FOREIGN KEY (user_id) REFERENCES Users (id) ON DELETE CASCADE
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE Student_competencies ADD CONSTRAINT FK_F1A9ADC4CB944F1A FOREIGN KEY (student_id) REFERENCES Students (id_student) ON DELETE CASCADE
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE Student_competencies ADD CONSTRAINT FK_F1A9ADC4FB9F58C FOREIGN KEY (competency_id) REFERENCES Competencies (id_competency) ON DELETE CASCADE
        SQL);
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql(<<<'SQL'
            ALTER TABLE Companies DROP FOREIGN KEY FK_B52899A76ED395
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE Educations DROP FOREIGN KEY FK_3C55757DCB944F1A
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE Experiences DROP FOREIGN KEY FK_49E81A7CB944F1A
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE Job_offers DROP FOREIGN KEY FK_C51F2A76979B1AD6
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
            ALTER TABLE Matches DROP FOREIGN KEY FK_C99B2C26CB944F1A
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE Matches DROP FOREIGN KEY FK_C99B2C26979B1AD6
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE Search_preferences DROP FOREIGN KEY FK_CF1E835BCB944F1A
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE Student_languages DROP FOREIGN KEY FK_E91CA003CB944F1A
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE Student_languages DROP FOREIGN KEY FK_E91CA00382F1BAF4
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE Students DROP FOREIGN KEY FK_5D1FEFE4A76ED395
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE Student_competencies DROP FOREIGN KEY FK_F1A9ADC4CB944F1A
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE Student_competencies DROP FOREIGN KEY FK_F1A9ADC4FB9F58C
        SQL);
        $this->addSql(<<<'SQL'
            DROP TABLE Companies
        SQL);
        $this->addSql(<<<'SQL'
            DROP TABLE Competencies
        SQL);
        $this->addSql(<<<'SQL'
            DROP TABLE Educations
        SQL);
        $this->addSql(<<<'SQL'
            DROP TABLE Experiences
        SQL);
        $this->addSql(<<<'SQL'
            DROP TABLE Job_offers
        SQL);
        $this->addSql(<<<'SQL'
            DROP TABLE Languages
        SQL);
        $this->addSql(<<<'SQL'
            DROP TABLE Likes_offer
        SQL);
        $this->addSql(<<<'SQL'
            DROP TABLE Likes_student
        SQL);
        $this->addSql(<<<'SQL'
            DROP TABLE Matches
        SQL);
        $this->addSql(<<<'SQL'
            DROP TABLE Search_preferences
        SQL);
        $this->addSql(<<<'SQL'
            DROP TABLE Student_languages
        SQL);
        $this->addSql(<<<'SQL'
            DROP TABLE Students
        SQL);
        $this->addSql(<<<'SQL'
            DROP TABLE Student_competencies
        SQL);
        $this->addSql(<<<'SQL'
            DROP TABLE Users
        SQL);
    }
}
