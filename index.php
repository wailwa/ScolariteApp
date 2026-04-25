<?php
// Projet programmation web - TP PHP & SQL
// Realise par :
// MECHAI OUIAM         KHELIL MERIEM      AKOUIRADJEMOU OUAIL ABDERRAOUF 
// 232331602210         242431575703              222231581410  
// Encadre par : Dr. LAACHEMI 

session_start();

// If already logged in, redirect to login which handles role-based routing
if (isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit();
}
?>
<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>USTHB – Gestion de Scolarité</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700;800&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">
    <link rel="stylesheet" href="index.css">
</head>
<body>

<!-- Navbar -->
<nav class="navbar">
    <div class="navbar-brand">
        <div class="brand-logo">USTHB</div>
        <div class="brand-text">
            Scolarité
            <span>Système de Gestion de Scolarité</span>
        </div>
    </div>
    <ul class="navbar-links">
        <li><a href="index.php" class="active">Accueil</a></li>
        <li><a href="#fonctionnalites">Fonctionnalités</a></li>
        <li><a href="#roles">Utilisateurs</a></li>
        <li><a href="login.php" class="nav-btn"><i class="fa fa-right-to-bracket"></i> Se connecter</a></li>
    </ul>
</nav>

<!-- Hero -->
<section class="hero">
    <div class="hero-grid">

        <!-- Left text -->
        <div class="hero-content">
            <h1>
                Gérez la <span class="highlight">scolarité</span><br>
                simplement et efficacement
            </h1>
            <p>
                Application web développée dans le cadre du module
                Programmation Web pour faciliter la gestion académique.
            </p>
            <div class="hero-actions">
                <a href="login.php" class="btn-hero-primary">
                    <i class="fa fa-right-to-bracket"></i>
                    Se connecter
                </a>
                <a href="#fonctionnalites" class="btn-hero-secondary">
                    <i class="fa fa-circle-info"></i>
                    En savoir plus
                </a>
            </div>
        </div>

        <!-- Right card -->
        <div class="hero-card">
            <div class="hero-card-title">
                <i class="fa fa-chart-pie"></i>
                Aperçu du système
            </div>
            <div class="hero-stat-row">
                <div class="hero-stat">
                    <div class="hero-stat-value">450+</div>
                    <div class="hero-stat-label">Étudiants</div>
                </div>
                <div class="hero-stat">
                    <div class="hero-stat-value">32</div>
                    <div class="hero-stat-label">Modules</div>
                </div>
                <div class="hero-stat">
                    <div class="hero-stat-value">18</div>
                    <div class="hero-stat-label">Enseignants</div>
                </div>
            </div>
            <ul class="hero-feature-list">
                <li><i class="fa fa-check-circle"></i> Gestion complète des étudiants</li>
                <li><i class="fa fa-check-circle"></i> Saisie et calcul automatique des notes</li>
                <li><i class="fa fa-check-circle"></i> Relevés de notes téléchargeables</li>
                <li><i class="fa fa-check-circle"></i> Accès sécurisé par rôle</li>
                <li><i class="fa fa-check-circle"></i> Statistiques et rapports</li>
            </ul>
        </div>

    </div>
</section>

<!-- Features -->
<section id="fonctionnalites">
    <div class="section">
        <div class="section-title">
            <h2>Fonctionnalités principales</h2>
            <p>Tout ce dont vous avez besoin pour gérer la vie académique</p>
        </div>
        <div class="features-grid">
            <div class="feature-card">
                <div class="feature-icon"><i class="fa fa-users"></i></div>
                <h3>Gestion des étudiants</h3>
                <p>Ajout, modification, suppression et recherche d'étudiants. Suivi complet du profil académique.</p>
            </div>
            <div class="feature-card">
                <div class="feature-icon"><i class="fa fa-star-half-stroke"></i></div>
                <h3>Gestion des notes</h3>
                <p>Saisie par module, calcul automatique des moyennes pondérées et statut d'admission.</p>
            </div>
            <div class="feature-card">
                <div class="feature-icon"><i class="fa fa-book"></i></div>
                <h3>Gestion des modules</h3>
                <p>Définition des modules avec coefficients et affectation aux enseignants responsables.</p>
            </div>
            <div class="feature-card">
                <div class="feature-icon"><i class="fa fa-file-lines"></i></div>
                <h3>Relevés de notes</h3>
                <p>Génération automatique des relevés officiels avec moyenne générale et statut.</p>
            </div>
            <div class="feature-card">
                <div class="feature-icon"><i class="fa fa-shield-halved"></i></div>
                <h3>Sécurité & accès</h3>
                <p>Authentification par rôle, protection contre les injections SQL et gestion des sessions.</p>
            </div>
            <div class="feature-card">
                <div class="feature-icon"><i class="fa fa-chart-bar"></i></div>
                <h3>Statistiques</h3>
                <p>Vue d'ensemble avec statistiques d'admission, répartition des notes et indicateurs clés.</p>
            </div>
        </div>
    </div>
</section>

<!-- Roles -->
<section id="roles" class="roles-section">
    <div class="roles-inner">
        <div class="section-title">
            <h2>Trois types d'utilisateurs</h2>
            <p>Chaque acteur a son espace dédié et ses fonctionnalités spécifiques</p>
        </div>
        <div class="roles-grid">
            <div class="role-card">
                <div class="role-icon"><i class="fa fa-user-shield"></i></div>
                <h3>Administrateur</h3>
                <p>Gestion complète : étudiants, enseignants, modules, notes, inscriptions et statistiques globales.</p>
            </div>
            <div class="role-card">
                <div class="role-icon"><i class="fa fa-chalkboard-user"></i></div>
                <h3>Enseignant</h3>
                <p>Consultation de ses modules, saisie et modification des notes, visualisation de la liste étudiants.</p>
            </div>
            <div class="role-card">
                <div class="role-icon"><i class="fa fa-graduation-cap"></i></div>
                <h3>Étudiant</h3>
                <p>Consultation de son profil, visualisation de ses notes et téléchargement de son relevé de notes.</p>
            </div>
        </div>
    </div>
</section>

<!-- Footer -->
<footer class="public-footer">
    &copy; <?= date('Y') ?>
    <strong>USTHB</strong> – Faculté d'Informatique –
    Module Programmation Web (PHP &amp; MySQL) –
    Encadré par <strong>Dr. LAACHEMI</strong><br>
    Realise par
    <strong>
        MECHAI OUIAM <span class="matricule">232331602210</span>,
        KHELIL MERIEM <span class="matricule">242431575703</span>,
        AKOUIRADJEMOU WAIL <span class="matricule">222231581410</span>
    </strong>
</footer>

</body>
</html>