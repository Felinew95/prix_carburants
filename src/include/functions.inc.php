<?php
    declare(strict_types=1);

    /**
     * functions.inc.php - Point d'entrée des fonctions utilitaires du site.
     *
     * Ce module inclut les différents fichiers de fonctions
     * séparés par responsabilité.
     *
     * @author Alexandre BURIN
     * @author Tauseef AHMED
     * 
     * @version 2.0.0
     */

    // Directives nécessaires
    require_once(__DIR__."/../config.php");
    require_once(__DIR__."/helper.inc.php");
    require_once(__DIR__."/../classes/ObjectFactory.php");
    
    // Définit le fuseau horaire par défaut
    defineDateTime();

    // Inclusion des modules de fonctions
    require_once(__DIR__."/functions/functions-ghibli.inc.php");
    require_once(__DIR__."/functions/functions-geoloc.inc.php");
    require_once(__DIR__."/functions/functions-geo.inc.php");
    require_once(__DIR__."/functions/functions-carburants.inc.php");
    require_once(__DIR__."/functions/functions-stats.inc.php");
