-- Active: 1741510497819@@127.0.0.1@3306@jobboard

-- Créer ma database
CREATE DATABASE IF NOT EXISTS jobboard;

-- Créer la table Students
CREATE TABLE Students
(
    id_student INTEGER PRIMARY KEY AUTO_INCREMENT,
    name VARCHAR(255) NOT NULL,
    firstname VARCHAR(255) NOT NULL,
    email VARCHAR(255) NOT NULL,
    password VARCHAR(255) NOT NULL,
    phone_number VARCHAR(255) NOT NULL,
    age INTEGER,
    city VARCHAR(255),
    address TEXT,
    postal_code VARCHAR(10),
    photo VARCHAR(255),
    description TEXT NOT NULL,
    created_at DATETIME DEFAULT CURRENT_TIMESTAMP,
    updated_at DATETIME DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    linkedin VARCHAR(255),
    github VARCHAR(255),
    cv VARCHAR(255)
);

-- Créer la table éducation
CREATE TABLE Educations
(
    id_education INTEGER PRIMARY KEY AUTO_INCREMENT,
    student_id INTEGER,
    school_name VARCHAR(255) NOT NULL,
    degree VARCHAR(255) NOT NULL,
    field_of_study VARCHAR(255) NOT NULL,
    start_date DATETIME,
    end_date DATETIME,
    FOREIGN KEY (student_id) REFERENCES Students(id_student) ON DELETE CASCADE
);

-- Créer la table Expériences
CREATE TABLE Experiences
(
    id_experience INTEGER PRIMARY KEY AUTO_INCREMENT,
    student_id INTEGER,
    company_name VARCHAR(255) NOT NULL,
    job_title VARCHAR(255) NOT NULL,
    description TEXT,
    start_date DATETIME,
    end_date DATETIME,
    FOREIGN KEY (student_id) REFERENCES Students(id_student) ON DELETE CASCADE
);

-- Créer la table Competencies
CREATE TABLE Competencies
(
    id_competency INTEGER PRIMARY KEY AUTO_INCREMENT,
    name VARCHAR(255) NOT NULL
);

-- Créer la table Student_competencies | table many to many
CREATE TABLE Student_competencies
(
    student_id INTEGER,
    competency_id INTEGER,
    PRIMARY KEY (student_id, competency_id),
    FOREIGN KEY (student_id) REFERENCES Students(id_student) ON DELETE CASCADE,
    FOREIGN KEY (competency_id) REFERENCES Competencies(id_competency) ON DELETE CASCADE
);

-- Créer la table Language
CREATE TABLE Languages
(
    id_language INTEGER PRIMARY KEY AUTO_INCREMENT,
    name VARCHAR(255) NOT NULL
);

-- Créer la table Student_languages | table many-to-many
CREATE TABLE Student_languages
(
    student_id INTEGER,
    language_id INTEGER,
    -- Niveau de compétence : "Débutant" - "Intermédiaire" - "Avancé" - "Bilingue"
    proficiency ENUM('Beginner', 'Intermediate', 'Advanced', 'Fluent') NOT NULL,
    PRIMARY KEY (student_id, language_id),
    FOREIGN KEY (student_id) REFERENCES Students(id_student) ON DELETE CASCADE,
    FOREIGN KEY (language_id) REFERENCES Languages(id_language) ON DELETE CASCADE
);

-- Créer la table de recherche Search_preferences
CREATE TABLE Search_preferences
(
    id_preferences INTEGER PRIMARY KEY AUTO_INCREMENT,
    student_id INTEGER,
    field VARCHAR(255) NOT NULL,
    -- Type de contrat : 'Stage' - 'Alternance' - 'CDI' - 'CDD'
    contract_type ENUM('Stage', 'Alternance', 'CDI', 'CDD'),
    min_salary INTEGER,
    preferred_city VARCHAR(255) NOT NULL,
    remote BOOLEAN,
    availability DATE,
    FOREIGN KEY (student_id) REFERENCES Students(id_student) ON DELETE CASCADE
);

