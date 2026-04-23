<?php
/* Projet programmation web - TP PHP & SQL
  Realise par :
  MECHAI OUIAM         KHELIL MERIEM      AKOUIRADJEMOU OUAIL ABDERRAOUF 
  232331602210         242431575703              222231581410  

 Encadre par : Dr. LAACHEMI 
 */



    $db_server = "localhost";
    $db_user   = "root";
    $db_pass   = "";
    $db_name   = "Usthb_app";

    $conn = mysqli_connect($db_server, $db_user, $db_pass, $db_name);

    if(isset($_POST['action']) && $_POST['action'] === 'save_grade'){ //le boutton sauveagarder note est cliqué, update database
        $student_id = intval($_POST['student_id']);
        $module_id  = intval($_POST['module_id']);
        $grade      = floatval($_POST['grade']);
        if($student_id <= 0 || $module_id <= 0){
            header("Location: Notes.php");
            exit();
        }
        //requete pour inserer la note dans la table grades au module et l'etudiant correspondant
        $sql = "INSERT INTO Grades (student_id, module_id, grade)
                VALUES ($student_id, $module_id, $grade)
                ON DUPLICATE KEY UPDATE grade = $grade"; //si il exsite deja un grade pour le module, update it
        mysqli_query($conn, $sql);
        header("Location: Notes.php?student_id=$student_id"); //refresh
        exit();
    }
    //requete pour sauvegarder tout les etudiants dans le tableau student[]
    $queryStudents = "SELECT id, matricule, family_name, surname FROM Students ORDER BY family_name ASC";
    $resultStudents = mysqli_query($conn, $queryStudents);
    $students = [];
    while($s = mysqli_fetch_assoc($resultStudents)) $students[] = $s; //mettre chaque ligne dans le tableau

    $selectedStudent = null;
    $studentGrades   = [];
    $modules         = [];
    $moyenne         = null;
    $statut          = null;

    $selectedId = isset($_GET['student_id']) ? intval($_GET['student_id']) : null;

    if($selectedId){
        $r = mysqli_query($conn, "SELECT id, matricule, family_name, surname, lvl FROM Students WHERE id=$selectedId");
        $selectedStudent = mysqli_fetch_assoc($r);

        // Loader les modules correspondant au niveau de l'etudiant
        $studentLvl = $selectedStudent['lvl'];
        $queryModules = "SELECT id, code, `name`, coefficient FROM Modules WHERE lvl=$studentLvl ORDER BY `name` ASC";
        $resultModules = mysqli_query($conn, $queryModules);

        while($m = mysqli_fetch_assoc($resultModules)) $modules[] = $m; //les mettre dans le tableau
        //rechercher les note des modules de cet etudiant
        $queryGrades = "SELECT g.module_id, g.grade, m.code, m.`name`, m.coefficient
                        FROM Grades g JOIN Modules m ON g.module_id = m.id
                        WHERE g.student_id = $selectedId";
        $resultGrades = mysqli_query($conn, $queryGrades);
        while($g = mysqli_fetch_assoc($resultGrades)) $studentGrades[$g['module_id']] = $g; //les mettre dans un tableau

        //calculer la moyenne
        $totalWeight = 0; $weightedSum = 0;
        foreach($studentGrades as $g){
            $weightedSum += $g['grade'] * $g['coefficient'];
            $totalWeight += $g['coefficient'];
        }
        if($totalWeight > 0){
            $moyenne = round($weightedSum / $totalWeight, 2);
            $statut  = $moyenne >= 10 ? 'Admis' : 'Ajourné'; //admis ou ajourné
        }
    }
?>
<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>USTHB – Gestion des Notes</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link href="https://fonts.googleapis.com/css2?family=Syne:wght@400;600;700;800&family=DM+Sans:wght@300;400;500&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">
    <link rel="stylesheet" href="Notes.css">
