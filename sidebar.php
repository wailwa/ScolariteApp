<?php
/* Projet programmation web - TP PHP & SQL
  Realise par :
  MECHAI OUIAM         KHELIL MERIEM      AKOUIRADJEMOU OUAIL ABDERRAOUF 
  232331602210         242431575703              222231581410  

 Encadre par : Dr. LAACHEMI 
 */





 //Menu latéral dynamique selon le rôle
// Ce fichier est inclus automatiquement par header.php
header("Cache-control:no-store,no-cache, must-revalidate");
header("Pragma: no-cache");
header("Expires: 0");

$current_page = basename($_SERVER['PHP_SELF']);
$user_role    = $_SESSION['user_role']  ?? 'etudiant';
$user_name    = $_SESSION['user_nom']   ?? 'Utilisateur';
$user_initials = strtoupper(substr($user_name, 0, 1));

// Menus selon le rôle
$menus_admin = [
  'Général' => [
    ['icon'=>'fa-gauge',          'label'=>'Tableau de bord', 'href'=>'dashboard_admin.php'],
  ],
  'Gestion' => [
    ['icon'=>'fa-users',          'label'=>'Étudiants',       'href'=>'etudiants.php'],
    ['icon'=>'fa-chalkboard-user','label'=>'Enseignants',     'href'=>'enseignants.php'],
    ['icon'=>'fa-book',           'label'=>'Modules',         'href'=>'modules.php'],
    ['icon'=>'fa-star-half-stroke','label'=>'Notes',          'href'=>'notes.php'],
    ['icon'=>'fa-clipboard-list', 'label'=>'Inscriptions',    'href'=>'inscriptions.php'],
  ],
  'Rapports' => [
    ['icon'=>'fa-chart-bar',      'label'=>'Statistiques',    'href'=>'statistiques.php'],
    ['icon'=>'fa-file-lines',     'label'=>'Relevés de notes','href'=>'releves.php'],
  ],
];

$menus_enseignant = [
  'Général' => [
    ['icon'=>'fa-gauge',          'label'=>'Tableau de bord', 'href'=>'dashboard_enseignant.php'],
  ],
  'Mon espace' => [
    ['icon'=>'fa-book',           'label'=>'Mes modules',     'href'=>'mes_modules.php'],
    ['icon'=>'fa-star-half-stroke','label'=>'Saisir les notes','href'=>'saisir_notes.php'],
    ['icon'=>'fa-users',          'label'=>'Mes étudiants',   'href'=>'mes_etudiants.php'],
  ],
];

$menus_etudiant = [
  'Général' => [
    ['icon'=>'fa-gauge',          'label'=>'Tableau de bord', 'href'=>'dashboard_etudiant.php'],
  ],
  'Mon espace' => [
    ['icon'=>'fa-user',           'label'=>'Mon profil',      'href'=>'profile.php'],
    ['icon'=>'fa-star',           'label'=>'Mes notes',       'href'=>'mes_notes.php'],
    ['icon'=>'fa-file-lines',     'label'=>'Relevé de notes', 'href'=>'releve_etudiant.php'],
  ],
];

// Sélection du menu selon le rôle
switch ($user_role) {
  case 'admin':       $menus = $menus_admin;       break;
  case 'enseignant':  $menus = $menus_enseignant;  break;
  default:            $menus = $menus_etudiant;    break;
}
?>

<aside class="sidebar" id="sidebar">

  <! Logo >
  <div class="sidebar-logo">
    <div style="width:42px;height:42px;border-radius:8px;background:white;display:flex;
                align-items:center;justify-content:center;flex-shrink:0;">
      <span style="font-weight:900;font-size:13px;color:#1a3a6b;letter-spacing:-1px;">USTHB</span>
    </div>
    <div class="logo-text">
      Scolarité
      <span>Gestion académique</span>
    </div>
  </div>

  <! Info utilisateur >
  <div class="sidebar-user">
    <div class="user-avatar"><?= htmlspecialchars($user_initials) ?></div>
    <div class="user-info">
      <div class="user-name"><?= htmlspecialchars($user_name) ?></div>
      <div class="user-role">
        <?php
        echo match($user_role) {
          'admin'      => 'Administrateur',
          'enseignant' => 'Enseignant',
          default      => 'Étudiant',
        };
        ?>
      </div>
    </div>
  </div>

  <!Navigation >
  <nav class="sidebar-nav">
    <?php foreach ($menus as $section => $items): ?>
      <div class="nav-section-title"><?= htmlspecialchars($section) ?></div>
      <?php foreach ($items as $item): ?>
        <a href="<?= htmlspecialchars($item['href']) ?>"
           class="nav-item <?= ($current_page === $item['href']) ? 'active' : '' ?>">
          <i class="fa <?= htmlspecialchars($item['icon']) ?>"></i>
          <span><?= htmlspecialchars($item['label']) ?></span>
        </a>
      <?php endforeach; ?>
    <?php endforeach; ?>

    <! Déconnexion >
    <div class="nav-section-title" style="margin-top:12px;"></div>
    <a href="logout.php" class="nav-item logout"
       onclick="return confirm('Voulez-vous vous déconnecter ?')">
      <i class="fa fa-right-from-bracket"></i>
      <span>Déconnexion</span>
    </a>
  </nav>
  <!Pied de la sidebar >
  <div class="sidebar-footer">
    USTHB – FI &copy; <?= date('Y') ?>
  </div>

</aside>