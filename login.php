<?php

    session_start();
    
    $db_server = "localhost";
    $db_user = "root";
    $db_pass = "";
    $db_name = "Usthb_app";

    $conn = mysqli_connect($db_server, $db_user, $db_pass, $db_name);

    $error = ""; //pour sauvegarder un message d'erreur au cas ou la connection fails

    //rechercher le password et email dans la table users, pas de securité contre sql injection pour l'instant
    if(isset($_POST['submit'])){
        $email    = mysqli_real_escape_string($conn, $_POST['email']);
        $password = mysqli_real_escape_string($conn, $_POST['password']);

        //requete pour rechercher un user selon le password et email entré
        $sql = "SELECT role, id FROM users WHERE email='$email' AND pass_word='$password'";
        $result = mysqli_query($conn, $sql);
        
        
        if($result && mysqli_num_rows($result) > 0){    //si une combination d'email et password a été trouver, redirecter le user vers sa page selon le role
            $row = mysqli_fetch_assoc($result);
            $_SESSION['user_id'] = $row['id']; //sauvegarder l'id du user dans la session pour le redirecter vers sa page
            $_SESSION['user_role'] = $row['role']; 
            $role = $row['role'];

            if($role === 'admin'){ //le user est admin, redirecter vers admin dashboard
                header("Location: dashboard_admin.php");
                exit();
            }
            elseif($role === 'student'){ // le user est etudiant, redirecter vers dashboard etudiant
                header("Location: dashboard_etudiant.php"); 
                exit();
            }
            elseif($role === 'teacher'){ //le user est teacher, redirecter vers dashboard enseignant
                header("Location: dashboard_enseignant.php");
                exit();
            }else{
                echo "Rôle inconnu."; //si le user n'a pas de role (impossible)
            }
        }else{
            $error = "Email ou mot de passe incorrect. Veuillez réessayer."; //si le email ou password est incorrect, show error
        }
    }

    
?>


<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<title>University Portal</title>
<link rel="stylesheet" href="login.css">
</head>

<body>
<div class="login-container">
    
    
    <div class="logo"> <img src="USTHB LOGO.jpg" alt="University Logo"> </div>

    <h1>Portail de la faculté d'informatique</h1>
    <p class="subtitle">Université de Sciences et de Technologie Houari Boumédiène</p>

    <form action="login.php" method="post" autocomplete="off" > <!-- form pour mettre les informations -->

        <label>Adresse Email</label>
        <input type="email" name="email" placeholder="Entrer votre Email" autocomplete="off">

        <label>Mot de passe</label>
        <input type="password" name="password" placeholder="Entrer votre mot de passe" autocomplete="off">

        <button type="submit" name="submit">Se connecter</button>
        <span class="error"><?php echo $error; ?></span> <!-- show error msg -->
    </form>
    

</div>

</body>
</html>

<?php mysqli_close($conn); ?>