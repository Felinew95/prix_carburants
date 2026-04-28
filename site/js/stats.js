
/**
 * stats.js — Graphiques de la page statistiques OùFaireLePlein
 *
 * Ce fichier gère l'affichage des graphiques Chart.js.
 *
 * Les données viennent du PHP via une balise script JSON :
 *   <script type="application/json" id="donnees-graphique">
 *
 * Le JSON contient :
 *   - mode         : "ville" ou "national"
 *   - nomVille     : nom de la ville sélectionnée (mode ville uniquement)
 *   - prixVille    : tableau des prix de la ville [gazole, sp95, sp98, e10]
 *   - prixNational : tableau des prix nationaux   [gazole, sp95, sp98, e10]
 *
 * @author Alexandre BURIN
 * @author Tauseef AHMED
 * @version 1.0.0
 */

import {
    LIBELLES_CARBURANTS,
    DONNEES,
    COULEURS_PLEINES,
    COULEURS_TRANSPARENTES,
    optionsVille,
    optionsNational
} from './config.js';

/**
 * Crée un graphique comparant les prix des carburants 
 * entre une ville donnée et la moyenne nationale. 
 * 
 * Le graphique affiche :
 * - Une série pour la ville sélectionnée 
 * - Une série pour la moyenne nationale 
 * 
 * @param {HTMLCanvasElement} elementCanvas - Élément canvas utilisé pour afficher le graphique 
 * 
 * @returns {void} 
 */
function creerGraphiqueVille(elementCanvas) {
    const nomVille     = DONNEES.nomVille;
    const prixVille    = DONNEES.prixVille;
    const prixNational = DONNEES.prixNational;

    //On utilise une référence pour la création du graphe
    new Chart(elementCanvas, {
        type : 'bar',
        data : {
            labels   : LIBELLES_CARBURANTS,
            datasets : [
                {
                    label           : nomVille,
                    data            : prixVille,
                    backgroundColor : COULEURS_PLEINES,
                    borderRadius    : 6,
                },
                {
                    label           : 'Moyenne nationale',
                    data            : prixNational,
                    backgroundColor : COULEURS_TRANSPARENTES,
                    borderRadius    : 6,
                }
            ]
        },
        options : optionsVille
    });
}

/**
 * Crée un graphique affichant uniquement les prix moyens nationaux 
 * pour chaque type de carburant. 
 * 
 * @param {HTMLCanvasElement} elementCanvas - Élément canvas utilisé pour afficher le graphique 
 * 
 * @returns {void} 
 */
function creerGraphiqueNational(elementCanvas) {
    const prixNational = DONNEES.prixNational;

    //On utilise une référence pour la création du graphe
    new Chart(elementCanvas, {
        type : 'bar',
        data : {
            labels   : LIBELLES_CARBURANTS,
            //Paramètres de la bar pour l'affichage
            datasets : [
                {
                    label           : 'Prix moyen national (€/L)',
                    data            : prixNational,
                    backgroundColor : COULEURS_PLEINES,
                    borderRadius    : 8,
                }
            ]
        },
        options : optionsNational
    });
}

/**
 * Initialise les graphiques présents sur la page. 
 * 
 * Cette fonction : 
 * - Vérifie la présence des canvas dans le DOM 
 * - Crée le graphique correspondant si l'élément existe 
 * 
 * Permet d'éviter les erreurs si certains graphiques 
 * ne sont pas présents sur la page. 
 * 
 * @returns {void} 
 */
function selectionneurGraphique() {
    const canvasVille    = document.getElementById('graphiquePrixVille');
    const canvasNational = document.getElementById('graphiqueNational');

    if (canvasVille !== null) {
        creerGraphiqueVille(canvasVille);
    }

    if (canvasNational !== null) {
        creerGraphiqueNational(canvasNational);
    }
}

// Exécution automatique lors du chargement du DOM
document.addEventListener('DOMContentLoaded', selectionneurGraphique);
