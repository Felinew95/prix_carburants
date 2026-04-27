<?php 
    declare(strict_types=1);

    /**
     * Fabrique d'objets (Factory Pattern).
     * 
     * Cette classe permet de centraliser la création des différentes entités
     * du projet (Film, Visiteur, Région, Ville, Station, etc.).
     * 
     * Avantages :
     * - Simplifie l'instanciation des objets
     * - Centralise la logique de création
     * - Facilite la maintenance et l'évolution du code
     * 
     * @author Alexandre
     * @author Tauseef 
     * 
     * @version 1.0.0
     */

    require_once(__DIR__ . "/Auteur.php");
    require_once(__DIR__ . "/Film.php");
    require_once(__DIR__ . "/Visiteur.php");
    require_once(__DIR__ . "/Region.php");
    require_once(__DIR__ . "/Ville.php");
    require_once(__DIR__ . "/Departement.php");
    require_once(__DIR__ . "/Station.php");

    /**
     * Classe qui permet de créer des objets pour le site 
     * 
     * @author Alexandre 
     * @author Tauseef 
     * 
     * @version 1.0.0
     */
    class ObjectFactory {

        /**
         * Constructeur privé pour empêcher l'instanciation de la classe.
         */
        private function __construct() {}

        /**
         * Crée un objet Film.
         *
         * @param string $titre Titre du film
         * @param string $titreOriginal Titre original
         * @param string $titreOriginalRomanise Titre original romanisé
         * @param string $realisateur Nom du réalisateur
         * @param string $producteur Nom du producteur
         * @param string $dateSortie Date de sortie
         * @param int $duree Durée en minutes
         * @param string $description Description du film
         * @param string $image URL ou chemin de l'image
         * @param string $banniere URL ou chemin de la bannière
         * @return Film
         */
        public static function createFilm(string $titre, string $titreOriginal, string $titreOriginalRomanise, 
            string $realisateur, string $producteur, string $dateSortie, int $duree, string $description,
            string $image, string $banniere) {

            return new Film($titre, $titreOriginal, $titreOriginalRomanise, $realisateur, $producteur, $dateSortie, $duree, $description,
                $image, $banniere);
        }

        /**
         * Crée un objet Visiteur.
         *
         * @param string $ip Adresse IP
         * @param string $pays Nom du pays
         * @param string $codePays Code ISO du pays
         * @param string $region Région
         * @param string $codeRegion Code région
         * @param string $ville Ville
         * @param string $codePostal Code postal
         * @param string $latitude Latitude
         * @param string $longitude Longitude
         * @param string $as ASN
         * @param string $nomAsn Nom de l'ASN
         * @return Visiteur
         */
        public static function createVisiteur(string $ip, string $pays, string $codePays, string $region, string $codeRegion, 
            string $ville, string $codePostal, string $latitude, string $longitude, string $as, string $nomAsn) {

            return new Visiteur($ip, $pays, $codePays, $region, $codeRegion, $ville, $codePostal, $latitude, $longitude, $as, $nomAsn);
        }

        /**
         * Crée une Région.
         *
         * @param string $nom Nom de la région
         * @param string $code Code de la région
         * @return Region
         */
        public static function createRegion(string $nom, string $code) {
            return new Region($nom, $code);
        }

        /**
         * Crée un Département.
         *
         * @param string $nom Nom du département
         * @param string $code Code du département
         * @param Region $region Région associée
         * @return Departement
         */
        public static function createDepartement(string $nom, string $code, Region $region) {
            return new Departement($nom, $code, $region);
        }

        /**
         * Crée une Ville.
         *
         * @param string $nom Nom de la ville
         * @param string $codeCommuneInsee Code INSEE
         * @param Departement $departement Département associé
         * @param string $codePostal Code postal
         * @param string $latitude Latitude
         * @param string $longitude Longitude
         * @return Ville
         */
        public static function createVille(string $nom, string $codeCommuneInsee, Departement $departement, string $codePostal, 
            string $latitude, string $longitude) {

            return new Ville($nom, $codeCommuneInsee, $departement, $codePostal, $latitude, $longitude);
        }

        /**
         * Crée une Station de carburant.
         *
         * @param Ville $ville Ville associée
         * @param string $adresse Adresse de la station
         * @param float|null $prixE10 Prix du E10 (nullable)
         * @param float|null $prixSP95 Prix du SP95 (nullable)
         * @param float|null $prixSP98 Prix du SP98 (nullable)
         * @param float|null $prixGazole Prix du gazole (nullable)
         * @param string $maj Date de mise à jour
         * @return Station
         */
        public static function createStation(Ville $ville, string $adresse, float|null $prixE10, float|null $prixSP95, 
            float|null $prixSP98, float|null $prixGazole, string $maj) {

            return new Station($ville, $adresse, $prixE10, $prixSP95, $prixSP98, $prixGazole, $maj);
        }

        /**
         * Crée un Auteur.
         *
         * @param string $nom Nom
         * @param string $prenom Prénom
         * @param string $role Rôle
         * @param string $email Email
         * @param string $telephone Téléphone
         * @param string $localisation Localisation
         * @param string $linkedin Lien LinkedIn
         * @param string $github Lien GitHub
         * @param string $classeCouleur Classe CSS ou couleur associée
         * @return Auteur
         */
        public static function createAuteur(string $nom, string $prenom, string $role, string $email, string $telephone, string $localisation, 
            string $linkedin, string $github, string $classeCouleur) {
            
            return new Auteur($nom, $prenom, $role, $email, $telephone, $localisation, $linkedin, $github, $classeCouleur);
        }

    }
