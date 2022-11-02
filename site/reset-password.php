<?php
require_once $_SERVER['DOCUMENT_ROOT'] . '/config/database.php';

session_start();
 
if(!isset($_SESSION["loggedin"]) || $_SESSION["loggedin"] !== true){
    header("location: login.php");
    exit;
}
 
 
$new_password = $confirm_password = "";
$new_password_err = $confirm_password_err = "";

if($_SERVER["REQUEST_METHOD"] == "POST"){
 
    if(empty(trim($_POST["new_password"]))){
        $new_password_err = "Vul het nieuwe wachtwoord in.";     
    } elseif(strlen(trim($_POST["new_password"])) < 6){
        $new_password_err = "Nieuwe wachtwoord moet minimaal 6 karakters bevatten.";
    } else{
        $new_password = trim($_POST["new_password"]);
    }
    
    if(empty(trim($_POST["confirm_password"]))){
        $confirm_password_err = "Bevestig het nieuwe wachtwoord.";
    } else{
        $confirm_password = trim($_POST["confirm_password"]);
        if(empty($new_password_err) && ($new_password != $confirm_password)){
            $confirm_password_err = "Wachtwoord is niet juist.";
        }
    }
        
    if(empty($new_password_err) && empty($confirm_password_err)){
        $sql = "UPDATE users SET password = ? WHERE id = ?";
        
        if($stmt = mysqli_prepare($link, $sql)){
            mysqli_stmt_bind_param($stmt, "si", $param_password, $param_id);
            
            $param_password = password_hash($new_password, PASSWORD_DEFAULT);
            $param_id = $_SESSION["id"];
            
            if(mysqli_stmt_execute($stmt)){
                session_destroy();
                header("location: login.php");
                exit();
            } else{
                echo "Oeps! Er is iets fout gegaan. Probeer het later opnieuw.";
            }

            // Close statement
            mysqli_stmt_close($stmt);
        }
    }
    
    // Close connection
    mysqli_close($link);
}
?>
 
<!DOCTYPE html>
<html lang="en">
<head>
<meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="apple-touch-icon" sizes="180x180" href="../static/favicon/apple-touch-icon.png">
    <link rel="icon" type="image/png" sizes="32x32" href="../static/favicon/favicon-32x32.png">
    <link rel="icon" type="image/png" sizes="16x16" href="../static/favicon/favicon-16x16.png">
    <link rel="manifest" href="../static/favicon/site.webmanifest">
    <link rel="stylesheet" href="../static/css/bootstrap.min.css">
    <script src="../static/js/bootstrap.bundle.min.js"></script>
    <script src="../static/js/jquery-3.6.1.min.js"></script>
    <title>Hyves - Reset Wachtwoord</title>
    <style>
        body{ font: 14px sans-serif; }
        .wrapper{ width: 360px; padding: 20px; }
    </style>
</head>
<body>
    <div class="wrapper">
        <h2>Reset jou wachtwoord</h2>
        <p>Vul dit in om jou wachtwoord te resetten</p>
        <form action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>" method="post"> 
            <div class="form-group">
                <label>Nieuwe wachtwoord</label>
                <input type="password" name="new_password" class="form-control <?php echo (!empty($new_password_err)) ? 'is-invalid' : ''; ?>" value="<?php echo $new_password; ?>">
                <span class="invalid-feedback"><?php echo $new_password_err; ?></span>
            </div>
            <div class="form-group">
                <label>Bevestig nieuwe wachtwoord</label>
                <input type="password" name="confirm_password" class="form-control <?php echo (!empty($confirm_password_err)) ? 'is-invalid' : ''; ?>">
                <span class="invalid-feedback"><?php echo $confirm_password_err; ?></span>
            </div>
            <div class="form-group">
                <input type="submit" class="btn btn-primary" value="Submit">
                <a class="btn btn-link ml-2" href="home.php">Annuleer</a>
            </div>
        </form>
    </div>    
</body>
</html>