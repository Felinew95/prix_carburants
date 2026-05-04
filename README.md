# OùFaireLePlein

Site web permettant de consulter les prix des carburants en France par région, département et ville.

## Auteurs

- **Alexandre BURIN** - Co-fondateur & Développeur
- **Tauseef AHMED** - Co-fondateur & Développeur


## Liens des sites

- [tauseefahmed.alwaysdata.net](https://tauseefahmed.alwaysdata.net/) — **Tauseef AHMED**
- [burin.alwaysdata.net/stats.php](https://burin.alwaysdata.net/stats.php) — **Alexandre BURIN**


## Structure du projet

### Pages principales

| Fichier | Description |
|---------|-------------|
| `index.php` | Page d'accueil - Recherche de stations par région/département/ville |
| `stats.php` | Page de statistiques et graphiques des prix des carburants |
| `contacts.php` | Page de contact avec formulaire et informations des auteurs |
| `plan.php` | Plan du site et navigation |
| `tech.php` | Page technique avec API Ghibli (films) et informations système |
| `config.php` | Configuration centrale du site (constantes, API, régions) |
| `save_theme.php` | Sauvegarde du thème choisi par l'utilisateur |

### Dossiers

#### `/classes/` - Classes PHP (modèle POO)
- `Auteur.php` - Classe représentant un auteur du site
- `Departement.php` - Classe représentant un département français
- `Film.php` - Classe représentant un film (API Ghibli)
- `ObjectFactory.php` - Factory pour créer les objets du site
- `Region.php` - Classe représentant une région française
- `Station.php` - Classe représentant une station-service
- `Ville.php` - Classe représentant une ville française
- `Visiteur.php` - Classe représentant les informations du visiteur (géolocalisation)

#### `/include/` - Fichiers d'inclusion
- `header.inc.php` - En-tête HTML commun
- `footer.inc.php` - Pied de page HTML commun
- `functions.inc.php` - Fonctions globales
- `helper.inc.php` - Fonctions d'aide (helpers)

##### `/include/functions/` - Fonctions spécialisées
- `functions-carburants.inc.php` - Fonctions liées aux carburants (API gouvernement)
- `functions-geo.inc.php` - Fonctions géographiques (régions, départements, villes)
- `functions-geoloc.inc.php` - Fonctions de géolocalisation des visiteurs
- `functions-ghibli.inc.php` - Fonctions d'intégration API Ghibli
- `functions-stats.inc.php` - Fonctions de calculs statistiques

#### `/style/` - Feuilles de style CSS
- `common.css` - Styles communs à toutes les pages
- `index.css` - Styles de la page d'accueil
- `stats.css` - Styles de la page statistiques
- `contacts.css` - Styles de la page contacts
- `plan.css` - Styles du plan du site
- `tech.css` - Styles de la page technique
- `theme.css` - Styles des thèmes (clair/sombre)

#### `/js/` - Scripts JavaScript
- `config.js` - Configuration et gestion des appels API
- `index.js` - Scripts de la page d'accueil
- `stats.js` - Scripts des graphiques statistiques
- `theme.js` - Gestion du thème (clair/sombre)
- `helper.js` - Fonctions utilitaires JavaScript

#### `/csv/` - Données CSV
- `departements-france.csv` - Liste des départements français
- `villes-france.csv` - Liste des villes françaises (avec codes postaux, coordonnées GPS)

#### `/images/` - Ressources graphiques
- `favicon-carburants.svg` - Icône du site
- `oufaireleplein.png` - Logo principal
- `carteFrance.png` - Carte de France

#### `/cache/` - Fichiers de cache (générés automatiquement)
- `filmsGhibli.json` - Cache des films API Ghibli
- `infosVisiteur.xml` - Cache des informations visiteurs
- `station*.json` - Cache des données des stations
- `nombreStations.txt` - Cache du nombre de stations
- `villesPopulaireAnnuel.csv` - Statistiques des villes les plus consultées
- `nombreVisitesTotalesTousUtilisateurs.txt` - Compteur de visites

## APIs utilisées

| API | Utilisation |
|-----|-------------|
| [data.economie.gouv.fr](https://data.economie.gouv.fr) | Prix des carburants en France (flux instantané) |
| [ghibliapi.vercel.app](https://ghibliapi.vercel.app) | Films du Studio Ghibli (page tech) |
| [ip-api.com](http://ip-api.com) | Géolocalisation des visiteurs (45 req/min) |

## Régions supportées

- Hauts-de-France
- Normandie
- Île-de-France
- Grand Est
- Bretagne
- Pays de la Loire
- Centre-Val de Loire
- Bourgogne-Franche-Comté
- Nouvelle-Aquitaine
- Auvergne-Rhône-Alpes
- Occitanie
- Provence-Alpes-Côte d'Azur
- Corse

## Carburants disponibles

- Gazole
- SP95
- SP98
- E10

## Fonctionnalités

- Recherche de stations-service par localisation (région → département → ville)
- Géolocalisation automatique du visiteur via IP
- Affichage des prix des carburants en temps réel
- Statistiques et graphiques des prix
- Thème clair/sombre persistant
- Cache des données pour optimiser les performances
- Formulaire de contact
- Informations sur les auteurs

## Prérequis

- PHP 7.4+ avec extensions : SimpleXML, cURL, JSON
- Hébergement compatible PHP (déployé sur [Alwaysdata](https://www.alwaysdata.com/))
- Droits d'écriture sur le dossier `/cache/`

## Installation

Le site est hébergé sur **Alwaysdata**. Pour un déploiement similaire :

1. Créer un compte sur [Alwaysdata](https://www.alwaysdata.com/)
2. Uploader les fichiers via FTP ou SSH dans le dossier `www/`
3. Créer le dossier `cache/` et vérifier les permissions en écriture
4. Configurer les constantes dans `config.php` si nécessaire

## Configuration

Les principales constantes sont définies dans `config.php` :
- Durées de cache (Ghibli, géolocalisation, stations)
- URLs des APIs
- Chemins des fichiers CSV et cache
- Codes régions et départements

## Notes

- Le site utilise un système de cache pour limiter les appels aux APIs
- La géolocalisation est limitée à 45 requêtes par minute (ip-api.com)
- Les données des carburants proviennent de l'API ouverte du gouvernement français
