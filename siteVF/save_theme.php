<?php
    declare(strict_types=1);

    /**
     * save_theme.php - Sauvegarde le thème choisi par l'utilisateur via un cookie.
     * Appelé en arrière plan par fetch() depuis theme.js
     *
     * @author Alexandre BURIN
     * @author Tauseef AHMED
     * @version 1.0.0
     */

    if (isset($_GET['theme']) && in_array($_GET['theme'], ['jour', 'nuit'])) {
        setcookie('theme_prefere', $_GET['theme'], time() + (30 * 24 * 3600), '/');
    }