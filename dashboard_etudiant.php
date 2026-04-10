<?php

    
    session_start();
    if(!isset($_SESSION['user_id'])){
        header("Location: login.php");
        exit();
        }

    $db_server = "localhost";
    $db_user = "root";
    $db_pass = "";
    $db_name = "Usthb_app";

    $conn = mysqli_connect($db_server, $db_user, $db_pass, $db_name);

    $loggedInUserId = $_SESSION['user_id'];
    $querryInfo = "SELECT matricule, surname, family_name, lvl, email, birth_date FROM Students where user_id = $loggedInUserId";
    $resultInfo = mysqli_query($conn, $querryInfo);
    $studentInfo = mysqli_fetch_assoc($resultInfo);
















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

<div class="page">

    <!-- ── Page Header ── -->
    <div class="page-header">
        <div>
            <h1>Tableau de Bord Étudiant</h1>
            <p>Consultez vos informations et vos résultats académiques</p>
        </div>
    </div>

    <!-- ── Student Info Card ── -->
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
                    <span class="meta-value"><?php echo $studentInfo['lvl']; ?></span>
                </div>
                <div class="meta-item">
                    <span class="meta-label">Date de Naissance</span>
                    <span class="meta-value"><?php echo $studentInfo['birth_date']; ?></span>
                </div>
            </div>
        </div>
    </div>

    <!-- ── Notes Panel ── -->
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
                    <tr>
                        <td><span class="code-badge">BD</span></td>
                        <td class="module-name">Base de Données</td>
                        <td>3</td>
                        <td>
                            <span class="grade-value">14.00</span>
                            <span class="grade-max">/ 20</span>
                        </td>
                        <td><span class="badge-valide">✓ Validé</span></td>
                    </tr>
                    <tr>
                        <td><span class="code-badge">ALGO</span></td>
                        <td class="module-name">Algorithmique</td>
                        <td>4</td>
                        <td>
                            <span class="grade-value">12.00</span>
                            <span class="grade-max">/ 20</span>
                        </td>
                        <td><span class="badge-valide">✓ Validé</span></td>
                    </tr>
                    <tr>
                        <td><span class="code-badge">PWEB</span></td>
                        <td class="module-name">Programmation Web</td>
                        <td>3</td>
                        <td>
                            <span class="grade-value">18.00</span>
                            <span class="grade-max">/ 20</span>
                        </td>
                        <td><span class="badge-valide">✓ Validé</span></td>
                    </tr>
                </tbody>
            </table>
        </div>

        <!-- Moyenne footer -->
        <div class="moyenne-bar">
            <div class="moyenne-bar-left">
                <span class="moyenne-title">Moyenne Générale</span>
                <span class="moyenne-sub">Résultat global</span>
            </div>
            <div class="moyenne-bar-right">
                <span class="moyenne-number">14.40</span>
                <span class="moyenne-denom">/ 20</span>
                <span class="badge-admis">Admis</span>
            </div>
        </div>

    </div><!-- /.notes-panel -->

</div><!-- /.page -->

<div class="help-btn" title="Aide"><i class="fa-solid fa-question"></i></div>

</body>
</html>