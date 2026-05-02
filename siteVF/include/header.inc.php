<?php
    declare(strict_types=1);

    /**
     * header.inc.php - Génère l'en-tête principal du site.
     * Lit le cookie de thème pour appliquer le bon mode au chargement.
     *
     * @author Alexandre BURIN
     * @author Tauseef AHMED
     * @version 2.0.0
     */

    /**
     * Thème courant (jour ou nuit) lu depuis le cookie 'theme_prefere'
     * Valeur par défaut: 'jour'
     */
    $themeActuel = $_COOKIE['theme_prefere'] ?? 'jour';
    
    /**
     * Icône correspondant au thème actuel
     * 'fa-moon' pour le mode jour, 'fa-sun' pour le mode nuit
     */
    $iconeTheme = ($themeActuel === 'jour') ? 'fa-moon' : 'fa-sun';

?>
<!DOCTYPE html>
<html lang="fr">
    <head>
        <meta charset="UTF-8" />
        <meta name="viewport" content="width=device-width, initial-scale=1.0" />

        <title><?= $titre ?></title>

        <link rel="stylesheet" href="style/theme.css"/>
        <link rel="stylesheet" href="style/common.css" />
        <link rel="stylesheet" href="<?= $style ?>" />

        <link rel="icon" type="image/svg+xml" href="<?= $logoBanniere ?>" />
        <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css" />

        <meta name="author" content="<?= $auteurs ?>" />
        <meta name="description" content="<?= $description ?>" />
        <meta name="keywords" content="<?= $motsCles ?>" />

        <style><?= $styles ?></style>
    </head>

    <body class="mode-<?= htmlspecialchars($themeActuel) ?>">
        <header>
            <nav class="nav_bar">
                <ul class="nav_menu">
                    <li><a href="./index.php"><img src="<?= $logo ?>" id="logo" alt="Logo du site internet" /></a></li>
                    <li><a href="./stats.php"><i class="fas fa-chart-bar"></i> Statistiques</a></li>
                    <li><a href="./contacts.php"><i class="fas fa-envelope"></i> Contact</a></li>
                </ul>

                <button id="btn-theme" class="btn-theme" aria-label="Basculer le thème">
                    <i class="fa-solid <?= $iconeTheme ?>" id="icone-theme"></i>
                </button>
            </nav>
        </header>

        <main>