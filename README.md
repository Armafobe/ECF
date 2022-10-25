# Guide Administrateur

- Se rendre sur https://haltere-ego.fly.dev/
- Identifiants de connexion : admin@haltere-ego.com / T2eOzXknazrwWitLH88O
- Ajout de partenaire ou structure sur les onglets de navigation correspondants. ATTENTION : une fois ajoutée, une nouvelle entité ne se supprime que via la base de données.
- Possibilité d'activer ou désactiver un partenaire ou une structure dans sa page d'informations puis en cliquant sur le bouton correspondant et en validant la fenêtre pop-up. ATTENTION : Désactiver un partenaire désactivera toutes ses structures !
- Possibilité de modifier les permissions d'un partenaire ou d'une structure dans sa page d'informations puis en cliquant sur les permissions souhaitées de sorte à ce qu'après validation, celles cochées seront les nouvelles permissions. Confirmer la pop-up pour valider le formulaire.

# Guide de déploiement sur Heroku plus migration sur fly.io

- Créer une nouvelle application en région Europe.
- Dans l'onglet "Deploy", choisir déploiement avec GitHub et autoriser les déploiements automatiques.
- Si il n'existe pas, créer un fichier Procfile à la racine du projet et écrire "web: vendor/bin/heroku-php-apache2 public/" puis sauvegarder.
- S'assurer que le package Apache est bien installé pour que le fichier public/.htaccess soit créé.
- Dans l'onglet Resources, télécharger JawsDB MySQL en choisissant l'option gratuite.
- Après quoi sur MySQL Workbench ou PhpMyAdmin, créer une nouvelle connexion en rentrant les identifiants de base de données présentes en cliquant sur JawsDB MySQL.
- Dans l'onglet Settings, rentrer les variables d'environnement nécessaires :
  - APP_DEBUG => 0
  - APP_ENV => PROD (si également prod dans le fichier .env)
  - APP_SECRET => (clé secrète de l'app présente dans le fichier .env)
  - DATABASE_URL => (url complet de la base de données présente dans le dashboard JawsDB MySQL)
  - MAIL_API_KEY => (clé mailjet si désiré pour éviter que la clé apparaisse dans le code)
  - MAIL_API_SECRET => (clé secrère mailjet, nécessaire avec MAIL_API_KEY)
