<?php
session_start();
require_once 'includes/db.php';

// Récupérer les actualités
$stmt = $pdo->query("SELECT * FROM actualites ORDER BY date_publication DESC LIMIT 3");
$actualites = $stmt->fetchAll();
?>
<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>TechSolutions - Services Informatiques</title>
    <link rel="stylesheet" href="css/styles.css">
</head>
<body>
    <!-- Header -->
    <header id="header">
        <div class="container">
            <div class="header-content">
                <a href="index.php" class="logo">
                    <img src="images/logo.png" alt="TechSolutions" class="logo-svg">
                </a>
                <nav>
                    <ul>
                        <li><a href="#accueil">Accueil</a></li>
                        <li><a href="#services">Services</a></li>
                        <li><a href="#a-propos">À Propos</a></li>
                        <li><a href="#actualites">Actualités</a></li>
                        <li><a href="#contact">Contact</a></li>
                    </ul>
                    <div class="auth-buttons">
                        <?php if (isset($_SESSION['client_id'])): ?>
                            <a href="client/dashboard.php" class="btn btn-outline">Mon Compte</a>
                            <a href="client/logout.php" class="btn btn-primary">Déconnexion</a>
                        <?php else: ?>
                            <a href="client/login.php" class="btn btn-outline">Connexion Client</a>
                        <?php endif; ?>
                    </div>
                </nav>
            </div>
        </div>
    </header>

    <!-- Hero Section -->
    <section class="hero" id="accueil">
        <div class="container">
            <div class="hero-content">
                <span class="hero-tag">Innovation • Excellence • Performance</span>
                <h1>Votre Partenaire<br>Digital de Confiance</h1>
                <p class="hero-subtitle">
                    TechSolutions accompagne votre transformation numérique avec des solutions sur mesure. 
                    De l'infrastructure IT au développement logiciel, nous construisons votre réussite digitale.
                </p>
                <div class="hero-buttons">
                    <a href="#contact" class="btn-hero btn-hero-primary">Demander un Devis</a>
                    <a href="#services" class="btn-hero btn-hero-secondary">Nos Services</a>
                </div>
            </div>
        </div>
    </section>

    <!-- Stats Section -->
    <section class="stats-section">
        <div class="container">
            <div class="stats-grid">
                <div class="stat-item">
                    <div class="stat-number">50+</div>
                    <div class="stat-label">Collaborateurs</div>
                </div>
                <div class="stat-item">
                    <div class="stat-number">200+</div>
                    <div class="stat-label">Projets Réalisés</div>
                </div>
                <div class="stat-item">
                    <div class="stat-number">15</div>
                    <div class="stat-label">Ans d'Expérience</div>
                </div>
                <div class="stat-item">
                    <div class="stat-number">98%</div>
                    <div class="stat-label">Clients Satisfaits</div>
                </div>
            </div>
        </div>
    </section>

    <!-- Services Section -->
    <section class="services-section" id="services">
        <div class="container">
            <div class="section-header">
                <span class="section-tag">Nos Expertises</span>
                <h2 class="section-title">Services Informatiques<br>Complets</h2>
                <p class="section-subtitle">
                    Une gamme complète de services pour répondre à tous vos besoins IT
                </p>
            </div>
            <div class="services-grid">
                <div class="service-card">
                    <div class="service-number">01</div>
                    <h3>Développement Logiciel</h3>
                    <p>Applications sur mesure, sites web, applications mobiles. Notre équipe de 15 développeurs experts crée des solutions adaptées à vos besoins spécifiques.</p>
                    <div class="service-team">15 Experts • Full Stack</div>
                </div>
                <div class="service-card">
                    <div class="service-number">02</div>
                    <h3>Infrastructure IT</h3>
                    <p>Mise en place et maintenance d'infrastructures réseau et serveurs sécurisées. Solutions complètes pour votre système d'information.</p>
                    <div class="service-team">5 Ingénieurs • Réseau & Sécurité</div>
                </div>
                <div class="service-card">
                    <div class="service-number">03</div>
                    <h3>Design UX/UI</h3>
                    <p>Interfaces utilisateur attractives et fonctionnelles. Design thinking et expérience utilisateur au cœur de chaque projet.</p>
                    <div class="service-team">5 Designers • UX/UI</div>
                </div>
                <div class="service-card">
                    <div class="service-number">04</div>
                    <h3>Cybersécurité</h3>
                    <p>Protection complète de vos données et systèmes. Audit, conseil et mise en place de solutions de sécurité avancées.</p>
                    <div class="service-team">Sécurité • Conformité</div>
                </div>
                <div class="service-card">
                    <div class="service-number">05</div>
                    <h3>Support Client</h3>
                    <p>Assistance technique réactive et efficace. Notre équipe de 5 experts vous accompagne au quotidien pour garantir la continuité de vos activités.</p>
                    <div class="service-team">5 Techniciens • 24/7</div>
                </div>
                <div class="service-card">
                    <div class="service-number">06</div>
                    <h3>Conseil & Formation</h3>
                    <p>Accompagnement stratégique et montée en compétences. Formations personnalisées pour vos équipes sur nos solutions.</p>
                    <div class="service-team">Experts • Sur Mesure</div>
                </div>
            </div>
        </div>
    </section>

    <!-- About Section -->
    <section class="about-section" id="a-propos">
        <div class="container">
            <div class="about-grid">
                <div class="about-image"></div>
                <div class="about-content">
                    <span class="section-tag">Notre Entreprise</span>
                    <h2>Innovation & Excellence depuis 2009</h2>
                    <p>
                        TechSolutions est une entreprise de services informatiques basée à Brive-la-Gaillarde, 
                        spécialisée dans l'accompagnement des entreprises dans leur transformation digitale.
                    </p>
                    <p>
                        Avec une équipe de 50 collaborateurs passionnés et expérimentés, nous mettons notre expertise 
                        au service de votre réussite. De la conception à la maintenance, nous assurons un suivi 
                        personnalisé de chaque projet.
                    </p>
                    <p>
                        TechSolutions est fière de sa culture inclusive, mettant un point d'honneur à accueillir et 
                        à soutenir les personnes en situation de handicap, en adaptant les postes de travail pour 
                        répondre à leurs besoins.
                    </p>
                    <div class="about-features">
                        <div class="feature-item">
                            <div class="feature-icon">✓</div>
                            <div class="feature-text">
                                <h4>Expertise Reconnue</h4>
                                <p>15 ans d'expérience dans tous les domaines de l'IT</p>
                            </div>
                        </div>
                        <div class="feature-item">
                            <div class="feature-icon">✓</div>
                            <div class="feature-text">
                                <h4>Approche Sur Mesure</h4>
                                <p>Solutions adaptées à vos besoins spécifiques</p>
                            </div>
                        </div>
                        <div class="feature-item">
                            <div class="feature-icon">✓</div>
                            <div class="feature-text">
                                <h4>Engagement Qualité</h4>
                                <p>98% de satisfaction client</p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- News Section -->
    <section class="news-section" id="actualites">
        <div class="container">
            <div class="section-header">
                <span class="section-tag">Actualités</span>
                <h2 class="section-title">Nos Dernières<br>Nouvelles</h2>
                <p class="section-subtitle">
                    Restez informés de nos projets, innovations et tendances du secteur
                </p>
            </div>
            <div class="news-grid">
                <?php if (!empty($actualites)): ?>
                    <?php foreach ($actualites as $actu): ?>
                        <div class="news-card">
                            <div class="news-date"><?php echo date('d M Y', strtotime($actu['date_publication'])); ?></div>
                            <h3><?php echo htmlspecialchars($actu['titre']); ?></h3>
                            <p><?php echo htmlspecialchars(substr($actu['contenu'], 0, 150)); ?>...</p>
                        </div>
                    <?php endforeach; ?>
                <?php else: ?>
                    <div class="news-card">
                        <div class="news-date">10 DÉC 2025</div>
                        <h3>Nouveau Partenariat Stratégique</h3>
                        <p>TechSolutions annonce un partenariat avec un leader européen du cloud computing pour offrir des solutions encore plus performantes.</p>
                    </div>
                    <div class="news-card">
                        <div class="news-date">05 DÉC 2025</div>
                        <h3>Innovation en Cybersécurité</h3>
                        <p>Découvrez notre nouvelle solution de protection avancée contre les cybermenaces, développée par notre équipe R&D.</p>
                    </div>
                    <div class="news-card">
                        <div class="news-date">01 DÉC 2025</div>
                        <h3>Prix de l'Innovation 2025</h3>
                        <p>TechSolutions récompensée pour son engagement dans l'accessibilité numérique et l'innovation sociale.</p>
                    </div>
                <?php endif; ?>
            </div>
        </div>
    </section>

    <!-- Contact Section -->
    <section class="contact-section" id="contact">
        <div class="container">
            <div class="section-header">
                <span class="section-tag">Contactez-nous</span>
                <h2 class="section-title">Démarrez Votre<br>Projet Aujourd'hui</h2>
            </div>
            <div class="contact-grid">
                <div class="contact-info">
                    <h3>Nos Coordonnées</h3>
                    <div class="contact-item">
                        <div class="contact-label">Adresse</div>
                        <div class="contact-value">12 rue des Innovateurs<br>19100 Brive-la-Gaillarde</div>
                    </div>
                    <div class="contact-item">
                        <div class="contact-label">Téléphone</div>
                        <div class="contact-value">05 55 17 38 00</div>
                    </div>
                    <div class="contact-item">
                        <div class="contact-label">Email</div>
                        <div class="contact-value">contact@techsolutions.fr</div>
                    </div>
                    <div class="contact-item">
                        <div class="contact-label">Horaires</div>
                        <div class="contact-value">Lun-Ven : 9h00 - 18h00</div>
                    </div>
                </div>
                <div class="contact-form">
                    <?php if (isset($_GET['success'])): ?>
                        <div class="message success-message" style="display: block;">
                            Votre message a été envoyé avec succès ! Nous vous répondrons dans les plus brefs délais.
                        </div>
                    <?php endif; ?>
                    <?php if (isset($_GET['error'])): ?>
                        <div class="message error-message" style="display: block;">
                            Une erreur s'est produite. Veuillez réessayer.
                        </div>
                    <?php endif; ?>
                    <form action="includes/contact.php" method="POST">
                        <div class="form-group">
                            <label for="nom">Nom complet *</label>
                            <input type="text" id="nom" name="nom" required>
                        </div>
                        <div class="form-group">
                            <label for="email">Email *</label>
                            <input type="email" id="email" name="email" required>
                        </div>
                        <div class="form-group">
                            <label for="telephone">Téléphone</label>
                            <input type="tel" id="telephone" name="telephone">
                        </div>
                        <div class="form-group">
                            <label for="sujet">Sujet *</label>
                            <select id="sujet" name="sujet" required>
                                <option value="">Sélectionnez un sujet</option>
                                <option value="devis">Demande de devis</option>
                                <option value="information">Demande d'information</option>
                                <option value="support">Support technique</option>
                                <option value="partenariat">Partenariat</option>
                                <option value="autre">Autre</option>
                            </select>
                        </div>
                        <div class="form-group">
                            <label for="message">Message *</label>
                            <textarea id="message" name="message" required></textarea>
                        </div>
                        <div class="form-group">
                            <label>
                                <input type="checkbox" name="rgpd" required>
                                J'accepte que mes données soient utilisées dans le cadre de ma demande. *
                            </label>
                        </div>
                        <button type="submit" class="btn-submit">Envoyer le message</button>
                    </form>
                </div>
            </div>
        </div>
    </section>

    <!-- Footer -->
    <footer>
        <div class="container">
            <div class="footer-content">
                <div class="footer-section">
                    <h3>À propos</h3>
                    <p>Votre partenaire digital de confiance depuis 2009. Innovation, excellence et performance au service de votre réussite.</p>
                </div>
                <div class="footer-section">
                    <h3>Services</h3>
                    <ul class="footer-links">
                        <li><a href="#services">Développement</a></li>
                        <li><a href="#services">Infrastructure IT</a></li>
                        <li><a href="#services">Design UX/UI</a></li>
                        <li><a href="#services">Cybersécurité</a></li>
                        <li><a href="#services">Support</a></li>
                    </ul>
                </div>
                <div class="footer-section">
                    <h3>Entreprise</h3>
                    <ul class="footer-links">
                        <li><a href="#a-propos">À Propos</a></li>
                        <li><a href="#actualites">Actualités</a></li>
                        <li><a href="#contact">Contact</a></li>
                        <li><a href="mentions-legales.php">Mentions Légales</a></li>
                        <li><a href="confidentialite.php">Confidentialité</a></li>
                    </ul>
                </div>
                <div class="footer-section">
                    <h3>Contact</h3>
                    <ul class="footer-links">
                        <li>12 rue des Innovateurs</li>
                        <li>19100 Brive-la-Gaillarde</li>
                        <li>05 55 17 38 00</li>
                        <li>contact@techsolutions.fr</li>
                    </ul>
                </div>
            </div>
            <div class="footer-bottom">
                <p>&copy; 2025 TechSolutions. Tous droits réservés. | <a href="admin/login.php">Administration</a></p>
            </div>
        </div>
    </footer>

    <script src="js/main.js"></script>
</body>
</html>
