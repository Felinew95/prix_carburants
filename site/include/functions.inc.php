<?php
    declare(strict_types=1);

    /**
     * Ensemble de fonctions utilitaires utilisées dans le site web.
     *
     * Ce module fournit des outils de génération HTML, 
     * ainsi que des fonctions liées.
     *
     * @author Alexandre
     * @author Tauseef
     * 
     * @version 1.0.0
     */

    require_once(__DIR__."/../config.php"); // __DIR__ : Chemin absolu fichier
    require_once(__DIR__."/../class/Film.php");
    require_once(__DIR__."/../class/Visiteur.php");
    
    /**
     * Récupère la liste des films du Studio Ghibli depuis le cache ou l'API.
     * @param int $tempsCache Durée de validité du cache en secondes
     * @return array Tableau associatif des films
     */
    function getFilmsGhibli(int $tempsCache = TEMPS_CACHE_GHIBLI) : array {
        
        // Vérifie si le fichier cache est bien à jour pour extraire les informations
        if (file_exists(CACHE_GHIBLI) && (time() - filemtime(CACHE_GHIBLI)) < $tempsCache) {
            $films = json_decode(file_get_contents(CACHE_GHIBLI), true);
            return is_array($films) ? $films : [];
        }
    
        // Sinon on le récupère via l'API
        $films = @file_get_contents(API_GHIBLI); // @ ignore les warnings
        if ($films === false) {
            return [];
        }

        if (!empty($films)) {
            $films = json_decode($films, true);
            
            $cacheDir = dirname(CACHE_GHIBLI);
            if (!is_dir($cacheDir)) {
                mkdir($cacheDir, 0755, true);
            }
        
            if (is_writable($cacheDir)) {
                file_put_contents(CACHE_GHIBLI, json_encode($films));
            }
        }

        return (is_array($films)) ? $films : [];
    }

    /**
     * Récupère un film aléatoire depuis la liste des films.
     * @return array|null Film aléatoire ou null si aucun film
     */
    function getRandomFilm() : array|null {
        $films = getFilmsGhibli();
        if (!empty($films)) {
            return $films[rand(0, count($films)-1)];
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
        if (empty($infosFilmAleatoire)) {
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

        return new Film(
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

    /**
     * Retourne l'adresse IP du client 
     * @return string : L'adresse IP
     */
    function getAdresseIP() {
        return $_SERVER['REMOTE_ADDR'];
    }

    /**
     * Récupère les informations géographiques approximatives d'un visiteur via son adresse IP.
     *
     * Cette fonction utilise l'API WhatIsMyIP pour retourner les informations en XML.
     * Elle renvoie un objet SimpleXMLElement contenant les données si la récupération réussit,
     * ou false si l'adresse IP est vide ou si la récupération échoue.
     *
     * @return 
     */
    function getInfosVisiteurJSON() : array|bool {
        $adresseIP = getAdresseIP();
        if (empty($adresseIP)) {
            return false;
        }

        $url = "https://api.ipinfo.io/lite/{$adresseIP}?token=f9b97e2be03351";
        $data = @file_get_contents($url);
        $informations = json_decode($data, true);

        if (empty($informations)) {
            return false;
        }

        return $informations;
    }

    /**
     * Récupère les informations du visiteur sous forme d'objet Visiteur.
     *
     * Cette fonction interroge une source de données (API JSON) afin d'obtenir
     * des informations sur l'utilisateur à partir de son adresse IP.
     *
     * Si aucune donnée n'est disponible ou en cas d'erreur, un objet Visiteur
     * avec des valeurs par défaut est retourné afin de garantir la cohérence
     * de l'application et éviter les erreurs.
     *
     * @return Visiteur Objet contenant les informations du visiteur
     */
    function getInfosVisiteur(): Visiteur {
        $infos = getInfosVisiteurJSON();

        // Valeurs par défaut
        $defaults = [
            'ip' => 'Adresse IP inconnue',
            'country' => 'Pays inconnu',
            'country_code' => 'Code pays inconnu',
            'continent' => 'Continent inconnu',
            'continent_code' => 'Code continent inconnu',
            'asn' => 'Numéro ASN inconnu',
            'as_name' => 'Nom de l\'organisation ASN inconnu',
            'as_domain' => 'Domaine associé inconnu'
        ];

        // Si erreur ou données vides 
        if ($infos === false || empty($infos)) {
            return new Visiteur(
                $defaults['ip'],
                $defaults['country'],
                $defaults['country_code'],
                $defaults['continent'],
                $defaults['continent_code'],
                $defaults['asn'],
                $defaults['asn_name'],
                $defaults['asn_domain']
            );
        }

        return new Visiteur(
            $infos['ip'] ?? $defaults['ip'],
            $infos['country'] ?? $defaults['country'],
            $infos['country_code'] ?? $defaults['country_code'],
            $infos['continent'] ?? $defaults['continent'],
            $infos['continent_code'] ?? $defaults['continent_code'],
            $infos['asn'] ?? $defaults['asn'],
            $infos['as_name'] ?? $defaults['as_name'],
            $infos['as_domain'] ?? $defaults['as_domain']
        );
    }