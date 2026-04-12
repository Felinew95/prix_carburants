<?php 

    /**
     * Configuration centrale du site
     * 
     * Définit toutes les constantes et paramètres globaux utilisés dans le site
     * 
     * @author Alexandre
     * @version 1.0.0
     */

    // Constantes utiles

    /**
     * Fichier cache des films Ghibli
     */
    const CACHE_GHIBLI = __DIR__."/cache/filmsGhibli.json";

    /**
     * Durée du cache en secondes (1 mois)
     */
    const TEMPS_CACHE_GHIBLI = 2_592_000; 

    /**
     * URL de l'API Ghibli
     */
    const API_GHIBLI = "https://ghibliapi.vercel.app/films";

    