-- Créer la table companies
CREATE TABLE Companies (
    id_company INTEGER PRIMARY KEY AUTO_INCREMENT,
    name VARCHAR(255) NOT NULL,
    email VARCHAR(255) NOT NULL UNIQUE,
    password VARCHAR(255) NOT NULL,
    phone_number VARCHAR(20) NOT NULL,
    website VARCHAR(255),
    linkedin VARCHAR(255),
    logo VARCHAR(255),
    industry VARCHAR(255),
    city VARCHAR(255),
    address TEXT,
    postal_code VARCHAR(10),
    description TEXT,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
);

-- Créer la table Job_offers
CREATE TABLE Job_offers (
    id_job INTEGER PRIMARY KEY AUTO_INCREMENT,
    state VARCHAR(50) NOT NULL,
    company_id INT NOT NULL,
    title VARCHAR(255) NOT NULL,
    description TEXT NOT NULL,
    contract_type ENUM('Stage', 'Alternance', 'CDI', 'CDD') NOT NULL,
    salary INTEGER,
    city VARCHAR(255),
    remote BOOLEAN,
    start_date DATE NOT NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (company_id) REFERENCES Companies(id_company) ON DELETE CASCADE
);

-- Créer la table Likes_student
CREATE TABLE Likes_student
(
    id_likes_student INTEGER PRIMARY KEY AUTO_INCREMENT,
    student_id INTEGER,
    company_id INTEGER,
    liked_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY(student_id) REFERENCES Students(id_student),
    FOREIGN KEY(company_id) REFERENCES Companies(id_company)
);

-- Créer la table Matches
CREATE TABLE Matches
(
    id_matches INTEGER PRIMARY KEY AUTO_INCREMENT,
    student_id INTEGER,
    company_id INTEGER,
    matched_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    is_valid BOOLEAN DEFAULT FALSE,
    is_contacted BOOLEAN DEFAULT FALSE,
    FOREIGN KEY(student_id) REFERENCES Students(id_student),
    FOREIGN KEY(company_id) REFERENCES Companies(id_company)
);

-- Créer la table Like_company
CREATE TABLE Likes_company
(
    id_like_company INTEGER PRIMARY KEY AUTO_INCREMENT,
    student_id INTEGER,
    company_id INTEGER,
    liked_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY(student_id) REFERENCES Students(id_student),
    FOREIGN KEY(company_id) REFERENCES Companies(id_company)
);


-- Insérer des Etudiants
INSERT INTO Students (name, firstname, email, password, phone_number, age, city, address, postal_code, photo, description, linkedin, github, cv, created_at, updated_at)
VALUES 
    ('Dupont', 'Alice', 'alice.dupont@email.com', 'password123', '0601020304', 22, 'Paris', '12 Rue des Champs', '75001', NULL, 'Étudiante en développement web.', 'https://linkedin.com/alice', 'https://github.com/alice', NULL, CURRENT_TIMESTAMP, CURRENT_TIMESTAMP),
    ('Martin', 'Lucas', 'lucas.martin@email.com', 'password123', '0612345678', 24, 'Lyon', '45 Rue Victor Hugo', '69002', NULL, 'Passionné par la cybersécurité.', 'https://linkedin.com/lucas', 'https://github.com/lucas', NULL, CURRENT_TIMESTAMP, CURRENT_TIMESTAMP),
    ('Bernard', 'Emma', 'emma.bernard@email.com', 'password123', '0623456789', 23, 'Marseille', '8 Boulevard de la République', '13001', NULL, 'Développeuse full-stack en devenir.', 'https://linkedin.com/emma', 'https://github.com/emma', NULL, CURRENT_TIMESTAMP, CURRENT_TIMESTAMP),
    ('Durand', 'Hugo', 'hugo.durand@email.com', 'password123', '0634567890', 21, 'Toulouse', '67 Rue du Capitole', '31000', NULL, 'Intéressé par l’intelligence artificielle.', 'https://linkedin.com/hugo', 'https://github.com/hugo', NULL, CURRENT_TIMESTAMP, CURRENT_TIMESTAMP),
    ('Morel', 'Sophie', 'sophie.morel@email.com', 'password123', '0645678901', 25, 'Bordeaux', '23 Rue Sainte-Catherine', '33000', NULL, 'Développeuse mobile.', 'https://linkedin.com/sophie', 'https://github.com/sophie', NULL, CURRENT_TIMESTAMP, CURRENT_TIMESTAMP);

