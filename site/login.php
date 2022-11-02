<?php
require_once $_SERVER['DOCUMENT_ROOT'] . '/config/database.php';

session_start();

if(isset($_SESSION["loggedin"]) && $_SESSION["loggedin"] === true){
    header("location: home.php");
    exit;
}
 
 
$username = $password = "";
$username_err = $password_err = $login_err = "";
 
if($_SERVER["REQUEST_METHOD"] == "POST"){
 
    if(empty(trim($_POST["username"]))){
        $username_err = "Voer een gebruikers naam in.";
    } else{
        $username = trim($_POST["username"]);
    }
    
    if(empty(trim($_POST["password"]))){
        $password_err = "Voer een wachtwoord in.";
    } else{
        $password = trim($_POST["password"]);
    }
    
    if(empty($username_err) && empty($password_err)){
        $sql = "SELECT id, username, password FROM users WHERE username = ?";
        
        if($stmt = mysqli_prepare($link, $sql)){
            mysqli_stmt_bind_param($stmt, "s", $param_username);
            
            $param_username = $username;
            
            if(mysqli_stmt_execute($stmt)){
                mysqli_stmt_store_result($stmt);
                if(mysqli_stmt_num_rows($stmt) == 1){                    
                    mysqli_stmt_bind_result($stmt, $id, $username, $hashed_password);
                    if(mysqli_stmt_fetch($stmt)){
                        if(password_verify($password, $hashed_password)){
                            session_start();
                            
                            $_SESSION["loggedin"] = true;
                            $_SESSION["id"] = $id;
                            $_SESSION["username"] = $username;                            
                            
                            header("location: home.php");
                        } else{
                            $login_err = "Onjuiste gebruikersnaam of wachtwoord.";
                        }
                    }
                } else{
                    $login_err = "Onjuiste gebruikersnaam of wachtwoord.";
                }
            } else{
                echo "Oeps! Er is iets fout gegaan. Probeer het later opnieuw.";
            }

            mysqli_stmt_close($stmt);
        }
    }

    mysqli_close($link);
}
?>
 
<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="apple-touch-icon" sizes="180x180" href="../static/favicon/apple-touch-icon.png">
    <link rel="icon" type="image/png" sizes="32x32" href="../static/favicon/favicon-32x32.png">
    <link rel="icon" type="image/png" sizes="16x16" href="../static/favicon/favicon-16x16.png">
    <link rel="manifest" href="../static/favicon/site.webmanifest">
    <link rel="stylesheet" href="../static/css/bootstrap.min.css">
    <script src="../static/js/bootstrap.bundle.min.js"></script>
    <script src="../static/js/jquery-3.6.1.min.js"></script>
    <title>Hyves - Log in uw account</title>
    <style>
        body{ font: 14px sans-serif; }
        .wrapper{ width: 360px; padding: 20px; }
    </style>
</head>
<body>
    <div class="wrapper">
        <h2>Inloggen</h2>
        <p>Vul jouw gegevens in om in te loggen.</p>

        <?php 
        if(!empty($login_err)){
            echo '<div class="alert alert-danger">' . $login_err . '</div>';
        }        
        ?>

        <form action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>" method="post">
            <div class="form-group">
                <label>Gebruikersnaam</label>
                <input type="text" name="username" class="form-control <?php echo (!empty($username_err)) ? 'is-invalid' : ''; ?>" value="<?php echo $username; ?>">
                <span class="invalid-feedback"><?php echo $username_err; ?></span>
            </div>    
            <div class="form-group">
                <label>Wachtwoord</label>
                <input type="password" name="password" class="form-control <?php echo (!empty($password_err)) ? 'is-invalid' : ''; ?>">
                <span class="invalid-feedback"><?php echo $password_err; ?></span>
            </div>
            <div class="form-group">
                <input type="submit" class="btn btn-primary" value="Login">
            </div>
            <p>Heb je nog geen account? Registreer je dan via <a href="register.php">hier</a>.</p>
        </form>
    </div>
</body>
</html>