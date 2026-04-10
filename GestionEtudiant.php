<?php
    $db_server = "localhost";
    $db_user = "root";
    $db_pass = "";
    $db_name = "Usthb_app";

    $conn = mysqli_connect($db_server, $db_user, $db_pass, $db_name);

    function generatePassword($length = 10) {
        $chars = 'abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ0123456789!@#$%^&*()_+-=';
        $password = '';
        $max_index = strlen($chars) - 1;

        for ($i = 0; $i < $length; $i++) {
            $index = random_int(0, $max_index); // cryptographically secure
            $password .= $chars[$index];
        }

        return $password;
    }

    $lvlLabels = [1 => 'L1', 2 => 'L2', 3 => 'L3', 4 => 'M1', 5 => 'M2'];

    // ── Handle DELETE ──
    if(isset($_GET['delete_id'])){
        $delete_id = intval($_GET['delete_id']);
    
        $res = mysqli_query($conn, "SELECT user_id FROM Students WHERE id=$delete_id");
        $row = mysqli_fetch_assoc($res);
    
        if($row){
            $user_id = intval($row['user_id']);
            mysqli_begin_transaction($conn);
            $ok1 = mysqli_query($conn, "DELETE FROM Students WHERE id=$delete_id");
            $ok2 = mysqli_query($conn, "DELETE FROM users WHERE id=$user_id");
            if($ok1 && $ok2){
                mysqli_commit($conn);
            } else {
                mysqli_rollback($conn);
            
            }
        }
        header("Location: GestionEtudiant.php");
        exit();
    }

    // ── Handle ADD ──
    if(isset($_POST['action']) && $_POST['action'] === 'add'){
        $matricule   = intval($_POST['matricule']);
        $family_name = mysqli_real_escape_string($conn, $_POST['family_name']);
        $surname     = mysqli_real_escape_string($conn, $_POST['surname']);
        $birth_date  = mysqli_real_escape_string($conn, $_POST['birth_date']);
        $email       = mysqli_real_escape_string($conn, $_POST['email']);
        $lvl         = intval($_POST['lvl']);

        $password    = generatePassword(10);
        $emailusthb  = $surname . "." . $family_name . "@usthb.dz";

        $sql_user = "INSERT INTO users (email, pass_word, role) 
                 VALUES ('$emailusthb', '$password', 'student')";

        $sql_student = "INSERT INTO Students (matricule, family_name, surname, birth_date, email, lvl, user_id) 
                    VALUES ('$matricule', '$family_name', '$surname', '$birth_date', '$email', '$lvl', LAST_INSERT_ID())";

        mysqli_begin_transaction($conn);
        $ok1 = mysqli_query($conn, $sql_user);
        $ok2 = mysqli_query($conn, $sql_student);
        if($ok1 && $ok2){
            mysqli_commit($conn);
        } else {
            mysqli_rollback($conn);
        }
    

        header("Location: GestionEtudiant.php");
        exit();
    }

    // ── Handle EDIT ──
    if(isset($_POST['action']) && $_POST['action'] === 'edit'){
        $id          = intval($_POST['student_id']);
        $matricule   = mysqli_real_escape_string($conn, $_POST['matricule']);
        $family_name = mysqli_real_escape_string($conn, $_POST['family_name']);
        $surname     = mysqli_real_escape_string($conn, $_POST['surname']);
        $birth_date  = mysqli_real_escape_string($conn, $_POST['birth_date']);
        $email       = mysqli_real_escape_string($conn, $_POST['email']);
        $lvl         = intval($_POST['lvl']);

        $sql = "UPDATE Students SET
                    matricule=$matricule,
                    family_name='$family_name',
                    surname='$surname',
                    birth_date='$birth_date',
                    email='$email',
                    lvl='$lvl'
                WHERE id=$id";
        mysqli_query($conn, $sql);
        header("Location: GestionEtudiant.php");
        exit();
    }

    $filterLvl = isset($_GET['lvl']) ? intval($_GET['lvl']) : null;
    // ── Fetch all modules ──
    $queryModules = $filterLvl
    ? "SELECT id, `name`, code, coefficient FROM Modules WHERE lvl=$filterLvl"
    : "SELECT id, `name`, code, coefficient FROM Modules";
    $resultModules = mysqli_query($conn, $queryModules);
    $modules = [];
    while($mod = mysqli_fetch_assoc($resultModules)){
        $modules[] = $mod;
    }

    // ── Fetch all students ──
    $filterLvl = isset($_GET['lvl']) ? intval($_GET['lvl']) : null;
    $queryStudents = $filterLvl
        ? "SELECT id, matricule, family_name, surname, email, lvl, birth_date FROM Students WHERE lvl=$filterLvl ORDER BY id ASC"
        : "SELECT id, matricule, family_name, surname, email, lvl, birth_date FROM Students ORDER BY id ASC";
    $resultStudents = mysqli_query($conn, $queryStudents);
    $students = [];
    while($student = mysqli_fetch_assoc($resultStudents)){
        $students[$student['id']] = $student;
        $students[$student['id']]['grades'] = [];
    }

    // ── Fetch all grades ──
    $queryGrades = "SELECT student_id, module_id, grade FROM Grades";
    $resultGrades = mysqli_query($conn, $queryGrades);
    while($grade = mysqli_fetch_assoc($resultGrades)){
        $sid = $grade['student_id'];
        $mid = $grade['module_id'];
        if(isset($students[$sid])){
            $students[$sid]['grades'][$mid] = $grade['grade'];
        }
    }

    // ── Calculate moyenne & statut ──
    foreach($students as &$student){
        $totalWeight = 0;
        $weightedSum = 0;
        foreach($modules as $mod){
            $mid = $mod['id'];
            if(isset($student['grades'][$mid])){
                $weightedSum += $student['grades'][$mid] * $mod['coefficient'];
                $totalWeight += $mod['coefficient'];
            }
        }
        $student['moyenne'] = $totalWeight > 0 ? round($weightedSum / $totalWeight, 2) : null;
        $student['statut']  = $student['moyenne'] !== null ? ($student['moyenne'] >= 10 ? 'Admis' : 'Recalé') : null;
    }
    unset($student);
