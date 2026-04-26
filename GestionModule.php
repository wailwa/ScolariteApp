<?php
/* Projet programmation web - TP PHP & SQL
  Realise par :
  MECHAI OUIAM         KHELIL MERIEM      AKOUIRADJEMOU OUAIL ABDERRAOUF 
  232331602210         242431575703              222231581410  

 Encadre par : Dr. LAACHEMI 
 */


require_once "connexion.php";

    $lvlLabels = [1=>'L1',2=>'L2',3=>'L3',4=>'M1',5=>'M2'];
    //boutton delete cliqué, supprimer de la base de données
    if(isset($_GET['delete_id'])){
        $delete_id = intval($_GET['delete_id']);
        mysqli_query($conn, "DELETE FROM Modules WHERE id=$delete_id");
        header("Location: GestionModule.php");
        exit();
    }
    //boutton ajout cliqué, ajouter dans la base
    if(isset($_POST['action']) && $_POST['action'] === 'add'){
        $code= mysqli_real_escape_string($conn, $_POST['code']);
        $name= mysqli_real_escape_string($conn, $_POST['name']);
        $coefficient = intval($_POST['coefficient']);
        $teacher_id = !empty($_POST['teacher_id']) ? intval($_POST['teacher_id']) : 'NULL';
        $lvl = intval($_POST['lvl']);
        $sql = "INSERT INTO Modules (code, `name`, coefficient, teacher_id, lvl) VALUES ('$code', '$name', '$coefficient', $teacher_id, $lvl)";
        mysqli_query($conn, $sql);
        header("Location: GestionModule.php");
        exit();
    }
    //boutton edit cliqué, editer dans la base
    if(isset($_POST['action']) && $_POST['action'] === 'edit'){
        $id = intval($_POST['module_id']);
        $code = mysqli_real_escape_string($conn, $_POST['code']);
        $name = mysqli_real_escape_string($conn, $_POST['name']);
        $coefficient = intval($_POST['coefficient']);
        $teacher_id = !empty($_POST['teacher_id']) ? intval($_POST['teacher_id']) : 'NULL';
        $lvl = intval($_POST['lvl']);
        $sql = "UPDATE Modules SET code='$code', `name`='$name', coefficient='$coefficient', teacher_id=$teacher_id, lvl=$lvl WHERE id=$id";
        mysqli_query($conn, $sql);
        header("Location: GestionModule.php");
        exit();
    }
    //rechercher tout les modules dans la tables modules
    $queryModules = "SELECT m.id, m.code, m.`name`, m.coefficient, m.lvl, t.first_name, t.last_name
                 FROM Modules m LEFT JOIN Teachers t ON m.teacher_id = t.id ORDER BY m.lvl ASC, m.id ASC";
    $resultModules = mysqli_query($conn, $queryModules);
    $modules = [];
    while($m = mysqli_fetch_assoc($resultModules)) $modules[] = $m; //mettre toute les ligne dans le tableau $module[]

    //rechercher les enseignant dan la table enseignant
    $queryTeachers = "SELECT id, first_name, last_name FROM Teachers ORDER BY last_name ASC";
    $resultTeachers = mysqli_query($conn, $queryTeachers);
    $teachers = [];
    while($t = mysqli_fetch_assoc($resultTeachers)) $teachers[] = $t; //les mettre dans le tableau $teachers[]
?>
<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>USTHB – Gestion des Modules</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link href="https://fonts.googleapis.com/css2?family=Syne:wght@400;600;700;800&family=DM+Sans:wght@300;400;500&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">
    <link rel="stylesheet" href="GestionModule.css">
</head>
<!-- sidebar -->
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
            <a href="GestionEnseignant.php" class="nav-item"><i class="fa-solid fa-chalkboard-user"></i> Gestion des Enseignants</a>
            <a href="GestionModule.php" class="nav-item active"><i class="fa-solid fa-book-open"></i> Gestion des Modules</a>
            <a href="Notes.php" class="nav-item"><i class="fa-solid fa-file-lines"></i> Gestion des Notes</a>
            <a href="inscriptions.php" class="nav-item"><i class="fa-solid fa-user-plus"></i> Inscriptions</a>
        </nav>
        <div class="sidebar-footer">
            <a href="login.php"><i class="fa-solid fa-arrow-right-from-bracket"></i> Déconnexion</a>
        </div>
    </aside>
    <!-- header -->
    <main class="main">
        <div class="page-header">
            <h1>Gestion des Modules</h1>
            <p>Ajouter, modifier et gérer les modules</p>
        </div>
        <div class="toolbar">
            <div class="search-box"> <!-- searchbox des modules -->
                <i class="fa-solid fa-magnifying-glass"></i>
                <input type="text" id="searchInput" placeholder="Rechercher un module...">
            </div>
            <button class="btn-add" onclick="openAddModal()"><i class="fa-solid fa-plus"></i> Ajouter Module</button> <!-- boutton d'ajout cliqué, open panel -->
        </div>
        <!-- tableau d'affichage des modules-->
        <div class="table-panel">
            <div class="table-wrapper">
                <table id="modulesTable">
                    <thead>
                        <tr><th>ID</th><th>CODE</th><th>INTITULÉ</th><th>NIVEAU</th><th>COEFFICIENT</th><th>ENSEIGNANT RESPONSABLE</th><th>ACTIONS</th></tr>
                    </thead>
                    <tbody>
                        <?php if(count($modules) > 0): ?> <!-- si il exist des modules dans la table-->
                            <?php foreach($modules as $m): ?> <!-- boucle pour afficher chaque module dans la table-->
                            <tr>
                                <td><?= htmlspecialchars($m['id']) ?></td>
                                <td><strong><?= htmlspecialchars($m['code']) ?></strong></td>
                                <td><?= htmlspecialchars($m['name']) ?></td>
                                <td><?= $lvlLabels[$m['lvl']] ?? 'N/A' ?></td>
                                <td><?= htmlspecialchars($m['coefficient']) ?></td>
                                <td><?= $m['first_name'] ? htmlspecialchars($m['first_name'].' '.$m['last_name']) : '<span class="no-teacher">–</span>' ?></td>
                                <td class="actions-cell">
                                    <button class="btn-icon btn-edit" title="Modifier" onclick="openEditModal(<?= $m['id'] ?>,'<?= addslashes($m['code']) ?>','<?= addslashes($m['name']) ?>','<?= $m['coefficient'] ?>', '<?= $m['lvl'] ?>')">
                                        <i class="fa-solid fa-pen"></i>
                                    </button>
                                    <a href="GestionModule.php?delete_id=<?= $m['id'] ?>" class="btn-icon btn-delete" title="Supprimer" onclick="return confirm('Supprimer ce module ? Toutes les notes associées seront supprimées.')">
                                        <i class="fa-solid fa-trash"></i>
                                    </a>
                                </td>
                            </tr>
                            <?php endforeach; ?>
                        <?php else: ?>
                            <tr><td colspan="6" class="empty-msg">Aucun module trouvé.</td></tr> <!-- il n'existe pas de module dans la table -->
                        <?php endif; ?>
                    </tbody>
                </table>
            </div>
        </div>
    </main>
