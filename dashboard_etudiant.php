<?php

session_start();
header("Cache-Control: no-store, no-cache, must-revalidate");
header("Pragma: no-cache");
header("Expires: 0");
if(!isset($_SESSION['user_id']) || $_SESSION['user_role'] !== 'admin'){
    header("Location: login.php");
    exit();
}

    $db_server = "localhost";
    $db_user = "root";
    $db_pass = "";
    $db_name = "Usthb_app";

    $conn = mysqli_connect($db_server, $db_user, $db_pass, $db_name);

    $lvlLabels = [1 => 'L1', 2 => 'L2', 3 => 'L3', 4 => 'M1', 5 => 'M2'];
    $loggedInUserId = $_SESSION['user_id'];

    $queryInfo = "SELECT id, matricule, surname, family_name, lvl, email, birth_date FROM Students WHERE user_id = $loggedInUserId";
    $resultInfo = mysqli_query($conn, $queryInfo);
    $studentInfo = mysqli_fetch_assoc($resultInfo);

    $studentGrades = [];
    $moyenne = null;
    $statut  = null;

    if($studentInfo){
        $studentId  = $studentInfo['id'];
        $studentLvl = $studentInfo['lvl'];

 
        $queryGrades = "SELECT g.grade, m.code, m.name, m.coefficient
                        FROM Grades g
                        JOIN Modules m ON g.module_id = m.id
                        WHERE g.student_id = $studentId AND m.lvl = $studentLvl
                        ORDER BY m.name ASC";
        $resultGrades = mysqli_query($conn, $queryGrades);
        while($g = mysqli_fetch_assoc($resultGrades)) $studentGrades[] = $g;
 

        $totalWeight = 0; $weightedSum = 0;
        foreach($studentGrades as $g){
            $weightedSum += $g['grade'] * $g['coefficient'];
            $totalWeight += $g['coefficient'];
        }
        if($totalWeight > 0){
            $moyenne = round($weightedSum / $totalWeight, 2);
            $statut  = $moyenne >= 10 ? 'Admis' : 'Recalé';
        }
    }
?>


<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>USTHB – Tableau de Bord Étudiant</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link href="https://fonts.googleapis.com/css2?family=Syne:wght@400;600;700;800&family=DM+Sans:wght@300;400;500;600&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">
    <link rel="stylesheet" href="dashboard_etudiant.css">
</head>
<body>  
<div class="layout">
    <!-- sidebar -->
    <aside class="sidebar">
        <div class="sidebar-brand">
            <h2>USTHB – Etudiant</h2>
            <p>Faculté d'Informatique</p>
        </div>
        <nav class="sidebar-nav">
            <a href="dashboard_etudiant.php" class="nav-item active">
                <i class="fa-solid fa-table-columns"></i> Tableau de Bord
            </a>
            <a href="ChangermdpEtu.php" class="nav-item">
                <i class="fa-solid fa-user-graduate"></i> Changer Mot de Passe
            </a>
        </nav>
        <div class="sidebar-footer">
            <a href="logout.php"><i class="fa-solid fa-arrow-right-from-bracket"></i> Déconnexion</a>
        </div>
    </aside>
    <div class="page">
    <!-- Page Header -->
    <div class="page-header">
        <div>
            <h1>Tableau de Bord Étudiant</h1>
            <p>Consultez vos informations et vos résultats académiques</p>
        </div>
    </div>

    <!--Student Info Card -->
    <div class="student-card">
        <div class="student-avatar">
            <i class="fa-solid fa-user"></i>
        </div>
        <div class="student-details">
            <h2 class="student-name"><?php echo $studentInfo['surname'] . ' ' . $studentInfo['family_name']; ?></h2>
            <div class="student-meta">
                <div class="meta-item">
                    <span class="meta-label">Matricule</span>
                    <span class="meta-value"><?php echo $studentInfo['matricule']; ?></span>
                </div>
                <div class="meta-item">
                    <span class="meta-label">Email</span>
                    <span class="meta-value"><?php echo $studentInfo['email']; ?></span>
                </div>
                <div class="meta-item">
                    <span class="meta-label">Niveau</span>
                    <span class="meta-value"><?php echo $lvlLabels[$studentInfo['lvl']] ?? 'N/A'; ?></span>
                </div>
                <div class="meta-item">
                    <span class="meta-label">Date de Naissance</span>
                    <span class="meta-value"><?php echo $studentInfo['birth_date']; ?></span>
                </div>
            </div>
        </div>
    </div>

    <!--Notes Panel-->
    <div class="notes-panel">

        <!-- Notes header with gradient -->
        <div class="notes-header">
            <div class="notes-header-left">
                <div class="notes-icon">
                    <i class="fa-regular fa-file-lines"></i>
                </div>
                <div>
                    <h3>Mes Notes</h3>
                    <p>Détails de vos notes par module</p>
                </div>
            </div>
            <button class="btn-download">
                <i class="fa-solid fa-download"></i> Télécharger Relevé
            </button>
        </div>

        <!-- Notes table -->
        <div class="notes-table-wrapper">
            <table class="notes-table">
                <thead>
                    <tr>
                        <th>CODE MODULE</th>
                        <th>INTITULÉ DU MODULE</th>
                        <th>COEFFICIENT</th>
                        <th>NOTE</th>
                        <th>STATUT</th>
                    </tr>
                </thead>
                <tbody>
                    <?php if(count($studentGrades) > 0): ?>
                        <?php foreach($studentGrades as $g): ?>
                        <tr>
                            <td><span class="code-badge"><?= htmlspecialchars($g['code']) ?></span></td>
                            <td class="module-name"><?= htmlspecialchars($g['name']) ?></td>
                            <td><?= $g['coefficient'] ?></td>
                            <td>
                                <span class="grade-value"><?= number_format($g['grade'], 2) ?></span>
                                <span class="grade-max">/ 20</span>
                            </td>
                            <td>
                                <?php if($g['grade'] >= 10): ?>
                                    <span class="badge-valide">✓ Validé</span>
                                <?php else: ?>
                                    <span class="badge-echec">✗ Échoué</span>
                                <?php endif; ?>
                            </td>
                        </tr>
                        <?php endforeach; ?>
                    <?php else: ?>
                        <tr><td colspan="5" style="text-align:center; color:var(--muted); padding:24px;">Aucune note disponible.</td></tr>
                    <?php endif; ?>
                </tbody>
            </table>
        </div>

        <!-- Moyenne footer -->
        <?php if($moyenne !== null): ?>
        <div class="moyenne-bar" style="background: <?= $statut === 'Admis' ? 'linear-gradient(135deg,#16a34a,#22c55e)' : 'linear-gradient(135deg,#be123c,#e11d48)' ?>">
            <div class="moyenne-bar-left">
                <span class="moyenne-title">Moyenne Générale</span>
                <span class="moyenne-sub">Résultat global</span>
            </div>
            <div class="moyenne-bar-right">
                <span class="moyenne-number"><?= $moyenne ?></span>
                <span class="moyenne-denom">/ 20</span>
                <span class="badge-admis"><?= $statut ?></span>
            </div>
        </div>
        <?php endif; ?>

    </div>
</div>
</div>



</body>
</html>