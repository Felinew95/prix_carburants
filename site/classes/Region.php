<?php
    declare(strict_types=1);

    /**
     * Module qui représente une région française 
     * 
     * @author Alexandre
     * @author Tauseef
     * 
     * @version 1.0.0
     */

    /**
     * Représente une région française avec son nom et son code.
     *
     * Cette classe permet de stocker les informations essentielles
     * d'une région, puis de les récupérer via des méthodes d'accès.
     */
    class Region {
        
        /**
         * Nom complet de la région.
         */
        private string $nom;

        /**
         * Code officiel de la région.
         */
        private string $code;

        /**
         * Initialise une nouvelle région avec son nom et son code.
         *
         * @param string $nom Nom complet de la région.
         * @param string $code Code officiel de la région.
         */
        public function __construct(string $nom, string $code) {
            $this->nom = $nom;
            $this->code = $code;
        }

        /**
         * Retourne le nom de la région.
         *
         * @return string Nom complet de la région.
         */
        public function getNom(): string {
            return $this->nom;
        }

        /**
         * Retourne le code de la région.
         *
         * @return string Code officiel de la région.
         */
        public function getCode(): string {
            return $this->code;
        }
        
    }