-- Insérer des Compétences
INSERT INTO Competencies (name)
VALUES 
    ('JavaScript'),
    ('Python'),
    ('SQL'),
    ('Docker'),
    ('ReactJS'),
    ('Java'),
    ('Machine Learning'),
    ('Cybersécurité'),
    ('DevOps'),
    ('Swift');


-- Jointure compétences - étudiants
INSERT INTO Student_competencies (student_id, competency_id)
VALUES 
    (1, 1), (1, 5), (1, 3),
    (2, 2), (2, 8), (2, 4),
    (3, 1), (3, 6), (3, 3),
    (4, 7), (4, 2), (4, 4),
    (5, 9), (5, 10), (5, 3);

-- Insérer des Formations
INSERT INTO Educations (student_id, school_name, degree, field_of_study, start_date, end_date)
VALUES 
    (1, 'Université Paris-Saclay', 'Master', 'Développement Web', '2021-09-01', '2023-06-30'),
    (2, 'INSA Lyon', 'Ingénieur', 'Cybersécurité', '2019-09-01', '2024-06-30'),
    (3, 'Epitech', 'Bachelor', 'Développement Logiciel', '2020-09-01', '2023-06-30'),
    (4, 'Université Toulouse 3', 'Master', 'Intelligence Artificielle', '2021-09-01', '2023-06-30'),
    (5, 'Université de Bordeaux', 'Licence', 'Développement Mobile', '2019-09-01', '2022-06-30');


-- Insérer des Expériences
INSERT INTO Experiences (student_id, company_name, job_title, description, start_date, end_date)
VALUES 
    (1, 'Tech Solutions', 'Développeur Web', 'Stage en développement front-end.', '2022-06-01', '2022-12-01'),
    (2, 'CyberSecure', 'Analyste en Cybersécurité', 'Détection et prévention des cyberattaques.', '2021-03-01', '2021-09-01'),
    (3, 'Freelance', 'Développeur Full-Stack', 'Développement de sites web pour PME.', '2021-07-01', NULL),
    (4, 'AI Innovators', 'Ingénieur en Machine Learning', 'Développement d’algorithmes IA.', '2022-02-01', '2022-08-01'),
    (5, 'Start-up locale', 'Développeur Mobile', 'Création d’une application de messagerie.', '2021-04-01', '2021-10-01');


-- Insérer des Langues
INSERT INTO Languages (name)
VALUES 
    ('Français'),
    ('Anglais'),
    ('Espagnol'),
    ('Allemand'),
    ('Italien');


-- Jointure langues - étudiants
INSERT INTO Student_languages (student_id, language_id, proficiency)
VALUES 
    (1, 1, 'Fluent'), (1, 2, 'Intermediate'),
    (2, 1, 'Fluent'), (2, 3, 'Beginner'),
    (3, 1, 'Fluent'), (3, 2, 'Advanced'), (3, 4, 'Beginner'),
    (4, 1, 'Fluent'), (4, 5, 'Intermediate'),
    (5, 2, 'Fluent'), (5, 1, 'Advanced');


-- Insérer des Search_preferences
INSERT INTO Search_preferences (student_id, field, contract_type, min_salary, preferred_city, remote, availability)
VALUES 
    (1, 'Développement Web', 'Alternance', 32000, 'Paris', TRUE, '2024-09-01'),
    (2, 'Cybersécurité', 'CDI', 45000, 'Lyon', FALSE, '2024-06-01'),
    (3, 'Développement Logiciel', 'Stage', 0, 'Marseille', TRUE, '2024-05-01'),
    (4, 'Intelligence Artificielle', 'CDI', 50000, 'Toulouse', FALSE, '2024-07-01'),
    (5, 'Développement Mobile', 'Alternance', 30000, 'Bordeaux', TRUE, '2024-09-01');

