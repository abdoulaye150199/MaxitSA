STRUCTURE DU PROJET MAXITSA
==========================

/app
----
Core/               # Contient les classes fondamentales du framework
  ├── Abstract/     # Classes abstraites (AbstractController, etc.)
  ├── Errors/       # Gestion des messages d'erreur
  ├── App.php       # Classe principale de l'application (Container IoC)
  ├── Database.php  # Gestion de la connexion base de données
  ├── FileUpload.php # Gestion des uploads de fichiers
  ├── Middleware.php # Gestion des middlewares
  ├── Router.php    # Routage des requêtes HTTP
  ├── Session.php   # Gestion des sessions
  └── Validator.php # Validation des données

config/            # Configuration de l'application
  ├── bootstrap.php # Initialisation de l'application
  └── helpers.php   # Fonctions utilitaires globales

/public            # Dossiers publics accessibles par le navigateur
  ├── index.php    # Point d'entrée de l'application
  ├── css/         # Fichiers CSS
  ├── js/          # Fichiers JavaScript
  └── images/      # Images et uploads

/routes            # Définition des routes
  └── route.web.php # Routes de l'application web

/src               # Code source de l'application
  ├── Controller/  # Contrôleurs de l'application
  ├── Entite/      # Entités/Modèles de données
  ├── Repository/  # Couche d'accès aux données
  └── Service/     # Logique métier

/templates         # Templates de vues
  ├── layouts/     # Layouts principaux
  └── views/       # Vues de l'application

/vendor           # Dépendances externes (Composer)

FICHIERS PRINCIPAUX
------------------
.env              # Variables d'environnement
composer.json     # Configuration des dépendances
MLD.txt          # Modèle Logique de Données

DESCRIPTION DES COMPOSANTS CLÉS
-----------------------------
1. Controllers/
   - SecurityController.php : Gestion authentification
   - ServiceCommercialController.php : Interface service commercial
   - UserController.php : Gestion des utilisateurs

2. Repositories/
   - UserRepository.php : Accès données utilisateurs
   - CompteRepository.php : Accès données comptes
   - TransactionRepository.php : Accès données transactions

3. Core/
   - App.php : Container IoC et point central
   - Router.php : Routage des requêtes
   - Validator.php : Validation des données
   - Session.php : Gestion des sessions
   - Database.php : Connexion base de données

4. Middleware/
   - AuthMiddleware.php : Vérification authentification
   - GuestMiddleware.php : Accès visiteurs
   - ServiceCommercialMiddleware.php : Accès service commercial

FONCTIONNALITÉS PRINCIPALES
-------------------------
1. Authentification utilisateurs
2. Gestion des comptes (principal/secondaire)
3. Transactions (dépôt/retrait/paiement)
4. Interface service commercial
5. Upload et validation documents
6. Gestion des sessions
7. Validation des données

CONFIGURATION
------------
1. Créer fichier .env à la racine
2. Configurer la base de données
3. Configurer les variables d'environnement
4. Lancer les migrations base de données
5. Démarrer le serveur PHP

SÉCURITÉ
--------
- Validation des entrées
- Protection CSRF
- Hachage des mots de passe
- Middleware d'authentification
- Sessions sécurisées
- Validation fichiers upload