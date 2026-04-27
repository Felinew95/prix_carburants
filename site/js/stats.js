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

/**
 * Liste des carburants utilisés pour l'affichage des graphiques.
 * L'ordre est important car il correspond aux couleurs et aux données associées.
 * 
 * @constant {string[]}
 */
const LIBELLES_CARBURANTS = ['Gazole', 'SP95', 'SP98', 'E10'];

/**
 * Données brutes récupérées depuis le DOM.
 * Elles doivent être stockées au format JSON dans un élément HTML
 * ayant l'identifiant 'donnees-graphique'.
 * 
 * @constant {Object}
 */
const donnees = JSON.parse(
    document.getElementById('donnees-graphique').textContent
);

/**
 * Couleurs principales utilisées pour les graphiques (bordures, lignes).
 * Chaque couleur correspond à un carburant dans LIBELLES_CARBURANTS.
 * 
 * @constant {string[]}
 */
const COULEURS_PLEINES = [
    '#D97706',  // Gazole — orange
    '#1D4ED8',  // SP95   — bleu
    '#B91C1C',  // SP98   — rouge
    '#047857',  // E10    — vert
];

/**
 * Couleurs transparentes utilisées pour les fonds (remplissage des graphiques).
 * Correspondent aux couleurs pleines avec une opacité réduite.
 * 
 * @constant {string[]}
 */
const COULEURS_TRANSPARENTES = [
    'rgba(245, 158, 11, 0.3)',  // Gazole transparent
    'rgba(59, 130, 246, 0.3)',  // SP95   transparent
    'rgba(239, 68, 68, 0.3)',   // SP98   transparent
    'rgba(16, 185, 129, 0.3)',  // E10    transparent
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
const optionsVille = {
    responsive: true,
    plugins: {
        legend: { position: 'bottom' },
        tooltip: {
            callbacks: {
                /**
                 * Formate le contenu du tooltip pour chaque point.
                 * 
                 * @param {Object} contexte - Contexte fourni par Chart.js
                 * @returns {string}
                 */
                label: function (contexte) {
                    const valeur = contexte.parsed.y;

                    if (valeur === null) {
                        return contexte.dataset.label + ' : données indisponibles';
                    }

                    return contexte.dataset.label + ' : ' + valeur.toFixed(3) + ' €';
                }
            }
        }
    },
    scales: {
        x: {
            title: {
                display: true,
                text: 'Carburant',
                font: { weight: 'bold' }
            }
        },
        y: {
            beginAtZero: false,
            title: {
                display: true,
                text: 'Prix',
                font: { weight: 'bold' }
            },
            ticks: {
                /**
                 * Formate les valeurs de l'axe Y en euros.
                 * 
                 * @param {number|string} valeur
                 * @returns {string}
                 */
                callback: function (valeur) {
                    return Number(valeur).toFixed(3) + ' €';
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
const optionsNational = {
    responsive: true,
    plugins: {
        legend: { display: false },
        tooltip: {
            callbacks: {
                /**
                 * Formate le tooltip pour les données nationales.
                 * 
                 * @param {Object} contexte
                 * @returns {string}
                 */
                label: function (contexte) {
                    const valeur = contexte.parsed.y;

                    if (valeur === null) {
                        return 'Données indisponibles';
                    }

                    return 'Prix moyen : ' + valeur.toFixed(3) + ' €';
                }
            }
        }
    },
    scales: {
        x: {
            title: {
                display: true,
                text: 'Carburant',
                font: { weight: 'bold' }
            }
        },
        y: {
            beginAtZero: false,
            title: {
                display: true,
                text: 'Prix',
                font: { weight: 'bold' }
            },
            ticks: {
                /**
                 * Formate les valeurs de l'axe Y en euros.
                 * 
                 * @param {number|string} valeur
                 * @returns {string}
                 */
                callback: function (valeur) {
                    return Number(valeur).toFixed(3) + ' €';
                }
            }
        }
    }
};

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
    const nomVille     = donnees.nomVille;
    const prixVille    = donnees.prixVille;
    const prixNational = donnees.prixNational;

    // Création du graphique via Chart.js
    new Chart(elementCanvas, {
        type: 'bar',
        data: {
            labels: LIBELLES_CARBURANTS,
            datasets: [
                {
                    label: nomVille,
                    data: prixVille,
                    backgroundColor: COULEURS_PLEINES,
                    borderRadius: 6,
                },
                {
                    label: 'Moyenne nationale',
                    data: prixNational,
                    backgroundColor: COULEURS_TRANSPARENTES,
                    borderRadius: 6,
                }
            ]
        },
        options: optionsVille
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
    const prixNational = donnees.prixNational;

    // Création du graphique via Chart.js
    new Chart(elementCanvas, {
        type: 'bar',
        data: {
            labels: LIBELLES_CARBURANTS,
            datasets: [
                {
                    label: 'Prix moyen national (€/L)',
                    data: prixNational,
                    backgroundColor: COULEURS_PLEINES,
                    borderRadius: 8,
                }
            ]
        },
        options: optionsNational
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


document.addEventListener('DOMContentLoaded', selectionneurGraphique);