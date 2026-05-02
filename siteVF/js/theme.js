/**
 * theme.js - Gestion du thème (clair / sombre) du site
 *
 * Ce module permet de basculer entre le mode jour et le mode nuit.
 * Le changement est instantané (sans rechargement de page).
 * La sauvegarde est effectuée côté serveur via save_theme.php
 *
 * @author Alexandre BURIN
 * @author Tauseef AHMED
 * @version 2.0.0
 */

/**
 * Bouton permettant de changer le thème.
 * @type {HTMLElement|null}
 */
const bouton = document.getElementById('btn-theme');

/**
 * Icône affichant l'état actuel du thème.
 * @type {HTMLElement|null}
 */
const icone = document.getElementById('icone-theme');

/**
 * Applique visuellement le thème au document.
 *
 * @param {string} mode - Le thème à appliquer ("jour" ou "nuit").
 */
function appliquerTheme(mode) {
    const estNuit = mode === 'nuit';

    document.body.classList.toggle('mode-nuit', estNuit);
    document.body.classList.toggle('mode-jour', !estNuit);

    if (!icone) return;

    icone.classList.toggle('fa-moon', !estNuit);
    icone.classList.toggle('fa-sun', estNuit);
}

/**
 * Sauvegarde le thème côté serveur via fetch.
 * PHP enregistre le cookie sans recharger la page.
 *
 * @param {string} mode - Le thème à sauvegarder ("jour" ou "nuit").
 */
function sauvegarderTheme(mode) {
    fetch('save_theme.php?theme=' + mode);
}

/**
 * Bascule entre le mode jour et le mode nuit.
 * Met à jour l'affichage et sauvegarde le choix côté serveur.
 */
function basculerTheme() {
    const estNuit = document.body.classList.contains('mode-nuit');
    const nouveauMode = estNuit ? 'jour' : 'nuit';

    appliquerTheme(nouveauMode);
    sauvegarderTheme(nouveauMode);
}

if (bouton) {
    bouton.addEventListener('click', basculerTheme);
}