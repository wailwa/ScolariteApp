<?php

    session_start();
    
    $db_server = "localhost";
    $db_user = "root";
    $db_pass = "";
    $db_name = "Usthb_app";

    $conn = mysqli_connect($db_server, $db_user, $db_pass, $db_name);

    $error = ""; 

    if(isset($_POST['submit'])){
        $email = $_POST['email'];
        $password = $_POST['password'];

        
        $sql = "SELECT role, id FROM users WHERE email='$email' AND pass_word='$password'";
        $result = mysqli_query($conn, $sql);
        

        if($result && mysqli_num_rows($result) > 0){ 
            $row = mysqli_fetch_assoc($result);
            $_SESSION['user_id'] = $row['id'];

            $role = $row['role'];

            if($role === 'admin'){
                header("Location: dashboard_admin.php");
                exit();
            }
            elseif($role === 'student'){
                header("Location: dashboard_etudiant.php"); 
                exit();
            }
            elseif($role === 'teacher'){
                header("Location: dashboard_enseignant.php");
                exit();
            }else{
                echo "Rôle inconnu.";
            }
        }else{
            $error = "Email ou mot de passe incorrect. Veuillez réessayer.";
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

    <form action="login.php" method="post">

        <label>Adresse Email</label>
        <input type="email" name="email" placeholder="Entrer votre Email">

        <label>Mot de passe</label>
        <input type="password" name="password" placeholder="Entrer votre mot de passe">

        <button type="submit" name="submit">Se connecter</button>
        <span class="error"><?php echo $error; ?></span>
    </form>
    

</div>

</body>
</html>

<?php mysqli_close($conn); ?>