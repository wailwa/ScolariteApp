/* Projet programmation web - TP PHP & SQL
  Realise par :
  MECHAI OUIAM         KHELIL MERIEM      AKOUIRADJEMOU OUAIL ABDERRAOUF 
  232331602210         242431575703              222231581410  

 Encadre par : Dr. LAACHEMI 
 */



<?php

session_start();
header("Cache-Control: no-store, no-cache, must-revalidate");
header("Cache-Control: post-check=0, pre-check=0",false);
header("Pragma: no-cache");
header("Expires: Sat,26 Jul 1997 05:00:00 GMT");
if(!isset($_SESSION['user_id']) || $_SESSION['user_role'] !== 'admin'){
    header("Location: login.php");
    exit();
}

    $db_server = "localhost";
    $db_user = "root";
    $db_pass = "";
    $db_name = "Usthb_app";

    $conn = mysqli_connect($db_server, $db_user, $db_pass, $db_name);

    $lvlLabels = [1 => 'L1', 2 => 'L2', 3 => 'L3', 4 => 'M1', 5 => 'M2']; //affecter chaque valeur a un niveau puisque les valeurs sont INT dans la base de données

    //compter le nombre de students
    $queryStudents = "SELECT * FROM Students";
    $resultStudents = mysqli_query($conn, $queryStudents);
    $studentCounter = mysqli_num_rows($resultStudents);

    //compter le nombre de modules
    $queryModules = "SELECT * FROM Modules";
    $resultModules = mysqli_query($conn, $queryModules);
    $moduleCounter = mysqli_num_rows($resultModules);
    
    //compter le nombre de teachers
    $queryTeachers = "SELECT * FROM Teachers";
    $resultTeachers = mysqli_query($conn, $queryTeachers);
    $teacherCounter = mysqli_num_rows($resultTeachers);

    //sauvegarder le nom, code et coefficient de chaque module dans $result
    $sql = "SELECT `name`, code, coefficient FROM Modules";
    $result = mysqli_query($conn, $sql);

    //sauvegarder le matricule, family_name, name, id, lvl de chaque student dans $resultRecentStudents dans l'ordre decroissant par id
    $queryRecentStudents = "SELECT matricule, family_name, surname, id, lvl FROM Students ORDER BY id DESC LIMIT 5";
    $resultRecentStudents = mysqli_query($conn, $queryRecentStudents);
?>


<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>USTHB – Tableau de Bord Admin</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link href="https://fonts.googleapis.com/css2?family=Syne:wght@400;600;700;800&family=DM+Sans:wght@300;400;500&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">
    <link rel="stylesheet" href="dashboard_admin.css">
    
    <script> 
    window.onpageshow =function(event) {
       if(event.persisted){
        window.location.reload()
       }
}
    </script>
</head>
<body>
<div class="layout">
    <!-- sidebar -->
    <aside class="sidebar">
        <div class="sidebar-brand">
            <h2>USTHB – Admin</h2>
            <p>Faculté d'Informatique</p>
        </div>
        <nav class="sidebar-nav">
            <a href="dashboard_admin.php" class="nav-item active">
                <i class="fa-solid fa-table-columns"></i> Tableau de Bord
            </a>
            <a href="GestionEtudiant.php" class="nav-item">
                <i class="fa-solid fa-user-graduate"></i> Gestion des Étudiants
            </a>
            <a href="GestionEnseignant.php" class="nav-item">
                <i class="fa-solid fa-chalkboard-user"></i> Gestion des Enseignants
            </a>
            <a href="GestionModule.php" class="nav-item">
                <i class="fa-solid fa-book-open"></i> Gestion des Modules
            </a>
            <a href="Notes.php" class="nav-item">
                <i class="fa-solid fa-file-lines"></i> Gestion des Notes
            </a>
            <a href="inscriptions.php" class="nav-item">
                <i class="fa-solid fa-user-plus"></i> Inscriptions
            </a>
        </nav>
        <div class="sidebar-footer">
            <a href="logout.php"><i class="fa-solid fa-arrow-right-from-bracket"></i> Déconnexion</a>
        </div>
    </aside>

    <!-- header -->
    <main class="main">
        <div class="page-header">
            <h1>Tableau de Bord Administrateur</h1>
            <p>Vue d'ensemble de la gestion universitaire</p>
        </div>

        <!-- ecrire le contenu des variable compteurs from php-->
        <div class="stats-grid">
            <div class="stat-card">
                <div class="stat-info">
                    <label>Nombre total d'étudiants</label>
                    <strong><?=$studentCounter?></strong>
                </div>
                <div class="stat-icon icon-blue">
                    <i class="fa-solid fa-user-group"></i>
                </div>
            </div>
            <div class="stat-card">
                <div class="stat-info">
                    <label>Nombre de modules</label>
                    <strong><?=$moduleCounter?></strong>
                </div>
                <div class="stat-icon icon-green">
                    <i class="fa-solid fa-book-open"></i>
                </div>
            </div>
            <div class="stat-card">
                <div class="stat-info">
                    <label>Nombre d'enseignants</label>
                    <strong><?=$teacherCounter?></strong>
                </div>
                <div class="stat-icon icon-purple">
                    <i class="fa-solid fa-graduation-cap"></i>
                </div>
            </div>
        </div>

        <!-- recent students panel et modules panel-->
        <div class="bottom-grid">
            <div class="panel">
                <!-- recent students-->
                <div class="panel-title">Étudiants Récents</div>
                <!-- mettre les informations des etudiants recent dans le panel -->
                <?php while($student = mysqli_fetch_assoc($resultRecentStudents)): ?> <!-- une boucle qui vas itérer pour chaque ligne dans la table student -->
                <div class="student-row">
                    <div class="student-info">
                        <strong><?= htmlspecialchars($student['family_name']) ?> <?= htmlspecialchars($student['surname']) ?></strong>
                        <span><?= htmlspecialchars($student['matricule']) ?></span>
                    </div>
                    <span class="badge-level"><?= $lvlLabels[$student['lvl']] ?? 'N/A' ?></span> <!-- si $lvllabels est null, on ecrit N/A -->
                </div>
                <?php endwhile; ?> <!-- boucle finit -->
                
                <!-- si la table des etudiants est vide, on ecrit qu'aucun etudiant est trouvé -->
                <?php if(mysqli_num_rows($resultRecentStudents) === 0): ?>
                    <p style="color: var(--muted); font-size: .85rem; padding: 14px 0;">Aucun étudiant trouvé.</p>
                <?php endif; ?>
            </div>
            
            <!-- panel des modules -->
            <div class="panel modules-panel">
                <div class="panel-title">Modules</div>
                <div class="modules-slider-wrapper">
                    <?php while($module = mysqli_fetch_assoc($result)): ?> <!-- itérer pour chaque ligne dans la table -->
                    <div class="module-row">
                        <div class="module-info">
                            <strong><?= htmlspecialchars($module['name']) ?></strong>
                            <span><?= htmlspecialchars($module['code']) ?></span>
                        </div>
                        <span class="badge-coef">Coef. <?= $module['coefficient'] ?></span>
                    </div>
                    <?php endwhile; ?> <!-- fin de boucle -->
                    <!-- si la table est vide on ecrit qu'aucun module est trouvé -->
                    <?php if(mysqli_num_rows($result) === 0): ?>
                        <p style="color: var(--muted); font-size: .85rem; padding: 14px 0;">Aucun module trouvé.</p>
                    <?php endif; ?>
                </div>
            </div>
        </div>
    </main>
</div>


</body>
</html>
