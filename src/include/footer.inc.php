<?php
    declare(strict_types=1);

    /**
     * footer.php - Génère le pied de page du site
     * 
     * Génère le pied de page avec informations de copyright et auteurs.
     * Ferme les balises HTML principales (main, body, html).
     * 
     * @author Alexandre & Tauseef
     * @version 1.0.0
     */

    // Directive requise pour la date 
    require_once(__DIR__."/helper.inc.php");

    // Configuration de la timezone
    defineDateTime();

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
                <a href="./plan.php">Plan du site</a>
            </div>

            <script src="./js/theme.js" defer></script>
        </footer>
    </body>
</html>