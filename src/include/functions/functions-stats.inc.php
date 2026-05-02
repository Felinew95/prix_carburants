<?php
    declare(strict_types=1);

    /**
     * functions-stats.inc.php - Fonctions de gestion et d’analyse des statistiques du site.
     *
     * Ce module regroupe les fonctions permettant de collecter, stocker,
     * traiter et analyser les données d’utilisation du site (consultations,
     * recherches, fréquentation, etc.), ainsi que de préparer ces données
     * pour leur affichage (graphiques, tableaux, indicateurs).
     *
     * @author  Alexandre BURIN
     * @author  Tauseef AHMED
     * @version 2.0.0
     */

    // Directives nécessaires
    require_once(__DIR__."/../../config.php");
    require_once(__DIR__."/../helper.inc.php");
    require_once(__DIR__."/../../classes/ObjectFactory.php");

    /**
     * Retourne le nombre total de stations-service disponibles en France.
     *
     * Cette fonction récupère et compte l'ensemble des stations
     * présentes dans la base de données ou via l'API utilisée.
     *
     * @param int $tempsCache Durée de validité du cache en secondes
     * @return int Nombre total de stations-service en France
     */
    function getNombreTotalStationsFrance(int $tempsCache = TEMPS_CACHE_NOMBRE_STATIONS_FRANCE): int {
        if (file_exists(FICHIER_NOMBRE_STATIONS) && (time() - filemtime(FICHIER_NOMBRE_STATIONS)) < $tempsCache) {
            return (int) file_get_contents(FICHIER_NOMBRE_STATIONS);
        }
    
        $urlApi = API_CARBURANTS;
        $reponseApi = file_get_contents($urlApi);

        if ($reponseApi === false || empty($reponseApi)) {
            return 0;
        }

        $donnees = json_decode($reponseApi, true);
        $nombreStations = $donnees['total_count'];
        
        file_put_contents(FICHIER_NOMBRE_STATIONS, $nombreStations);
        
        return (int) $nombreStations;
    }

    /**
     * Enregistre une ville consultée dans un fichier CSV côté serveur.
     *
     * Chaque appel à cette fonction ajoute une nouvelle ligne dans le fichier
     * de stockage des consultations. La ligne contient un horodatage ainsi que
     * les informations de localisation de la ville (nom, département, région).
     *
     * @param string $fichier Fichier CSV 
     * @param Ville $ville Ville consultée par l'utilisateur
     * @param int $tempsCache Durée de validité du cache en secondes
     * @return void
     */
    function enregistrerVilleCSV(string $fichier, Ville $ville, int $tempsCache) : void {
        // Création du dossier si il existe pas 
        creerDossier(dirname($fichier));

        // Réinitialise le fichier si le cache est expiré
        reinitialiserCSV($fichier, $tempsCache);

        // Création du fichier si il existe pas 
        creerFichier($fichier);

        $date = date("Y-m-d H:i:s");
        
        $ligne = [
            $date, 
            $ville->getNom(), 
            $ville->getDepartement()->getNom(), 
            $ville->getDepartement()->getRegion()->getNom()
        ];

        $fp = fopen($fichier, "a");

        if ($fp !== false) {
            fputcsv($fp, $ligne, ";", '"', "\\");
            fclose($fp);
        }
    }

    /**
     * Ouvre un fichier et permet d’accéder à son contenu pour lecture ou traitement.
     *
     * @param string $cheminFichier Chemin du fichier à ouvrir
     * @return mixed Ressource du fichier ou false en cas d'échec
     */
    function ouvrirFichier(string $cheminFichier) : mixed {
        // Vérification du fichier si il existe
        if (!file_exists($cheminFichier)) {
            return false;
        }
        
        // Ouverture du fichier 
        $fichier = fopen($cheminFichier, "r");
        if ($fichier === false) {
            return false;
        }
        
        return $fichier;
    }

    /**
     * Récupère et comptabilise les villes consultées à partir du fichier de cache.
     *
     * @return array Retourne un tableau vide si le fichier n'existe pas ou est illisible.
     * Sinon, retourne un tableau associatif indexé par le nom de la ville.
     */
    function getVillesRecherchesCSV(string $cheminFichier = FICHIER_CACHE_VILLES_CONSULTES_ANNUEL) : array {
        // Lecture et extraction 
        $villes = [];
        $fichier = ouvrirFichier($cheminFichier);

        if ($fichier === false) {
            return [];
        }

        while (($ligne = fgetcsv($fichier, 0, ";", '"', "\\")) !== false) {
            if (count($ligne) < 4) {
                continue;
            }

            [$date, $nomVille, $nomDepartement, $nomRegion] = $ligne;
            if (!isset($villes[$nomVille])) {
                $villes[$nomVille] = [
                    'departement' => $nomDepartement, 
                    'region' => $nomRegion,
                    'nombre' => 1
                ];
            } else {
                $villes[$nomVille]['nombre']++;
            }
        }
        fclose($fichier);
        
        return getLes5VillesLesPlusRecherches($villes);
    }

    /**
     * Récupère et comptabilise les villes consultées pour un mois spécifique à partir du fichier de cache.
     *
     * @param string $cheminFichier Le chemin vers le fichier de cache.
     * @return array Retourne un tableau vide si le fichier n'existe pas ou est illisible.
     * Sinon, retourne un tableau associatif indexé par le nom de la ville.
     */
    function getVillesRecherchesParMoisCSV(string $cheminFichier = FICHIER_CACHE_VILLES_CONSULTES_ANNUEL) : array {
        $mois = date("m");
        $villes = [];
        $fichier = ouvrirFichier($cheminFichier);

        if ($fichier === false) {
            return [];
        }
        
        while (($ligne = fgetcsv($fichier, 0, ";", '"', "\\")) !== false) {
            if (count($ligne) < 4) {
                continue;
            }

            [$date, $nomVille, $nomDepartement, $nomRegion] = $ligne;
            
            if (date("m", strtotime($date)) === $mois) {
                if (!isset($villes[$nomVille])) {
                    $villes[$nomVille] = [
                        'departement' => $nomDepartement, 
                        'region' => $nomRegion,
                        'nombre' => 1
                    ];
                } else {
                    $villes[$nomVille]['nombre']++;
                }
            }
        }
        fclose($fichier);
        
        return getLes5VillesLesPlusRecherches($villes);
    }

    /**
     * Trie les villes par popularité et retourne les 5 premières.
     *
     * @param array $villes Le tableau associatif des villes (généré par getVillesLesPlusRecherches).
     * @return array Le tableau filtré contenant au maximum les 5 villes les plus consultées.
     */
    function getLes5VillesLesPlusRecherches(array $villes) : array {
        // Compare en fonction du nombre d'apparition de la ville
        uasort($villes, function($a, $b){
            return comparer2Valeurs($b["nombre"], $a["nombre"]);
        }); 

        return array_slice($villes, 0, 5, true);
    }

    /**
     * Génère une liste HTML ordonnée des 5 villes les plus recherchés.
     *
     * @param array $villes Le tableau des villes trié ou non (idéalement le top 5).
     * @return string Une chaîne de caractères contenant le code HTML de la liste (<ol><li>...</li></ol>).
    */
    function creerListeVillesLesPlusRecherches(array $villes) : string {
        $liste = "<ol>";

        foreach ($villes as $nomVille => $info) {
            $nomVille = normaliserNom(htmlspecialchars($nomVille));
            $infoDepartement = normaliserNom(htmlspecialchars($info['departement']));
            $infoRegion = normaliserNom(htmlspecialchars($info['region']));
            $consultations = $info['nombre'] === 1 ? 'consultation' : 'consultations';

            $liste .= "<li><strong>" . htmlspecialchars($nomVille) . "</strong>&nbsp;<em>(" . $infoDepartement  . ", " 
                . $infoRegion . ")</em>&nbsp;-&nbsp;" . $info['nombre'] . " " . $consultations . "</li>";
        }

        $liste .= "</ol>";
        return $liste;
    }

    /**
     * Sauvegarde la dernière ville consultée en cookie côté client.
     * @param Ville $ville La ville à sauvegarder
     * @return void
     */
    function sauvegarderVilleCookie(Ville $ville): void {
        setcookie('derniere_ville_nom', $ville->getNom(), time() + 365 * 24 * 3600, '/');
        setcookie('derniere_ville_code', $ville->getCodeCommuneInsee(), time() + 365 * 24 * 3600, '/');
        setcookie('derniere_ville_date', date('d/m/Y'), time() + 365 * 24 * 3600, '/');
        setcookie('derniere_ville_heure', date('H:i:s'), time() + 365 * 24 * 3600, '/');
    }

    /**
     * Récupère le nombre total de consultations (tous utilisateurs confondus).
     * 
     * @param string $fichierCache Le chemin du fichier de cache.
     * @param int $limite La limite du nombre de consultations.
     * @return int Le nombre total de consultations.
     */
    function getConsultationsTotales(string $fichierCache = FICHIER_NOMBRE_VISITES_TOTALES_TOUS_UTILISATEURS) : int {
        if (!file_exists($fichierCache) || ($compteur = file_get_contents($fichierCache)) === false) {
            file_put_contents($fichierCache, 0);
            $compteur = 0;
        }
        
        return (int) $compteur;
    }

    /**
     * Met à jour le fichier des consultations totales avec réinitialisation si la limite est atteinte.
     * 
     * @param int $nombre Le nombre de consultations totales.
     * @param string $fichierCache Le chemin du fichier de cache.
     * @param int $limite La limite du nombre de consultations.
     * @return void
     */
    function setConsultationsTotales(int $nombre, string $fichierCache = FICHIER_NOMBRE_VISITES_TOTALES_TOUS_UTILISATEURS, int $limite = LIMITE_NOMBRE_VISITEURS_CONSULTATION) : void {
        if ($nombre >= $limite) {
            $nombre = 0;
        }
        file_put_contents($fichierCache, (string) $nombre);
    }