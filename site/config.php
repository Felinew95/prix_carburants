<?php
    declare(strict_types=1);

    /**
     * Configuration centrale du site.
     *
     * Ce fichier regroupe les constantes globales utilisées dans l'application :
     * - chemins de fichiers
     * - durée de cache
     * - URL d'API
     * - correspondances entre régions, slugs et codes
     *
     * @author Alexandre
     * @version 1.1.0
     */

    require_once(__DIR__ . "/classes/ObjectFactory.php");

    /**
     * Chemin du fichier JSON utilisé pour mettre en cache la liste des films Ghibli.
     * Le cache évite de recharger les données depuis l'API à chaque requête.
     */
    const CACHE_GHIBLI = __DIR__ . '/cache/filmsGhibli.json';

    /**
     * Chemin du fichier JSON utilisé pour mettre en cache la liste des infos des visiteurs.
     * Le cache évite de recharger les données depuis l'API à chaque requête.
     */
    const CACHE_GEOLOC = __DIR__ . '/cache/infosVisiteur.xml';

    /**
     * Durée de validité du cache Ghibli, en secondes.
     * Ici, 30 jours = 30 * 24 * 60 * 60.
     */
    const TEMPS_CACHE_GHIBLI = 30 * 24 * 60 * 60;

    /**
     * Durée de validité du cache Géolocalisation, en secondes
     * Ici, 10 minutes = 10 * 60
     */
    const TEMPS_CACHE_GEOLOC = 10 * 60;

    /**
     * URL de l'API publique utilisée pour récupérer les films Ghibli.
     * Cette URL sert de source de données principale avant mise en cache.
     */
    const API_GHIBLI = 'https://ghibliapi.vercel.app/films';

    /**
     * URL de l'API publique utilisée pour récupérer les informations géographiques des visiteurs.
     * Cette URL sert de source de données principale avant mise en cache.
     * 
     * Il y a le droit à 45 requêtes par minutes 
     */
    const API_GEOLOC = "http://ip-api.com";

    /**
     * Dossier contenant les fichiers CSV liés aux départements.
     * On stocke ici les fichiers de données à lire côté PHP.
     */
    const FICHIER_DEPARTEMENTS = __DIR__ . '/csv/departements-france.csv';

    /**
     * Dossier contenant les fichiers CSV liés aux villes.
     * On stocke ici les fichiers de données à lire côté PHP.
     */
    const FICHIER_COMMUNES = __DIR__ . '/csv/villes-france.csv';

    /**
     * Tableau associatif des régions françaises.
     *
     * Clé = nom de la région
     * Valeur = code officiel de la région
     */
    const CODE_REGIONS = [
        'Île-de-France' => '11',
        'Centre-Val de Loire' => '24',
        'Bourgogne-Franche-Comté' => '27',
        'Normandie' => '28',
        'Hauts-de-France' => '32',
        'Grand Est' => '44',
        'Pays de la Loire' => '52',
        'Bretagne' => '53',
        'Nouvelle-Aquitaine' => '75',
        'Occitanie' => '76',
        'Auvergne-Rhône-Alpes' => '84',
        'Provence-Alpes-Côte d\'Azur' => '93',
        'Corse' => '94',
    ];

    /**
     * Tableau de correspondance entre un identifiant URL simple et le nom affiché.
     *
     * Clé = slug sans accents ni caractères spéciaux, pratique dans les URLs
     * Valeur = nom complet de la région à afficher à l'utilisateur
     */
    const NOM_REGIONS = [
        'hauts-de-france' => 'Hauts-de-France',
        'normandie' => 'Normandie',
        'ile-de-france' => 'Île-de-France',
        'grand-est' => 'Grand Est',
        'bretagne' => 'Bretagne',
        'pays-de-la-loire' => 'Pays de la Loire',
        'centre-val-de-loire' => 'Centre-Val de Loire',
        'bourgogne-franche-comte' => 'Bourgogne-Franche-Comté',
        'nouvelle-aquitaine' => 'Nouvelle-Aquitaine',
        'auvergne-rhone-alpes' => 'Auvergne-Rhône-Alpes',
        'occitanie' => 'Occitanie',
        'provence-alpes-cote-dazur' => 'Provence-Alpes-Côte d\'Azur',
        'corse' => 'Corse',
    ];

    /**
     * Tableau de correspondance entre un nom de département et le code département.
     *
     * Clé = Nom de département 
     * Valeur = Code département 
     */
    const CODE_DEPARTEMENTS = [
        'Ain' => '01',
        'Aisne' => '02',
        'Allier' => '03',
        'Alpes-de-Haute-Provence' => '04',
        'Hautes-Alpes' => '05',
        'Alpes-Maritimes' => '06',
        'Ardèche' => '07',
        'Ardennes' => '08',
        'Ariège' => '09',
        'Aube' => '10',
        'Aude' => '11',
        'Aveyron' => '12',
        'Bouches-du-Rhône' => '13',
        'Calvados' => '14',
        'Cantal' => '15',
        'Charente' => '16',
        'Charente-Maritime' => '17',
        'Cher' => '18',
        'Corrèze' => '19',
        'Corse-du-Sud' => '2A',
        'Haute-Corse' => '2B',
        'Côte-d\'Or' => '21',
        'Côtes-d\'Armor' => '22',
        'Creuse' => '23',
        'Dordogne' => '24',
        'Doubs' => '25',
        'Drôme' => '26',
        'Eure' => '27',
        'Eure-et-Loir' => '28',
        'Finistère' => '29',
        'Gard' => '30',
        'Haute-Garonne' => '31',
        'Gers' => '32',
        'Gironde' => '33',
        'Hérault' => '34',
        'Ille-et-Vilaine' => '35',
        'Indre' => '36',
        'Indre-et-Loire' => '37',
        'Isère' => '38',
        'Jura' => '39',
        'Landes' => '40',
        'Loir-et-Cher' => '41',
        'Loire' => '42',
        'Haute-Loire' => '43',
        'Loire-Atlantique' => '44',
        'Loiret' => '45',
        'Lot' => '46',
        'Lot-et-Garonne' => '47',
        'Lozère' => '48',
        'Maine-et-Loire' => '49',
        'Manche' => '50',
        'Marne' => '51',
        'Haute-Marne' => '52',
        'Mayenne' => '53',
        'Meurthe-et-Moselle' => '54',
        'Meuse' => '55',
        'Morbihan' => '56',
        'Moselle' => '57',
        'Nièvre' => '58',
        'Nord' => '59',
        'Oise' => '60',
        'Orne' => '61',
        'Pas-de-Calais' => '62',
        'Puy-de-Dôme' => '63',
        'Pyrénées-Atlantiques' => '64',
        'Hautes-Pyrénées' => '65',
        'Pyrénées-Orientales' => '66',
        'Bas-Rhin' => '67',
        'Haut-Rhin' => '68',
        'Rhône' => '69',
        'Haute-Saône' => '70',
        'Saône-et-Loire' => '71',
        'Sarthe' => '72',
        'Savoie' => '73',
        'Haute-Savoie' => '74',
        'Paris' => '75',
        'Seine-Maritime' => '76',
        'Seine-et-Marne' => '77',
        'Yvelines' => '78',
        'Deux-Sèvres' => '79',
        'Somme' => '80',
        'Tarn' => '81',
        'Tarn-et-Garonne' => '82',
        'Var' => '83',
        'Vaucluse' => '84',
        'Vendée' => '85',
        'Vienne' => '86',
        'Haute-Vienne' => '87',
        'Vosges' => '88',
        'Yonne' => '89',
        'Territoire de Belfort' => '90',
        'Essonne' => '91',
        'Hauts-de-Seine' => '92',
        'Seine-Saint-Denis' => '93',
        'Val-de-Marne' => '94',
        'Val-d\'Oise' => '95',
    ];

    /**
     * Lien URL vers l'API du gouvernement des prix des carburants 
     */
    const API_CARBURANTS = "https://data.economie.gouv.fr/api/explore/v2.1/catalog/datasets/prix-des-carburants-en-france-flux-instantane-v2/records";

    /**
     * Rayon de la terre en km pour le calcul de la distance entre deux stations
     */
    const RAYON_TERRE = 6371;

    /**
     * Représente les auteurs du site 
     */
    $AUTEURS = [
        ObjectFactory::createAuteur("BURIN", "Alexandre", "Co-fondateur &amp; Développeur", "alexandreburin50@gmail.com", "+33 6 32 89 20 30", "Cergy-Pontoise, France" , "https://www.linkedin.com/in/alexandre-burin-73b05a2b6/" , "https://github.com/Felinew95", "color-1"),
        ObjectFactory::createAuteur("AHMED", "Tauseef", "Co-fondateur &amp; Développeur", "tauseef2006200620062006@gmail.com", "+33 6 58 14 36 89", "Cergy-Pontoise, France", "#" , "https://github.com/goldkashezada95-hash", "color-2")
    ];