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

require_once "connexion.php";

$lvlLabels = [1 => 'L1', 2 => 'L2', 3 => 'L3', 4 => 'M1', 5 => 'M2'];

$teacherUserId = $_SESSION['user_id'];

// Fetch teacher info
$queryTeacher  = "SELECT t.id FROM Teachers t WHERE t.user_id = $teacherUserId";
$resultTeacher = mysqli_query($conn, $queryTeacher);
$teacher       = mysqli_fetch_assoc($resultTeacher);
$realTeacherId = $teacher['id'];

$message     = '';
$messageType = '';

// Handle bulk grade save
if (isset($_POST['action']) && $_POST['action'] === 'save_all_grades') {
    $module_id = intval($_POST['module_id']);

    // Verify the module belongs to this teacher
    $checkRes = mysqli_query($conn, "SELECT id FROM Modules WHERE id=$module_id AND teacher_id=$realTeacherId");

    if (mysqli_num_rows($checkRes) === 0) {
        $message     = 'Ce module ne vous appartient pas.';
        $messageType = 'error';
    } elseif (!empty($_POST['grades']) && is_array($_POST['grades'])) {
        mysqli_begin_transaction($conn);
        $ok = true;

        foreach ($_POST['grades'] as $student_id => $grade_value) {
            if ($grade_value === '') continue; // skip blank inputs

            $student_id = intval($student_id);
            $grade      = max(0, min(20, floatval($grade_value))); // clamp 0–20

            $sql = "INSERT INTO Grades (student_id, module_id, grade)
                    VALUES ($student_id, $module_id, $grade)
                    ON DUPLICATE KEY UPDATE grade = $grade";

            if (!mysqli_query($conn, $sql)) {
                $ok = false;
                break;
            }
        }

        if ($ok) {
            mysqli_commit($conn);
            $message     = 'Notes enregistrées avec succès.';
            $messageType = 'success';
        } else {
            mysqli_rollback($conn);
            $message     = 'Une erreur est survenue. Veuillez réessayer.';
            $messageType = 'error';
        }
    } else {
        $message     = 'Aucune note à enregistrer.';
        $messageType = 'error';
    }
}

// Fetch this teacher's modules
$queryModules  = "SELECT id, code, name, lvl FROM Modules
                  WHERE teacher_id = $realTeacherId ORDER BY lvl ASC, name ASC";
$resultModules = mysqli_query($conn, $queryModules);
$modules       = [];
while ($m = mysqli_fetch_assoc($resultModules)) $modules[] = $m;

// Selected module from URL
$selectedModuleId = isset($_GET['module_id']) ? intval($_GET['module_id']) : null;
$selectedModule   = null;
$students         = [];

if ($selectedModuleId) {
    foreach ($modules as $m) {
        if ($m['id'] == $selectedModuleId) {
            $selectedModule = $m;
            break;
        }
    }
}

