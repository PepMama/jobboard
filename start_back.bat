@echo off
echo Démarrage du backend Symfony...

REM Se déplacer dans le dossier backend
cd back

REM Démarrer Docker (si nécessaire)
echo Vérification de Docker...
docker-compose up -d

REM Installer les dépendances Symfony si elles ne sont pas déjà installées
if not exist "vendor" (
    echo Installation des dépendances Symfony...
    composer install
)

REM Appliquer les migrations
echo Mise à jour de la base de données...
php bin/console doctrine:migrations:migrate --no-interaction

REM Lancer le serveur Symfony
echo Démarrage du serveur Symfony...
symfony server:start

echo Backend démarré avec succès !
pause