</div>


<div class="modal-overlay" id="addModal">
    <div class="modal">
        <div class="modal-header">
            <h2>Ajouter un Module</h2>
            <button class="modal-close" onclick="closeModal('addModal')"><i class="fa-solid fa-xmark"></i></button> <!--panel d'ajout de module -->
        </div>
        <form method="POST" action="GestionModule.php">
            <input type="hidden" name="action" value="add">
            <div class="modal-body">
                <div class="form-row">
                    <div class="form-group"><label>Code</label><input type="text" name="code" placeholder="Ex: BD" required></div>
                    <div class="form-group"><label>Coefficient</label><input type="number" name="coefficient" min="1" placeholder="Ex: 3" required></div>
                </div>
                <div class="form-group">
                    <label>Niveau</label>
                    <select name="lvl" required>
                        <option value="1">L1</option>
                        <option value="2">L2</option>
                        <option value="3">L3</option>
                        <option value="4">M1</option>
                        <option value="5">M2</option>
                    </select>
                </div>
                <div class="form-group"><label>Intitulé</label><input type="text" name="name" placeholder="Ex: Base de Données" required></div>
                <div class="form-group">
                    <label>Enseignant Responsable</label>
                    <select name="teacher_id">
                        <option value="">– Aucun –</option>
                        <?php foreach($teachers as $t): ?>
                            <option value="<?= $t['id'] ?>"><?= htmlspecialchars($t['first_name'].' '.$t['last_name']) ?></option>
                        <?php endforeach; ?>
                    </select>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn-cancel" onclick="closeModal('addModal')">Annuler</button>
                <button type="submit" class="btn-save">Ajouter</button>
            </div>
        </form>
    </div>
</div>

<div class="modal-overlay" id="editModal"> <!-- panel d'edit de module-->
    <div class="modal">
        <div class="modal-header">
            <h2>Modifier le Module</h2>
            <button class="modal-close" onclick="closeModal('editModal')"><i class="fa-solid fa-xmark"></i></button>
        </div>
        <form method="POST" action="GestionModule.php">
            <input type="hidden" name="action" value="edit">
            <input type="hidden" name="module_id" id="edit_module_id">
            <div class="modal-body">
                <div class="form-row">
                    <div class="form-group"><label>Code</label><input type="text" name="code" id="edit_code" required></div>
                    <div class="form-group"><label>Coefficient</label><input type="number" name="coefficient" id="edit_coefficient" min="1" required></div>
                </div>
                <div class="form-group">
                    <label>Niveau</label>
                    <select name="lvl" id="edit_lvl" required>
                        <option value="1">L1</option>
                        <option value="2">L2</option>
                        <option value="3">L3</option>
                        <option value="4">M1</option>
                        <option value="5">M2</option>
                    </select>
                </div>
                <div class="form-group"><label>Intitulé</label><input type="text" name="name" id="edit_name" required></div>
                <div class="form-group">
                    <label>Enseignant Responsable</label>
                    <select name="teacher_id" id="edit_teacher_id">
                        <option value="">– Aucun –</option>
                        <?php foreach($teachers as $t): ?>
                            <option value="<?= $t['id'] ?>"><?= htmlspecialchars($t['first_name'].' '.$t['last_name']) ?></option>
                        <?php endforeach; ?>
                    </select>
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
        document.querySelectorAll('#modulesTable tbody tr').forEach(row => {
            row.style.display = row.textContent.toLowerCase().includes(query) ? '' : 'none';
        });
    });
    function openAddModal() { document.getElementById('addModal').classList.add('active'); }
    function openEditModal(id, code, name, coefficient, lvl) {
    document.getElementById('edit_module_id').value   = id;
    document.getElementById('edit_code').value        = code;
    document.getElementById('edit_name').value        = name;
    document.getElementById('edit_coefficient').value = coefficient;
    document.getElementById('edit_lvl').value         = lvl;
    document.getElementById('editModal').classList.add('active');
    }
    function closeModal(id) { document.getElementById(id).classList.remove('active'); }
    document.querySelectorAll('.modal-overlay').forEach(overlay => {
        overlay.addEventListener('click', function(e) { if(e.target === this) closeModal(this.id); });
    });
</script>
</body>
</html>
