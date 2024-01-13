<?php
    require_once "../config.php";

    if (isset($_SESSION["user_id"]) && !checkAdmin($_SESSION["user_id"])) {
        header("Location: ../user/account.php");
    } else if (!isset($_SESSION["user_id"])) {
        header("Location: ../user/login.php");
    }
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
</head>
<body>
    
</body>
</html>