<?php
    declare(strict_types=1);
    
    /**
     * Module qui représente un film en général 
     * 
     * @author Alexandre 
     * @version 1.0.0
     */

    /**
     * Classe représentant un film.
     *
     * Cette classe contient toutes les informations liées à un film
     * (titre, réalisateur, durée, description, etc.).
     *
     * Elle est utilisée pour structurer les données issues de l'API
     * ou du cache.
     *
     * @author Alexandre
     * @version 1.1.0
     */
    class Film {
        
        /**
         * Titre du film.
         * @var string
         */
        private string $titre;

        /**
         * Titre original du film.
         * @var string
         */
        private string $titreOriginal;

        /**
         * Titre original romanisé du film.
         * @var string
         */
        private string $titreOriginalRomanise;

        /**
         * Réalisateur du film.
         * @var string
         */
        private string $realisateur;

        /**
         * Producteur du film.
         * @var string
         */
        private string $producteur;

        /**
         * Date de sortie du film.
         * @var string
         */
        private string $dateSortie;

        /**
         * Durée du film en minutes.
         * @var int
         */
        private int $duree;

        /**
         * Description du film.
         * @var string
         */
        private string $description;

        /**
         * URL ou chemin de l'image du film.
         * @var string
         */
        private string $image;

        /**
         * URL ou chemin de la bannière du film.
         * @var string
         */
        private string $banniere;

        /**
         * Construit un objet Film à partir de toutes ses informations.
         *
         * @param string $titre Titre du film
         * @param string $titreOriginal Titre original
         * @param string $titreOriginalRomanise Titre original romanisé
         * @param string $realisateur Nom du réalisateur
         * @param string $producteur Nom du producteur
         * @param string $dateSortie Date de sortie du film
         * @param int $duree Durée du film en minutes
         * @param string $description Résumé du film
         * @param string $image URL ou chemin de l'image
         * @param string $banniere URL ou chemin de la bannière
         */
        public function __construct(string $titre, string $titreOriginal, string $titreOriginalRomanise, 
            string $realisateur, string $producteur, string $dateSortie, int $duree, string $description,
            string $image, string $banniere) {
            
            $this->titre = $titre;
            $this->titreOriginal = $titreOriginal;
            $this->titreOriginalRomanise = $titreOriginalRomanise;
            $this->realisateur = $realisateur;
            $this->producteur = $producteur;
            $this->dateSortie = $dateSortie;
            $this->duree = $duree;
            $this->description = $description;
            $this->image = $image;
            $this->banniere = $banniere;
        }

        /**
         * Retourne le titre officiel du film.
         *
         * Ce titre correspond au nom principal utilisé pour identifier le film
         * dans les bases de données ou les interfaces utilisateur.
         *
         * @return string Titre du film
         */
        public function getTitre(): string {
            return $this->titre;
        }

        /**
         * Retourne le titre original du film.
         *
         * Il s'agit du titre dans la langue d'origine (souvent le japonais pour Ghibli).
         *
         * @return string Titre original du film
         */
        public function getTitreOriginal(): string {
            return $this->titreOriginal;
        }

        /**
         * Retourne le titre original romanisé du film.
         *
         * La romanisation correspond à une transcription du titre original
         * en caractères latins (alphabet occidental).
         *
         * @return string Titre original romanisé du film
         */
        public function getTitreOriginalRomanise(): string {
            return $this->titreOriginalRomanise;
        }

        /**
         * Retourne le nom du réalisateur du film.
         *
         * Le réalisateur est la personne responsable de la direction artistique
         * et de la mise en scène du film.
         *
         * @return string Nom du réalisateur
         */
        public function getRealisateur(): string {
            return $this->realisateur;
        }

        /**
         * Retourne le nom du producteur du film.
         *
         * Le producteur est responsable de la gestion globale du projet
         * (financement, organisation, production).
         *
         * @return string Nom du producteur
         */
        public function getProducteur(): string {
            return $this->producteur;
        }

        /**
         * Retourne la date de sortie du film.
         *
         * Cette date correspond à la première diffusion publique du film.
         * Elle est généralement exprimée sous forme d'année ou de date complète.
         *
         * @return string Date de sortie du film
         */
        public function getDateSortie(): string {
            return $this->dateSortie;
        }

        /**
         * Retourne la durée du film.
         *
         * La durée est exprimée en minutes et représente la longueur totale
         * du film de son début à sa fin.
         *
         * @return int Durée du film en minutes
         */
        public function getDuree(): int {
            return $this->duree;
        }

        /**
         * Retourne la description du film.
         *
         * Il s'agit d'un résumé du scénario permettant de comprendre
         * l'histoire principale du film sans spoiler détaillé.
         *
         * @return string Description du film
         */
        public function getDescription(): string {
            return $this->description;
        }

        /**
         * Retourne l'image associée au film.
         *
         * Cela peut être une URL ou un chemin local pointant vers une affiche
         * ou une illustration du film.
         *
         * @return string Chemin ou URL de l'image
         */
        public function getImage(): string {
            return $this->image;
        }

        /**
         * Retourne la bannière du film.
         *
         * La bannière est une image large utilisée généralement en haut de page
         * ou dans des interfaces de présentation.
         *
         * @return string Chemin ou URL de la bannière
         */
        public function getBanniere(): string {
            return $this->banniere;
        }

    }

?>
