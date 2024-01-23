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
    <title>Add product</title>
</head>
<body>
    <h1>Add product</h1>
    <form action="./admin.php" method="POST" enctype="multipart/form-data">
        <input type="text" name="title" placeholder="Title" required>
        <textarea name="description" id="" cols="30" rows="5" placeholder="description"></textarea>
        <input type="number" name="price" placeholder="Price" required step="0.01">
        <label for="images">Images</label>
        <input type="file" name="images[]" id="images" multiple accept="image/webp">
        <button type="submit" name="create" >Add</button>
    </form>
</body>
</html>