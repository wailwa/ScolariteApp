<?php
    $db_server = "localhost";
    $db_user   = "root";
    $db_pass   = "";
    $db_name   = "Usthb_app";

    $conn = mysqli_connect($db_server, $db_user, $db_pass, $db_name);

    if(isset($_GET['delete_id'])){
        $delete_id = intval($_GET['delete_id']);
        mysqli_query($conn, "DELETE FROM Teachers WHERE id=$delete_id");
        header("Location: GestionEnseignant.php");
        exit();
    }

    if(isset($_POST['action']) && $_POST['action'] === 'add'){
        $first_name = mysqli_real_escape_string($conn, $_POST['first_name']);
        $last_name  = mysqli_real_escape_string($conn, $_POST['last_name']);
        $email      = mysqli_real_escape_string($conn, $_POST['email']);
        $sql = "INSERT INTO Teachers (first_name, last_name, email) VALUES ('$first_name', '$last_name', '$email')";
        mysqli_query($conn, $sql);
        header("Location: GestionEnseignant.php");
        exit();
    }

    if(isset($_POST['action']) && $_POST['action'] === 'edit'){
        $id         = intval($_POST['teacher_id']);
        $first_name = mysqli_real_escape_string($conn, $_POST['first_name']);
        $last_name  = mysqli_real_escape_string($conn, $_POST['last_name']);
        $email      = mysqli_real_escape_string($conn, $_POST['email']);
        $sql = "UPDATE Teachers SET first_name='$first_name', last_name='$last_name', email='$email' WHERE id=$id";
        mysqli_query($conn, $sql);
        header("Location: GestionEnseignant.php");
        exit();
    }

    $queryTeachers = "SELECT t.id, t.first_name, t.last_name, t.email, m.name AS module_name, m.code AS module_code
                      FROM Teachers t
                      LEFT JOIN Modules m ON m.teacher_id = t.id
                      ORDER BY t.id ASC";
    $resultTeachers = mysqli_query($conn, $queryTeachers);
    $teachers = [];
    while($t = mysqli_fetch_assoc($resultTeachers)) $teachers[] = $t;
?>
<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>USTHB – Gestion des Enseignants</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link href="https://fonts.googleapis.com/css2?family=Syne:wght@400;600;700;800&family=DM+Sans:wght@300;400;500&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">
    <link rel="stylesheet" href="GestionEnseignant.css">