?>


<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>USTHB – Gestion des Étudiants</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link href="https://fonts.googleapis.com/css2?family=Syne:wght@400;600;700;800&family=DM+Sans:wght@300;400;500&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">
    <link rel="stylesheet" href="GestionEtudiant.css">
</head>
<body>
<div class="layout">

    <aside class="sidebar">
        <div class="sidebar-brand">
            <h2>USTHB – Admin</h2>
            <p>Faculté d'Informatique</p>
        </div>
        <nav class="sidebar-nav">
            <a href="dashboard_admin.php" class="nav-item">
                <i class="fa-solid fa-table-columns"></i> Tableau de Bord
            </a>
            <a href="GestionEtudiant.php" class="nav-item active">
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
            <a href="login.php"><i class="fa-solid fa-arrow-right-from-bracket"></i> Déconnexion</a>
        </div>
    </aside>

    <main class="main">
        <div class="page-header">
            <h1>Gestion des Étudiants</h1>
            <p>Ajouter, modifier et gérer les étudiants</p>
        </div>

        <?php $filterLvl = isset($_GET['lvl']) ? intval($_GET['lvl']) : null; ?>
    <div class="toolbar">
        <div class="search-box">
            <i class="fa-solid fa-magnifying-glass"></i>
            <input type="text" id="searchInput" placeholder="Rechercher un étudiant...">
        </div>
        <div style="display:flex; gap:6px; flex-wrap:wrap;">
            <?php foreach([null=>'Tous',1=>'L1',2=>'L2',3=>'L3',4=>'M1',5=>'M2'] as $val=>$label): ?>
                <a href="GestionEtudiant.php<?= $val ? '?lvl='.$val : '' ?>"
                style="padding:8px 14px; border-radius:8px; font-size:.82rem; font-weight:600; text-decoration:none;
                        background:<?= $filterLvl===$val ? 'var(--accent-blue)' : 'var(--bg)' ?>;
                        color:<?= $filterLvl===$val ? '#fff' : 'var(--muted)' ?>;
                        border:1px solid <?= $filterLvl===$val ? 'var(--accent-blue)' : 'var(--border)' ?>;">
                    <?= $label ?>
                </a>
            <?php endforeach; ?>
        </div>
        <button class="btn-add" onclick="openAddModal()">
            <i class="fa-solid fa-plus"></i> Ajouter Étudiant
        </button>
    </div>

        <div class="table-panel">
            <div class="table-wrapper">
                <table id="studentsTable">
                    <thead>
                        <tr>
                            <th>ID</th>
                            <th>MATRICULE</th>
                            <th>NOM</th>
                            <th>PRÉNOM</th>
                            <th>NIVEAU</th>
                            <?php foreach($modules as $mod): ?>
                                <th><?= htmlspecialchars(strtoupper($mod['code'])) ?></th>
                            <?php endforeach; ?>
                            <?php if(count($modules) > 0): ?>
                                <th>MOYENNE</th>
                                <th>STATUT</th>
                            <?php endif; ?>
                            <th>ACTIONS</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php if(count($students) > 0): ?>
                            <?php foreach($students as $student): ?>
                            <tr>
                                <td><?= htmlspecialchars($student['id']) ?></td>
                                <td><?= htmlspecialchars($student['matricule']) ?></td>
                                <td><?= htmlspecialchars($student['family_name']) ?></td>
                                <td><?= htmlspecialchars($student['surname']) ?></td>
                                <td><?= htmlspecialchars($lvlLabels[$student['lvl']] ?? 'N/A') ?></td>
                                <?php foreach($modules as $mod): ?>
                                    <td><?= isset($student['grades'][$mod['id']]) ? htmlspecialchars($student['grades'][$mod['id']]) : '–' ?></td>
                                <?php endforeach; ?>
                                <?php if(count($modules) > 0): ?>
                                    <td><strong><?= $student['moyenne'] !== null ? $student['moyenne'] : '–' ?></strong></td>
                                    <td>
                                        <?php if($student['statut'] !== null): ?>
                                            <span class="badge-statut <?= $student['statut'] === 'Admis' ? 'badge-admis' : 'badge-recale' ?>">
                                                <?= $student['statut'] ?>
                                            </span>
                                        <?php else: ?>
                                            <span>–</span>
                                        <?php endif; ?>
                                    </td>
                                <?php endif; ?>
                                <td class="actions-cell">
                                    <button class="btn-icon btn-edit" title="Modifier" onclick="openEditModal(
                                        <?= $student['id'] ?>,
                                        '<?= addslashes($student['matricule']) ?>',
                                        '<?= addslashes($student['family_name']) ?>',
                                        '<?= addslashes($student['surname']) ?>',
                                        '<?= addslashes($student['birth_date']) ?>',
                                        '<?= addslashes($student['email'] ?? '') ?>',
                                        '<?= addslashes($student['lvl']) ?>'
                                    )">
                                        <i class="fa-solid fa-pen"></i>
                                    </button>
                                    <a href="GestionEtudiant.php?delete_id=<?= $student['id'] ?>"
                                       class="btn-icon btn-delete"
                                       title="Supprimer"
                                       onclick="return confirm('Supprimer cet étudiant ? Toutes ses notes seront supprimées.')">
                                        <i class="fa-solid fa-trash"></i>
                                    </a>
                                </td>
                            </tr>
                            <?php endforeach; ?>
                        <?php else: ?>
                            <tr>
                                <td colspan="<?= 6 + count($modules) + (count($modules) > 0 ? 2 : 0) ?>" class="empty-msg">Aucun étudiant trouvé.</td>
                            </tr>
                        <?php endif; ?>
                    </tbody>
                </table>
            </div>
        </div>
    </main>
