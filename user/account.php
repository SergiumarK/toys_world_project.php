<?php
    require_once "../config.php";

    if (!isset($_SESSION["user_id"])) {
        header("Location: ./login.php");
    }

    $stmt = $conn -> prepare("SELECT * FROM users WHERE id = ?");
    $stmt -> bind_param("i", $_SESSION["user_id"]);
    $stmt -> execute();
    $result = $stmt -> get_result();
    $user = $result -> fetch_assoc();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= "Hi, " . $user["login"] ?></title>
</head>
<body>
    <h2><?= $user["login"] ?></h2>
    <form action="./auth.php" method="POST">
        <button type="submit" name="logout">Logout</button>
    </form>
</body>
</html>

