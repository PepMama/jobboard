# Alternance Matching - Plateforme de mise en relation Étudiants/Entreprises

## Description

Alternance Matching est une plateforme permettant aux étudiants et entreprises de se connecter via un système de matching inspiré de Tinder. Les étudiants peuvent liker des entreprises et vice-versa. Si les deux parties matchent, un contact est établi.

## Fonctionnalités

- Inscription et connexion sécurisée (JWT)
- Profil étudiant : expériences, compétences, préférences de recherche
- Entreprises : offres d’emploi, recherche de candidats
- Algorithme de matching intelligent
- Interface web responsive (Vue.js)
- API RESTful avec Symfony

## Stack technique

- Technologie
Vue.js : front 
Symfony : Backend API REST
MariaDB : Base de données relationnelle
Docker : Conteneurisation et déploiement
GitHub : Gestion du code en équipe

## Organisation du projet

Nous utilisons Git Flow pour organiser le développement :

- Branches principales :

main → Version stable en production.

develop → Développement en cours.

## Installation et démarrage du projet

🔹 Backend (Symfony)

- Aller dans le dossier back et installer les dépendances :

```bash
cd back
composer install
```

- Configurer .env :

```bash
DATABASE_URL="mysql://user:password@mariadb:3306/alternance_db"
```

- Lancer la base de données (Docker) :

```bash
docker-compose up -d
```

- Appliquer les migrations :

```bash
php bin/console doctrine:migrations:migrate
```

- Lancer le serveur Symfony :

```bash
symfony server:start
```

🔹 Frontend (Vue.js)

- Aller dans le dossier front et installer les dépendances :

```bash
cd front
npm install
```

- Lancer le serveur Vue.js :

```bash
npm run dev
```

🔹 Base de données

Dans le fichier back/.env, modifier la variable d'environment avec vos informations :

```bash
DATABASE_URL="mysql://nom_utilisateur:mot_de_passe@127.0.0.1:3306/jobboard?serverVersion=version_maria_db&charset=utf8"
```

## Fichier requetes_sql.sql

Ce fichier permet d'ajouter toutes les requètes à tester.