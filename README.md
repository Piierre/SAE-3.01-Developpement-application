# SAE 3.01 - Application Météorologique

Développement d'une application web complète de gestion et visualisation de données météorologiques avec récupération via API.

## 📋 Table des matières

- [Description](#description)
- [Fonctionnalités](#fonctionnalités)
- [Technologies utilisées](#technologies-utilisées)
- [Architecture du projet](#architecture-du-projet)
- [Installation](#installation)
- [Structure des fichiers](#structure-des-fichiers)
- [Utilisation](#utilisation)

## 📝 Description

Cette application est une solution complète pour consulter, gérer et visualiser des données météorologiques. Elle permet aux utilisateurs de :
- S'authentifier et gérer leurs comptes
- Consulter des stations météorologiques et leurs données
- Créer et gérer une collection personnelle de météothèques
- Visualiser les données sur une carte thermique interactive
- Exporter des données en CSV
- Partager des retours et commentaires

## ✨ Fonctionnalités

### Authentification & Gestion des utilisateurs
- ✅ Inscription et connexion sécurisées
- ✅ Gestion des profils utilisateurs
- ✅ Système de sessions HTTP
- ✅ Panel d'administration pour gérer les utilisateurs

### Données Météorologiques
- ✅ Récupération de données via API externe
- ✅ Consultation des stations météorologiques
- ✅ Affichage en temps réel des données météo
- ✅ Recherche et filtrage avancés

### Météothèques (Collections personnalisées)
- ✅ Création de collections de stations
- ✅ Système de favoris
- ✅ Partage et consultation des météothèques
- ✅ Export de données en format CSV

### Visualisation
- ✅ Carte thermique interactive
- ✅ Clustering de données géographiques
- ✅ Représentation visuelle des données

### Retours utilisateurs
- ✅ Formulaire de feedback
- ✅ Gestion des commentaires et suggestions

## 🛠 Technologies utilisées

- **Backend** : PHP 7.4+
- **Frontend** : HTML5, CSS3, JavaScript
- **Base de données** : MySQL/MariaDB
- **Architecture** : MVC (Model-View-Controller)
- **Autoloader** : PSR-4
- **API** : Intégration API météorologique externe

## 🏗 Architecture du projet

L'application suit le pattern **MVC** avec une structure modulaire :

```
SAE-3.01-Developpement-application/
├── web/                          # Dossier public (point d'entrée)
│   ├── frontController.php        # Routeur principal
│   └── assets/                    # Ressources statiques
│       ├── css/                   # Feuilles de styles
│       ├── js/                    # Scripts JavaScript
│       └── img/                   # Images
│
├── src/                           # Code source principal
│   ├── config/
│   │   └── Conf.php              # Configuration de l'application
│   │
│   ├── Controller/               # Contrôleurs
│   │   ├── AuthController.php
│   │   ├── UserController.php
│   │   ├── CarteController.php
│   │   ├── StationController.php
│   │   ├── MeteothequeController.php
│   │   ├── SearchController.php
│   │   ├── FeedbackController.php
│   │   └── DashboardController.php
│   │
│   ├── Model/                    # Modèles de données
│   │   ├── UserModel.php
│   │   ├── FavorisModel.php
│   │   ├── MeteothequeModel.php
│   │   ├── FeedbackModel.php
│   │   └── HTTP/
│   │       ├── Session.php
│   │       └── Cookie.php
│   │
│   ├── View/                     # Vues (Templates PHP)
│   │   ├── auth/                 # Pages d'authentification
│   │   ├── home/                 # Accueil et recherche
│   │   ├── map/                  # Cartes et visualisations
│   │   ├── station/              # Données des stations
│   │   ├── meteotheque/          # Gestion des collections
│   │   ├── dashboard/            # Tableaux de bord
│   │   ├── feedback/             # Retours utilisateurs
│   │   ├── admin/                # Panneau d'administration
│   │   └── error/                # Pages d'erreur
│   │
│   └── Lib/                      # Bibliothèques et utilitaires
│       ├── auth.php              # Gestion de l'authentification
│       ├── MessageFlash.php      # Système de messages flash
│       └── Psr4AutoloaderClass.php  # Autoloader PSR-4
│
└── db/                           # Scripts de base de données
    ├── db_connection.php         # Connexion à la BDD
    ├── db_creation.php           # Création des tables
    ├── db_insert.php             # Insertion de données
    └── db_test_insert.php        # Tests d'insertion
```

## 🚀 Installation

### Prérequis
- PHP 7.4 ou supérieur
- MySQL/MariaDB 5.7+
- Serveur web (Apache, Nginx)
- Accès à une API météorologique

### Étapes

1. **Cloner le repository**
   ```bash
   git clone <url-du-repo>
   cd SAE-3.01-Developpement-application
   ```

2. **Configurer la base de données**
   ```bash
   # Exécuter les scripts de création
   php db/db_creation.php
   php db/db_insert.php
   ```

3. **Configurer l'application**
   - Modifiez `src/config/Conf.php` avec vos paramètres :
     - Identifiants de base de données
     - URL de l'API météorologique
     - Configuration serveur

4. **Déployer le projet**
   - Placez le dossier dans le répertoire web de votre serveur
   - Configurez le serveur web pour que `web/` soit le dossier racine
   - Assurez-vous que le fichier `web/frontController.php` est accessible

5. **Vérifier l'installation**
   - Accédez à `http://localhost/` ou votre domaine
   - Vérifiez que la base de données est accessible
   - Testez l'intégration API

## 📂 Structure des fichiers détaillée

### Base de données (`db/`)
- `db_connection.php` : Paramètres et connexion MySQL
- `db_creation.php` : Schéma et création des tables
- `db_insert.php` : Données d'initialisation
- `db_test_insert.php` : Tests de fonctionnalité

### Contrôleurs (`src/Controller/`)
Chaque contrôleur gère une fonctionnalité :
- **AuthController** : Authentification et enregistrement
- **UserController** : Gestion des utilisateurs
- **CarteController** : Cartes et visualisations
- **StationController** : Données des stations météo
- **MeteothequeController** : Collections personnalisées
- **SearchController** : Recherche et API
- **FeedbackController** : Gestion des retours
- **DashboardController** : Tableaux de bord

### Modèles (`src/Model/`)
Gestion des données et de la logique métier :
- **UserModel** : Opérations sur les utilisateurs
- **MeteothequeModel** : Gestion des collections
- **FavorisModel** : Système de favoris
- **FeedbackModel** : Stockage des retours

### Vues (`src/View/`)
Templates PHP pour les pages web, organisés par fonctionnalité.

## 💻 Utilisation

### Pages principales

| Page | URL | Description |
|------|-----|-------------|
| Accueil | `/` | Page d'accueil et recherche |
| Authentification | `/login`, `/register` | Connexion et inscription |
| Carte thermique | `/map` | Visualisation des données |
| Stations | `/station` | Liste et détails des stations |
| Météothèques | `/meteotheque` | Gestion des collections |
| Tableau de bord | `/dashboard` | Espace utilisateur |
| Admin | `/admin` | Gestion des utilisateurs (admin) |

### Points d'entrée API

- `search_api.php` : Recherche de stations
- `get_meteo_data.php` : Récupération des données météo
- `get_clustered_data.php` : Données en clusters

## 📄 Licence

Projet SAE de formation (BUT Informatique)

## 👥 Auteur

Développé dans le cadre du SAE 3.01 - Développement d'application