/* Projet programmation web - TP PHP & SQL
  Realise par :
  MECHAI OUIAM         KHELIL MERIEM      AKOUIRADJEMOU OUAIL ABDERRAOUF 
  232331602210         242431575703              222231581410  

 Encadre par : Dr. LAACHEMI 
 */


<?php
session_start();
header("Cache-Control: no-store, no-cache, must-revalidate");
header("Pragma: no-cache");
header("Expires: 0");
if(!isset($_SESSION['user_id']) || $_SESSION['user_role'] !== 'teacher'){
    header("Location: login.php");
    exit();
}

$db_server = "localhost";
$db_user   = "root";
$db_pass   = "";
$db_name   = "Usthb_app";

$conn = mysqli_connect($db_server, $db_user, $db_pass, $db_name);

$lvlLabels = [1=>'L1', 2=>'L2', 3=>'L3', 4=>'M1', 5=>'M2'];

$teacherId = $_SESSION['user_id'];

// Récupérer les infos de l'enseignant
$queryTeacher = "SELECT t.id, t.first_name, t.last_name, t.email 
                 FROM Teachers t WHERE t.user_id = $teacherId";
$resultTeacher = mysqli_query($conn, $queryTeacher);
$teacher = mysqli_fetch_assoc($resultTeacher);
$realTeacherId = $teacher['id'];

// Compter ses modules
$queryModules = "SELECT id, code, name, coefficient, lvl FROM Modules 
                 WHERE teacher_id = $realTeacherId";
$resultModules = mysqli_query($conn, $queryModules);
$modules = [];
while($m = mysqli_fetch_assoc($resultModules)) $modules[] = $m;
$moduleCount = count($modules);

// Compter ses étudiants (par niveau de ses modules)
$lvls = array_unique(array_column($modules, 'lvl'));
$studentCount = 0;
$recentStudents = [];
if(!empty($lvls)){
    $lvlList = implode(',', $lvls);
    $queryStudents = "SELECT id, matricule, family_name, surname, lvl 
                      FROM Students WHERE lvl IN ($lvlList) ORDER BY id DESC LIMIT 5";
    $resultStudents = mysqli_query($conn, $queryStudents);
    while($s = mysqli_fetch_assoc($resultStudents)) $recentStudents[] = $s;

    $queryCount = "SELECT COUNT(*) as total FROM Students WHERE lvl IN ($lvlList)";
    $resultCount = mysqli_query($conn, $queryCount);
    $rowCount = mysqli_fetch_assoc($resultCount);
    $studentCount = $rowCount['total'];
}
?>
<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>USTHB – Tableau de Bord Enseignant</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link href="https://fonts.googleapis.com/css2?family=Syne:wght@400;600;700;800&family=DM+Sans:wght@300;400;500&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">
    <link rel="stylesheet" href="dashboard_enseignant.css">
</head>
<body>
<div class="layout">
    <aside class="sidebar">
        <div class="sidebar-brand">
            <h2>USTHB – Enseignant</h2>
            <p>Faculté d'Informatique</p>
        </div>
        <nav class="sidebar-nav">
            <a href="dashboard_enseignant.php" class="nav-item active">
                <i class="fa-solid fa-table-columns"></i> Tableau de Bord
            </a>
            <a href="mes_modules.php" class="nav-item">
                <i class="fa-solid fa-book-open"></i> Mes Modules
            </a>
            <a href="saisir_notes.php" class="nav-item">
                <i class="fa-solid fa-file-lines"></i> Saisir les Notes
            </a>
            <a href="mes_etudiants.php" class="nav-item">
                <i class="fa-solid fa-user-graduate"></i> Mes Étudiants
            </a>
        </nav>
        <div class="sidebar-footer">
            <a href="logout.php"><i class="fa-solid fa-arrow-right-from-bracket"></i> Déconnexion</a>
        </div>
    </aside>

    <main class="main">
        <div class="page-header">
            <h1>Bonjour, <?= htmlspecialchars($teacher['first_name'].' '.$teacher['last_name']) ?> 👋</h1>
            <p>Votre espace enseignant – Faculté d'Informatique</p>
        </div>

        <!-- Stats -->
        <div class="stats-grid">
            <div class="stat-card">
                <div class="stat-info">
                    <label>Mes Modules</label>
                    <strong><?= $moduleCount ?></strong>
                </div>
                <div class="stat-icon icon-blue">
                    <i class="fa-solid fa-book-open"></i>
                </div>
            </div>
            <div class="stat-card">
                <div class="stat-info">
                    <label>Mes Étudiants</label>
                    <strong><?= $studentCount ?></strong>
                </div>
                <div class="stat-icon icon-green">
                    <i class="fa-solid fa-user-graduate"></i>
                </div>
            </div>
        </div>

        <!-- Panels -->
        <div class="bottom-grid">
            <!-- Modules panel -->
            <div class="panel modules-panel">
                <div class="panel-title">Mes Modules</div>
                <div class="modules-slider-wrapper">
                    <?php if(count($modules) > 0): ?>
                        <?php foreach($modules as $m): ?>
                        <div class="module-row">
                            <div class="module-info">
                                <strong><?= htmlspecialchars($m['name']) ?></strong>
                                <span><?= htmlspecialchars($m['code']) ?> – <?= $lvlLabels[$m['lvl']] ?? 'N/A' ?></span>
                            </div>
                            <span class="badge-coef">Coef. <?= $m['coefficient'] ?></span>
                        </div>
                        <?php endforeach; ?>
                    <?php else: ?>
                        <p style="color:var(--muted);font-size:.85rem;padding:14px 0;">Aucun module assigné.</p>
                    <?php endif; ?>
                </div>
            </div>

            <!-- Étudiants récents panel -->
            <div class="panel">
                <div class="panel-title">Étudiants Récents</div>
                <?php