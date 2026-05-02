<?php
    declare(strict_types=1);

    /**
     * helper.inc.php - Module utilitaire principal
     * 
     * Ce module regroupe les fonctions d'aide générales utilisées dans l'application.
     * 
     * @author     Alexandre BURIN
     * @author     Tauseef AHMED
     * @version    1.0.0
     */

    // Directives requises
    require_once(__DIR__."/../config.php");
    require_once(__DIR__."/../classes/ObjectFactory.php");
    require_once(__DIR__."/../classes/Region.php");
    require_once(__DIR__."/../classes/Departement.php");

    /**
     * Retourne l'adresse IP du client 
     * 
     * @return string : L'adresse IP
     */
    function getAdresseIP() : string {
        return $_SERVER['REMOTE_ADDR'] ?? "";
    }

    /**
     * Définit le fuseau horaire par défaut pour l'application.
     *
     * Si aucun fuseau horaire n'est actuellement configuré,
     * la fonction applique celui de Paris ("Europe/Paris").
     *
     * Cela garantit la cohérence des dates et heures utilisées
     * dans le site (logs, statistiques, horodatage CSV, etc.).
     *
     * @return void
     */
    function defineDateTime() : void {
        $timezone = date_default_timezone_get();
        if (!$timezone || empty($timezone)) {
            date_default_timezone_set("Europe/Paris");
        }
    }

    /**
     * Crée un dossier si celui-ci n'existe pas encore.
     *
     * @param string $dossier Le chemin complet du dossier à créer.
     * @return void
     */
    function creerDossier(string $dossier) : void {
        if (!is_dir($dossier)) {
            mkdir($dossier, 0755, true);
        }
    }

    /**
     * Crée un fichier s'il n'existe pas déjà.
     *
     * Cette fonction vérifie si le fichier existe à l'emplacement donné.
     * S'il n'existe pas, il est créé avec un contenu vide.
     *
     * @param string $fichier Chemin du fichier à créer
     *
     * @return void
     */
    function creerFichier(string $fichier) : void {
        if (!file_exists($fichier)) {
            file_put_contents($fichier, "");
        }
    }
    
    /**
     * Normalise une chaîne de caractères pour permettre des comparaisons fiables.
     *
     * Elle est utile notamment pour comparer des noms (villes, départements, etc.)
     * sans être sensible aux majuscules/minuscules ou aux espaces accidentels.
     *
     * @param string $s Chaîne de caractères à normaliser.
     * @return string Chaîne normalisée (trim + minuscules).
     */
    function normalize(string $s): string {
        return mb_strtolower(trim($s));
    }

    /**
     * Traduit un numéro de mois en son nom complet.
     *
     * @param string $numero Le numéro du mois (ex: "01", "1", "12").
     * @param array $mois Le tableau de correspondance (par défaut NOM_MOIS_FR).
     * @return string Le nom du mois ou "Inconnu" si l'index n'existe pas.
     */
    function getNomMois(string $numero, array $mois = NOM_MOIS_FR): string {
        return $mois[$numero] ?? "Inconnu";
    }

    /**
     * Retourne la région associée à un code donné
     * @param string $nomRegion : Le nom d'une région 
     * @return Region|null Une région ou null si le nom n'est pas valide
     */
    function getRegion(string $nomRegion): ?Region {
        $code = getCodeRegion($nomRegion);
        if ($code === null) {
            return null;
        }
        return ObjectFactory::createRegion($nomRegion, $code);
    }

    /**
     * Retourne le nom uniformisé d'une région.
     * 
     * Cette fonction est utile pour standardiser l'affichage ou le stockage des données,
     * en s'assurant que le nom de la région respecte une nomenclature précise 
     * définie dans la constante `NOM_REGIONS`.
     *
     * @param string $nomRegion Le nom ou l'alias de la région à uniformiser.
     * @return string Le nom officiel/uniformisé ou "Inconnu" si aucune correspondance n'existe.
     */
    function getNomRegionUniforme(string $nomRegion): string {
        return NOM_REGIONS[$nomRegion] ?? "Inconnu";
    }

    /**
     * Récupère le code d'une région à partir de son nom.
     *
     * S'appuie sur la table de correspondance définie dans la constante `CODE_REGIONS`.
     * Utile pour les statistiques ou le filtrage géographique.
     *
     * @param string $nomRegion Le nom de la région (ex: "Bretagne", "Occitanie").
     * @return string Le code associé à la région ou "Inconnu" si le nom est introuvable.
     */
    function getCodeRegion(string $nomRegion): string {
        return CODE_REGIONS[$nomRegion] ?? "Inconnu";
    }

    /**
     * Récupère le code d'un département à partir de son nom.
     *
     * Cette fonction effectue une recherche dans la constante globale
     * `CODE_DEPARTEMENTS`. Si le nom n'est pas trouvé, elle retourne une valeur par défaut.
     *
     * @param string $nomDepartement Le nom complet du département (ex: "Paris").
     * @return string Le code du département correspondant ou "Inconnu" si aucune correspondance n'est trouvée.
     */
    function getCodeDepartement(string $nomDepartement): string {
        return CODE_DEPARTEMENTS[$nomDepartement] ?? "Inconnu";
    }

    /**
     * Récupère le nom d'un département à partir de son code.
     *
     * La fonction parcourt le tableau `CODE_DEPARTEMENTS` pour trouver la clé 
     * correspondant à la valeur fournie.
     *
     * @param string $codeDepartement Le code du département (ex: "69", "2A").
     * @return string Le nom du département correspondant ou "Inconnu" si le code n'existe pas.
     */
    function getNomDepartement(string $codeDepartement): string {
        return array_search($codeDepartement, CODE_DEPARTEMENTS, true) ?: "Inconnu";
    }

    /**
     * Récupère la ou les zones d'une région choisie.
     *
     * Cette fonction parcourt le tableau `ZONES_REGIONS` pour trouver la ou les zones
     * correspondant au nom de région fourni.
     *
     * @param string $nomRegion Le nom de la région (ex: "Bretagne", "Occitanie").
     * @param array $zonesRegions Le tableau des zones par région.
     * 
     * @return string La ou les zones correspondantes ou "Inconnu" si aucune correspondance n'est trouvée.
     */
    function getZoneRegion(string $nomRegion, array $zonesRegions = ZONES_REGIONS): string {
        $zones = [];

        foreach ($zonesRegions as $zone => $regions) {
            foreach ($regions as $region) {
                if ($region === $nomRegion) {
                    $zones[] = $zone;
                    break;
                }
            }
        }

        return !empty($zones) ? implode(" / ", $zones) : "Inconnu";
    }

    /**
     * Compare deux valeurs numériques.
     *
     * Cette fonction permet de comparer deux nombres et de déterminer leur ordre relatif.
     * Elle peut être utilisée comme fonction de tri (ex : avec usort ou uasort).
     *
     * @param int|float $a Première valeur à comparer
     * @param int|float $b Deuxième valeur à comparer
     *
     * @return int Retourne :
     *             - 1 si $a est supérieur à $b
     *             - -1 si $a est inférieur à $b
     *             - 0 si les deux valeurs sont égales
     */
    function comparer2Valeurs(int|float $a, int|float $b) : int {
        switch(true) {
            case $a > $b:
                return 1;
            case $a < $b:
                return -1;
            default:
                return 0;
        }
    }

    /**
     * Réinitialise un fichier CSV s'il est trop ancien.
     *
     * Cette fonction vérifie l'existence du fichier puis compare sa date de dernière modification
     * avec un temps limite (cache). Si le fichier existe et que son ancienneté dépasse le temps
     * de cache autorisé, il est supprimé.
     *
     * @param string $fichier Chemin du fichier CSV à vérifier
     * @param int $tempsCache Durée maximale de validité du fichier en secondes
     *
     * @return void
     */
    function reinitialiserCSV(string $fichier, int $tempsCache) : void {
        if (file_exists($fichier) && (time() - filemtime($fichier)) > $tempsCache) {
            if (!unlink($fichier)) {
                error_log("Erreur lors de la suppression du fichier: " . $fichier);
            }
        }
    }

    /**
     * Normalise un nombre entier en le formatant avec des unités (k pour milliers, M pour millions).
     *
     * @param int $nombre Le nombre à normaliser.
     * @return string Le nombre normalisé avec l'unité appropriée (ex: 1500 -> 1.50k, 1500000 -> 1.50M).
     */
    function normaliserNombre(int $nombre) : string {
        if ($nombre >= 1_000_000 || $nombre <= -1_000_000) {
            return round($nombre / 1_000_000, 2) . "M";
        } else if ($nombre >= 1_000 || $nombre <= -1_000) {
            return round($nombre / 1_000, 2) . "k";
        }
        return (string) $nombre;
    }

    /**
     * Transforme un nom de région en article défini (en + nom).
     *
     * @param string $nom Le nom de la région à transformer.
     * @return string Le nom avec l'article défini.
     */
    function transformerNomRegion(string $nom) : string {
        return "en " . $nom;
    }

    /**
     * Transforme un nom de département en article défini (dans + nom).
     *
     * @param string $nom Le nom du département à transformer.
     * @return string Le nom avec l'article défini.
     */
    function transformerNomDepartement(string $nom) : string {
        $articleDepartement = null;

        foreach (ARTICLES_DEPARTEMENTS as $article => $nomDepartement) {
            foreach ($nomDepartement as $nomDepart) {
                if ($nom === $nomDepart) {
                    $articleDepartement = $article;
                    break 2; // Casse les deux boucles
                }
            }
        }

        if ($articleDepartement === "l'") {
            return "dans " . $articleDepartement . $nom;
        }

        return $articleDepartement !== null ? "dans ". $articleDepartement . " " . $nom : "dans " . $nom;
    }

    /**
     * Transforme un nom de commune en article défini (à + nom).
     *
     * @param string $nom Le nom de la commune à transformer.
     * @return string Le nom avec l'article défini.
     */
    function transformerNomCommune(string $nom) : string {
        return "à " . $nom;
    }

    /**
     * Transforme l'affichage de h1 selon le nom de la région, du département ou de la commune.
     *
     * @param string $nomRegion Le nom de la région à transformer.
     * @param string $nomDepartement Le nom du département à transformer.
     * @param string $nomVille Le nom de la ville à transformer.
     * @return string Le nom avec l'article défini.
     */
    function transformerAffichageNom(string $nomRegion, string $nomDepartement, string $nomVille) : string {
        $result = "";
        if (!empty($nomVille)) {
            $result = htmlspecialchars(transformerNomCommune($nomVille));
        } elseif (!empty($nomDepartement)) {
            $result = htmlspecialchars(transformerNomDepartement($nomDepartement));
        } elseif (!empty($nomRegion)) {
            $result = htmlspecialchars(transformerNomRegion($nomRegion));
        } else {
            $result = "près de chez vous";
        }
        return $result . " avec OùFaireLePlein !";
    }

    /**
     * Transforme l'affichage de la localisation selon la région, le département et la commune.
     * 
     * @param string|null $nomRegion Le nom de la région à transformer.
     * @param string|null $nomDepartement Le nom du département à transformer.
     * @param string|null $nomVille Le nom de la ville à transformer.
     * @return string La localisation transformée.
     */
    function transformerLocalisation(string|null $nomRegion, string|null $nomDepartement, string|null $nomVille) : string {
        $parts = [];

        if ($nomRegion !== null && $nomRegion !== "") {
            $parts[] = htmlspecialchars($nomRegion);
        }

        if ($nomDepartement !== null && $nomDepartement !== "") {
            $parts[] = htmlspecialchars($nomDepartement);
        }

        if ($nomVille !== null && $nomVille !== "") {
            $parts[] = htmlspecialchars($nomVille);
        }

        return !empty($parts) ? implode(" > ", $parts) : "Non sélectionnée";
    }

    /**
     * Normalise le nom d'une région, d'un département ou d'une ville.
     *
     * @param string $nom Le nom à normaliser.
     * @return string Le nom normalisé.
     */
    function normaliserNom(string $nom) : string {
        $nomNormalise = mb_strtolower($nom, "UTF-8");
        $nomNormalise = str_replace(" ", "-", $nomNormalise);
        $nomNormalise = mb_convert_case($nomNormalise, MB_CASE_TITLE, "UTF-8");
        return $nomNormalise;
    }

    /**
     * Normalise la latitude.
     *
     * @param float $latitude La latitude à normaliser.
     * @return string La latitude normalisée.
     */
    function normaliserLatitude(float $latitude) : string {
        $latitude = round($latitude, 4);
        $latitude = ($latitude < 0) ? abs($latitude) . "S" : $latitude . "N";
        return $latitude;
    }

    /**
     * Normalise la longitude.
     *
     * @param float $longitude La longitude à normaliser.
     * @return string La longitude normalisée.
     */
    function normaliserLongitude(float $longitude) : string {
        $longitude = round($longitude, 4);
        $longitude = ($longitude < 0) ? abs($longitude) . "O" : $longitude . "E";
        return $longitude;
    }