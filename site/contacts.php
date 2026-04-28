<?php
    declare(strict_types=1);

    /**
     * Page de mise en relation et de présentation de l'équipe OùFaireLePlein.
     * 
     * Ce script gère l'affichage dynamique des profils des collaborateurs
     * en s'appuyant sur le modèle Auteur et le Factory pour la génération
     * des données.
     * 
     * @author     Alexandre BURIN
     * @author     Tauseef AHMED
     * @version    1.0.0
     */

    /**
     * @var string Titre de la page 
     */
    $titre = "OùFaireLePlein : Contacts";
    
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
    $style = "./style/contacts.css";

    /**
     * @var string Auteurs du site
     */
    $auteurs = "Alexandre BURIN &amp; Tauseef AHMED";
    
    /**
     * @var string Description de la page
     */
    $description = "Page de contact du site internet";
    
    /**
     * @var string Mots-clés de la page
     */
    $motsCles = "Contact, Carburants, Prix";
    
    /**
     * @var string Styles supplémentaires
     */
    $styles = "";

    // Directives de configuration
    require_once(__DIR__ . "/config.php");
    
    // Inclusion du header
    require_once(__DIR__ . "/include/header.inc.php");

?>

            <section id="contacts-carburants">
                <div class="step-container">
                    <div class="step-header-main">
                        <div class="step-icon">
                            <i class="fa-solid fa-comment-dots"></i>
                        </div>
                        <div class="step-title-text">
                            <h1><strong>Contactez-nous</strong></h1>
                            <span>L’équipe OùFaireLePlein à votre service</span>
                        </div>
                    </div>

                    <div class="contact-grid">
                        <?php foreach($AUTEURS as $membre): ?><div class="contact-card">
                            <div class="card-header-contact <?php echo $membre->getClasseCouleur(); ?>">
                                <div class="initial-box"><?= $membre->getInitiale()?></div>
                                <div class="header-info">
                                    <h2><?= $membre->getPrenom()?>&nbsp;<?= $membre->getNom() ?></h2>
                                    <span><?=$membre->getRole()?></span>
                                </div>
                            </div>

                            <div class="card-content">
                                <div class="info-item">
                                    <div class="icon-wrap email-icon"><i class="fa-solid fa-envelope"></i></div>
                                    <div class="text-wrap">
                                        <dt>Email</dt>
                                        <dd><?=$membre->getEmail()?></dd>
                                    </div>
                                </div>

                                <div class="info-item">
                                    <div class="icon-wrap phone-icon"><i class="fa-solid fa-phone"></i></div>
                                    <div class="text-wrap">
                                        <dt>Téléphone</dt>
                                        <dd><?=$membre->getTelephone()?></dd>
                                    </div>
                                </div>

                                <div class="info-item">
                                    <div class="icon-wrap geo-icon"><i class="fa-solid fa-location-dot"></i></div>
                                    <div class="text-wrap">
                                        <dt>Localisation</dt>
                                        <dd><?=$membre->getLocalisation()?></dd>
                                    </div>
                                </div>
                            </div>

                            <div class="card-socials">
                                <p>Réseaux sociaux</p>
                                <div class="social-buttons">
                                    <a href="<?= $membre->getLinkedin() ?>" class="btn-linkedin"><i class="fa-brands fa-linkedin"></i> LinkedIn</a>
                                    <a href="<?= $membre->getGithub() ?>" class="btn-github"><i class="fa-brands fa-github"></i> GitHub</a>
                                </div>
                            </div>
                        </div> 
                        <?php endforeach; ?> 
                    </div>
                </div>
            </section>

            <section id="a-propos">
                <div class="step-container">
                    <div class="step-header-main">
                        <div class="step-icon">
                            <i class="fa-solid fa-question"></i>
                        </div>
                        <div class="step-title-text">
                            <h1><strong>À propos de OùFaireLePlein</strong></h1>
                            <span>Découvrez notre projet et notre engagement</span>
                        </div>
                    </div>

                    <div class="step-main-content">
                        <div class="mission-container info-card">
                            <h2>Notre mission</h2>
                            <p>
                                Fournir aux automobilistes français un accès simple et rapide aux prix des carburants 
                                pour les aider à faire des économies au quotidien.
                            </p>
                        </div>

                        <div class="technologie-container info-card">
                            <h2>Notre technologie</h2>
                            <p>
                                Une plateforme web moderne développée avec PHP 8, HTML5 et CSS3, offrant une expérience 
                                utilisateur fluide et intuitive.
                            </p>
                        </div>
                        
                        <div class="horaires-content info-card">
                            <h2>Horaires de disponibilité</h2>
                            <p>
                                Du lundi au vendredi, de 9h à 18h. Nous répondons à tous les messages dans un 
                                délai de 24 heures maximum.
                            </p>
                        </div>

                        <div class="support-tech-content info-card">
                            <h2>Support technique</h2>
                            <p>
                                Besoin d'aide ? Contactez-nous par email et notre équipe technique vous 
                                répondra dans les plus brefs délais.
                            </p>
                        </div>
                    </div>
                </div>
            </section>

<?php
    // Inclusion du footer
    require_once ("./include/footer.inc.php");