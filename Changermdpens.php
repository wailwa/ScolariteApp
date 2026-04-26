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

$message     = '';
$messageType = '';

if (isset($_POST['change_password'])) {
    $loggedInUserId  = $_SESSION['user_id'];
    $newPassword     = $_POST['new_password'];
    $confirmPassword = $_POST['confirm_password'];

    if (empty($newPassword)) {
        $message     = 'Le mot de passe ne peut pas être vide.';
        $messageType = 'error';
    } elseif (strlen($newPassword) < 6) {
        $message     = 'Le mot de passe doit contenir au moins 6 caractères.';
        $messageType = 'error';
    } elseif ($newPassword !== $confirmPassword) {
        $message     = 'Les mots de passe ne correspondent pas.';
        $messageType = 'error';
    } else {
        $safePassword = mysqli_real_escape_string($conn, $newPassword);
        $sql = "UPDATE users SET pass_word = '$safePassword' WHERE id = $loggedInUserId";
        mysqli_query($conn, $sql);
        $message     = 'Mot de passe modifié avec succès.';
        $messageType = 'success';
    }
}
?>
<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>USTHB – Changer Mot de Passe</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link href="https://fonts.googleapis.com/css2?family=Syne:wght@400;600;700;800&family=DM+Sans:wght@300;400;500&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">
    <link rel="stylesheet" href="ChangermdpEns.css">
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
            <a href="mes_etudiants.php" class="nav-item">
                <i class="fa-solid fa-user-graduate"></i> Mes Étudiants
            </a>
            <a href="ChangermdpEns.php" class="nav-item active">
                <i class="fa-solid fa-key"></i> Changer Mot de Passe
            </a>
        </nav>
        <div class="sidebar-footer">
            <a href="logout.php"><i class="fa-solid fa-arrow-right-from-bracket"></i> Déconnexion</a>
        </div>
    </aside>

    <!-- Main -->
    <div class="page">

        <div class="page-header">
            <h1>Changer Mot de Passe</h1>
            <p>Modifiez votre mot de passe pour sécuriser votre compte</p>
        </div>

        <div class="change-password-form">

            <?php if ($message): ?>
                <div class="msg-<?= $messageType ?>">
                    <?= htmlspecialchars($message) ?>
                </div>
            <?php endif; ?>

            <form method="POST" action="ChangermdpEns.php">
                <div class="form-group">
                    <label for="new_password">Nouveau Mot de Passe</label>
                    <input type="password" id="new_password" name="new_password"
                           placeholder="Entrer votre nouveau mot de passe" required>

                    <label for="confirm_password">Confirmer le Mot de Passe</label>
                    <input type="password" id="confirm_password" name="confirm_password"
                           placeholder="Confirmer votre mot de passe" required>

                    <button type="submit" name="change_password">
                        <i class="fa-solid fa-floppy-disk"></i> Enregistrer
                    </button>
                </div>
            </form>

        </div>
    </div>
</div>
</body>
</html>