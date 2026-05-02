<?php
    declare(strict_types=1);

    /**
     * functions-ghibli.inc.php - Fonctions d'accès et de traitement des données
     * provenant de l'API Studio Ghibli.
     *
     * Ce fichier regroupe les fonctions permettant de récupérer, manipuler
     * et exploiter les informations relatives aux films, personnages et autres
     * ressources fournies par l'API.
     * 
     * @author Alexandre BURIN
     * @author Tauseef AHMED
     * 
     * @version 2.0.0
     */

    // Directives nécessaires
    require_once(__DIR__."/../../config.php");
    require_once(__DIR__."/../helper.inc.php");
    require_once(__DIR__."/../../classes/ObjectFactory.php");

    /**
     * Récupère la liste des films du Studio Ghibli depuis le cache ou l'API.
     * 
     * @param int $tempsCache Durée de validité du cache en secondes
     * @return array Tableau associatif des films
     */
    function getFilmsGhibli(int $tempsCache = TEMPS_CACHE_GHIBLI) : array {
        
        // Vérifie si le fichier cache est bien à jour pour extraire les informations
        if (file_exists(CACHE_GHIBLI) && (time() - filemtime(CACHE_GHIBLI)) < $tempsCache) {
            $films = json_decode(file_get_contents(CACHE_GHIBLI), true); // Convertit le JSON en tableau associatif
            return is_array($films) ? $films : [];
        }
    
        // Sinon on le récupère via l'API
        $films = file_get_contents(API_GHIBLI);
        if ($films === false) {
            return [];
        }

        if (!empty($films)) {
            $films = json_decode($films, true); 
            
            // Créer un cache si nécessaire
            creerDossier(dirname(CACHE_GHIBLI));
        
            // Écrit dans le fichier
            file_put_contents(CACHE_GHIBLI, json_encode($films));
        }

        return (is_array($films)) ? $films : [];
    }

    /**
     * Récupère un film aléatoire depuis la liste des films.
     * 
     * @return array|null Film aléatoire ou null si aucun film
     */
    function getRandomFilm() : array|null {
        $films = getFilmsGhibli();
        if (!is_array($films) && $films !== null) {
            return null;
        }
        
        // Choisi un film aléatoire si possible
        if (!empty($films)) {
            return $films[random_int(0, count($films)-1)];
        }

        return null;
    }

    /**
     * Récupère les informations d'un film aléatoire sous forme d'objet Film.
     *
     * Cette fonction interroge la source de données des films (API ou cache)
     * et retourne un objet Film contenant les informations du film sélectionné.
     *
     * Si aucun film n'est disponible, un objet Film par défaut est retourné
     * afin d'éviter les erreurs et garantir un affichage cohérent.
     *
     * @return Film Objet représentant un film
     */
    function getInfosFilm(): Film {
        $infosFilmAleatoire = getRandomFilm();

        // Cas où aucun film n'est disponible
        if (!is_array($infosFilmAleatoire) || $infosFilmAleatoire === null || empty($infosFilmAleatoire)) {
            return new Film(
                'Titre inconnu',
                'Titre original inconnu',
                'Titre original romanisé inconnu',
                'Réalisateur inconnu',
                'Producteur inconnu',
                'Date de publication inconnue',
                0,
                'Description indisponible',
                'placeholder.jpg',
                'placeholder.jpg'
            );
        }

        // Cas où un film est trouvé
        return ObjectFactory::createFilm(
            $infosFilmAleatoire['title'] ?? 'Titre inconnu',
            $infosFilmAleatoire['original_title'] ?? 'Titre original inconnu',
            $infosFilmAleatoire['original_title_romanised'] ?? 'Titre original romanisé inconnu',
            $infosFilmAleatoire['director'] ?? 'Réalisateur inconnu',
            $infosFilmAleatoire['producer'] ?? 'Producteur inconnu',
            $infosFilmAleatoire['release_date'] ?? 'Date inconnue',
            isset($infosFilmAleatoire['running_time']) ? (int)$infosFilmAleatoire['running_time'] : 0,
            $infosFilmAleatoire['description'] ?? 'Description indisponible',
            $infosFilmAleatoire['image'] ?? 'placeholder.jpg',
            $infosFilmAleatoire['movie_banner'] ?? 'placeholder.jpg'
        );
    }