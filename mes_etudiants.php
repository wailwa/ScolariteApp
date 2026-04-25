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
$teacher       = mysqli_fetch_assoc($resultTeacher);
$realTeacherId = $teacher['id'];

// Fetch all modules assigned to this teacher
$queryModules = "SELECT id, code, name, coefficient, lvl FROM Modules
                 WHERE teacher_id = $realTeacherId ORDER BY lvl ASC";
$resultModules = mysqli_query($conn, $queryModules);
$modules = [];
while ($m = mysqli_fetch_assoc($resultModules)) $modules[] = $m;

// Get the distinct levels this teacher covers
$lvls = array_unique(array_column($modules, 'lvl'));

// Optional level filter from URL
$filterLvl = isset($_GET['lvl']) ? intval($_GET['lvl']) : null;

// Only allow filtering by levels this teacher actually teaches
if ($filterLvl && !in_array($filterLvl, $lvls)) {
    $filterLvl = null;
}

// Build student query
$students = [];
if (!empty($lvls)) {
    $lvlList = implode(',', $lvls);
    $whereClause = $filterLvl ? "WHERE s.lvl = $filterLvl" : "WHERE s.lvl IN ($lvlList)";

    $queryStudents = "SELECT s.id, s.matricule, s.family_name, s.surname, s.email, s.lvl
                      FROM Students s
                      $whereClause
                      ORDER BY s.lvl ASC, s.family_name ASC";
    $resultStudents = mysqli_query($conn, $queryStudents);
    while ($s = mysqli_fetch_assoc($resultStudents)) {
        $students[$s['id']] = $s;
        $students[$s['id']]['grades'] = [];
    }

    // Fetch grades for teacher's modules only
    if (!empty($students)) {
        $moduleIds  = implode(',', array_column($modules, 'id'));
        $studentIds = implode(',', array_keys($students));

        $queryGrades = "SELECT g.student_id, g.module_id, g.grade
                        FROM Grades g
                        WHERE g.student_id IN ($studentIds)
                          AND g.module_id IN ($moduleIds)";
        $resultGrades = mysqli_query($conn, $queryGrades);
        while ($g = mysqli_fetch_assoc($resultGrades)) {
            $students[$g['student_id']]['grades'][$g['module_id']] = $g['grade'];
        }
    }


}

// Build the module columns for the current filter level (or all levels)
$displayModules = $filterLvl
    ? array_values(array_filter($modules, fn($m) => $m['lvl'] == $filterLvl))
    : $modules;
?>
<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>USTHB – Mes Étudiants</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link href="https://fonts.googleapis.com/css2?family=Syne:wght@400;600;700;800&family=DM+Sans:wght@300;400;500&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">
    <link rel="stylesheet" href="dashboard_enseignant.css">
    <link rel="stylesheet" href="mes_etudiants.css">
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
            <a href="saisir_notes.php" class="nav-item">
                <i class="fa-solid fa-file-lines"></i> Saisir les Notes
            </a>
            <a href="mes_etudiants.php" class="nav-item active">
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
            <h1>Mes Étudiants</h1>
            <p>Étudiants des niveaux correspondant à vos modules</p>
        </div>

        <div class="toolbar">
            <div class="search-box">
                <i class="fa-solid fa-magnifying-glass"></i>
                <input type="text" id="searchInput" placeholder="Rechercher un étudiant...">
            </div>
            <div class="filter-btns">
                <a href="mes_etudiants.php"
                   class="filter-btn <?= $filterLvl === null ? 'active' : '' ?>">Tous</a>
                <?php foreach ($lvls as $lvl): ?>
                    <a href="mes_etudiants.php?lvl=<?= $lvl ?>"
                       class="filter-btn <?= $filterLvl === $lvl ? 'active' : '' ?>">
                        <?= $lvlLabels[$lvl] ?? $lvl ?>
                    </a>
                <?php endforeach; ?>
            </div>
        </div>

        <div class="table-panel">
            <div class="table-wrapper">
                <table id="studentsTable">
                    <thead>
                        <tr>
                            <th>MATRICULE</th>
                            <th>NOM</th>
                            <th>PRÉNOM</th>
                            <th>NIVEAU</th>
                            <?php foreach ($displayModules as $mod): ?>
                                <th><?= htmlspecialchars(strtoupper($mod['code'])) ?></th>
                            <?php endforeach; ?>
                        </tr>
                    </thead>
                    <tbody>
                        <?php if (count($students) > 0): ?>
                            <?php foreach ($students as $s): ?>
                            <tr>
                                <td><?= htmlspecialchars($s['matricule']) ?></td>
                                <td><?= htmlspecialchars($s['family_name']) ?></td>
                                <td><?= htmlspecialchars($s['surname']) ?></td>
                                <td><?= $lvlLabels[$s['lvl']] ?? 'N/A' ?></td>
                                <?php foreach ($displayModules as $mod): ?>
                                    <td>
                                        <?php if ($mod['lvl'] == $s['lvl']): ?>
                                            <?= isset($s['grades'][$mod['id']]) ? htmlspecialchars($s['grades'][$mod['id']]) : '–' ?>
                                        <?php else: ?>
                                            <span style="color:var(--muted);">–</span>
                                        <?php endif; ?>
                                    </td>
                                <?php endforeach; ?>
                            </tr>
                            <?php endforeach; ?>
                        <?php else: ?>
                            <tr>
                                <td colspan="<?= 4 + count($displayModules) ?>"
                                    class="empty-msg">
                                    <?= empty($lvls) ? 'Aucun module assigné.' : 'Aucun étudiant trouvé.' ?>
                                </td>
                            </tr>
                        <?php endif; ?>
                    </tbody>
                </table>
            </div>
        </div>
    </main>
</div>

<script>
    document.getElementById('searchInput').addEventListener('input', function () {
        const query = this.value.toLowerCase();
        document.querySelectorAll('#studentsTable tbody tr').forEach(row => {
            row.style.display = row.textContent.toLowerCase().includes(query) ? '' : 'none';
        });
    });
</script>
</body>
</html>