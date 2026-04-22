<?php
    declare(strict_types=1);

    /**
     * Module qui représente une station service 
     * 
     * @author Alexandre 
     * @author Tauseef
     * 
     * @version 1.0.0
     */

    require_once(__DIR__.'/Ville.php');

    /**
     * Représente une station-service avec sa localisation et ses prix carburants.
     *
     * Cette classe stocke les informations principales d'une station :
     * - sa ville,
     * - les prix des différents carburants.
     */
    class Station {
        
        /**
         * Nom de la ville où se trouve la station.
         */
        private Ville $ville;

        /**
         * Adresse de la station 
         */
        private string $adresse;

        /**
         * Prix du carburant E10.
         */
        private float|null $prixE10;

        /**
         * Prix du carburant SP95.
         */
        private float|null $prixSP95;       

        /**
         * Prix du carburant SP98.
         */
        private float|null $prixSP98;

        /**
         * Prix du carburant gazole.
         */
        private float|null $prixGazole;

        /**
         * Dernière mise à jour de la station 
         */
        private string $maj;

        /**
         * Initialise une nouvelle station avec sa localisation et ses prix carburants.
         *
         * @param Ville $ville Nom de la ville.
         * @param string $adresse Adresse de la station 
         * @param float|null $prixE10 Prix du carburant E10.
         * @param float|null $prixSP95 Prix du carburant SP95.
         * @param float|null $prixSP98 Prix du carburant SP98.
         * @param float|null $prixGazole Prix du carburant gazole.
         * @param string $maj Dernière mise à jour de la station 
         */
        public function __construct(Ville $ville, string $adresse, float|null $prixE10, float|null $prixSP95, 
            float|null $prixSP98, float|null $prixGazole, string $maj) {
            $this->ville = $ville;
            $this->adresse = $adresse;
            $this->prixE10 = $prixE10;
            $this->prixSP95 = $prixSP95;    
            $this->prixSP98 = $prixSP98;
            $this->prixGazole = $prixGazole;
            $this->maj = $maj;
        }

        /** 
         * Retourne le nom de la ville.
         *
         * @return string Nom de la ville.
         */
        public function getVille(): Ville {
            return $this->ville;
        }

        /**
         * Retourne l'adresse de la station 
         * @return string L'adresse 
         */
        public function getAdresse(): string {
            return $this->adresse;
        }

        /**
         * Retourne le prix du carburant E10.
         *
         * @return float Prix du carburant E10.
         */
        public function getPrixE10(): float|null {
            return $this->prixE10;
        }

        /**
         * Retourne le prix du carburant SP95.
         *
         * @return float Prix du carburant SP95.
         */
        public function getPrixSP95(): float|null {
            return $this->prixSP95;
        }

        /**
         * Retourne le prix du carburant SP98.
         *
         * @return float Prix du carburant SP98.
         */
        public function getPrixSP98(): float|null {
            return $this->prixSP98;
        }

        /**
         * Retourne le prix du carburant gazole.
         *
         * @return float Prix du carburant gazole.
         */
        public function getPrixGazole(): float|null {
            return $this->prixGazole;
        }

        /**
         * Retourne la dernière mise à jour de la station.
         *
         * @return string Dernière mise à jour de la station.
         */
        public function getMaj(): string {
            return $this->maj;
        }

    }
    