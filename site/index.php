<?php
    declare(strict_types=1);

    /**
     * Page d'accueil du site OùFaireLePlein.
     *
     * Cette page permet à l'utilisateur de sélectionner une région,
     * un département puis une ville afin d'afficher les informations
     * de localisation souhaitées.
     *
     * @author Alexandre BURIN
     * @author Tauseef AHMED
     * @version 1.0.0
     */

    /**
     * @var string Titre de la page
     */
    $titre = "OùFaireLePlein : Accueil";
    
    /**
     * @var string Logo de la bannière
     */
    $logoBanniere = "./images/favicon-carburants.svg";
    
    /**
     * @var string Logo du site
     */
    $logo = "./images/oufaireleplein.png";
    
    /**
     * @var string Style de la page
     */
    $style = "./style/index.css";
    
    /**
     * @var string Auteurs du site
     */
    $auteurs = "Alexandre BURIN &amp; Tauseef AHMED";
    
    /**
     * @var string Description de la page
     */
    $description = "Page d'accueil du site internet";
    
    /**
     * @var string Mots-clés de la page
     */
    $motsCles = "Accueil, Carburants, Prix";
    
    /**
     * @var string Styles supplémentaires
     */
    $styles = "";

    /**
     * @var string Région sélectionnée
     */
    $regionSel = (string) ($_GET['region'] ?? '');
    
    /**
     * @var string Département sélectionné
     */
    $departementSel = (string) ($_GET['departement'] ?? '');
    
    /**
     * @var string Ville sélectionnée
     */
    $villeSel = (string) ($_GET['ville'] ?? '');

    require_once(__DIR__ . "/include/functions.inc.php");
    require_once(__DIR__ . "/include/helper.inc.php");
    require_once(__DIR__ . "/config.php");
    require_once(__DIR__."/classes/ObjectFactory.php");

    // Démarrage session pour sauvegarder la sélection
    session_start();

    if (!isset(NOM_REGIONS[$regionSel])) {
        $regionSel = '';
    }

    if (!isset(CODE_DEPARTEMENTS[$departementSel])) {
        $departementSel = '';
    }

    $region = null;
    $departement = null;
    $ville = null;
    $nomVille = '';

    $mode = "manual";
    if ($regionSel === '' && $departementSel === '' && $villeSel === '') {
        $mode = "ip";
    }

    if ($mode === "ip") {
        $infos = getInfosVisiteur();
        $region = getRegion($infos->getRegion()) ;

        $codeDepartement = substr($infos->getCodePostal(), 0, 2);

        $departement = ObjectFactory::createDepartement(
            getNomDepartement($codeDepartement),
            $codeDepartement,
            $region
        );

        $codeComunneInsee = getCodeCommuneInsee($departement, $infos->getVille());

        $ville = ObjectFactory::createVille(
            $infos->getVille(),
            $codeComunneInsee,
            $departement,
            $infos->getCodePostal(),
            $infos->getLatitude(),
            $infos->getLongitude()
        );
    }

    if ($mode === "manual") {
        if ($regionSel !== '') {
            $nomRegion = getNomRegionUniforme($regionSel) ?? '';
            $codeRegion = getCodeRegion($nomRegion) ?? '';

            if ($nomRegion !== '' && $codeRegion !== '') {
                $region = ObjectFactory::createRegion($nomRegion, $codeRegion);
            }
        }

        if ($region !== null && $departementSel !== '') {
            $codeDepartement = getCodeDepartement($departementSel) ?? '';
            $nomDepartement = $departementSel;

            if ($nomDepartement !== '') {
                $departement = ObjectFactory::createDepartement($nomDepartement, $codeDepartement, $region);
            }
        }

        if ($departement !== null && $villeSel !== '') {
            $communes = getCommunes($departement);

            if (isset($communes[$villeSel])) {
                $ville = $communes[$villeSel];
                $nomVille = $ville->getNom();

                // Sauvegarde en session pour les stats
                $_SESSION['derniere_region'] = $regionSel;
                $_SESSION['derniere_departement'] = $departementSel;
                $_SESSION['derniere_ville'] = $villeSel;
            }
        }
    }

    // Mode IP : sauvegarde aussi en session
    if ($mode === "ip" && $ville !== null) {
        $_SESSION['derniere_region'] = '';
        $_SESSION['derniere_departement'] = '';
        $_SESSION['derniere_ville'] = $ville->getCodeCommuneInsee();
    }

    require_once(__DIR__ . "/include/header.inc.php");
