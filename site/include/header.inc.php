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
        <meta name="author" content= "<?= $auteurs ?>" />
        <meta name="description" content= "<?= $description ?>" />
        <meta name="keywords" content= "<?= $motsCles ?>" />
    </head>

    <body>
        <header>
            <nav class="nav_bar">
                <ul class="nav_menu">
                    <li><a href="./index.php"><img src="<?=$logo?>" id="logo" alt="Logo" /></a></li>
                    <li><a href="./index.php">Carburants</a></li>
                    <li><a href="./contact.php">Contact</a></li>
                </ul>
            </nav>
        </header>

        <main>