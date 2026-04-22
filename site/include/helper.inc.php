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
     * Retourne la région associée à un code donné
     * @param string $nomRegion : Le nom d'une région 
     * @return Region|null Une région ou null si le code n'est pas valide 
     */
    function getRegion(string $nomRegion): Region {
        return new Region($nomRegion, getCodeRegion($nomRegion));
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