</head>
<body>
<div class="layout">
    <aside class="sidebar">
        <div class="sidebar-brand">
            <h2>USTHB – Admin</h2>
            <p>Faculté d'Informatique</p>
        </div>
        <nav class="sidebar-nav">
            <a href="dashboard_admin.php" class="nav-item"><i class="fa-solid fa-table-columns"></i> Tableau de Bord</a>
            <a href="GestionEtudiant.php" class="nav-item"><i class="fa-solid fa-user-graduate"></i> Gestion des Étudiants</a>
            <a href="GestionEnseignant.php" class="nav-item active"><i class="fa-solid fa-chalkboard-user"></i> Gestion des Enseignants</a>
            <a href="GestionModule.php" class="nav-item"><i class="fa-solid fa-book-open"></i> Gestion des Modules</a>
            <a href="Notes.php" class="nav-item"><i class="fa-solid fa-file-lines"></i> Gestion des Notes</a>
            <a href="inscriptions.php" class="nav-item"><i class="fa-solid fa-user-plus"></i> Inscriptions</a>
        </nav>
        <div class="sidebar-footer">
            <a href="login.php"><i class="fa-solid fa-arrow-right-from-bracket"></i> Déconnexion</a>
        </div>
    </aside>

    <main class="main">
        <div class="page-header">
            <h1>Gestion des Enseignants</h1>
            <p>Ajouter, modifier et gérer les enseignants</p>
        </div>
        <div class="toolbar">
            <div class="search-box">
                <i class="fa-solid fa-magnifying-glass"></i>
                <input type="text" id="searchInput" placeholder="Rechercher un enseignant...">
            </div>
            <button class="btn-add" onclick="openAddModal()"><i class="fa-solid fa-plus"></i> Ajouter Enseignant</button>
        </div>
        <div class="table-panel">
            <div class="table-wrapper">
                <table id="teachersTable">
                    <thead>
                        <tr>
                            <th>ID</th><th>NOM</th><th>PRÉNOM</th><th>EMAIL</th><th>MODULE</th><th>CODE</th><th>ACTIONS</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php if(count($teachers) > 0): ?>
                            <?php foreach($teachers as $t): ?>
                            <tr>
                                <td><?= htmlspecialchars($t['id']) ?></td>
                                <td><?= htmlspecialchars($t['last_name']) ?></td>
                                <td><?= htmlspecialchars($t['first_name']) ?></td>
                                <td><?= htmlspecialchars($t['email'] ?? '–') ?></td>
                                <td><?= htmlspecialchars($t['module_name'] ?? '–') ?></td>
                                <td>
                                    <?php if($t['module_code']): ?>
                                        <span class="badge-module"><?= htmlspecialchars($t['module_code']) ?></span>
                                    <?php else: ?>
                                        <span class="no-module">–</span>
                                    <?php endif; ?>
                                </td>
                                <td class="actions-cell">
                                    <button class="btn-icon btn-edit" title="Modifier" onclick="openEditModal(<?= $t['id'] ?>,'<?= addslashes($t['first_name']) ?>','<?= addslashes($t['last_name']) ?>','<?= addslashes($t['email'] ?? '') ?>')">
                                        <i class="fa-solid fa-pen"></i>
                                    </button>
                                    <a href="GestionEnseignant.php?delete_id=<?= $t['id'] ?>" class="btn-icon btn-delete" title="Supprimer" onclick="return confirm('Supprimer cet enseignant ?')">
                                        <i class="fa-solid fa-trash"></i>
                                    </a>
                                </td>
                            </tr>
                            <?php endforeach; ?>
                        <?php else: ?>
                            <tr><td colspan="7" class="empty-msg">Aucun enseignant trouvé.</td></tr>
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
            <h2>Ajouter un Enseignant</h2>
            <button class="modal-close" onclick="closeModal('addModal')"><i class="fa-solid fa-xmark"></i></button>
        </div>
        <form method="POST" action="GestionEnseignant.php">
            <input type="hidden" name="action" value="add">
            <div class="modal-body">
                <div class="form-row">
                    <div class="form-group"><label>Nom</label><input type="text" name="last_name" placeholder="Ex: Laachemi" required></div>
                    <div class="form-group"><label>Prénom</label><input type="text" name="first_name" placeholder="Ex: Mohamed" required></div>
                </div>
                <div class="form-group"><label>Email</label><input type="email" name="email" placeholder="Ex: m.laachemi@usthb.dz"></div>
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
            <h2>Modifier l'Enseignant</h2>
            <button class="modal-close" onclick="closeModal('editModal')"><i class="fa-solid fa-xmark"></i></button>
        </div>
        <form method="POST" action="GestionEnseignant.php">
            <input type="hidden" name="action" value="edit">
            <input type="hidden" name="teacher_id" id="edit_teacher_id">
            <div class="modal-body">
                <div class="form-row">
                    <div class="form-group"><label>Nom</label><input type="text" name="last_name" id="edit_last_name" required></div>
                    <div class="form-group"><label>Prénom</label><input type="text" name="first_name" id="edit_first_name" required></div>
                </div>
                <div class="form-group"><label>Email</label><input type="email" name="email" id="edit_email"></div>
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
        document.querySelectorAll('#teachersTable tbody tr').forEach(row => {
            row.style.display = row.textContent.toLowerCase().includes(query) ? '' : 'none';
        });
    });
    function openAddModal() { document.getElementById('addModal').classList.add('active'); }
    function openEditModal(id, firstName, lastName, email) {
        document.getElementById('edit_teacher_id').value = id;
        document.getElementById('edit_first_name').value = firstName;
        document.getElementById('edit_last_name').value  = lastName;
        document.getElementById('edit_email').value      = email;
        document.getElementById('editModal').classList.add('active');
    }
    function closeModal(id) { document.getElementById(id).classList.remove('active'); }
    document.querySelectorAll('.modal-overlay').forEach(overlay => {
        overlay.addEventListener('click', function(e) { if(e.target === this) closeModal(this.id); });
    });
</script>
</body>
</html>
