<?php
    declare(strict_types=1);

    /**
     * functions-carburants.inc.php - Fonctions liées aux stations-service et aux carburants.
     *
     * Ce module regroupe les fonctions permettant de récupérer, traiter et
     * exploiter les données des stations-service (prix, localisation, types
     * de carburants disponibles, etc.), ainsi que de faciliter leur affichage
     * et leur utilisation au sein du site.
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
     * Récupère les stations-service les plus proches d'une ville donnée.
     *
     * Cette fonction interroge l'API des prix des carburants en France afin de récupérer
     * les stations-service triées par distance par rapport à la ville fournie.
     *
     * Elle limite les résultats aux 20 stations les plus proches et construit des objets
     * métier `Station` à partir des données retournées par l'API
     *
     * @param Ville $ville Ville de référence contenant latitude et longitude.
     * @return Station[] Tableau associatif de stations indexé par leur identifiant.
     *                    Retourne un tableau vide si l'API est inaccessible ou invalide.
     */
    function getStationsProches(Ville $ville) : array {
        $latitude = round((float) $ville->getLatitude(), 2);
        $longitude = round((float) $ville->getLongitude(), 2);

        $url = API_CARBURANTS . "?select=*&order_by=distance(geom%2C%20geom%27POINT({$longitude}%20{$latitude})%27)%20&limit=30";
        $cheminCache = DEBUT_FICHIER_JSON_STATIONS . "_{$latitude}_{$longitude}.json";
        
        // Récupération des stations dans le cache si existe la ville 
        $data = getStationsProchesJSON($cheminCache);
        if (!empty($data)) {
            return construireStationsProche($data);
        }

        // Ouverture du fichier JSON 
        $fichier = file_get_contents($url);
        if (empty($fichier)) {
            return [];
        }

        // Décodage du fichier JSON 
        $data = json_decode($fichier, true);
        if (!is_array($data) || !isset($data["results"])) {
            return [];
        }

        file_put_contents($cheminCache, json_encode($data));

        // Recherche des stations proches 
        return construireStationsProche($data);
    }

    /**
     * Récupère les données des stations proches d'une ville depuis un fichier cache JSON.
     *
     * Cette fonction vérifie l'existence et la validité (selon le temps de cache)
     * d'un fichier JSON local. Si le cache est valide, elle lit et décode son contenu.
     *
     * @param string $fichier Chemin complet vers le fichier cache JSON des stations.
     * @param int $tempsCache Durée de validité du cache en secondes.
     *                        Par défaut, utilise la constante `TEMPS_CACHE_STATIONS`.
     *
     * @return array Tableau associatif contenant les données des stations (issues du JSON),
     *               ou un tableau vide si le fichier n'existe pas, est invalide,
     *               ou si le cache est expiré.
     */
    function getStationsProchesJSON(string $fichier, int $tempsCache = TEMPS_CACHE_STATIONS) : array {
        if (file_exists($fichier) && time() - filemtime($fichier) < $tempsCache) {
            $content = file_get_contents($fichier);
            if ($content !== false) {
                $data = json_decode($content, true);
                if (is_array($data) && isset($data["results"])) {
                    return $data;
                }
            }
        }

        return [];
    }

    /**
     * Transforme les données JSON brutes en une collection d'objets Station.
     *
     * Cette fonction itère sur les résultats d'un flux JSON (issu de l'API ou du cache)
     * pour reconstruire l'arborescence complète des objets (Region, Departement, Ville)
     * via l'ObjectFactory et instancier chaque station avec ses prix et sa date de mise à jour.
     *
     * @param array $data Tableau associatif contenant la clé "results" issue de l'API des carburants.
     *
     * @return Station[] Tableau d'objets Station indexé par l'identifiant unique de chaque station.
     *                    Retourne un tableau vide si aucune donnée n'est présente.
     */
    function construireStationsProche(array $data) : array {
        $stations = [];
        foreach ($data["results"] as $station) {
            $maj = getDerniereMiseAJour($station);
            $stations[(string) $station["id"]] = new Station(
                ObjectFactory::createVille(
                    $station["ville"] ?? "",
                    "",
                    ObjectFactory::createDepartement(
                        $station["departement"] ?? "",
                        $station["code_departement"] ?? "",
                        ObjectFactory::createRegion(
                            $station["region"] ?? "",
                            $station["code_region"] ?? ""
                        )
                    ),
                    $station["cp"] ?? "",
                    (string) $station["geom"]["lat"] ?? "",
                    (string) $station["geom"]["lon"] ?? ""
                ),
            $station["adresse"] ?? "",
            $station["e10_prix"] ?? null,
            $station["sp95_prix"] ?? null,
            $station["sp98_prix"] ?? null,
            $station["gazole_prix"] ?? null,
            $maj ?? ""
            );
        }

        return $stations;
    }

    /**
     * Retourne la date de dernière mise à jour d'une station-service.
     *
     * La mise à jour d'une station est définie comme la date la plus récente
     * parmi les mises à jour de ses différents carburants (gazole, SP95, SP98, E10, etc.).
     *
     * @param array $station Tableau représentant une station issue de l'API carburants.
     * @return string|null Date ISO 8601 de la dernière mise à jour (ex: 2026-04-20T10:16:29+00:00)
     *                     ou null si aucune date n'est disponible.
     */
    function getDerniereMiseAJour(array $station) : string|null {
        $dates = [];
        $carburants = [
            "gazole_maj",
            "e10_maj",
            "sp95_maj",
            "sp98_maj",
            "e85_maj",
            "gplc_maj"
        ];

        foreach($carburants as $carburant) {
            if (isset($station[$carburant])) {
                $dates[] = $station[$carburant];
            }
        }

        if (empty($dates)) {
            return null;
        }

        $date = max($dates);
        return getMajRelative($date);
    }

    /**
     * Convertit une date de mise à jour en texte lisible relatif.
     *
     * Cette fonction transforme une date ISO 8601 en une chaîne de caractères
     * indiquant depuis combien de temps la mise à jour a été effectuée,
     * sous une forme humaine (ex : "MAJ il y a 2 h", "MAJ il y a 3 jours").
     *
     * Le format d'affichage dépend de l'ancienneté :
     * - Moins d'1 heure : affichage en minutes
     * - Moins de 24 heures : affichage en heures
     * - Moins de 7 jours : affichage en jours
     * - Au-delà : affichage de la date complète
     *
     * @param string $dateString Date de mise à jour au format ISO 8601
     *                            (ex : 2026-04-20T10:16:29+00:00)
     *
     * @return string Chaîne lisible indiquant la dernière mise à jour
     *                (ex : "MAJ il y a 23 h", "MAJ il y a 2 jours", "MAJ le 18/04/2026")
     */
    function getMajRelative(string $dateString) {
        $date = new DateTime($dateString);
        $mtn = new DateTime();
        
        $diff = $mtn->diff($date);

        // Moins d'un jour
        if ($diff->days === 0) {
            $hours = ($diff->h) + ($diff->i / 60);

            if ($hours < 1) {
                return "MAJ il y a " . round($diff->i) . " min";
            }

            return "MAJ il y a " . round($hours) . " h";
        }

        // Moins de 7 jours
        if ($diff->days < 7) {
            $jours = "jour";
            if ($diff->days > 1) {
                $jours = "jours";
            }

            return "MAJ il y a " . $diff->days . " " . $jours;
        }

        // Sinon date classique
        return "MAJ le " . $date->format('d/m/Y');
    }

    /**
     * Calcule la distance entre deux points géographiques (en kilomètres).
     * 
     * Cette fonction utilise la formule de Haversine pour déterminer la distance 
     * du grand cercle entre deux points de coordonnées (longitude/latitude) 
     * en tenant compte de la courbure terrestre.
     *
     * @param float $long1 Longitude du point de départ (en degrés décimaux).
     * @param float $lat1  Latitude du point de départ (en degrés décimaux).
     * @param float $long2 Longitude du point d'arrivée (en degrés décimaux).
     * @param float $lat2  Latitude du point d'arrivée (en degrés décimaux).
     *  
     * @return float Distance entre les deux points en kilomètres.
     */
    function distanceGPS(float $long1, float $lat1, float $long2, float $lat2) : float {
        $long1 = deg2rad($long1);
        $lat1 = deg2rad($lat1);
        $long2 = deg2rad($long2);
        $lat2 = deg2rad($lat2);

        // Différences
        $dLat = $lat2 - $lat1;
        $dLon = $long2 - $long1;

        // Formule de Haversine
        $a = sin($dLat / 2) * sin($dLat / 2) +
            cos($lat1) * cos($lat2) *
            sin($dLon / 2) * sin($dLon / 2);

        $c = 2 * atan2(sqrt($a), sqrt(1 - $a));

        $d = RAYON_TERRE * $c;
        return round($d, 2); 
    }


    /**
     * Calcule les prix moyens des différents carburants à partir d'une liste de stations.
     *
     * Chaque station peut contenir des prix de carburants (Gazole, SP95, SP98, E10).
     * Les valeurs null sont ignorées dans le calcul des moyennes.
     *
     * @param array $stations Tableau de stations contenant les prix des carburants
     *
     * @return array<float|null> Tableau associatif des prix moyens par carburant,
     *                           ou null si aucune donnée n'est disponible
     */
    function calculerPrixMoyens(array $stations): array {
        $totaux = ['gazole' => [], 'sp95' => [], 'sp98' => [], 'e10' => []];
        
        foreach ($stations as $station) {
            if ($station->getPrixGazole() !== null) $totaux['gazole'][] = $station->getPrixGazole();
            if ($station->getPrixSP95()   !== null) $totaux['sp95'][]   = $station->getPrixSP95();
            if ($station->getPrixSP98()   !== null) $totaux['sp98'][]   = $station->getPrixSP98();
            if ($station->getPrixE10()    !== null) $totaux['e10'][]     = $station->getPrixE10();
        }

        $moyennes = [];
        foreach ($totaux as $type => $vals) {
            $moyennes[$type] = !(empty($vals)) ? round(array_sum($vals) / count($vals), 3) : null;
        }

        return $moyennes;
    }
