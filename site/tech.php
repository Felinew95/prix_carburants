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

    $logo = $logoBanniere = "./images/oufaireleplein.png";
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
            <h2>Page Technique - Démonstration API</h2>

            <div class="step-infos">
                <p>Cette page démontre la maîtrise des formats d'échange JSON et XML avec des API externes.</p>
            </div>
        </section>

        <section class="Film_Ghibli">

            <h2>Infos Ghibli</h2>
            <div id="TopRow">
                <div id="ColonneGauche">
                    <div id="InfosFilms">
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

                        <dl id="infos-API-Ghibli">
                            <dt>Source API :</dt>
                            <dd><?= API_GHIBLI ?></dd>

                            <dt>Format :</dt>
                            <dd>JSON</dd>
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
                    <dt>Pays :</dt>
                    <dd><?= htmlspecialchars($infosVisiteur->getPays())?></dd>

                    <dt>Code du pays</dt>
                    <dd><?= htmlspecialchars($infosVisiteur->getCodePays())?></dd>

                    <dt>Région :</dt>
                    <dd><?= htmlspecialchars($infosVisiteur->getRegion())?></dd>

                    <dt>Code de la région :</dt>
                    <dd><?= htmlspecialchars($infosVisiteur->getCodeRegion())?></dd>

                    <dt>Ville :</dt>
                    <dd><?= htmlspecialchars($infosVisiteur->getVille())?></dd>

                    <dt>Code postal :</dt>
                    <dd><?= htmlspecialchars($infosVisiteur->getCodePostal())?></dd>

                    <dt>Latitude :</dt>
                    <dd><?php 
                        $latitude = (float) $infosVisiteur->getLatitude();
                        
                        $direction = "N";
                        if ($latitude < 0) {
                            $direction = "S";
                        }
                        
                        echo htmlspecialchars($latitude.$direction);
                    ?></dd>

                    <dt>Longitude :</dt>
                    <dd><?php 
                        $longitude = $infosVisiteur->getLongitude();
                        
                        $direction = "E";
                        if ($longitude < 0) {
                            $direction = "O";
                        }
                        
                        echo htmlspecialchars($longitude.$direction);
                    ?></dd>

                    <dt>AS :</dt>
                    <dd><?= htmlspecialchars($infosVisiteur->getAs())?></dd>

                    <dt>Nom de l'organisation AS :</dt>
                    <dd><?= htmlspecialchars($infosVisiteur->getNomAsn())?></dd>
                </dl>

                <dl id="infos-API-Geo">
                    <dt>Source API :</dt>
                    <dd><?= API_GEOLOC ?></dd>

                    <dt>Format :</dt>
                    <dd>XML</dd>
                </dl>
            </div>
        </section>

        <section class="infos-API">
            <h2>Informations Techniques</h2>
            <div id="view-infos-techniques-content">
                <div class="view-infos-techniques">
                    <div id="view-api">
                        <h3>APIs JSON utilisées</h3>
                        <ul>
                            <li>Studio Ghibli API <em>(films)</em></li>
                            <li>IP API <em>(géolocalisation)</em></li>
                        </ul>
                    </div>
    
                    <div id="view-tech">
                        <h3>Technologies utilisées</h3>
                        <ul>
                            <li>PHP 8.0 + HTML5 + CSS3</li>
                            <li>Fetch API <em>(requêtes asynchrones)</em></li>
                            <li>JSON parsing</li>
                            <li>XML parsing</li>
                        </ul>
                    </div>
                </div>
            </div>
        </section>

<?php 
    require_once("./include/footer.inc.php");