</div>

<div class="help-btn" title="Aide"><i class="fa-solid fa-question"></i></div>

<div class="modal-overlay" id="addModal">
    <div class="modal">
        <div class="modal-header">
            <h2>Ajouter un Étudiant</h2>
            <button class="modal-close" onclick="closeModal('addModal')"><i class="fa-solid fa-xmark"></i></button>
        </div>
        <form method="POST" action="GestionEtudiant.php">
            <input type="hidden" name="action" value="add">
            <div class="modal-body">
                <div class="form-row">
                    <div class="form-group">
                        <label>Matricule</label>
                        <input type="text" name="matricule" placeholder="Ex: 12345" required>
                    </div>
                    <div class="form-group">
                        <label>Niveau</label>
                        <select name="lvl" required>
                            <option value="">-- Choisir --</option>
                            <option value="1">L1</option>
                            <option value="2">L2</option>
                            <option value="3">L3</option>
                            <option value="4">M1</option>
                            <option value="5">M2</option>
                        </select>
                    </div>
                    
                    <div class="form-group">
                        <label>Nom de famille</label>
                        <input type="text" name="family_name" placeholder="Ex: Akouiradjemou" required>
                    </div>
                    <div class="form-group">
                        <label>Prénom</label>
                        <input type="text" name="surname" placeholder="Ex: Ouail" required>
                    </div>
                </div>
                <div class="form-group">
                    <label>Email</label>
                    <input type="email" name="email" placeholder="Ex: Ouail@email.com" required>
                </div>
                <div class="form-group">
                    <label>Date de naissance</label>
                    <input type="date" name="birth_date">
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn-cancel" onclick="closeModal('addModal')">Annuler</button>
                <button type="submit" class="btn-save">Ajouter</button>
            </div>
        </form>
    </div>
