<?php
    declare(strict_types=1);

    /**
     * Module utilitaire pour la gestion des données géographiques.
     * 
     * Ce module regroupe les fonctions d'aide permettant la conversion et 
     * l'uniformisation des noms et codes des départements et régions de France.
     * 
     * @author     Alexandre 
     * @author     Tauseef 
     * @version    1.0.0
     */

    require_once(__DIR__."/../config.php");
    require_once(__DIR__."/functions.inc.php");
    require_once(__DIR__."/../classes/ObjectFactory.php");
    require_once(__DIR__."/../classes/Region.php");
    require_once(__DIR__."/../classes/Departement.php");

    /**
     * Retourne l'adresse IP du client 
     * 
     * @return string : L'adresse IP
     */
    function getAdresseIP() {
        return $_SERVER['REMOTE_ADDR'];
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
        if (!date_default_timezone_get()) {
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
     * @return Region|null Une région ou null si le code n'est pas valide 
     */
    function getRegion(string $nomRegion): Region {
        return ObjectFactory::createRegion($nomRegion, getCodeRegion($nomRegion));
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
     * Recherche le code INSEE d'une commune par son nom au sein d'un département.
     * 
     * La fonction récupère la liste des communes du département via `getCommunes()`,
     * puis parcourt cette liste jusqu'à trouver une correspondance avec le nom fourni.
     *
     * @param Departement $departement L'objet département dans lequel effectuer la recherche.
     * @param string      $nomVille    Le nom exact de la ville recherchée.
     * @return string Le code INSEE de la commune (ex: "33063") ou une chaîne vide si non trouvée.
     */
    function getCodeCommuneInsee(Departement $departement, string $nomVille) : string {
        // Récupération de la collection des communes pour le département donné
        $infosCommunesDepartement = getCommunes($departement);
        
        // Itération sur le tableau : la clé est le code INSEE, la valeur est l'objet commune
        foreach ($infosCommunesDepartement as $codeCommuneInsee => $donneesCommune) {
            if (normalize($donneesCommune->getNom()) === normalize($nomVille)) {
                return (string) $codeCommuneInsee;
            }
        }

        return "Inconnu";
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
    function renitialiserCSV(string $fichier, int $tempsCache) {
        if (file_exists($fichier) && (time() - filemtime($fichier)) > $tempsCache) {
            unlink($fichier);
        }
    }
