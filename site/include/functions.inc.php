<?php
    declare(strict_types=1);

    /**
     * Ensemble de fonctions utilitaires utilisées dans le site web.
     *
     * Ce module fournit des outils de génération HTML, 
     * ainsi que des fonctions liées.
     *
     * @author Alexandre BURIN
     * @author Tauseef AHMED
     * 
     * @version 1.0.0
     */

    // Directives nécessaires
    require_once(__DIR__."/../config.php"); // __DIR__ : Chemin absolu fichier
    require_once(__DIR__."/helper.inc.php");
    require_once(__DIR__."/../classes/ObjectFactory.php");

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
        $films = @file_get_contents(API_GHIBLI); // @ ignore les warnings
        if ($films === false) {
            return [];
        }

        if (!empty($films)) {
            $films = json_decode($films, true); 
            
            // Créer un cache si nécessaire
            $cacheDir = dirname(CACHE_GHIBLI);
            if (!is_dir($cacheDir)) {
                mkdir($cacheDir, 0755, true);
            }
        
            // Écrit dans le fichier si possible
            if (is_writable($cacheDir)) {
                file_put_contents(CACHE_GHIBLI, json_encode($films));
            }
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
        
        // Choisi un film aléatoire si possible
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

    /**
     * Récupère les informations géographiques approximatives d'un visiteur via son adresse IP.
     *
     * Cette fonction interroge une API externe de géolocalisation IP et renvoie les données
     * au format XML sous forme de SimpleXMLElement si la requête réussit.
     * Elle renvoie false si l'adresse IP est vide ou si la récupération des données échoue.
     *
     * @param string $adresseIP L'adresse IP du visiteur 
     * @param int $tempsCache Durée de validité du cache en secondes
     * 
     * @return SimpleXMLElement|bool Données XML du visiteur, ou false en cas d'erreur.
     */
    function getInfosVisiteurXML(string $adresseIP, int $tempsCache = TEMPS_CACHE_GEOLOC) : SimpleXMLElement|bool {
        // Retourne faux si l'adresse ip n'est pas trouvée
        if (empty($adresseIP)) {
            return false;
        }

        // Construction de l'URL de l'API
        $url = API_GEOLOC . "/xml" . "/" . $adresseIP;

        // Vérification du cache
        if (file_exists(CACHE_GEOLOC) && (time() - filemtime(CACHE_GEOLOC)) < $tempsCache) {
           $station = @file_get_contents(CACHE_GEOLOC);
           $xml = simplexml_load_string($station);
           return $xml !== false ? $xml : false;
        }

        // Recherche des informations sur le visiteur 
        $station = @file_get_contents($url);
        if (empty($station) || $station === false) {
            return false;
        }

        $informations = simplexml_load_string($station);
        if (empty($informations)) {
            return false;
        }

        // Mise à jour du cache 
        file_put_contents(CACHE_GEOLOC, $station);

        return $informations;
    }

    /**
     * Récupère les informations du visiteur sous forme d'objet Visiteur.
     *
     * Cette fonction interroge une source de données afin d'obtenir
     * des informations sur l'utilisateur à partir de son adresse IP.
     *
     * Si aucune donnée n'est disponible ou en cas d'erreur, un objet Visiteur
     * avec des valeurs par défaut est retourné afin de garantir la cohérence
     * de l'application et éviter les erreurs.
     *
     * @return Visiteur Objet contenant les informations du visiteur
     */
    function getInfosVisiteur(): Visiteur {
        $infos = getInfosVisiteurXML(getAdresseIP());

        // Valeurs par défaut
        $defaults = [
            'ip' => 'Adresse IP inconnue',
            'pays' => 'Pays inconnu',
            'codePays' => 'Code pays inconnu',
            'region' => 'Région inconnue',
            'codeRegion' => 'Code région inconnu',
            'ville' => 'Ville inconnue',
            'codePostal' => 'Code postal inconnu',
            'latitude' => 'Latitude inconnue',
            'longitude' => 'Longitude inconnue',
            'as' => 'Numéro ASN inconnu',
            'nomAsn' => "Nom de l'organisation ASN inconnu",
        ];

        // Si erreur ou données vides 
        if ($infos === false) {
            return ObjectFactory::createVisiteur(
                $defaults['ip'],
                $defaults['pays'],
                $defaults['codePays'],
                $defaults['region'],
                $defaults['codeRegion'],
                $defaults['ville'],
                $defaults['codePostal'],
                $defaults['latitude'],
                $defaults['longitude'],
                $defaults['as'],
                $defaults['nomAsn']
            );
        }

        return ObjectFactory::createVisiteur(
            (string) ($infos->query ?? $defaults['ip']),
            (string) ($infos->country ?? $defaults['pays']),
            (string) ($infos->countryCode ?? $defaults['codePays']),
            (string) ($infos->regionName ?? $defaults['region']),
            (string) ($infos->region ?? $defaults['codeRegion']),
            (string) ($infos->city ?? $defaults['ville']),
            (string) ($infos->zip ?? $defaults['codePostal']),
            (string) ($infos->lat ?? $defaults['latitude']),
            (string) ($infos->lon ?? $defaults['longitude']),
            (string) ($infos->as ?? $defaults['as']),
            (string) ($infos->org ?? $defaults['nomAsn'])
        );
    }

    // Fonctions principales du site

    /**
     * Récupère les départements appartenant à une région donnée à partir d'un fichier CSV.
     *
     * @param Region $region La région.
     * @return array Tableau associatif [code_departement => departement].
     */
    function getDepartements(Region $region) : array {
        $departements = [];
        $codeRegion = $region->getCode();

        // Ouverture du fichier CSV des départements 
        $fichierDepartements = fopen(FICHIER_DEPARTEMENTS, "r");
        if ($fichierDepartements === false) {
            return [];
        }

        fgets($fichierDepartements); // Saute l'en-tête

        // Récupération des départements 
        while ($ligneDepartement = fgets($fichierDepartements)) {
            $ligneDepartement = trim($ligneDepartement); // trim() réduit les espaces du début et de la fin de la chaîne
            if (empty($ligneDepartement)) {
                continue;
            } 

            $donnees = str_getcsv($ligneDepartement, ',', '"', '\\'); // str_getcsv() permet de lire une ligne CSV et de la convertir en tableau
            if (count($donnees) < 4) {
                continue;
            }

            [$codeDepartement, $nomDepartement, $codeRegionCsv, $nomRegion] = $donnees;
            if ($codeRegionCsv === $codeRegion) {
                $departements[$codeDepartement] = ObjectFactory::createDepartement($nomDepartement, $codeDepartement, $region);
            }
        }

        fclose($fichierDepartements);
        return $departements;
    }

    /**
     * Génère une liste déroulante HTML contenant les départements d'une région.
     *
     * La fonction récupère les départements associés au code de région fourni,
     * puis construit une suite de balises. 
     * 
     * @param Region $region La région pour filtrer les départements.
     * @return string Chaîne HTML contenant les balises <option> du select.
     */
    function creerListeDeroulanteDepartement(Region $region, string $nomDepartementChoisi = "") : string {
        $departements = getDepartements($region);
        $listeDeroulante =  "<option value=\"\">--Veuillez choisir un département--</option>\n";

        foreach ($departements as $code => $departement) {
            $selected = ($nomDepartementChoisi === $departement->getNom()) ? ' selected' : '';
            $listeDeroulante .= "\t\t\t\t\t\t\t<option value=\"".htmlspecialchars($departement->getNom())."\" $selected> ".htmlspecialchars($departement->getNom()).
            " (".$code.") </option>\n";
        }

        return $listeDeroulante;
    }

    /**
     * Récupère les communes appartenant à un département donnée à partir d'un fichier CSV.
     *
     * @param Departement $departement Code du département.
     * @return array Tableau associatif 
     *               codeCommuneInsee => [
     *                  'nom_commune' => $nomCommune,
     *                  'code_postal' => $codePostal,
     *                  'latitude' => $latitude,
     *                  'longitude' => $longitude
     *               ].
     */
    function getCommunes(Departement $departement) : array {
        $communes = [];
        $codeDepartement = $departement->getCode();
        
        // Ouverture du fichier CSV des communes 
        $fichierCommunes = fopen(FICHIER_COMMUNES, "r");
        if ($fichierCommunes === false) {
            return [];
        }

        fgets($fichierCommunes);

        // Récupération des communes 
        while ($ligneCommune = fgets($fichierCommunes)) {
            $ligneCommune = trim($ligneCommune);
            if (empty($ligneCommune)) {
                continue;
            }

            $donnees = str_getcsv($ligneCommune, ',', '"', '\\');
            if (count($donnees) < 5) {
                continue;
            }

            [$codeCommuneInsee, $nomCommune, $codePostal, $latitude, $longitude] = $donnees;
            $debutCodeInsee = substr($codeCommuneInsee, 0, 2);
            if ($debutCodeInsee === $codeDepartement || in_array($debutCodeInsee, ['2A', '2B'], true) && ($codeDepartement === '2A' || $codeDepartement === '2B')) {
                $communes[$codeCommuneInsee] = ObjectFactory::createVille($nomCommune, $codeCommuneInsee, $departement, $codePostal, $latitude, $longitude);
            }
        }

        fclose($fichierCommunes);
        return $communes;
    }

    /**
     * Génère une liste déroulante HTML contenant les communes d'un département.
     *
     * @param Departement $departement Un département.
     * @return string HTML des options du select.
     */
    function creerListeDeroulanteCommune(Departement $departement, string $codeCommuneInseeChoisie = "") : string {
        $communes = getCommunes($departement);
        $listeDeroulante =  "\t\t\t\t\t\t<option value=\"\">--Veuillez choisir une commune--</option>\n";

        foreach ($communes as $codeCommuneInsee => $donneesCommune) {
            $selected = ($codeCommuneInseeChoisie == $codeCommuneInsee) ? "selected" : "";
            $listeDeroulante .= "\t\t\t\t\t\t\t<option value=\"".$codeCommuneInsee."\" $selected> ".htmlspecialchars($donneesCommune->getNom()).
                " (".htmlspecialchars($donneesCommune->getCodePostal()).") </option>\n";
        }

        return $listeDeroulante;
    }

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
        $url = API_CARBURANTS . "?select=*&order_by=distance(geom%2C%20geom%27POINT({$ville->getLongitude()}%20{$ville->getLatitude()})%27)%20&limit=30";
        
        // Ouverture du fichier JSON 
        $fichier = @file_get_contents($url);
        if (empty($fichier)) {
            return [];
        }

        // Décodage du fichier JSON 
        $data = json_decode($fichier, true);
        if (!is_array($data) || !isset($data["results"])) {
            return [];
        }

        // Recherche des stations proches 
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
     * Calcule les prix moyens des différents carburants à partir d’une liste de stations.
     *
     * Chaque station peut contenir des prix de carburants (Gazole, SP95, SP98, E10).
     * Les valeurs null sont ignorées dans le calcul des moyennes.
     *
     * @param array $stations Tableau de stations contenant les prix des carburants
     *
     * @return array<float|null> Tableau associatif des prix moyens par carburant,
     *                           ou null si aucune donnée n’est disponible
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

    /**
     * Retourne le nombre total de stations-service disponibles en France.
     *
     * Cette fonction récupère et compte l’ensemble des stations
     * présentes dans la base de données ou via l’API utilisée.
     *
     * @return int Nombre total de stations-service en France
     */
    function getNombreTotalStationsFrance(): int {
        $urlApi     = API_CARBURANTS ."?limit=1";
        $reponseApi = @file_get_contents($urlApi);

        if ($reponseApi === false || empty($reponseApi)) {
            return 0;
        }

        $donnees = json_decode($reponseApi, true);
        return (int) ($donnees['total_count'] ?? 0);
    }