</div>

<div class="modal-overlay" id="editModal">
    <div class="modal">
        <div class="modal-header">
            <h2>Modifier l'Étudiant</h2>
            <button class="modal-close" onclick="closeModal('editModal')"><i class="fa-solid fa-xmark"></i></button>
        </div>
        <form method="POST" action="GestionEtudiant.php">
            <input type="hidden" name="action" value="edit">
            <input type="hidden" name="student_id" id="edit_student_id">
            <div class="modal-body">
                <div class="form-row">
                    <div class="form-group">
                        <label>Matricule</label>
                        <input type="text" name="matricule" id="edit_matricule" required>
                    </div>
                    <div class="form-group">
                        <label>Niveau</label>
                            <select name="lvl" id="edit_lvl" required>
                                <option value="">-- Choisir --</option>
                                <option value="1">L1</option>
                                <option value="2">L2</option>
                                <option value="3">L3</option>
                                <option value="4">M1</option>
                                <option value="5">M2</option>
                            </select>
                    </div>
                </div>
                <div class="form-row">
                    <div class="form-group">
                        <label>Nom de famille</label>
                        <input type="text" name="family_name" id="edit_family_name" required>
                    </div>
                    <div class="form-group">
                        <label>Prénom</label>
                        <input type="text" name="surname" id="edit_surname" required>
                    </div>
                </div>
                <div class="form-group">
                    <label>Email</label>
                    <input type="email" name="email" id="edit_email">
                </div>
                <div class="form-group">
                    <label>Date de naissance</label>
                    <input type="date" name="birth_date" id="edit_birth_date">
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn-cancel" onclick="closeModal('editModal')">Annuler</button>
                <button type="submit" class="btn-save">Enregistrer</button>
            </div>
        </form>
    </div>
</div>

<script>
    document.getElementById('searchInput').addEventListener('input', function() {
        const query = this.value.toLowerCase();
        document.querySelectorAll('#studentsTable tbody tr').forEach(row => {
            row.style.display = row.textContent.toLowerCase().includes(query) ? '' : 'none';
        });
    });

    function openAddModal() { document.getElementById('addModal').classList.add('active'); }

    function openEditModal(id, matricule, family_name, surname, birth_date, email, lvl) {
        document.getElementById('edit_student_id').value  = id;
        document.getElementById('edit_matricule').value   = matricule;
        document.getElementById('edit_family_name').value = family_name;
        document.getElementById('edit_surname').value     = surname;
        document.getElementById('edit_birth_date').value  = birth_date;
        document.getElementById('edit_email').value       = email;
        document.getElementById('edit_lvl').value         = lvl;
        document.getElementById('editModal').classList.add('active');
    }

    function closeModal(id) { document.getElementById(id).classList.remove('active'); }

    document.querySelectorAll('.modal-overlay').forEach(overlay => {
        overlay.addEventListener('click', function(e) {
            if(e.target === this) closeModal(this.id);
        });
    });
</script>
</body>
</html>
