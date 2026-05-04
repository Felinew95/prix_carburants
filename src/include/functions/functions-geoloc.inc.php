<?php
    declare(strict_types=1);

    /**
     * functions-geoloc.inc.php - Fonctions de géolocalisation des visiteurs.
     *
     * Ce module regroupe les fonctions permettant de déterminer la position
     * géographique d’un utilisateur à partir de son adresse IP, ainsi que
     * de traiter et exploiter les données de localisation obtenues
     * (pays, région, ville, etc.).
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

        // Création du fichier si besoin 
        creerDossier(dirname(CACHE_GEOLOC));
        creerFichier(CACHE_GEOLOC);

        if ((time() - filemtime(CACHE_GEOLOC)) >= $tempsCache || file_get_contents(CACHE_GEOLOC) === "") {
            $xml = new SimpleXMLElement('<visiteurs></visiteurs>');
            file_put_contents(CACHE_GEOLOC, $xml->asXML());
        }

        // Vérification du cache et recherche de l'IP dans le cache
        if (file_exists(CACHE_GEOLOC)) {
           $visiteurs = file_get_contents(CACHE_GEOLOC);
           $xml = simplexml_load_string($visiteurs);

           foreach ($xml->visiteur as $visiteur) {
                if ((string) $visiteur->query === $adresseIP) {
                    return $visiteur;
                }
           }
        }

        // Recherche des informations sur le visiteur 
        $visiteur = file_get_contents($url);
        if (empty($visiteur) || $visiteur === false) {
            return false;
        }

        $informations = simplexml_load_string($visiteur);
        if (empty($informations)) {
            return false;
        }

        saveVisiteurDansCache($informations, $adresseIP);
        return $informations;
    }

    /**
     * Sauvegarde les informations d'un visiteur dans le cache XML.
     * 
     * Cette fonction permet d'ajouter un visiteur dans le fichier cache XML
     * contenant les informations géographiques des visiteurs.
     * 
     * @param SimpleXMLElement $informations Les informations du visiteur à sauvegarder
     * @param string $adresseIP L'adresse IP du visiteur
     * @return void
     */
    function saveVisiteurDansCache(SimpleXMLElement $informations, string $adresseIP) : void {
        $xml = simplexml_load_string(file_get_contents(CACHE_GEOLOC));
        $visiteurNode = $xml->addChild('visiteur');

        $visiteurNode->addChild('status', (string) $informations->status);
        $visiteurNode->addChild('country', (string) $informations->country);
        $visiteurNode->addChild('countryCode', (string) $informations->countryCode);
        $visiteurNode->addChild('region', (string) $informations->region);
        $visiteurNode->addChild('regionName', (string) $informations->regionName);
        $visiteurNode->addChild('city', (string) $informations->city);
        $visiteurNode->addChild('zip', (string) $informations->zip);
        $visiteurNode->addChild('lat', (string) $informations->lat);
        $visiteurNode->addChild('lon', (string) $informations->lon);
        $visiteurNode->addChild('timezone', (string) $informations->timezone);
        $visiteurNode->addChild('isp', (string) $informations->isp);
        $visiteurNode->addChild('org', (string) $informations->org);
        $visiteurNode->addChild('as', (string) $informations->as);
        $visiteurNode->addChild('query', $adresseIP);

        file_put_contents(CACHE_GEOLOC, $xml->asXML());
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
