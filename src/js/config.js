
/**
 * config.js - Configuration principale des constantes 
 * 
 * Ce module contient toutes les constantes utilisées dans la réalisation du site.
 * Il est utilisé notamment dans l'affichage des graphiques dans la page statistiques.
 * 
 * @author Alexandre BURIN
 * @author Tauseef AHMED
 * 
 * @version 1.0.0
 */

import { formatPrix } from './helper.js';

/** 
 * Liste des carburants utilisés pour l'affichage des graphiques. 
 * L'ordre est important car il correspond aux couleurs et aux données associées. 
 * 
 * @constant {string[]}
 */
export const LIBELLES_CARBURANTS = ['Gazole', 'SP95', 'SP98', 'E10'];

/**
 * Données brutes récupérées depuis le DOM.
 * Elles doivent être stockées au format JSON dans un élément HTML
 * ayant l'identifiant 'donnees-graphique'.
 * 
 * @constant {Object}
 */
export const DONNEES = JSON.parse(document.getElementById('donnees-graphique').textContent);

/** 
 * Couleurs principales utilisées pour les graphiques (bordures, lignes). 
 * Chaque couleur correspond à un carburant dans LIBELLES_CARBURANTS. 
 * 
 * @constant {string[]} 
 */
export const COULEURS_PLEINES = [
    '#f59e0b',  // Gazole — orange
    '#3b82f6',  // SP95   — bleu
    '#ef4444',  // SP98   — rouge
    '#10b981',  // E10    — vert
];

/**
 * Couleurs transparentes utilisées pour les fonds (remplissage des graphiques). 
 * Correspondent aux couleurs pleines avec une opacité réduite. 
 * 
 * @constant {string[]} 
 */
export const COULEURS_TRANSPARENTES = [
    'rgba(245, 158, 11, 0.3)',  // Gazole transparent
    'rgba(59,  130, 246, 0.3)', // SP95   transparent
    'rgba(239,  68,  68, 0.3)', // SP98   transparent
    'rgba(16,  185, 129, 0.3)', // E10    transparent
];

/**
 * Options de configuration pour un graphique de prix par ville. 
 * Utilisé avec Chart.js. 
 * 
 * - Affiche une légende en bas 
 * - Formate les tooltips avec le prix en euros 
 * - Gère les valeurs nulles (données indisponibles) 
 * - Personnalise les axes (titres + format des ticks) 
 * 
 * @constant {Object} 
 */
export const optionsVille = {
    responsive : true,
    plugins    : {
    legend  : { position: 'bottom' },
    tooltip : {
    callbacks : {
    label : function(contexte) {
                const valeur = contexte.parsed.y;
                if (valeur === null) {
                    return contexte.dataset.label + ' : données indisponibles';
                }
                return contexte.dataset.label + ' : ' + formatPrix(valeur);
            }
        }
    }
    },
    scales : {
        x : {
            title : { display: true, text: 'Carburant', font: { weight: 'bold' } }
        },
        y : {
            beginAtZero : false,
            title       : { display: true, text: 'Prix', font: { weight: 'bold' } },
            ticks       : {
                callback : function(valeur) {
                    return formatPrix(valeur);
                }
            }
        }
    }
};

/**
 * Options de configuration pour un graphique national (prix moyens). 
 * 
 * Différences avec optionsVille :
 * - Pas de légende affichée 
 * - Tooltip simplifié 
 * 
 * @constant {Object} 
 */
export const optionsNational = {
    responsive : true,
    plugins    : {
        legend  : { display: false },
        tooltip : {
            callbacks : {
                label : function(contexte) {
                    const valeur = contexte.parsed.y;
                    if (valeur === null) return 'Données indisponibles';
                    return 'Prix moyen : ' + formatPrix(valeur);
                }
            }
        }
    },
    scales : {
        x : {
            title : { display: true, text: 'Carburant', font: { weight: 'bold' } }
        },
        y : {
            beginAtZero : false,
            title       : { display: true, text: 'Prix', font: { weight: 'bold' } },
            ticks       : {
                callback : function(valeur) {
                    return formatPrix(valeur);
                }
            }
        }
    }
};

