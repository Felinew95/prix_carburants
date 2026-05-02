<?php
    declare(strict_types=1);

    /**
     * Page de statistiques du site OùFaireLePlein.
     *
     * Deux modes d'affichage :
     *   - Ville sélectionnée (via GET ou session) : statistiques personnalisées pour cette ville
     *   - Aucune ville : statistiques générales nationales
     *
     * @author Alexandre BURIN
     * @author Tauseef AHMED
     * @version 1.0.0
     */

    /**
     * @var string Titre de la page
     */
    $titre        = "OùFaireLePlein : Statistiques";
    
    /**
     * @var string Logo de la bannière
     */
    $logoBanniere = "./images/favicon-carburants.svg";
    
    /**
     * @var string Logo du site
     */
    $logo         = "./images/oufaireleplein.png";
    
    /**
     * @var string Style de la page
     */
    $style        = "./style/stats.css";
    
    /**
     * @var string Auteurs du site
     */
    $auteurs      = "Alexandre BURIN &amp; Tauseef AHMED";
    
    /**
     * @var string Description de la page
     */
    $description  = "Page de statistiques du site internet";
    
    /**
     * @var string Mots-clés de la page
     */
    $motsCles     = "Statistiques, Carburants, Prix, Tendances";
    
    /**
     * @var string Styles supplémentaires
     */
    $styles       = "";

    // Directives nécessaires
    require_once(__DIR__ . "/include/functions.inc.php");
    require_once(__DIR__ . "/include/helper.inc.php");
    require_once(__DIR__ . "/config.php");

    // Démarre une nouvelle session via l'identifiant de session passé dans une requête GET, POST ou par un cookie.
    session_start();

    /**
     * @var string Région choisie
     */
    $regionChoisie     = (string) ($_GET['region']      ?? $_SESSION['derniere_region']      ?? '');
    
    /**
     * @var string Département choisi
     */
    $departementChoisi = (string) ($_GET['departement'] ?? $_SESSION['derniere_departement'] ?? '');
    
    /**
     * @var string Ville choisie
     */
    $villeChoisie      = (string) ($_GET['ville']       ?? $_SESSION['derniere_ville']       ?? '');

    // Vérification que les valeurs reçues existent bien dans nos tableaux de configuration
    if (!isset(NOM_REGIONS[$regionChoisie]))            $regionChoisie     = '';
    if (!isset(CODE_DEPARTEMENTS[$departementChoisi]))  $departementChoisi = '';

    $objetRegion      = null;
    $objetDepartement = null;
    $objetVille       = null;

    // Créer les objets nécéssaire pour l'affichage plus tard si une ville est passé en paramètre
    if ($regionChoisie !== '') {
        $nomRegion  = getNomRegionUniforme($regionChoisie);
        $codeRegion = getCodeRegion($nomRegion);
        if ($nomRegion !== '' && $codeRegion !== '') {
            $objetRegion = ObjectFactory::createRegion($nomRegion, $codeRegion);
        }
    }

    if ($objetRegion !== null && $departementChoisi !== '') {
        $codeDepartement  = getCodeDepartement($departementChoisi);
        $objetDepartement = ObjectFactory::createDepartement($departementChoisi, $codeDepartement, $objetRegion);
    }

    if ($objetDepartement !== null && $villeChoisie !== '') {
        $listeCommunes = getCommunes($objetDepartement);
        if (isset($listeCommunes[$villeChoisie])) {
            $objetVille = $listeCommunes[$villeChoisie];
        }
    }

    // Définit si le visiteur est un nouveau visiteur
    $estNouveauVisiteur = false;
    if (!isset($_COOKIE['oufaireleplein_visiteur'])) {
        setcookie('oufaireleplein_visiteur', '1', time() + 365 * 24 * 3600, '/');
        $estNouveauVisiteur = true;
    }

    $cheminFichierVisiteurs = __DIR__ . '/cache/visiteurs.txt';
    $nombreVisiteurs = 0;

    if (file_exists($cheminFichierVisiteurs)) {
        $nombreVisiteurs = (int) file_get_contents($cheminFichierVisiteurs);
    }

    // Si c'est un nouveau visiteur, on ajoute un au compteur 
    if ($estNouveauVisiteur) {
        $nombreVisiteurs++;
        $dossierCache = dirname($cheminFichierVisiteurs);
        if (!is_dir($dossierCache)) {
            mkdir($dossierCache, 0755, true);
        }
        file_put_contents($cheminFichierVisiteurs, (string) $nombreVisiteurs);
    }

    if (!isset($_SESSION['nombreConsultationsVilles'])) {
        $_SESSION['nombreConsultationsVilles'] = 0;
    }

    $nombreConsultationsTotales = getConsultationsTotales();
    $nombreConsultationsTotales++;
    setConsultationsTotales($nombreConsultationsTotales);


    if ($objetVille !== null) {
        $_SESSION['nombreConsultationsVilles']++;

        enregistrerVilleCSV(
            FICHIER_CACHE_VILLES_CONSULTES_ANNUEL, $objetVille, TEMPS_CACHE_VILLES_CONSULTES_ANNUEL
        );
    }

    $nombreConsultationsVilles = $_SESSION['nombreConsultationsVilles'];

    //On prend comme référence la ville de Bourges pour représenter le prix moyennes au niveau nationale
    $objetVilleCentrale = ObjectFactory::createVille("Bourges","18033",ObjectFactory::createDepartement("Cher","18",ObjectFactory::createRegion("Centre-Val de Loire", "24")),"18000","47.081012","2.398782");

    //On récupère les stations les stations les plus proches de Bourges
    $stationsNationales = getStationsProches($objetVilleCentrale);
    $prixMoyensFrance   = calculerPrixMoyens($stationsNationales);
    $nombreStationsFR   = getNombreTotalStationsFrance();

    $prixMoyensVille  = ['gazole' => null, 'sp95' => null, 'sp98' => null, 'e10' => null];
    $villesPopulaires = [];

    if ($objetVille !== null) {
        $stationsVille   = getStationsProches($objetVille);
        $prixMoyensVille = calculerPrixMoyens($stationsVille);

        // On compte combien de stations se trouvent dans chaque ville
        // parmi les résultats renvoyés par l'API autour de la ville sélectionnée
        $compteurParVille = [];
        foreach ($stationsVille as $stationProche) {
            $nomVille = $stationProche->getVille()->getNom();
            if ($nomVille !== '') {
                $compteurParVille[$nomVille] = ($compteurParVille[$nomVille] ?? 0) + 1;
            }
        }
        arsort($compteurParVille);
        $villesPopulaires = array_slice($compteurParVille, 0, 5, true);
    }

    $villesPlusRecherchesAnnuel= getVillesRecherchesCSV();
    $villesPlusRecherchesMensuel = getVillesRecherchesParMoisCSV();

    if ($objetDepartement !== null) {
        $nomDepartementNormalise = normaliserNom($objetDepartement->getNom());
    }

    if ($objetVille !== null) {
        $nomVilleNormalise = normaliserNom($objetVille->getNom());
    }

    // Inclusion du header 
    require_once(__DIR__ . "/include/header.inc.php");
