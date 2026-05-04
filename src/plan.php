<?php
    declare(strict_types=1);

    /**
     * Page du plan du site
     * 
     * Ce module regroupe la structure HTML de la page du plan du site.
     * Il permet à l'utilisateur de se repérer dans le site.
     * 
     * @author Alexandre BURIN
     * @author Tauseef AHMED
     * 
     * @version 1.0.0
     */

    /**
     * @var string Titre de la page 
     */
    $titre = "OùFaireLePlein : Plan";
    
    /**
     * @var string Logo de la bannière
     */
    $logoBanniere = "./images/favicon-carburants.svg";
    
    /**
     * @var string Logo du site
     */
    $logo = "./images/oufaireleplein.png";
    
    /**
     * @var string Style de la page
     */
    $style = "./style/plan.css";

    /**
     * @var string Auteurs du site
     */
    $auteurs = "Alexandre BURIN &amp; Tauseef AHMED";
    
    /**
     * @var string Description de la page
     */
    $description = "Page du plan du site internet";
    
    /**
     * @var string Mots-clés de la page
     */
    $motsCles = "Plan, Carburants, Prix";

    /**
     * @var string Styles suplémentaires 
     */
    $styles = "#plan {
                    display: flex;
                    flex-direction: column;
                    gap: 0;
                    padding: 40px;
                    max-width: 1100px;
                    margin: 0 auto;
                }

                #plan h2 {
                    font-size: 1.4rem;
                    color: var(--secondary-color);
                    margin: 25px 0 15px 0;
                    padding-bottom: 8px;
                    border-bottom: 2px solid var(--bg-light);
                    box-shadow: none;
                    text-align: left;
                }";

    // Inclusion du header
    require_once("./include/header.inc.php");
?>

            <section id="plan">
                <div class="plan-header">
                    <i class="fa-solid fa-map"></i> 
                    <h1>Plan du site</h1>
                </div>

                <div class="plan-content">
                    <p>
                        Ce plan du site vous permet de retrouver facilement l'ensemble des différentes pages disponibles sur le site
                        et de naviguer rapidement entre les différentes fonctionnalités. Vous y trouverez l'accès aux recherches de 
                        stations-service, aux statistiques de prix <em>(accessibles après sélection d'une ville)</em> ainsi qu'aux informations générales du projet.
                    </p>

                    <div id="acces-rapide">
                        <h2>Accès rapide</h2>
                        
                        <ul>
                            <li><a href="./index.php">Recherche de stations-service</a></li>
                            <li><a href="./stats.php">Statistiques de prix</a></li>
                            <li><a href="./contacts.php">Contact</a></li>
                        </ul>
                    </div>
                </div>
            </section>

<?php

    // Inclusion du footer 
    require_once("./include/footer.inc.php");
