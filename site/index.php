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
     * 
     * @version 1.0.0
     */

    $titre = "OùFaireLePlein : Accueil";
    $logoBanniere = "./images/favicon-carburants.svg";
    $logo = "./images/oufaireleplein.png";
    $style = "./style/style.css";

    $auteurs = "Alexandre BURIN &amp; Tauseef AHMED";
    $description = "Page d'accueil du site internet";
    $motsCles = "Accueil, Carburants, Prix";

    $styles = "";

    require_once("./include/functions.inc.php");
    require_once("./include/header.inc.php");
    require_once("./config.php");

    // Initialisation les sélections
    $regionSel = $_GET['region'] ?? "";
    $departementSel = $_GET['departement'] ?? "";
    $villeSel = $_GET['ville'] ?? "";

    $regionSel = (string) $regionSel;
    $departementSel = (string) $departementSel;
    $villeSel = (string) $villeSel;

    // Vérifications des données 
    if (!isset(NOM_REGIONS[$regionSel])) {
        $regionSel = "";
    }
    
    if (!isset(CODE_DEPARTEMENTS[$departementSel])) {
        $departementSel = "";
    }

    // Récupération du nom de la ville sélectionné
    if (!empty($departementSel) && !empty($villeSel)) {
        $communes = getCommunes(CODE_DEPARTEMENTS[$departementSel]);
        if (!isset($communes[$villeSel])) {
            $villeSel = "";
        } else {
            $nomVilleSel = $communes[$villeSel]['nom_commune'] ?? "";
        }
    }

?>

    <section class="localisation-container">
        <p class="localisation-text">
            <i class="fa-solid fa-location-dot"></i> Localisation : 
            <?php 
                $parts = [];
                if (!empty($regionSel)) $parts[] = htmlspecialchars(NOM_REGIONS[$regionSel]);
                if (!empty($departementSel)) $parts[] = htmlspecialchars($departementSel);
                if (!empty($villeSel)) $parts[] = htmlspecialchars($nomVilleSel);
                
                echo !empty($parts) ? implode(" > ", $parts) : "Non sélectionnée";
            ?>
        </p>
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

                <map name="franceMap">
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
                <p class="form-hint">Cliquez sur une région sur la carte pour commencer votre recherche</p>
                <?php else: ?>
                <div id="selection-region">
                    <p>Région sélectionnée : <?= NOM_REGIONS[$regionSel] ?></p>
                </div>
                <?php endif; ?>
            </div>
        </div>

        <?php if(!empty($regionSel)):?>
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
                            <?= creerListeDeroulanteDepartement(CODE_REGIONS[NOM_REGIONS[$regionSel]], $departementSel) ?>
                        </select>
                    </div>

                    <div class="form-actions">
                        <button type="submit" class="btn-submit">Valider</button>
                    </div>
                </form>
            </div>
        </div>
        <?php endif; ?>

        <?php if(!empty($regionSel) && !empty($departementSel)):?>
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
                            <?= creerListeDeroulanteCommune(CODE_DEPARTEMENTS[$departementSel], $villeSel) ?>
                        </select>
                    </div>

                    <div class="form-actions">
                        <button type="submit" class="btn-submit">Valider</button>
                    </div>
                </form>
            </div>
        </div>
        <?php endif; ?>
    </section>

    <?php if (!empty($regionSel) && !empty($departementSel) && !empty($villeSel)):?>
    <section id="view-section">
        
    </section>
    <?php endif; ?>

<?php
    require_once("./include/footer.inc.php");
?>