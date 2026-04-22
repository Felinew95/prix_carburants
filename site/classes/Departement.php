<?php
    declare(strict_types=1);

    /**
     * Module qui représente un département français 
     * 
     * @author Alexandre 
     * @author Tauseef
     * 
     * @version 1.0.0
     */

    require_once(__DIR__ . '/Region.php');

    /**
     * Représente un département français avec son nom, son code et sa région.
     *
     * Cette classe permet de stocker les informations essentielles d'un département
     * et de conserver le lien avec l'objet Region associé.
     */
    class Departement {

        /**
         * Nom complet du département.
         */
        private string $nom;

        /**
         * Code officiel du département.
         */
        private string $code;

        /**
         * Région associée au département.
         */
        private Region $region;

        /**
         * Initialise un nouveau département avec son nom, son code et sa région.
         *
         * @param string $nom Nom complet du département.
         * @param string $code Code officiel du département.
         * @param Region $region Objet représentant la région du département.
         */
        public function __construct(string $nom, string $code, Region $region) {
            $this->nom = $nom;
            $this->code = $code;
            $this->region = $region;
        }

        /**
         * Retourne le nom du département.
         *
         * @return string Nom complet du département.
         */
        public function getNom(): string {
            return $this->nom;
        }

        /**
         * Retourne le code du département.
         *
         * @return string Code officiel du département.
         */
        public function getCode(): string {
            return $this->code;
        }

        /**
         * Retourne la région associée au département.
         *
         * @return Region Région du département.
         */
        public function getRegion(): Region {
            return $this->region;
        }
        
    }