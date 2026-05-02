<?php
    declare(strict_types=1);

    /**
     * Module qui représente une ville française 
     * 
     * @author Alexandre 
     * @author Tauseef
     * 
     * @version 1.0.0
     */

    require_once(__DIR__.'/Departement.php');

    /**
     * Représente une ville avec son nom, son code INSEE, son département
     * et ses coordonnées géographiques.
     *
     * Cette classe sert à stocker les informations essentielles d'une commune.
     */
    class Ville {

        /**
         * Nom de la ville.
         */
        private string $nom;

        /**
         * Code INSEE de la commune.
         */
        private string $codeCommuneInsee;

        /**
         * Département auquel appartient la ville.
         */
        private Departement $departement;

        /**
         * Code postal de la ville.
         */
        private string $codePostal;

        /**
         * Latitude de la ville.
         */
        private string $latitude;

        /**
         * Longitude de la ville.
         */
        private string $longitude;

        /**
         * Initialise une nouvelle ville avec ses informations principales.
         *
         * @param string $nom Nom de la ville.
         * @param string $codeCommuneInsee Code INSEE de la commune.
         * @param Departement $departement Département auquel appartient la ville.
         * @param string $codePostal Code postal de la ville.
         * @param string $latitude Latitude de la ville.
         * @param string $longitude Longitude de la ville.
         */
        public function __construct(string $nom, string $codeCommuneInsee, Departement $departement, string $codePostal, 
            string $latitude, string $longitude) {

            $this->nom = $nom;
            $this->codeCommuneInsee = $codeCommuneInsee;
            $this->departement = $departement;
            $this->codePostal = $codePostal;
            $this->latitude = $latitude;
            $this->longitude = $longitude;
        }

         /**
         * Retourne le nom de la ville.
         *
         * @return string Nom de la ville.
         */
        public function getNom(): string {
            return $this->nom;
        }

        /**
         * Retourne le code INSEE de la commune.
         *
         * @return string Code INSEE de la commune.
         */
        public function getCodeCommuneInsee(): string {
            return $this->codeCommuneInsee;
        }

        /**
         * Retourne le département de la ville.
         *
         * @return Departement Département associé.
         */
        public function getDepartement(): Departement {
            return $this->departement;
        }

        /**
         * Retourne le code postal de la ville.
         *
         * @return string Code postal de la ville.
         */
        public function getCodePostal(): string {
            return $this->codePostal;
        }

        /**
         * Retourne la latitude de la ville.
         *
         * @return string Latitude de la ville.
         */
        public function getLatitude(): string {
            return $this->latitude;
        }

        /**
         * Retourne la longitude de la ville.
         *
         * @return string Longitude de la ville.
         */
        public function getLongitude(): string {
            return $this->longitude;
        }

    }
        