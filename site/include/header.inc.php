<?php

    /**
     * Module qui permet de créer le header du site 
     * 
     * @author Alexandre & Tauseef
     * @version 1.0.0
     */

    declare(strict_types=1);

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
                    <li><a href="./index.php"><img src="<?=$logo?>" id="logo" alt="Logo" /></a></li>
                    <li><a href="./stats.php"><i class="fas fa-chart-bar"></i> Statistiques</a></li>
                    <li><a href="./contact.php"><i class="fas fa-envelope"></i> Contact</a></li>
                </ul>
            </nav>
        </header>

        <main>