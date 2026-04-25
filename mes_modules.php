<?php
/* Projet programmation web - TP PHP & SQL
  Realise par :
  MECHAI OUIAM         KHELIL MERIEM      AKOUIRADJEMOU OUAIL ABDERRAOUF 
  232331602210         242431575703              222231581410  

 Encadre par : Dr. LAACHEMI 
 */

session_start();
header("Cache-Control: no-store, no-cache, must-revalidate");
header("Pragma: no-cache");
header("Expires: 0");

if (!isset($_SESSION['user_id']) || $_SESSION['user_role'] !== 'teacher') {
    header("Location: login.php");
    exit();
}

$db_server = "localhost";
$db_user   = "root";
$db_pass   = "";
$db_name   = "Usthb_app";

$conn = mysqli_connect($db_server, $db_user, $db_pass, $db_name);

$lvlLabels = [1 => 'L1', 2 => 'L2', 3 => 'L3', 4 => 'M1', 5 => 'M2'];

$teacherUserId = $_SESSION['user_id'];

// Fetch teacher info
$queryTeacher = "SELECT t.id, t.first_name, t.last_name
                 FROM Teachers t WHERE t.user_id = $teacherUserId";
$resultTeacher = mysqli_query($conn, $queryTeacher);
$teacher = mysqli_fetch_assoc($resultTeacher);
$realTeacherId = $teacher['id'];

// Fetch modules assigned to this teacher, with student count per level
$queryModules = "SELECT m.id, m.code, m.name, m.coefficient, m.lvl,
                        COUNT(DISTINCT s.id) AS student_count
                 FROM Modules m
                 LEFT JOIN Students s ON s.lvl = m.lvl
                 WHERE m.teacher_id = $realTeacherId
                 GROUP BY m.id
                 ORDER BY m.lvl ASC, m.name ASC";
$resultModules = mysqli_query($conn, $queryModules);
$modules = [];
while ($m = mysqli_fetch_assoc($resultModules)) $modules[] = $m;
?>
<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>USTHB – Mes Modules</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link href="https://fonts.googleapis.com/css2?family=Syne:wght@400;600;700;800&family=DM+Sans:wght@300;400;500&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">
    <link rel="stylesheet" href="dashboard_enseignant.css">
    <link rel="stylesheet" href="mes_modules.css">
    
</head>
<body>
<div class="layout">

    <!-- Sidebar -->
    <aside class="sidebar">
        <div class="sidebar-brand">
            <h2>USTHB – Enseignant</h2>
            <p>Faculté d'Informatique</p>
        </div>
        <nav class="sidebar-nav">
            <a href="dashboard_enseignant.php" class="nav-item">
                <i class="fa-solid fa-table-columns"></i> Tableau de Bord
            </a>
            <a href="mes_modules.php" class="nav-item active">
                <i class="fa-solid fa-book-open"></i> Mes Modules
            </a>
            <a href="saisir_notes.php" class="nav-item">
                <i class="fa-solid fa-file-lines"></i> Saisir les Notes
            </a>
            <a href="mes_etudiants.php" class="nav-item">
                <i class="fa-solid fa-user-graduate"></i> Mes Étudiants
            </a>
            <a href="ChangermdpEns.php" class="nav-item">
                <i class="fa-solid fa-key"></i> Changer Mot de Passe
            </a>
        </nav>
        <div class="sidebar-footer">
            <a href="logout.php"><i class="fa-solid fa-arrow-right-from-bracket"></i> Déconnexion</a>
        </div>
    </aside>

    <!-- Main -->
    <main class="main">
        <div class="page-header">
            <h1>Mes Modules</h1>
            <p>Liste des modules dont vous êtes responsable</p>
        </div>

        <?php if (count($modules) > 0): ?>
            <div class="modules-grid">
                <?php foreach ($modules as $m): ?>
                <div class="module-card">
                    <div class="module-card-header">
                        <span class="module-code"><?= htmlspecialchars($m['code']) ?></span>
                        <span class="module-lvl"><?= $lvlLabels[$m['lvl']] ?? 'N/A' ?></span>
                    </div>
                    <h3><?= htmlspecialchars($m['name']) ?></h3>
                    <div class="module-card-footer">
                        <span class="module-meta">
                            <i class="fa-solid fa-user-graduate"></i>
                            <?= $m['student_count'] ?> étudiant<?= $m['student_count'] != 1 ? 's' : '' ?>
                        </span>
                        <span class="badge-coef">Coef. <?= $m['coefficient'] ?></span>
                    </div>
                </div>
                <?php endforeach; ?>
            </div>
        <?php else: ?>
            <div class="panel" style="text-align:center; color:var(--muted); padding: 48px;">
                <i class="fa-solid fa-book-open" style="font-size:2rem; margin-bottom:12px; display:block;"></i>
                Aucun module ne vous est assigné pour le moment.
            </div>
        <?php endif; ?>
    </main>
</div>
</body>
</html>