?>

    <section class="bandeau-titre">
        <div class="bandeau-icone">
            <i class="fa-solid fa-chart-bar"></i>
        </div>
        <div class="bandeau-texte">
            <h1>
                <?php if ($objetVille !== null): ?>
                    Statistiques — <?= htmlspecialchars($nomVilleNormalise) ?>
                <?php else: ?>
                    Statistiques générales
                <?php endif; ?>
            </h1>
            <p>
                <?php if ($objetVille !== null): ?>
                    Découvrez les prix, tendances et comparaisons du carburant à 
                    <?= htmlspecialchars($nomVilleNormalise) ?>
                    (<?= htmlspecialchars($nomDepartementNormalise) ?>)
                <?php else: ?>
                    Accédez aux statistiques détaillées des prix du carburant en
                    sélectionnant une localisation <em>(ville, département et région)</em>.
                <?php endif; ?>
            </p>
        </div>
    </section>


    <section class="bloc-statistiques">
        <h2 class="titre-section">Utilisation du site</h2>
        <div class="grille-cartes-deux-colonnes">

            <div class="carte-statistique degrade-bleu">
                <div class="carte-icone"><i class="fa-solid fa-users"></i></div>
                <div class="carte-nombre"><?= $nombreVisiteurs ?></div>
                <div class="carte-libelle">Visiteurs uniques</div>
                <div class="carte-sous-titre">Comptabilisés via cookies (1 an)</div>
            </div>

            <div class="carte-statistique degrade-rose">
                <div class="carte-icone"><i class="fa-solid fa-eye"></i></div>
                <div class="carte-nombre"><?= $nombreConsultationsVilles ?></div>
                <div class="carte-libelle">Consultations de villes</div>
                <div class="carte-sous-titre">Stockage session horodaté</div>
            </div>

        </div>
    </section>

    <section class="bloc-statistiques">
        <h2 class="titre-section">Les 5 villes les plus consultées</h2>
        
        <div class="liste-villes">
            <h3>En <?= date("Y") ?> : </h3>
            <?= creerListeVillesLesPlusRecherches($villesPlusRecherchesAnnuel) ?>
        </div>

        <div class="liste-villes">
            <h3>En <?= getNomMois(date("m")) ?> <?= date("Y") ?> : </h3>
            <?= creerListeVillesLesPlusRecherches($villesPlusRecherchesMensuel) ?>
        </div>
    </section>

    <?php if ($objetVille !== null): ?>
        <section class="bloc-statistiques">
            <h2 class="titre-section">
                <i class="fa-solid fa-fire"></i>
                Stations par ville — Proche de <?= htmlspecialchars($nomVilleNormalise) ?>
            </h2>
            
            <div class="liste-villes-populaires">
                <?php if (empty($villesPopulaires)): ?>
                    <p class="message-aucune-donnee">Aucune donnée disponible.</p>
                <?php else: ?>
                    <?php
                    $maximumStations = max($villesPopulaires);
                    $numeroRang = 1;
                    foreach ($villesPopulaires as $nomVille => $nombreStations):
                        $largeurBarre = round(($nombreStations / $maximumStations) * 100);
                    ?>
                    <div class="ligne-ville">
                        <span class="rang-ville"><?= $numeroRang++ ?></span>
                        <span class="nom-ville"><?= htmlspecialchars($nomVille) ?></span>
                        <div class="barre-progression-fond">
                            <div class="barre-progression-remplie" style="width: <?= $largeurBarre ?>%"></div>
                        </div>
                        <span class="compteur-stations">
                            <?= $nombreStations ?> station<?= $nombreStations > 1 ? 's' : '' ?>
                        </span>
                    </div>
                    <?php endforeach; ?>
                <?php endif; ?>
            </div>
        </section>


        <!-- Prix moyens à la ville sélectionnée -->
        <section class="bloc-statistiques">
            <h2 class="titre-section">
                <i class="fa-solid fa-gas-pump"></i>
                Prix moyens à <?= htmlspecialchars($nomVilleNormalise) ?>
            </h2>
            <div class="grille-prix-carburants">
                <?php foreach ($informationsCarburants as $typeCarburant => $infos):
                    $prixVille    = $prixMoyensVille[$typeCarburant];
                    $prixNational = $prixMoyensFrance[$typeCarburant];
                    $difference   = ($prixVille !== null && $prixNational !== null)
                                    ? round($prixVille - $prixNational, 3)
                                    : null;
                ?>
                <div class="carte-prix">
                    <div class="carte-prix-libelle" style="color: var(--<?= $typeCarburant ?>)">
                        <?= $infos ?>
                    </div>
                    <div class="carte-prix-valeur" style="color: var(--<?= $typeCarburant ?>)">
                        <?= $prixVille !== null ? number_format($prixVille, 3, '.', '') . ' €' : '--' ?>
                    </div>
                    <?php if ($difference !== null): ?>
                    <div class="etiquette-comparaison <?= $difference <= 0 ? 'moins-cher' : 'plus-cher' ?>">
                        <?= $difference > 0 ? '+' : '' ?><?= number_format($difference, 3, '.', '') ?> € vs national
                    </div>
                    <?php endif; ?>
                </div>
                <?php endforeach; ?>
            </div>
        </section>


     
        <section class="bloc-statistiques">
            <h2 class="titre-section">
                <i class="fa-solid fa-chart-bar"></i>
                Prix actuels à <?= htmlspecialchars($nomVilleNormalise) ?> vs moyenne nationale
            </h2>
            <p class="note-graphique">
                Cette comparaison présente les prix du carburant issus de l'API gouvernementale : les barres colorées correspondent à 
                <?= htmlspecialchars($nomVilleNormalise) ?>, et les barres transparentes à la moyenne nationale calculée à partir 
                de Bourges.
            </p>
            
            <div class="conteneur-graphique">
                <canvas id="graphiquePrixVille"></canvas>
            </div>
        </section>


        <!-- Tableau de comparaison ville vs nationale -->
        <section class="bloc-statistiques">
            <h2 class="titre-section">
                <i class="fa-solid fa-scale-balanced"></i>
                Comparaison — <?= htmlspecialchars($nomVilleNormalise) ?> vs Moyenne nationale
            </h2>
            <div class="conteneur-tableau-comparaison">
                <table class="tableau-comparaison">
                    <thead>
                        <tr>
                            <th>Carburant</th>
                            <th>Prix à <?= htmlspecialchars($nomVilleNormalise) ?></th>
                            <th>Moyenne nationale</th>
                            <th>Différence</th>
                        </tr>
                    </thead>
                    
                    
                    <tbody>
                        <?php foreach ($informationsCarburants as $typeCarburant => $infos):
                            $prixVille    = $prixMoyensVille[$typeCarburant];
                            $prixNational = $prixMoyensFrance[$typeCarburant];
                            $difference   = ($prixVille !== null && $prixNational !== null)
                                            ? round($prixVille - $prixNational, 3)
                                            : null;
                        ?>
                        <tr>
                            <td>
                                <span class="puce-couleur" style="background: var(--<?= $typeCarburant ?>)"></span>
                                <?= $infos ?>
                            </td>
                            <td class="cellule-prix">
                                <?= $prixVille !== null ? number_format($prixVille, 3, '.', '') . ' €' : '--' ?>
                            </td>
                            <td class="cellule-prix">
                                <?= $prixNational !== null ? number_format($prixNational, 3, '.', '') . ' €' : '--' ?>
                            </td>
                            <td class="cellule-difference <?= ($difference !== null && $difference <= 0) ? 'moins-cher' : 'plus-cher' ?>">
                                <?php if ($difference !== null): ?>
                                    <?= $difference > 0 ? '+' : '' ?><?= number_format($difference, 3, '.', '') ?> €
                                <?php else: ?>
                                    --
                                <?php endif; ?>
                            </td>
                        </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            </div>
        </section>

    
    <!-- Mode Général dans le cas ou aucune ville est sélectionner -->
    <?php else: ?>
        <section class="bloc-statistiques">
            <h2 class="titre-section">
                <i class="fa-solid fa-chart-bar"></i>
                Prix moyens nationaux actuels
            </h2>
            
            <p class="note-graphique">
                Les prix proviennent de l'API gouvernementale et sont calculés à partir des 30 stations 
                les plus proches de Bourges, utilisé comme point de référence pour la France métropolitaine.
            </p>
            
            <div class="conteneur-graphique">
                <canvas id="graphiqueNational"></canvas>
            </div>

            <!-- Prix moyens affichés sous le graphique -->
            <div class="grille-prix-carburants" style="margin-top: 20px;">
                <?php foreach ($informationsCarburants as $typeCarburant => $infos): ?>
                <div class="carte-prix">
                    <div class="carte-prix-libelle" style="color: var(--<?= $typeCarburant ?>)">
                        Prix moyen <?= $infos ?>
                    </div>
                    <div class="carte-prix-valeur" style="color: var(--<?= $typeCarburant ?>)">
                        <?php if ($prixMoyensFrance[$typeCarburant] !== null): ?>
                            <?= number_format($prixMoyensFrance[$typeCarburant], 3, '.', '') ?> €
                        <?php else: ?>
                            --
                        <?php endif; ?>
                    </div>
                </div>
                <?php endforeach; ?>
            </div>
        </section>


        <!-- Statistiques générales France métropolitaine -->
        <section class="bloc-statistiques">
            <h2 class="titre-section">Statistiques France métropolitaine</h2>
            <div class="grille-cartes-quatre-colonnes">

                <div class="carte-statistique degrade-violet">
                    <div class="carte-icone"><i class="fa-solid fa-gas-pump"></i></div>
                    <div class="carte-nombre">
                        <?= $nombreStationsFR > 0 ? number_format($nombreStationsFR, 0, ',', ' ') : '--' ?>
                    </div>
                    <div class="carte-libelle">Stations en France</div>
                    <div class="carte-sous-titre">Source : API gouvernementale</div>
                </div>

                <div class="carte-statistique degrade-bleu">
                    <div class="carte-icone"><i class="fa-solid fa-location-dot"></i></div>
                    <div class="carte-nombre">13</div>
                    <div class="carte-libelle">Régions couvertes</div>
                    <div class="carte-sous-titre">France métropolitaine</div>
                </div>

                <div class="carte-statistique degrade-vert">
                    <div class="carte-icone"><i class="fa-solid fa-users"></i></div>
                    <div class="carte-nombre"><?= $nombreVisiteurs ?></div>
                    <div class="carte-libelle">Visiteurs uniques</div>
                    <div class="carte-sous-titre">Comptabilisés via cookies</div>
                </div>

                <div class="carte-statistique degrade-orange">
                    <div class="carte-icone"><i class="fa-solid fa-eye"></i></div>
                    <div class="carte-nombre"><?= normaliserNombre($nombreConsultationsTotales) ?></div>
                    <div class="carte-libelle">Consultations totales</div>
                    <div class="carte-sous-titre">Tous les utilisateurs</div>
                </div>

            </div>
        </section>


    <?php endif; ?>

    <!-- Section à propos des données -->
    <?php include("./include/a-propos.inc.html"); ?>


    <!-- Données pour représenter le graphe selon le mode -->
    <script type="application/json" id="donnees-graphique">                  
        <?= json_encode([
                    'mode'=> $objetVille !== null ? 'ville' : 'national',
                    'nomVille' => $objetVille !== null ? $objetVille->getNom() : null,
                    'prixVille'=> array_values($prixMoyensVille),
                    'prixNational' => array_values($prixMoyensFrance),], 
                     JSON_HEX_TAG | JSON_HEX_QUOT | JSON_HEX_AMP) ?>
    </script>


    <!-- Utilisation de Charts.js pour l'affichage graphique -->
    <script src="https://cdnjs.cloudflare.com/ajax/libs/Chart.js/4.4.1/chart.umd.min.js"></script>
    <!-- Contient tout le script pour l'affichage -->
    
    <!-- Import des fonctions utilitaires -->
    <script type="module" src="./js/helper.js"></script>
    <!-- Import des configurations Chart.js -->
    <script type="module" src="./js/config.js"></script>
    <!-- Import du script principal -->
    <script type="module" src="./js/stats.js"></script>

<?php 

    // Inclusion du footer 
    require_once("./include/footer.inc.php"); 
