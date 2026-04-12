<?php
    declare(strict_types=1);

    /**
     * Page d'accueil du site 
     * 
     * @author Alexandre
     * @author Tauseef
     * 
     * @version 1.0.0
     */

    $titre = "Accueil";

    $logo = "./images/favicon-carburants.svg";
    $logoBanniere = "./images/favicon-carburants.svg";
    $style = "./style/style.css";

    $auteurs = "Alexandre BURIN &amp; Tauseef AHMED";
    $description = "Page d'accueil du site internet";
    $motsCles = "Accueil, Carburants, Prix";

    $styles = "";

    require_once("./include/functions.inc.php");
    require_once("./include/header.inc.php");

?>

<?php
    require_once("./include/footer.inc.php");