</head>
<body>
<div class="layout">
    <aside class="sidebar">
        <div class="sidebar-brand">
            <h2>USTHB – Admin</h2>
            <p>Faculté d'Informatique</p>
        </div>
        <!-- sidebar-->
        <nav class="sidebar-nav">
            <a href="dashboard_admin.php" class="nav-item"><i class="fa-solid fa-table-columns"></i> Tableau de Bord</a>
            <a href="GestionEtudiant.php" class="nav-item"><i class="fa-solid fa-user-graduate"></i> Gestion des Étudiants</a>
            <a href="GestionEnseignant.php" class="nav-item"><i class="fa-solid fa-chalkboard-user"></i> Gestion des Enseignants</a>
            <a href="GestionModule.php" class="nav-item"><i class="fa-solid fa-book-open"></i> Gestion des Modules</a>
            <a href="Notes.php" class="nav-item active"><i class="fa-solid fa-file-lines"></i> Gestion des Notes</a>
            <a href="inscriptions.php" class="nav-item"><i class="fa-solid fa-user-plus"></i> Inscriptions</a>
        </nav>
        <div class="sidebar-footer">
            <a href="login.php"><i class="fa-solid fa-arrow-right-from-bracket"></i> Déconnexion</a>
        </div>
    </aside>
    <!-- header -->
    <main class="main">
        <div class="page-header">
            <h1>Gestion des Notes</h1>
            <p>Saisir et gérer les notes des étudiants</p>
        </div>
        <!-- le panel pour saisir les note-->
        <div class="notes-grid">
            <div class="panel">
                <h2 class="panel-title">Saisie de Note</h2>
                <form method="POST" action="Notes.php">
                    <input type="hidden" name="action" value="save_grade">
                    <div class="form-group">
                        <label>Sélectionner Étudiant</label>
                        <input type="text" id="studentSearch" placeholder="Rechercher un étudiant..." autocomplete="off"> <!-- un searchbox pour rechercher l'etudiant-->
                        <div class="student-dropdown" id="studentDropdown">
                            <?php foreach($students as $s): ?> <!-- dropdown pour afficher et selectioner les etudiants-->
                                <div class="student-option"
                                     data-id="<?= $s['id'] ?>"
                                     data-name="<?= htmlspecialchars($s['matricule'].' - '.$s['family_name'].' '.$s['surname']) ?>">
                                    <?= htmlspecialchars($s['matricule'].' - '.$s['family_name'].' '.$s['surname']) ?>
                                </div>
                            <?php endforeach; ?>
                        </div>
                        <input type="hidden" name="student_id" id="studentIdInput" value="<?= $selectedId ?>"> <!-- $selectedId continet l'id de letudiant selectioné-->
                    </div>
                    <div class="form-group">
                        <label>Sélectionner Module</label> <!-- dropdown pour selectionner le module -->
                        <select name="module_id">
                            <?php foreach($modules as $m): ?> <!-- afficher juste les modules correspondant au niveau de l'etudiant-->
                                <option value="<?= $m['id'] ?>"><?= htmlspecialchars($m['code'].' - '.$m['name']) ?></option>
                            <?php endforeach; ?>
                        </select>
                    </div>
                    <div class="form-group">
                        <label>Note (0-20)</label> <!-- afficher la note-->
                        <input type="number" name="grade" min="0" max="20" step="0.25" placeholder="Entrer la note" required>
                    </div>
                    <button type="submit" class="btn-save-grade">
                        <i class="fa-solid fa-floppy-disk"></i> Enregistrer Note
                    </button>
                </form>

                <?php if($moyenne !== null): ?> <!-- afficher la moyenne-->
                <div class="moyenne-card">
                    <span class="moyenne-label">Moyenne Générale:</span>
                    <span class="moyenne-value"><?= $moyenne ?> / 20</span>
                    <span class="badge-statut <?= $statut === 'Admis' ? 'badge-admis' : 'badge-recale' ?>"><?= $statut ?></span>
                </div>
                <?php endif; ?>
            </div>
            <!-- le panel pour afficher toute les notes de l'etudiant selectioné-->
            <div class="panel">
                <h2 class="panel-title">Notes de l'Étudiant</h2>
                <?php if($selectedStudent): ?>
                    <div class="student-card">
                        <span class="student-card-label">Étudiant:</span>
                        <strong><?= htmlspecialchars($selectedStudent['family_name'].' '.$selectedStudent['surname']) ?></strong>
                        <span class="student-card-sub">Matricule: <?= htmlspecialchars($selectedStudent['matricule']) ?></span>
                    </div>
                    <div class="grades-list">
                        <?php if(count($studentGrades) > 0): ?>
                            <?php foreach($studentGrades as $g): ?>
                            <div class="grade-row">
                                <div class="grade-module-info">
                                    <strong><?= htmlspecialchars($g['name']) ?></strong>
                                    <span><?= htmlspecialchars($g['code']) ?> – Coef. <?= $g['coefficient'] ?></span>
                                </div>
                                <div class="grade-value-wrap">
                                    <span class="grade-value"><?= number_format($g['grade'], 2) ?></span>
                                    <span class="grade-max">/ 20</span>
                                </div>
                            </div>
                            <?php endforeach; ?>
                        <?php else: ?>
                            <p class="empty-msg">Aucune note enregistrée pour cet étudiant.</p>
                        <?php endif; ?>
                    </div>
                <?php else: ?>
                    <p class="empty-msg">Sélectionnez un étudiant pour voir ses notes.</p>
                <?php endif; ?>
            </div>
        </div>
    </main>
</div>


<!-- javasript -->
<script>
    const searchInput = document.getElementById('studentSearch');
    const dropdown    = document.getElementById('studentDropdown');
    const hiddenInput = document.getElementById('studentIdInput');
    const options     = document.querySelectorAll('.student-option');

    <?php if($selectedStudent): ?>
        searchInput.value = '<?= addslashes($selectedStudent['matricule'].' - '.$selectedStudent['family_name'].' '.$selectedStudent['surname']) ?>';
    <?php endif; ?>

    searchInput.addEventListener('input', function() {
        const query = this.value.toLowerCase();
        let hasResults = false;
        options.forEach(opt => {
            const match = opt.dataset.name.toLowerCase().includes(query);
            opt.style.display = match ? '' : 'none';
            if(match) hasResults = true;
        });
        dropdown.style.display = hasResults ? 'block' : 'none';
    });

    searchInput.addEventListener('focus', function() {
        options.forEach(opt => opt.style.display = '');
        dropdown.style.display = 'block';
    });

    options.forEach(opt => {
        opt.addEventListener('click', function() {
            searchInput.value = this.dataset.name;
            hiddenInput.value = this.dataset.id;
            dropdown.style.display = 'none';
            window.location.href = 'Notes.php?student_id=' + this.dataset.id;
        });
    });

    document.addEventListener('click', function(e) {
        if(!searchInput.contains(e.target) && !dropdown.contains(e.target)){
            dropdown.style.display = 'none';
        }
    });
</script>
</body>
</html>