?>

            <section class="localisation-container">
                <div class="localisation-header">
                    <h1>Trouvez le carburant le moins cher avec OùFaireLePlein !</h1>
                </div>

                <div class="localisation-text">
                    <i class="fa-solid fa-location-dot"></i>
                    <p>Localisation :
                    <?php
                        $parts = [];

                        if ($region !== null) {
                            $parts[] = htmlspecialchars($region->getNom());
                        }

                        if ($departement !== null) {
                            $parts[] = htmlspecialchars($departement->getNom());
                        }

                        if ($ville !== null) {
                            $parts[] = htmlspecialchars($ville->getNom());
                        }

                        echo !empty($parts) ? implode(" > ", $parts) : "Non sélectionnée";
                    ?>
                    </p>
                </div>
            </section>

            <section class="selection-section">
                <div class="step-container">
                    <div class="step-header">
                        <div class="step-number">
                            <span>1</span>
                        </div>
                        <h2>Sélectionnez votre région</h2>
                    </div>
                    
                    <div class="carte-container">
                    
                        <img src="./images/carteFrance.png" alt="Carte des régions de France" usemap="#franceMap" class="carte-interactive">

                        <map name="franceMap" id="valider-region">
                            <area shape="circle" coords="310,72,33" href="?region=hauts-de-france" alt="Hauts-de-France">
                            <area shape="circle" coords="218,132,34" href="?region=normandie" alt="Normandie">
                            <area shape="circle" coords="300,154,22" href="?region=ile-de-france" alt="Île-de-France">
                            <area shape="circle" coords="426,138,40" href="?region=grand-est" alt="Grand Est">
                            <area shape="circle" coords="96,194,36" href="?region=bretagne" alt="Bretagne">
                            <area shape="circle" coords="170,200,38" href="?region=pays-de-la-loire" alt="Pays de la Loire">
                            <area shape="circle" coords="270,220,40" href="?region=centre-val-de-loire" alt="Centre-Val de Loire">
                            <area shape="circle" coords="380,220,38" href="?region=bourgogne-franche-comte" alt="Bourgogne-Franche-Comté">
                            <area shape="circle" coords="224,340,50" href="?region=nouvelle-aquitaine" alt="Nouvelle-Aquitaine">
                            <area shape="circle" coords="380,330,46" href="?region=auvergne-rhone-alpes" alt="Auvergne-Rhône-Alpes">
                            <area shape="circle" coords="300,450,46" href="?region=occitanie" alt="Occitanie">
                            <area shape="circle" coords="440,400,32" href="?region=provence-alpes-cote-dazur" alt="Provence-Alpes-Côte d'Azur">
                            <area shape="circle" coords="520,470,18" href="?region=corse" alt="Corse">
                        </map>

                        <?php if(empty($regionSel)): ?>
                        <p class="form-hint">Cliquez sur une région sur la carte pour commencer votre recherche personnalisé</p>
                        <?php else: ?>
                        <div id="selection-region">
                            <p>Région sélectionnée : <?= NOM_REGIONS[$regionSel] ?></p>
                        </div>
                        <?php endif; ?>
                    </div>
                </div>

                <?php if($mode === "manual" && $region !== null):?>
                <div class="selection-departement-container">
                    <div class="step-container">
                        <div class="step-header">
                            <div class="step-number">
                                <span>2</span>
                            </div>

                            <h2>Précisez votre département</h2>
                        </div>

                        <form action="./index.php" method="GET" class="departement-form">
                            <input type="hidden" name="region" value="<?= htmlspecialchars($regionSel) ?>">

                            <div class="form-line">
                                <label for="departement">Département :</label>
                                <select name="departement" id="departement" class="departement-select">
                                    <?= creerListeDeroulanteDepartement($region, $departementSel) ?>
                                </select>
                            </div>

                            <div class="form-actions">
                                <button type="submit" class="btn-submit" id="valider-departement">Valider</button>
                            </div>
                        </form>
                    </div>
                </div>
                <?php endif; ?>

                <?php if($mode === "manual" && $departement !== null):?>
                <div class="selection-ville-container">
                    <div class="step-container">
                        <div class="step-header">
                            <div class="step-number">
                                <span>3</span>
                            </div>

                            <h2>Précisez votre ville</h2>
                        </div>

                        <form action="./index.php" method="GET" class="departement-form">
                            <input type="hidden" name="region" value="<?= htmlspecialchars($regionSel) ?>">
                            <input type="hidden" name="departement" value="<?= htmlspecialchars($departementSel) ?>">

                            <div class="form-line">
                                <label for="ville">Ville :</label>
                                <select name="ville" id="ville" class="departement-select">
                                    <?= creerListeDeroulanteCommune($departement, $villeSel) ?>
                                </select>
                            </div>

                            <div class="form-actions">
                                <button type="submit" class="btn-submit" id="valider-ville">Valider</button>
                            </div>
                        </form>
                    </div>
                </div>
                <?php endif; ?>
            </section>

            <?php if ($ville !== null):?>
            <section id="view-infos-section">
                <div class="step-container">
                    <div class="step-header">
                        <div class="step-icon">
                            <i class="fa-solid fa-map-location-dot"></i>
                        </div>
                        <h2>
                            Détails de la localisation <?php if ($mode === "ip"): ?><em>(Adresse IP)</em><?php endif; ?>
                        </h2>
                    </div>

                    <div class="info-cards-container">
                        <div class="info-card-box">
                            <div class="card-header">
                                <i class="fa-solid fa-layer-group"></i>
                                <h3>Région</h3>
                            </div>
                            <div class="card-body">
                                <p>Nom : <strong><?= htmlspecialchars($region->getNom()) ?></strong></p>
                                <p>Code : <strong><?= htmlspecialchars($region->getCode()) ?></strong></p>
                            </div>
                        </div>

                        <div class="info-card-box">
                            <div class="card-header">
                                <i class="fa-solid fa-map"></i>
                                <h3>Département</h3>
                            </div>
                            <div class="card-body">
                                <p>Nom : <strong><?= htmlspecialchars($departement->getNom()) ?></strong></p>
                                <p>Code : <strong><?= htmlspecialchars($departement->getCode()) ?></strong></p>
                            </div>
                        </div>

                        <div class="info-card-box">
                            <div class="card-header">
                                <i class="fa-solid fa-city"></i>
                                <h3>Ville</h3>
                            </div>
                            <div class="card-body">
                                <p>Nom : <strong><?= htmlspecialchars($ville->getNom()) ?></strong></p>
                                <div class="city-grid">
                                    <p><i class="fa-solid fa-envelope"></i> CP : <strong><?= htmlspecialchars($ville->getCodePostal()) ?></strong></p>
                                    <p><i class="fa-solid fa-fingerprint"></i> INSEE : <strong><?= htmlspecialchars($ville->getCodeCommuneInsee()) ?></strong></p>
                                </div>
                                <p class="gps-tag"><i class="fa-solid fa-location-crosshairs"></i> Localisation : <?= round((float)$ville->getLatitude(), 4) ?>, <?= round((float)$ville->getLongitude(), 4) ?></p>
                            </div>
                        </div>
                    </div>
                </div>
            </section>

            <section id="view-carburant-section">
                <div class="step-container">
                    <div class="step-header">
                        <div class="step-icon">
                            <i class="fa-solid fa-gas-pump"></i>
                        </div>
                        <h2>Stations à proximité de <?= htmlspecialchars($ville->getNom()) ?></h2>
                    </div>

                    <div class="view-stations">
                        <?php 
                            $stations = getStationsProches($ville);

                            $latitudeVilleSel = (float) $ville->getLatitude();
                            $longitudeVilleSel = (float) $ville->getLongitude();

                            foreach ($stations as $station):
                        ?>
                        <div class="station-card">
                            <div class="station-top">
                                <h3 class="station-name"><?= htmlspecialchars($station->getVille()->getNom()) ?></h3>
                                <span class="update-time">
                                    <i class="fa-solid fa-arrow-trend-down"></i> <?= $station->getMaj() ?>
                                </span>
                            </div>

                            <div class="station-location">
                                <i class="fa-solid fa-location-dot"></i>
                                <span><?= htmlspecialchars($station->getAdresse()) ?></span>
                                <span class="distance-dot">• 
                                    <?= distanceGPS($longitudeVilleSel, $latitudeVilleSel, 
                                    (float) $station->getVille()->getLongitude(), (float) $station->getVille()->getLatitude())
                                    ?> km
                                </span>
                            </div>

                            <div class="prices-container">
                                <div class="price-box">
                                    <span class="fuel-type">Gazole</span>
                                    <span class="price-value gazole-color"><?= $station->getPrixGazole() ?? "--" ?> €</span>
                                </div>

                                <div class="price-box">
                                    <span class="fuel-type">SP95</span>
                                    <span class="price-value"><?= $station->getPrixSP95() ?? "--" ?> €</span>
                                </div>

                                <div class="price-box">
                                    <span class="fuel-type">SP98</span>
                                    <span class="price-value"><?= $station->getPrixSP98() ?? "--" ?> €</span>
                                </div>

                                <div class="price-box">
                                    <span class="fuel-type">E10</span>
                                    <span class="price-value e10-color"><?= $station->getPrixE10() ?? "--" ?> €</span>
                                </div>
                            </div>
                        </div>
                        <?php endforeach; ?>
                    </div>
                </div>
            </section>
            <?php endif; ?>

            <!-- Import des fonctions utilitaires -->
            <script type="module" src="./js/helper.js"></script>
            
            <!-- Import du script principal -->
            <script type="module" src="./js/index.js"></script>

<?php
    require_once ("./include/footer.inc.php");