/* Projet programmation web - TP PHP & SQL
  Realise par :
  MECHAI OUIAM         KHELIL MERIEM      AKOUIRADJEMOU OUAIL ABDERRAOUF 
  232331602210         242431575703              222231581410  

 Encadre par : Dr. LAACHEMI 
 */



<?php
//  En-tête de chaque page (après connexion)
// Inclure ce fichier en haut de chaque page protégée.
// Exemple : require_once 'header.php';

// Vérification de session  
// session_start();
// if (!isset($_SESSION['user_id'])) {
//     header("Location: login.php");
//     exit();
// }

//définir $page_title avant d'inclure ce fichier
$page_title  = $page_title  ?? 'Tableau de bord';
$breadcrumb  = $breadcrumb  ?? [];
$user_name   = $_SESSION['user_nom']    ?? 'Utilisateur';
$user_role   = $_SESSION['user_role']   ?? 'utilisateur';
$user_initials = strtoupper(substr($user_name, 0, 1));
?>
<!DOCTYPE html>
<html lang="fr">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title><?= htmlspecialchars($page_title) ?> – USTHB Scolarité</title>

  <! Google Fonts >
  <link rel="preconnect" href="https://fonts.googleapis.com">
  <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700&display=swap" rel="stylesheet">

  <! Font Awesome   >
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">

  <! Feuille de style principale  >
  <link rel="stylesheet" href="style.css">
</head>
<body>
<div class="wrapper">

  <! SIDEBAR >
  <?php require_once 'sidebar.php'; ?>

  <!  CONTENU PRINCIPAL >
  <div class="main-content">

    <!HEADER >
    <header class="header">
    

      <!Gauche : titre + fil d'Ariane >
      <div class="header-left">
        <div>
          <div class="header-title"><?= htmlspecialchars($page_title) ?></div>
          <div style="font-size:12px; color:#888;  margin-to:5px;">
          <?php if (!empty($breadcrumb)): ?>
          <nav class="breadcrumb" aria-label="Fil d'Ariane">
            <a href="index.php"><i class="fa fa-home"></i></a>
            <?php foreach ($breadcrumb as $label => $url): ?>
              <span>/</span>
              <?php if ($url): ?>
                <a href="<?= htmlspecialchars($url) ?>"><?= htmlspecialchars($label) ?></a>
              <?php else: ?>
                <span><?= htmlspecialchars($label) ?></span>
              <?php endif; ?>
            <?php endforeach; ?>
          </nav>
          <?php endif; ?>
        </div>
      </div>

      <! Droite : notifications + profil >
      <div class="header-right">

        <!Notifications >
        <button class="header-btn" title="Notifications" onclick="toggleNotifications()">
          <i class="fa fa-bell"></i>
          <span class="badge-dot"></span>
        </button>

        <!Paramètres >
        <button class="header-btn" title="Paramètres" onclick="window.location='settings.php'">
          <i class="fa fa-gear"></i>
        </button>

        <!Avatar utilisateur >
        <div class="header-user" style="display:flex;align-items:center;gap:10px;cursor:pointer;"
             onclick="window.location='profile.php'">
          <div class="user-avatar" style="width:36px;height:36px;font-size:13px;">
            <?= htmlspecialchars($user_initials) ?>
          </div>
          <div style="line-height:1.3;">
            <div style="font-size:13px;font-weight:600;color:var(--text);">
              <?= htmlspecialchars($user_name) ?>
            </div>
            <div style="font-size:11px;color:var(--text-muted);text-transform:capitalize;">
              <?= htmlspecialchars($user_role) ?>
            </div>
          </div>
        </div>

      </div>
    </header>
    <! FIN HEADER >

    <!MESSAGES FLASH >
    <?php if (isset($_SESSION['flash_success'])): ?>
      <div class="alert alert-success" style="margin:16px 28px 0;">
        <i class="fa fa-check-circle"></i>
        <?= htmlspecialchars($_SESSION['flash_success']) ?>
      </div>
      <?php unset($_SESSION['flash_success']); ?>
    <?php endif; ?>
    <?php if (isset($_SESSION['flash_error'])): ?>
      <div class="alert alert-danger" style="margin:16px 28px 0;">
        <i class="fa fa-triangle-exclamation"></i>
        <?= htmlspecialchars($_SESSION['flash_error']) ?>
      </div>
      <?php unset($_SESSION['flash_error']); ?>
    <?php endif; ?>

    <! DÉBUT DU CONTENU DE PAGE  >
    <div class="page-content">