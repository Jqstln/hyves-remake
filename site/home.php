<?php
session_start();
 
if(!isset($_SESSION["loggedin"]) || $_SESSION["loggedin"] !== true){
    header("location: login.php");
    exit;
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
    <title>Hyves - Home</title>
    <style>
        body{ font: 14px sans-serif; text-align: center; }
    </style>
</head>
<body>
    <?php include 'include/navbar.php'; ?>
    <br><br><br>
    <h1 class="my-5">Hallo, <b><?php echo htmlspecialchars($_SESSION["username"]); ?></b>.<br> Welkom bij Hyves.</h1>
    <p>
        <a href="reset-password.php" class="btn btn-warning">Reset jou wachtwoord.</a>
        <a href="logout.php" class="btn btn-danger ml-3">Uitloggen.</a>
    </p>
</body>
</html>