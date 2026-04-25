<?php
    declare(strict_types=1);

    /**
     * Module de footer du site
     * 
     * Génère le pied de page avec informations de copyright et auteurs.
     * Ferme les balises HTML principales (main, body, html).
     * 
     * @author Alexandre & Tauseef
     * @version 1.0.0
     */

    // Configuration de la timezone
    if (!date_default_timezone_get()) {
        date_default_timezone_set("Europe/Paris");
    }

    // Récupération de l'année actuelle
    $anneeActuelle = (int) date("Y");

?>
        </main>

        <footer>
            <div class="footer-text-group">
                <span>© Tous droits réservés - <?= $anneeActuelle ?></span>
                <span>Alexandre &amp; Tauseef</span>
            </div>
            <div class="footer-links">
                <a href="./tech.php">Page Tech</a>
            </div>
        </footer>

    </body>
</html>