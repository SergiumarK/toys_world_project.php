<?php
    require_once "../config.php";

    if (isset($_SESSION["user_id"]) && !checkAdmin($_SESSION["user_id"])) {
        header("Location: ../user/account.php");
    } else if (!isset($_SESSION["user_id"])) {
        header("Location: ../user/login.php");
    }

    $stmt = $conn -> prepare("SELECT * FROM products");
    $stmt -> execute();
    $results = $stmt -> get_result();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Products</title>
</head>
<body>
    <?php
        while ($row = $results -> fetch_assoc()) {
            echo "<div>
                <h1>$row[title]</h1>
                <p>$row[description]</p>
                <p>$row[price] MDL</p>
            </div>";
        }
    ?>
</body>
</html>