if ($selectedModule) {
    $lvl           = intval($selectedModule['lvl']);
    $queryStudents = "SELECT s.id, s.matricule, s.family_name, s.surname,
                             g.grade
                      FROM Students s
                      LEFT JOIN Grades g ON g.student_id = s.id
                                        AND g.module_id = $selectedModuleId
                      WHERE s.lvl = $lvl
                      ORDER BY s.family_name ASC, s.surname ASC";
    $resultStudents = mysqli_query($conn, $queryStudents);
    while ($s = mysqli_fetch_assoc($resultStudents)) $students[] = $s;
}
?>
<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>USTHB – Saisir les Notes</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link href="https://fonts.googleapis.com/css2?family=Syne:wght@400;600;700;800&family=DM+Sans:wght@300;400;500&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">
    <link rel="stylesheet" href="dashboard_enseignant.css">
    <link rel="stylesheet" href="saisir_notes.css">
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
            <a href="mes_modules.php" class="nav-item">
                <i class="fa-solid fa-book-open"></i> Mes Modules
            </a>
            <a href="saisir_notes.php" class="nav-item active">
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
            <h1>Saisir les Notes</h1>
            <p>Sélectionnez un module puis saisissez les notes des étudiants</p>
        </div>

        <?php if ($message): ?>
            <div class="msg-<?= $messageType ?>">
                <i class="fa-solid <?= $messageType === 'success' ? 'fa-circle-check' : 'fa-circle-exclamation' ?>"></i>
                <?= htmlspecialchars($message) ?>
            </div>
        <?php endif; ?>

        <div class="notes-layout">

            <!-- Left: module list -->
            <div class="module-selector">
                <div class="module-selector-title">Mes Modules</div>
                <?php if (count($modules) > 0): ?>
                    <?php foreach ($modules as $m): ?>
                        <a href="saisir_notes.php?module_id=<?= $m['id'] ?>"
                           class="module-option <?= ($selectedModuleId == $m['id']) ? 'active' : '' ?>">
                            <div class="module-option-info">
                                <strong><?= htmlspecialchars($m['name']) ?></strong>
                                <span><?= htmlspecialchars($m['code']) ?></span>
                            </div>
                            <span class="badge-lvl"><?= $lvlLabels[$m['lvl']] ?? 'N/A' ?></span>
                        </a>
                    <?php endforeach; ?>
                <?php else: ?>
                    <p style="padding:20px; color:var(--muted); font-size:.85rem;">
                        Aucun module assigné.
                    </p>
                <?php endif; ?>
            </div>

            <!-- Right: single form wrapping the whole table -->
            <div class="grade-panel">
                <?php if ($selectedModule): ?>

                    <form method="POST" action="saisir_notes.php?module_id=<?= $selectedModuleId ?>">
                        <input type="hidden" name="action" value="save_all_grades">
                        <input type="hidden" name="module_id" value="<?= $selectedModuleId ?>">

                        <div class="grade-panel-header">
                            <div>
                                <h2><?= htmlspecialchars($selectedModule['name']) ?></h2>
                                <p>
                                    <?= htmlspecialchars($selectedModule['code']) ?>
                                    – <?= $lvlLabels[$selectedModule['lvl']] ?? 'N/A' ?>
                                    – <?= count($students) ?> étudiant<?= count($students) != 1 ? 's' : '' ?>
                                </p>
                            </div>
                            <?php if (count($students) > 0): ?>
                                <button type="submit" class="btn-save-all">
                                    <i class="fa-solid fa-floppy-disk"></i> Enregistrer tout
                                </button>
                            <?php endif; ?>
                        </div>

                        <?php if (count($students) > 0): ?>
                            <table class="grade-table">
                                <thead>
                                    <tr>
                                        <th>#</th>
                                        <th>MATRICULE</th>
                                        <th>NOM</th>
                                        <th>PRÉNOM</th>
                                        <th>NOTE ACTUELLE</th>
                                        <th>NOUVELLE NOTE / 20</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php foreach ($students as $i => $s): ?>
                                    <tr>
                                        <td style="color:var(--muted);"><?= $i + 1 ?></td>
                                        <td><?= htmlspecialchars($s['matricule']) ?></td>
                                        <td><?= htmlspecialchars($s['family_name']) ?></td>
                                        <td><?= htmlspecialchars($s['surname']) ?></td>
                                        <td>
                                            <?php if ($s['grade'] !== null): ?>
                                                <span class="current-grade"><?= number_format($s['grade'], 2) ?></span>
                                                <span style="color:var(--muted); font-size:.78rem;">/ 20</span>
                                            <?php else: ?>
                                                <span class="no-grade">–</span>
                                            <?php endif; ?>
                                        </td>
                                        <td>
                                            <input type="number"
                                                   name="grades[<?= $s['id'] ?>]"
                                                   class="grade-input"
                                                   min="0" max="20" step="0.25"
                                                   value="<?= $s['grade'] !== null ? htmlspecialchars($s['grade']) : '' ?>"
                                                   placeholder="–"
                                                   data-original="<?= $s['grade'] !== null ? $s['grade'] : '' ?>">
                                        </td>
                                    </tr>
                                    <?php endforeach; ?>
                                </tbody>
                            </table>
                        <?php else: ?>
                            <div class="empty-panel">
                                <i class="fa-solid fa-user-graduate"></i>
                                Aucun étudiant inscrit dans ce niveau.
                            </div>
                        <?php endif; ?>

                    </form>

                <?php else: ?>
                    <div class="empty-panel">
                        <i class="fa-solid fa-hand-point-left"></i>
                        Sélectionnez un module pour afficher les étudiants.
                    </div>
                <?php endif; ?>
            </div>

        </div>
    </main>
</div>

<script>
    // Highlight inputs that differ from their saved value
    document.querySelectorAll('.grade-input').forEach(input => {
        input.addEventListener('input', function () {
            this.classList.toggle('changed', this.value !== this.dataset.original);
        });
    });
</script>
</body>
</html>