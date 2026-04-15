<?php 

    /**
     * Page "tech" du site internet 
     * 
     * @author Alexandre 
     * @author Tauseef
     * 
     * @version 1.0.0
     */

    declare(strict_types=1);

    $titre = "OùFaireLePlein : Tech";

    $logo = $logoBanniere = "./images/engrenage_tech_logo.svg";
    $style = "./style/tech.css";

    $auteurs = "Alexandre BURIN &amp; Tauseef AHMED";
    $description = "Page Technique du site internet";
    $motsCles = "Tech, Ghibli, Géolocalisation";

    $styles = "";

    require_once("./include/functions.inc.php");
    require_once("./include/header.inc.php");

    $infosFilmAleatoire = getInfosFilm();
    $infosVisiteur = getInfosVisiteur();
    
?>

        <section class="infos-API">
            <div class="step-header">
                <div class="step-number">
                    <span></span>
                </div>
                <h2>Page Technique - Démonstration API</h2>
            </div>

            <p>
                Cette page démontre la maîtrise des formats d'échange JSON et XML avec des API externes
            </p>
        </section>

        <section class="Film_Ghibli">

            <h2>Infos Ghibli</h2>
            <div id="TopRow">
                <div id="ColonneGauche">
                    <div id="InfosFilms">
                        <h3>Informations sur le film : </h3>

                        <dl>
                            <dt>Titre :</dt>
                            <dd><?= htmlspecialchars($infosFilmAleatoire->getTitre()) ?></dd>

                            <dt>Titre original :</dt>
                            <dd lang="ja"><?= htmlspecialchars($infosFilmAleatoire->getTitreOriginal()) ?></dd>

                            <dt>Titre romanisé :</dt>
                            <dd><?= htmlspecialchars($infosFilmAleatoire->getTitreOriginalRomanise()) ?></dd>

                            <dt>Réalisateur :</dt>
                            <dd><?= htmlspecialchars($infosFilmAleatoire->getRealisateur()) ?></dd>

                            <dt>Producteur :</dt>
                            <dd><?= htmlspecialchars($infosFilmAleatoire->getProducteur()) ?></dd>

                            <dt>Date de sortie :</dt>
                            <dd><?= htmlspecialchars($infosFilmAleatoire->getDateSortie()) ?></dd>

                            <dt>Durée :</dt>
                            <dd><?= $infosFilmAleatoire->getDuree() ?> min</dd>
                        </dl>
                    </div>

                    <figure id="Banner">
                        <img src="<?= htmlspecialchars($infosFilmAleatoire->getBanniere()) ?>"
                            alt="Bannière du film <?= htmlspecialchars($infosFilmAleatoire->getTitre()) ?>"
                            loading="lazy" />
                        <figcaption>Bannière : <?= htmlspecialchars($infosFilmAleatoire->getTitre()) ?></figcaption>
                    </figure>
                </div>

                <figure id="Couverture">
                    <img src="<?= htmlspecialchars($infosFilmAleatoire->getImage()) ?>"
                        alt="Affiche du film <?= htmlspecialchars($infosFilmAleatoire->getTitre()) ?>"
                        loading="lazy" />
                    <figcaption>Affiche : <?= htmlspecialchars($infosFilmAleatoire->getTitre()) ?></figcaption>
                </figure>

            </div>

            <div id="Description">
                <h3 style="text-decoration-line: underline;">Description <em>(en anglais)</em></h3>
                <p><?= htmlspecialchars($infosFilmAleatoire->getDescription()) ?></p>
            </div>
        </section>

        <section class="geolocatisation">
            <h2>Géolocalisation</h2>

            <div id="geolocalisationInfos">
                <h3>Informations de l'utilisateur : </h3>

                <dl>
                    <dt>IP <em>(IPv6)</em> :</dt>
                    <dd><?= htmlspecialchars($infosVisiteur->getIP())?></dd>

                    <dt>Pays :</dt>
                    <dd><?= htmlspecialchars($infosVisiteur->getPays())?></dd>

                    <dt>Continent :</dt>
                    <dd><?= htmlspecialchars($infosVisiteur->getContinent())?></dd>

                    <dt>Code Pays :</dt>
                    <dd><?= htmlspecialchars($infosVisiteur->getCodePays())?></dd>

                    <dt>Code Continent :</dt>
                    <dd><?= htmlspecialchars($infosVisiteur->getCodeContinent())?></dd>

                    <dt>ASN :</dt>
                    <dd><?= htmlspecialchars($infosVisiteur->getASN())?></dd>

                    <dt>Nom de l'organisation ASN :</dt>
                    <dd><?= htmlspecialchars($infosVisiteur->getNomAsn())?></dd>

                    <dt>Domaine associé :</dt>
                    <dd><?= htmlspecialchars($infosVisiteur->getDomaineAsn())?></dd>
                </dl>
            </div>
        </section>

<?php 
    require_once("./include/footer.inc.php");