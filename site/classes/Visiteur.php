<?php

    /**
     * Module qui représente un visiteur en général 
     * 
     * @author Alexandre 
     * @version 1.0.0
     */

    /**
     * Classe représentant un visiteur du site.
     *
     * Cette classe permet de stocker les informations liées à l'adresse IP
     * d'un utilisateur ainsi que des données de géolocalisation approximatives
     * (pays, continent) et des informations réseau (ASN).
     *
     * Ces données sont généralement obtenues via une API externe.
     *
     * @author Alexandre
     * @version 1.0.0
     */
    class Visiteur {

        /**
         * Adresse IP du visiteur.
         * @var string
         */
        private string $ip;

        /**
         * Nom du pays du visiteur.
         * @var string
         */
        private string $pays;

        /**
         * Code ISO du pays (ex: FR, US).
         * @var string
         */
        private string $codePays;

        /**
         * Nom de la région 
         * @var string
         */
        private string $region;

        /**
         * Code de la région 
         * @var string
         */
        private string $codeRegion;

        /**
         * Ville du visiteur
         * @var string
         */
        private string $ville;

        /**
         * Code postal 
         * @var string
         */
        private string $codePostal;

        /**
         * La latitude 
         * @var string
         */
        private string $latitude;

        /**
         * La longitude 
         * @var string
         */
        private string $longitude;

        /**
         * L'AS (Autonomous System).
         * @var string
         */
        private string $as;

        /**
         * Nom de l'organisation ASN (fournisseur réseau).
         * @var string
         */
        private string $nomAsn;

        public function __construct(string $ip, string $pays, string $codePays, string $region, string $codeRegion, 
            string $ville, string $codePostal, string $latitude, string $longitude, string $as, string $nomAsn) {

            $this->ip = $ip;
            $this->pays = $pays;
            $this->codePays = $codePays;
            $this->region = $region;
            $this->codeRegion = $codeRegion;
            $this->ville = $ville;
            $this->codePostal = $codePostal;
            $this->latitude = $latitude;
            $this->longitude = $longitude;
            $this->as = $as;  
            $this->nomAsn = $nomAsn;
        }

        /**
         * Retourne l'adresse IP du visiteur.
         *
         * @return string Adresse IP
         */
        public function getIp(): string {
            return $this->ip;
        }

        /**
         * Retourne le pays du visiteur.
         *
         * @return string Nom du pays
         */
        public function getPays(): string {
            return $this->pays;
        }

        /**
         * Retourne le code ISO du pays.
         *
         * @return string Code pays
         */
        public function getCodePays(): string {
            return $this->codePays;
        }

        /**
         * Retourne la région du visiteur 
         * 
         * @return string La région
         */
        public function getRegion(): string {
            return $this->region;
        }

        /**
         * Retourne le code de la région.
         *
         * @return string Code de la région
         */
        public function getCodeRegion(): string {
            return $this->codeRegion;
        }

        /**
         * Retourne la ville du visiteur.
         *
         * @return string Ville
         */
        public function getVille(): string {
            return $this->ville;
        }

        /**
         * Retourne le code postal.
         *
         * @return string Code postal
         */
        public function getCodePostal(): string {
            return $this->codePostal;
        }

        /**
         * Retourne la latitude.
         *
         * @return string Latitude
         */
        public function getLatitude(): string {
            return $this->latitude;
        }   

        /**
         * Retourne la longitude.
         *
         * @return string Longitude
         */
        public function getLongitude(): string {
            return $this->longitude;
        }

        /**
         * Retourne le numéro ASN du réseau.
         *
         * @return string ASN
         */
        public function getAs(): string {
            return $this->as;
        }

        /**
         * Retourne le nom de l'organisation ASN.
         *
         * @return string Nom ASN
         */
        public function getNomAsn(): string {
            return $this->nomAsn;
        }

    }