-- Insérer des Entreprises
INSERT INTO Companies (name, email, password, phone_number, website, linkedin, logo, industry, city, address, postal_code, description, created_at, updated_at)
VALUES 
    ('Tech Solutions', 'contact@techsolutions.com', 'securepass', '0145678901', 'https://techsolutions.com', 'https://linkedin.com/techsolutions', NULL, 'Développement Web', 'Paris', '50 Avenue des Champs-Élysées', '75008', 'Entreprise spécialisée en développement web et cloud computing.', CURRENT_TIMESTAMP, CURRENT_TIMESTAMP),
    ('CyberSecure', 'contact@cybersecure.com', 'securepass', '0156789012', 'https://cybersecure.com', 'https://linkedin.com/cybersecure', NULL, 'Cybersécurité', 'Lyon', '10 Rue de la République', '69001', 'Entreprise experte en sécurité informatique et protection des données.', CURRENT_TIMESTAMP, CURRENT_TIMESTAMP),
    ('AI Innovators', 'contact@aiinnovators.com', 'securepass', '0167890123', 'https://aiinnovators.com', 'https://linkedin.com/aiinnovators', NULL, 'Intelligence Artificielle', 'Toulouse', '5 Boulevard Matabiau', '31000', 'Entreprise spécialisée dans l’intelligence artificielle et le machine learning.', CURRENT_TIMESTAMP, CURRENT_TIMESTAMP);

-- Insérer des likes des entreprises
INSERT INTO Likes_company (student_id, company_id, liked_at)
VALUES 
    (1, 1, CURRENT_TIMESTAMP),
    (2, 2, CURRENT_TIMESTAMP), 
    (3, 3, CURRENT_TIMESTAMP),
    (4, 1, CURRENT_TIMESTAMP), 
    (5, 3, CURRENT_TIMESTAMP);

-- Insérer des likes des étudiants
INSERT INTO Likes_student (student_id, company_id, liked_at)
VALUES 
    (1, 1, CURRENT_TIMESTAMP), 
    (1, 2, CURRENT_TIMESTAMP),
    (2, 2, CURRENT_TIMESTAMP), 
    (2, 3, CURRENT_TIMESTAMP),
    (3, 1, CURRENT_TIMESTAMP), 
    (3, 3, CURRENT_TIMESTAMP),
    (4, 3, CURRENT_TIMESTAMP), 
    (4, 2, CURRENT_TIMESTAMP),
    (5, 1, CURRENT_TIMESTAMP);

-- Insérer des jobs
INSERT INTO Job_offers (state, company_id, title, description, contract_type, salary, city, remote, start_date, created_at)
VALUES 
    ('published', 1, 'Développeur Front-End', 'Développement de sites en ReactJS.', 'Alternance', 35000, 'Paris', TRUE, '2024-09-01', CURRENT_TIMESTAMP),
    ('published', 2, 'Analyste Cybersécurité', 'Gestion et sécurisation des réseaux.', 'CDI', 48000, 'Lyon', FALSE, '2024-06-15', CURRENT_TIMESTAMP),
    ('published', 3, 'Ingénieur Machine Learning', 'Création d’algorithmes IA avancés.', 'CDI', 55000, 'Toulouse', FALSE, '2024-07-01', CURRENT_TIMESTAMP);


-- Insérer des Matches
INSERT INTO Matches (student_id, company_id, matched_at, is_valid, is_contacted)
VALUES 
    (1, 1, NOW(), TRUE, FALSE),
    (2, 2, NOW(), TRUE, FALSE),
    (3, 3, NOW(), TRUE, TRUE);


--------------------------------------------------
-- Requetes Etudiants
--------------------------------------------------
SELECT *
FROM competencies;

SELECT *
FROM students;

SELECT *
FROM languages;

SELECT *
FROM educations;

SELECT *
FROM experiences;

SELECT *
FROM search_preferences;

SELECT *
FROM job_offers;

