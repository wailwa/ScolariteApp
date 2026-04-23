<?php
/* Projet programmation web - TP PHP & SQL
  Realise par :
  MECHAI OUIAM         KHELIL MERIEM      AKOUIRADJEMOU OUAIL ABDERRAOUF 
  232331602210         242431575703              222231581410  

 Encadre par : Dr. LAACHEMI 
 */

 
// Inclure ce fichier en haut de chaque page protégée
// Exemple : require_once 'auth.php'; checkRole('admin');

function checkRole($role_requis) {
    if (!isset($_SESSION['user_id'])) {
        header("Location: login.php");
        exit();
    }

    // Récupérer le rôle depuis la session (stocké lors du login)
    if (!isset($_SESSION['role'])) {
        header("Location: login.php");
        exit();
    }

    if ($_SESSION['role'] !== $role_requis) {
        // Redirige vers sa propre page s'il n'a pas accès
        $role = $_SESSION['role'];
        if ($role === 'admin')   header("Location: dashboard_admin.php");
        elseif ($role === 'teacher') header("Location: dashboard_enseignant.php");
        elseif ($role === 'student') header("Location: dashboard_etudiant.php");
        exit();
    }
}
?>