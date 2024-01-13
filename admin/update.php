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
    <title>Edit product</title>
</head>
<body>
    <h1>Edit product</h1>
    <form action="./admin.php" method="POST">
        <select name="product_id" id="">
            <?php
                while ($row = $results -> fetch_assoc()) {
                    echo "<option value='$row[id]'>$row[title]</option>";
                }
            ?>
        </select>
        <input type="text" name="title" placeholder="Title" required>
        <textarea name="description" id="" cols="30" rows="5" placeholder="description"></textarea>
        <input type="number" name="price" placeholder="Price" required step="0.01">
        <button type="submit" name="update" >Edit product</button>
    </form>
</body>
</html>