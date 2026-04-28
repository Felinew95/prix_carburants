
/**
 * theme.js - Gestion du thème (clair / sombre) du site
 *
 * Ce module permet de basculer entre le mode jour et le mode nuit.
 * Le choix de l'utilisateur est persisté via un cookie afin d'être
 * conservé lors des prochaines visites.
 *
 * Si aucun thème n'est enregistré, le thème par défaut est déterminé
 * à partir des préférences système (prefers-color-scheme).
 *
 * @author Alexandre BURIN
 * @author Tauseef AHMED
 * @version 1.0.0
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
 * Nom de la clé utilisée pour stocker le thème dans les cookies.
 * @constant {string}
 */
const CLE = 'theme-oufaireleplein';

/**
 * Récupère le thème stocké dans les cookies.
 *
 * @returns {string|null} Le thème enregistré ("jour" ou "nuit"), ou null si absent.
 */
function lireTheme() {
    const cookies = document.cookie.split('; ');
    const cookie = cookies.find(c => c.startsWith(CLE + '='));
    return cookie ? cookie.split('=')[1] : null;
}

/**
 * Enregistre le thème dans un cookie longue durée.
 *
 * @param {string} mode - Le thème à sauvegarder ("jour" ou "nuit").
 */
function sauvegarderTheme(mode) {
    document.cookie = `${CLE}=${mode}; max-age=31536000; path=/`;
}

/**
 * Applique visuellement le thème au document.
 *
 * @param {string} mode - Le thème à appliquer ("jour" ou "nuit").
 */
function appliquerTheme(mode) {
    const estNuit = mode === 'nuit';

    document.body.classList.toggle('mode-nuit', estNuit);

    if (!icone) return;

    icone.classList.toggle('fa-moon', !estNuit);
    icone.classList.toggle('fa-sun', estNuit);
}

/**
 * Initialise le thème au chargement de la page.
 *
 * Priorité :
 * 1. Cookie utilisateur
 * 2. Préférence système (dark mode)
 * 3. Mode jour par défaut
 */
function initialiserTheme() {
    const themeSauvegarde = lireTheme();

    if (themeSauvegarde) {
        appliquerTheme(themeSauvegarde);
        return;
    }

    if (window.matchMedia('(prefers-color-scheme: dark)').matches) {
        appliquerTheme('nuit');
    } else {
        appliquerTheme('jour');
    }
}

/**
 * Bascule entre le mode jour et le mode nuit.
 * Met à jour l'affichage et sauvegarde le choix.
 */
function basculerTheme() {
    const estNuit = document.body.classList.contains('mode-nuit');
    const nouveauMode = estNuit ? 'jour' : 'nuit';

    appliquerTheme(nouveauMode);
    sauvegarderTheme(nouveauMode);
}

// Initialisation
initialiserTheme();
if (bouton) {
    bouton.addEventListener('click', basculerTheme);
}