-- Récupérer les Likes d'un étudiant pour gérer l'historique
SELECT c.name AS nom_entreprise, CONCAT(s.name, ' ', s.firstname)  AS nom_etudiant
FROM students AS s
JOIN likes_student AS ls
    ON ls.student_id = s.id_student
JOIN companies AS c 
    ON c.id_company = ls.company_id
WHERE s.id_student = 1;

-- Récupérer les Matches d'un étudiant pour gérer l'historique
SELECT m.matched_at, CONCAT(s.name, ' ', s.firstname) AS etudiant
FROM matches AS m
JOIN students AS s 
    ON s.id_student = m.student_id
WHERE m.is_valid = TRUE;

-- Récupérer les écoles (educations) de l'étudiant
SELECT s.name AS nom_etudiant, CONCAT(e.degree, ', ', e.school_name) AS formation
FROM students AS s
JOIN educations AS e
    ON e.student_id = s.id_student
WHERE s.id_student = 1;


-- Récupérer les compétences d'un étudiant
SELECT s.firstname AS étudiant, c.name AS compétences
FROM  competencies AS c
JOIN student_competencies AS sc
    ON sc.competency_id = c.id_competency
JOIN students AS s
    ON s.id_student = sc.student_id
WHERE s.id_student = 1;

-- Récupérer les expériences d'un étudiant
SELECT e.description, s.name, s.firstname
FROM students AS s
JOIN experiences AS e
    ON e.student_id = s.id_student
WHERE s.id_student = 1;

-- Récupérer les Préférences de recherche d'un étudiant
SELECT s.name, s.firstname, sp.field, sp.contract_type, sp.preferred_city, sp.remote
FROM students AS s
JOIN search_preferences AS sp 
    ON sp.student_id = s.id_student
WHERE s.id_student = 1;

-- Récupérer les langues parlées de recherche d'un étudiant
SELECT l.name, CONCAT(s.name, ' ', s.firstname) AS nom_etudiant
FROM languages AS l
JOIN student_languages AS sl
    ON sl.language_id = l.id_language
JOIN students AS s
    ON s.id_student = sl.student_id
WHERE s.id_student = 3;

-- Récupérer les étudiants likés par une entreprise mais non encore matchés
SELECT CONCAT(s.name, ' ', s.firstname) AS etudiant, c.name AS entreprise
FROM likes_company AS lc
JOIN students AS s 
    ON s.id_student = lc.student_id
JOIN companies AS c 
    ON c.id_company = lc.company_id
LEFT JOIN matches AS m 
    ON m.student_id = lc.student_id AND m.company_id = lc.company_id
WHERE m.id_matches IS NULL;


--------------------------------------------------
-- Requetes Entreprises
--------------------------------------------------
-- Récupérer les Likes d'une entreprise pour gérer l'historique
SELECT c.name, CONCAT(s.name, ' ', s.firstname)
FROM companies AS c
JOIN likes_company AS lc
    ON lc.company_id = c.id_company
JOIN students AS s
    ON s.id_student = lc.student_id;

-- Récupérer les Matches d'une entreprise pour gérer l'historique


-- Récupérer les offres créées par une entreprise
SELECT jb.title, jb.contract_type, c.name
FROM job_offers AS jb
JOIN companies AS c
    ON c.id_company = jb.company_id;

--  Récupère tous les cas où une entreprise a liké un étudiant ET que cet étudiant a liké cette entreprise
SELECT 
    s.id_student,
    CONCAT(s.name, ' ', s.firstname) AS etudiant,
    c.id_company,
    c.name AS entreprise
FROM likes_student AS ls
JOIN likes_company AS lc 
    ON ls.student_id = lc.student_id
    AND ls.company_id = lc.company_id
JOIN students AS s ON s.id_student = ls.student_id
JOIN companies AS c ON c.id_company = ls.company_id;

-- Afficher toutes les entreprises et le nombre d’étudiants qui les ont likées
SELECT c.name AS entreprise, COUNT(ls.student_id) AS nb_likes
FROM companies AS c
LEFT JOIN likes_student AS ls 
    ON ls.company_id = c.id_company
GROUP BY c.id_company;

