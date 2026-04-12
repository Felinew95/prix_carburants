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
         * Nom du continent du visiteur.
         * @var string
         */
        private string $continent;

        /**
         * Code du continent (ex: EU, NA).
         * @var string
         */
        private string $codeContinent;

        /**
         * Numéro ASN (Autonomous System Number).
         * @var string
         */
        private string $asn;

        /**
         * Nom de l'organisation ASN (fournisseur réseau).
         * @var string
         */
        private string $nomAsn;

        /**
         * Domaine associé à l'ASN.
         * @var string
         */
        private string $domaineAsn;

        /**
         * Construit un objet Visiteur avec ses informations réseau et géographiques.
         *
         * @param string $ip Adresse IP du visiteur
         * @param string $pays Nom du pays
         * @param string $codePays Code ISO du pays
         * @param string $continent Nom du continent
         * @param string $codeContinent Code du continent
         * @param string $asn Numéro ASN
         * @param string $nomAsn Nom de l'organisation ASN
         * @param string $domaineAsn Domaine associé à l'ASN
         */
        public function __construct(string $ip, string $pays, string $codePays, string $continent, 
            string $codeContinent, string $asn, string $nomAsn, string $domaineAsn) {

            $this->ip = $ip;
            $this->pays = $pays;
            $this->codePays = $codePays;
            $this->continent = $continent;
            $this->codeContinent = $codeContinent;
            $this->asn = $asn;  
            $this->nomAsn = $nomAsn;
            $this->domaineAsn = $domaineAsn;
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
         * Retourne le continent du visiteur.
         *
         * @return string Nom du continent
         */
        public function getContinent(): string {
            return $this->continent;
        }

        /**
         * Retourne le code du continent.
         *
         * @return string Code continent
         */
        public function getCodeContinent(): string {
            return $this->codeContinent;
        }

        /**
         * Retourne le numéro ASN du réseau.
         *
         * @return string ASN
         */
        public function getAsn(): string {
            return $this->asn;
        }

        /**
         * Retourne le nom de l'organisation ASN.
         *
         * @return string Nom ASN
         */
        public function getNomAsn(): string {
            return $this->nomAsn;
        }

        /**
         * Retourne le domaine associé à l'ASN.
         *
         * @return string Domaine ASN
         */
        public function getDomaineAsn(): string {
            return $this->domaineAsn;
        }

    }