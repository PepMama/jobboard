@echo off
echo Démarrage du frontend Vue.js...

REM Se déplacer dans le dossier frontend
cd front

REM Installer les dépendances si nécessaires
if not exist "node_modules" (
    echo Installation des dépendances Vue.js...
    npm install
)

REM Lancer le serveur de développement Vue.js
echo Démarrage du serveur Vue.js...
npm run dev

echo Frontend démarré avec succès !
pause
