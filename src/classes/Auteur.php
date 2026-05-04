<?php
    declare(strict_types=1);

    /**
     * Module qui représente un auteur 
     * 
     * @author Alexandre 
     * @author Tauseef
     * 
     * @version 1.0.0
     */

    /**
     * Classe qui représente un auteur.
     * 
     * Cette classe stocke les informations personnelles, professionnelles 
     * et les liens sociaux d'un membre de l'équipe, ainsi que des 
     * paramètres d'affichage pour le front-end.
     * 
     * @author Alexandre 
     * @author Tauseef
     * 
     * @version 1.0.0
     */
    class Auteur {

        /** 
         * Nom de famille de l'auteur
         */
        private string $nom;

        /** 
         * Prénom de l'auteur
         */
        private string $prenom;

        /**
         * Rôle ou fonction au sein du projet 
         */
        private string $role;

        /** 
         * Adresse e-mail de contact 
         */
        private string $email;
        
        /**
         * Numéro de téléphone 
         */
        private string $telephone;

        /**
         * Ville ou pays de résidence 
         */
        private string $localisation;

        /**
         * URL du profil LinkedIn 
         */
        private string $linkedin;

        /**
         * URL du profil GitHub 
         */
        private string $github;

        /**
         * Référence CSS utilisée pour définir la couleur de la carte (ex: 'color-1') 
         */
        private string $classeCouleur;

        /**
         * Constructeur de la classe Auteur.
         *
         * @param string $nom            Nom de l'auteur
         * @param string $prenom         Prénom de l'auteur
         * @param string $role           Poste occupé
         * @param string $email          E-mail professionnel
         * @param string $telephone      Ligne téléphonique
         * @param string $localisation   Lieu de résidence
         * @param string $linkedin       Lien vers LinkedIn
         * @param string $github         Lien vers GitHub
         * @param string $classeCouleur  Identifiant de couleur pour le CSS
         */
        public function __construct(string $nom, string $prenom, string $role, string $email, string $telephone, string $localisation, 
            string $linkedin, string $github, string $classeCouleur) {
            $this->nom = $nom;
            $this->prenom = $prenom;
            $this->role = $role;
            $this->email = $email;
            $this->telephone = $telephone;
            $this->localisation = $localisation;
            $this->linkedin = $linkedin;
            $this->github = $github;
            $this->classeCouleur = $classeCouleur;
        }

        /**
         * Retourne le nom de l'auteur.
         * @return string
         */
        public function getNom(): string {
            return $this->nom;
        }

        /**
         * Retourne le prénom de l'auteur.
         * @return string
         */
        public function getPrenom(): string {
            return $this->prenom;
        }

        /**
         * Retourne le rôle de l'auteur.
         * @return string
         */
        public function getRole(): string {
            return $this->role;
        }

        /**
         * Retourne l'email de l'auteur.
         * @return string
         */
        public function getEmail(): string {
            return $this->email;
        }

        /**
         * Retourne le téléphone de l'auteur.
         * @return string
         */
        public function getTelephone(): string {
            return $this->telephone;
        }

        /**
         * Retourne la localisation de l'auteur.
         * @return string
         */
        public function getLocalisation(): string {
            return $this->localisation;
        }

        /**
         * Retourne le lien LinkedIn.
         * @return string
         */
        public function getLinkedin(): string {
            return $this->linkedin;
        }

        /**
         * Retourne le lien GitHub.
         * @return string
         */
        public function getGithub(): string {
            return $this->github;
        }

        /**
         * Retourne la classe CSS associée à la couleur de l'auteur.
         * @return string
         */
        public function getClasseCouleur(): string {
            return $this->classeCouleur;
        }

        /**
         * Génère l'initiale du prénom pour l'affichage de l'avatar.
         * @return string L'initiale en majuscule.
         */
        public function getInitiale(): string {
            return strtoupper(substr($this->prenom, 0, 1));
        }
        
    }
