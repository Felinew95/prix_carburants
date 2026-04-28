
/**
 * helper.js - Module de fonctions utilitaires 
 * 
 * Ce module regroupe différentes fonctions utilitaires 
 * essentielles à plusieurs endroits du site.
 * 
 * @author Alexandre BURIN
 * @author Tauseef AHMED
 * 
 * @version 1.0.0
 */

/**
 * Formate un prix en ajoutant le symbole euro et en limitant à 3 décimales
 * 
 * @param {number} valeur - Le prix à formater
 * @returns {string} Le prix formaté
 */
export function formatPrix(valeur) {
    return Number(valeur).toFixed(3) + ' €';
}

/**
 * Sauvegarde la position verticale actuelle du scroll dans le localStorage.
 *
 * La position est stockée sous la clé "scrollPosition" afin d’être restaurée
 * après le rechargement.
 *
 * @returns {void}
 */
export function saveScrollPosition() {    
    localStorage.setItem("scrollPosition", window.scrollY.toString());
}