<?php
    declare(strict_types=1);

    /**
     * Génère et gère l'affichage de l'en-tête (header) principal du site.
     * Responsable de l'affichage de la navigation principale et de l'état de connexion.
     *
     * @author Alexandre
     * @author Tauseef
     * 
     * @version 1.0.0
     */

?>
<!DOCTYPE html>
<html lang="fr">
    <head>
        <meta charset="UTF-8" />
        <meta name="viewport" content="width=device-width, initial-scale=1.0" />
            
        <title><?= $titre ?></title>

        <link rel="stylesheet" href="<?= $style ?>" />
        <link rel="icon" type="image/svg+xml" href="<?= $logoBanniere ?>" />
        <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
        
        <meta name="author" content= "<?= $auteurs ?>" />
        <meta name="description" content= "<?= $description ?>" />
        <meta name="keywords" content= "<?= $motsCles ?>" />
    </head>

    <body>
        <header>
            <nav class="nav_bar">
                <ul class="nav_menu">
                    <li><a href="./index.php"><img src="<?=$logo?>" id="logo" alt="Logo du site internet" /></a></li>
                    <li><a href="./stats.php"><i class="fas fa-chart-bar"></i> Statistiques</a></li>
                    <li><a href="./contacts.php"><i class="fas fa-envelope"></i> Contact</a></li>
                </ul>
            </nav>
        </header>

        <main>