/* Projet programmation web - TP PHP & SQL
  Realise par :
  MECHAI OUIAM         KHELIL MERIEM      AKOUIRADJEMOU OUAIL ABDERRAOUF 
  232331602210         242431575703              222231581410  

 Encadre par : Dr. LAACHEMI 
 */


<?php
// la page d'accueil principale du site 
ini_set('display_errors',1);
error_reporting(E_ALL);
session_start();

// Si l`utilisateur est deja connecte,on vas directement vers le tableau de bord
if (isset($_SESSION['user_id'])) {
  switch ($_SESSION['user_role']) {
    case 'admin':      header("Location: dashboard_admin.php");    exit();
    case 'enseignant': header("Location: dashboard_enseignant.php"); exit();
    default:           header("Location: dashboard_etudiant.php");  exit();
  }
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

  <style>
    :root {
      --primary: #1E88E5;
      --primary-light: #6EC6FF;
      --accent: #00e0ff;
      --bg: #F4F9FD;
      --text: #0D2B45;
      --text-muted: #5F7D95;
      --card: #092071;
      --border: #05b1f0;
      --radius: 12px;
    }
    .matricule{
      color: #666;
    }

    * { margin:0; padding:0; box-sizing:border-box; }

    body {
      font-family: 'Poppins', sans-serif;
      background: var(--bg);
      color: var(--text);
    }

    /* la bare de navigation */
    .navbar {
      background: white;
      border-bottom: 1px solid var(--border);
      padding: 0 60px;
      height: 64px;
      display: flex;
      align-items: center;
      justify-content: space-between;
      position: sticky;
      top: 0;
      z-index: 10;
      box-shadow: 0 2px 12px rgba(0,0,0,0.05);
    }

    .navbar-brand {
      display: flex;
      align-items: center;
      gap: 14px;
    }

    .brand-logo {
      width: 42px;
      height: 42px;
      border-radius: 10px;
      background: var(--primary);
      display: flex;
      align-items: center;
      justify-content: center;
      color: white;
      font-weight: 900;
      font-size: 12px;
      letter-spacing: -0.5px;
    }

    .brand-text {
      font-size: 17px;
      font-weight: 700;
      color: var(--primary);
    }

    .brand-text span {
      display: block;
      font-size: 11px;
      font-weight: 400;
      color: var(--text-muted);
    }

    .navbar-links {
      display: flex;
      align-items: center;
      gap: 32px;
      list-style: none;
    }

    .navbar-links a {
      text-decoration: none;
      color: var(--text-muted);
      font-size: 14px;
      font-weight: 500;
      transition: color 0.2s;
    }

    .navbar-links a:hover,
    .navbar-links a.active {
      color: var(--primary);
    }

    .nav-btn {
      background: var(--primary);
      color: white !important;
      padding: 9px 22px;
      border-radius: var(--radius);
      font-weight: 600 !important;
      transition: all 0.2s !important;
    }

    .nav-btn:hover {
      background: var(--primary-light) !important;
      transform: translateY(-1px);
    }

    /* HERO */
    .hero {
      background: linear-gradient(135deg, var(--primary) 0%, #021933 60%, #04325c 100%);
      min-height: 82vh;
      display: flex;
      align-items: center;
      position: relative;
      overflow: hidden;
      padding: 60px;
    }

    .hero::before {
      content: '';
      position: absolute;
      inset: 0;
      background-image:
        radial-gradient(circle at 20% 50%, rgba(255,255,255,0.04) 0%, transparent 50%),
        radial-gradient(circle at 80% 20%, rgba(232,160,32,0.1) 0%, transparent 40%);
    }

    .hero-grid {
      display: grid;
      grid-template-columns: 1fr 1fr;
      gap: 60px;
      align-items: center;
      max-width: 1200px;
      margin: 0 auto;
      width: 100%;
      position: relative;
      z-index: 1;
    }

    .hero-content h1 {
      font-size: 44px;
      font-weight: 800;
      color: white;
      line-height: 1.15;
      margin-bottom: 20px;
    }

    .hero-content h1 .highlight {
      color: var(--accent);
    }
    .hero-content p {
      font-size: 16px;
      color: rgba(255,255,255,0.78);
      margin-bottom: 36px;
      line-height: 1.7;
      max-width: 440px;
    }

    .hero-actions {
      display: flex;
      gap: 14px;
      flex-wrap: wrap;
    }

    .btn-hero-primary {
      background: var(--accent);
      color: white;
      padding: 14px 30px;
      border-radius: var(--radius);
      font-size: 15px;
      font-weight: 700;
      text-decoration: none;
      display: inline-flex;
      align-items: center;
      gap: 8px;
      transition: all 0.25s;
      box-shadow: 0 6px 20px rgba(0,0,0,0.15);
    }

    .btn-hero-primary:hover {
      transform: translateY(-2px);
      box-shadow: 0 12px 32px rgba(232,160,32,0.45);
    }

    .btn-hero-secondary {
      background: rgba(255,255,255,0.12);
      color: white;
      padding: 14px 30px;
      border-radius: var(--radius);
      font-size: 15px;
      font-weight: 600;
      text-decoration: none;
      display: inline-flex;
      align-items: center;
      gap: 8px;
      border: 1.5px solid rgba(255,255,255,0.25);
      transition: all 0.25s;
      backdrop-filter: blur(4px);
    }

    .btn-hero-secondary:hover {
      background: rgba(255,255,255,0.2);
    }

    /*  la carte droite */
    .hero-card {
      background: rgba(169, 172, 174, 0.2);
      backdrop-filter: blur(12px);
      border: 1px solid rgba(255,255,255,0.18);
      border-radius: 20px;
      padding: 32px;
    }

    .hero-card-title {
      color: white;
      font-size: 15px;
      font-weight: 600;
      margin-bottom: 20px;
      display: flex;
      align-items: center;
      gap: 10px;
    }

    .hero-stat-row {
      display: flex;
      justify-content: space-between;
      gap: 16px;
      margin-bottom: 24px;
    }

    .hero-stat {
      text-align: center;
      flex: 1;
    }

    .hero-stat-value {
      font-size: 30px;
      font-weight: 800;
      color: var(--accent);
    }

    .hero-stat-label {
      font-size: 12px;
      color: rgba(255,255,255,0.65);
      margin-top: 4px;
    }

    .hero-feature-list {
      list-style: none;
    }

    .hero-feature-list li {
      display: flex;
      align-items: center;
      gap: 10px;
      padding: 9px 0;
      color: rgba(255,255,255,0.85);
      font-size: 13.5px;
      border-bottom: 1px solid rgba(255,255,255,0.08);
    }

    .hero-feature-list li:last-child { border-bottom: none; }

    .hero-feature-list i {
      color: var(--accent);
      width: 18px;
    }

    /* FEATURES SECTION  */
    .section {
      padding: 70px 60px;
      max-width: 1200px;
      margin: 0 auto;
    }

    .section-title {
      text-align: center;
      margin-bottom: 48px;
    }

    .section-title h2 {
      font-size: 30px;
      font-weight: 800;
      color: var(--primary);
      margin-bottom: 10px;
    }

    .section-title p {
      color: var(--text-muted);
      font-size: 15px;
    }

    .features-grid {
      display: grid;
      grid-template-columns: repeat(3, 1fr);
      gap: 24px;
    }

    .feature-card {
      background: var(--card);
      border-radius: 16px;
      padding: 28px 24px;
      border: 1px solid var(--border);
      box-shadow: 0 4px 16px rgba(0,0,0,0.06);
      transition: transform 0.25s, box-shadow 0.25s;
    }

    .feature-card:hover {
      transform: translateY(-4px);
      box-shadow: 0 12px 32px rgba(0,0,0,0.1);
    }

    .feature-icon {
      width: 52px;
      height: 52px;
      border-radius: 14px;
      background: linear-gradient(135deg, var(--primary), var(--primary-light));
      display: flex;
      align-items: center;
      justify-content: center;
      color: white;
      font-size: 22px;
      margin-bottom: 18px;
    }

    .feature-card h3 {
      font-size: 16px;
      font-weight: 700;
      color: var(--primary);
      margin-bottom: 8px;
    }

    .feature-card p {
      font-size: 13.5px;
      color: var(--text-muted);
      line-height: 1.6;
    }
    /*ROLES SECTION  */
    .roles-section {
      background: #183570;
      padding: 60px;
    }

    .roles-inner {
      max-width: 1200px;
      margin: 0 auto;
    }

    .roles-inner .section-title h2 { color: white; }
    .roles-inner .section-title p  { color: rgba(255,255,255,0.6); }

    .roles-grid {
      display: grid;
      grid-template-columns: repeat(3, 1fr);
      gap: 24px;
    }

    .role-card {
      background: rgba(255,255,255,0.08);
      border: 1px solid rgba(255,255,255,0.12);
      border-radius: 16px;
      padding: 28px 24px;
      text-align: center;
      transition: all 0.25s;
    }

    .role-card:hover {
      background: rgba(255,255,255,0.14);
      transform: translateY(-3px);
    }

    .role-icon {
      width: 64px;
      height: 64px;
      border-radius: 50%;
      background: rgba(232,160,32,0.2);
      display: flex;
      align-items: center;
      justify-content: center;
      font-size: 26px;
      color: var(--accent);
      margin: 0 auto 18px;
    }

    .role-card h3 { color: white; font-size: 17px; font-weight: 700; margin-bottom: 10px; }
    .role-card p  { color: rgba(255,255,255,0.65); font-size: 13px; line-height: 1.6; }

    /* FOOTER */
    .public-footer {
      background: #ccd5de2d;
      color: rgba(6, 7, 9, 0.72);
      text-align: center;
      padding: 24px 60px;
      font-size: 13px;
    }

    .public-footer strong { color:  #00e0ff; }

    /* RESPONSIVE */
    @media (max-width: 900px) {
      .hero-grid, .features-grid, .roles-grid {
        grid-template-columns: 1fr;
      }
      .hero { padding: 40px 24px; }
      .hero-content h1 { font-size: 30px; }
      .section { padding: 40px 24px; }
      .navbar { padding: 0 24px; }
      .navbar-links { display: none; }
      .roles-section { padding: 40px 24px; }
    }
  </style>
</head>
<body>

<! la barre de navigation >
<nav class="navbar">
  <div class="navbar-brand">
    <div class="brand-logo">USTHB</div>
    <div class="brand-text">
      Scolarité
      <span> Système de Gestion de Scolarité</span>
    </div>
  </div>

  <ul class="navbar-links">
    <li><a href="index.php" class="active">Accueil</a></li>
    <li><a href="#fonctionnalites">Fonctionnalités</a></li>
    <li><a href="#roles">Utilisateurs</a></li>
    <li><a href="about.php">À propos</a></li>
    <li><a href="login.php" class="nav-btn"><i class="fa fa-right-to-bracket"></i> Se connecter</a></li>
  </ul>
</nav>

<! HERO >
<section class="hero">
  <div class="hero-grid">

    <! Texte gauche >
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

    <! Carte droite >
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

<!  FONCTIONNALITÉS >
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

<! RÔLES >
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

<! FOOTER PUBLIC >
<footer class="public-footer">
  &copy; <?= date('Y') ?>
  <strong>USTHB</strong> – Faculté d'Informatique –
  Module Programmation Web (PHP &amp; MySQL) –
  Encadré par <strong>Dr. LAACHEMI</strong><br>
  Realise par <strong> MECHAI OUIAM  <span class="matricule">232331602210 </span> , KHELIL MERIEM  <span class="matricule"> 242431575703 </span> , AKOUIRADJEMOU WAIL  <span class="matricule"> 222231581410 </span> </strong>

</footer>

</body>
</html>
