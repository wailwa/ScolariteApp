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

if (!isset($_SESSION['user_id']) || $_SESSION['user_role'] !== 'admin') {
    header("Location: login.php");
    exit();
}

require_once "connexion.php";

$lvlLabels = [1 => 'L1', 2 => 'L2', 3 => 'L3', 4 => 'M1', 5 => 'M2'];

$pendingStudents = [
    ['family_name' => 'Benali',   'surname' => 'Youcef', 'matricule' => '232331600101', 'birth_date' => '2004-03-15', 'lvl' => 1, 'email' => 'youcef.benali@email.com'],
    ['family_name' => 'Hamdi',    'surname' => 'Rania',  'matricule' => '232331600102', 'birth_date' => '2004-07-22', 'lvl' => 1, 'email' => 'rania.hamdi@email.com'],
    ['family_name' => 'Meziane',  'surname' => 'Karim',  'matricule' => '242431600201', 'birth_date' => '2003-11-08', 'lvl' => 2, 'email' => 'karim.meziane@email.com'],
    ['family_name' => 'Aissaoui', 'surname' => 'Lina',   'matricule' => '242431600202', 'birth_date' => '2003-05-30', 'lvl' => 2, 'email' => 'lina.aissaoui@email.com'],
    ['family_name' => 'Boudiaf',  'surname' => 'Amine',  'matricule' => '222231600301', 'birth_date' => '2002-09-14', 'lvl' => 3, 'email' => 'amine.boudiaf@email.com'],
    ['family_name' => 'Cherif',   'surname' => 'Sara',   'matricule' => '222231600302', 'birth_date' => '2002-01-19', 'lvl' => 3, 'email' => 'sara.cherif@email.com'],
    ['family_name' => 'Rahmani',  'surname' => 'Bilal',  'matricule' => '212131600401', 'birth_date' => '2001-06-03', 'lvl' => 4, 'email' => 'bilal.rahmani@email.com'],
    ['family_name' => 'Sellami',  'surname' => 'Nadia',  'matricule' => '202031600501', 'birth_date' => '2000-12-25', 'lvl' => 5, 'email' => 'nadia.sellami@email.com'],
];

$remaining = 0;
foreach ($pendingStudents as $p) {
    $mat = intval($p['matricule']);
    $res = mysqli_query($conn, "SELECT id FROM Students WHERE matricule=$mat");
    if (mysqli_num_rows($res) === 0) $remaining++;
}

mysqli_close($conn);
?>
<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>USTHB – Inscriptions</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link href="https://fonts.googleapis.com/css2?family=Syne:wght@400;600;700;800&family=DM+Sans:wght@300;400;500&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">
    <link rel="stylesheet" href="inscriptions.css">
</head>
<body>
<div class="layout">

    <!-- Sidebar -->
    <aside class="sidebar">
        <div class="sidebar-brand">
            <h2>USTHB – Admin</h2>
            <p>Faculté d'Informatique</p>
        </div>
        <nav class="sidebar-nav">
            <a href="dashboard_admin.php" class="nav-item"><i class="fa-solid fa-table-columns"></i> Tableau de Bord</a>
            <a href="GestionEtudiant.php"  class="nav-item"><i class="fa-solid fa-user-graduate"></i> Gestion des Étudiants</a>
            <a href="GestionEnseignant.php" class="nav-item"><i class="fa-solid fa-chalkboard-user"></i> Gestion des Enseignants</a>
            <a href="GestionModule.php"   class="nav-item"><i class="fa-solid fa-book-open"></i> Gestion des Modules</a>
            <a href="Notes.php"           class="nav-item"><i class="fa-solid fa-file-lines"></i> Gestion des Notes</a>
            <a href="inscriptions.php"    class="nav-item active"><i class="fa-solid fa-user-plus"></i> Inscriptions</a>
        </nav>
        <div class="sidebar-footer">
            <a href="logout.php"><i class="fa-solid fa-arrow-right-from-bracket"></i> Déconnexion</a>
        </div>
    </aside>

    <!-- Main -->
    <main class="main">
        <div class="page-header">
            <h1>Inscriptions</h1>
            <p>Liste des étudiants en attente d'inscription manuelle dans le système</p>
        </div>

        <!-- Pending students panel -->
        <div class="panel">
            <div class="panel-header">
                <div>
                    <h2>Étudiants en Attente</h2>
                    <p>Cliquez sur "Inscrire" pour ouvrir la page d'inscription</p>
                </div>
                <span class="badge-count"><?= $remaining ?> restant<?= $remaining != 1 ? 's' : '' ?></span>
            </div>

            <div class="notice">
                <i class="fa-solid fa-circle-info"></i>
                <span>
                    Ces étudiants ont été acceptés à la faculté. Inscrivez-les manuellement
                    dans <strong>Gestion des Étudiants</strong> en utilisant les informations ci-dessous.
                </span>
            </div>

            <div class="pending-list">
                <?php foreach ($pendingStudents as $p):
                    $mat    = intval($p['matricule']);
                    $conn2  = mysqli_connect($db_server, $db_user, $db_pass, $db_name);
                    $exists = mysqli_num_rows(mysqli_query($conn2, "SELECT id FROM Students WHERE matricule=$mat")) > 0;
                    mysqli_close($conn2);
                ?>
                <div class="pending-row" style="<?= $exists ? 'opacity:.45;' : '' ?>">
                    <div class="pending-info">
                        <span class="pending-name">
                            <?= htmlspecialchars($p['family_name'] . ' ' . $p['surname']) ?>
                            <?php if ($exists): ?>
                                <span class="enrolled-badge">
                                    <i class="fa-solid fa-circle-check"></i> Inscrit
                                </span>
                            <?php endif; ?>
                        </span>
                        <div class="pending-meta">
                            <span><i class="fa-solid fa-id-card"></i> <?= htmlspecialchars($p['matricule']) ?></span>
                            <span><i class="fa-solid fa-envelope"></i> <?= htmlspecialchars($p['email']) ?></span>
                            <span><i class="fa-solid fa-cake-candles"></i> <?= htmlspecialchars($p['birth_date']) ?></span>
                        </div>
                    </div>
                    <span class="badge-lvl"><?= $lvlLabels[$p['lvl']] ?></span>
                    <?php if (!$exists): ?>
                        <a href="GestionEtudiant.php" class="btn-enroll">
                            <i class="fa-solid fa-user-plus"></i> Inscrire
                        </a>
                    <?php else: ?>
                        <span class="enrolled-dash">–</span>
                    <?php endif; ?>
                </div>
                <?php endforeach; ?>
            </div>
        </div>

    </main>
</div>
</body>
</html>