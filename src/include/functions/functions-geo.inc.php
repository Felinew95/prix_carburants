<?php
    declare(strict_types=1);

    /**
     * functions-geo.inc.php - Fonctions de gestion des données géographiques françaises.
     *
     * Ce module regroupe les fonctions permettant de manipuler les informations
     * relatives aux départements, régions et communes (codes, noms, correspondances),
     * ainsi que de faciliter leur recherche, leur normalisation et leur utilisation
     * au sein du site.
     *
     * @author  Alexandre BURIN
     * @author  Tauseef AHMED
     * 
     * @version 2.0.0
     */

    // Directives nécessaires
    require_once(__DIR__."/../../config.php");
    require_once(__DIR__."/../helper.inc.php");
    require_once(__DIR__."/../../classes/ObjectFactory.php");

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
     * Recherche le code INSEE d'une commune par son nom au sein d'un département.
     * 
     * La fonction récupère la liste des communes du département via `getCommunes()`,
     * puis parcourt cette liste jusqu'à trouver une correspondance avec le nom fourni.
     *
     * @param Departement $departement L'objet département dans lequel effectuer la recherche.
     * @param string      $nomVille    Le nom exact de la ville recherchée.
     * @return string Le code INSEE de la commune (ex: "33063") ou "Inconnu" si non trouvée.
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
