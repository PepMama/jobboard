# Cahier des charges - Bonnes pratiques à suivre

## Conventions de nommage

- Pour les variables, utiliser camelCase
```bash
$userName = 'Pépin';
```

- Pour les fonctions et class, utiliser PascalCase
```bash
function getUser();
```

- Pour les constantes et les variables d'environnement
```bash
MSQL_ROOT = root;
```

- Pour les noms des class en CSS :  kebab-case
```bash
class="div-container";
```

## Gestion de git

- Nommer un commit : mettez vos initiales au début suivi d'un "-" 
```bash
git commit -m "PM - (fix ou feat; etc) Initialisation du projet"
```

- Types de Commits
fix : Corrige un bug dans le code .
```bash
"PM - 🐛 fix: correct alignment issue"
```
feat : Introduit une nouvelle fonctionnalité
```bash
"PM - ✨ feat: add user authentication endpoint"
```

docs : Ajouter de la documentation
```bash
"PM - 📝 docs: update REAME"
```

- Gestion des branches
_developpement : Branche principale de développement intégrant toutes les fonctionnalités validées, où le front et le back sont testés ensemble.

_branche_avec_nos_prenoms : Utilisées pour développer des fonctionnalités de façon isolée, front et back séparés

## Organisation du code Symfony
1 entité = 1 contrôleur, 1 service, 1 repository
Dossiers séparés : Controller/, Service/, Entity/, Repository/
