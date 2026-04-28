
/**
 * index.js - Fonctions pour la gestion du scroll
 * 
 * Ce module gère la sauvegarde et la restauration de la position du scroll
 * lors du chargement de la page pour le choix des régions, départements et villes.
 * 
 * @author Alexandre BURIN
 * @author Tauseef AHMED
 * 
 * @version 1.0.0
 */

import { saveScrollPosition } from "./helper.js";

/**
 * Restaure la position du scroll après le chargement de la page.
 *
 * Si une position a été précédemment sauvegardée dans le localStorage,
 * la page est automatiquement repositionnée à cette hauteur, puis la valeur
 * est supprimée pour éviter toute réutilisation involontaire.
 *
 * Cette fonction doit être appelée après le chargement complet du DOM
 * (ex : via window.addEventListener("load", ...)).
 *
 * @returns {void}
 */
function loadScrollPosition() {
    const scrollPosition = localStorage.getItem("scrollPosition");

    if (scrollPosition !== null) {
        window.scrollTo(0, parseInt(scrollPosition, 10));
        localStorage.removeItem("scrollPosition");
    }
}

/**
 * Attache la sauvegarde de la position du scroll aux boutons de validation
 * liés à la région, au département et à la ville.
 *
 * Cette fonction récupère les éléments correspondants dans le DOM et leur
 * associe un événement "click" permettant de sauvegarder la position actuelle
 * du scroll avant toute action (soumission, navigation ou mise à jour).
 *
 * Si un élément n’existe pas dans le DOM, il est simplement ignoré.
 *
 * @returns {void}
 */
function getElementsToSaveScrollPosition() {
    const validerRegion = document.getElementById("valider-region");
    const validerDepartement = document.getElementById("valider-departement");
    const validerVille = document.getElementById("valider-ville");

    if (validerRegion) {
        validerRegion.addEventListener("click", saveScrollPosition);
    }

    if (validerDepartement) {
        validerDepartement.addEventListener("click", saveScrollPosition);
    }

    if (validerVille) {
        validerVille.addEventListener("click", saveScrollPosition);
    }
}

// Vérifie si les éléments existent et les attache à la sauvegarde du scroll
getElementsToSaveScrollPosition();

// Charge la position du scroll après le chargement de la page
window.addEventListener("load", loadScrollPosition);
document.addEventListener("DOMContentLoaded", loadScrollPosition);
