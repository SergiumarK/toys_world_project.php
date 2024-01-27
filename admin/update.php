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

    $products = [];
    while ($row = $results -> fetch_assoc()) {
        $products[] = $row;
    }
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
                foreach ($products as $product) {
                    echo "<option value='$product[id]'>$product[title]</option>";
                }
            ?>
        </select>
        <input type="text" name="title" placeholder="Title" required>
        <textarea name="description" id="" cols="30" rows="5" placeholder="description"></textarea>
        <input type="number" name="price" placeholder="Price" required step="0.01">
        <button type="submit" name="update" >Edit product</button>
    </form>
    <br>
    <br>

    <hr>

    <br>
    <br>
    <h1>Edit product images</h1>
    <form action="./admin.php" method="POST" enctype="multipart/form-data">
        <select name="product_id" id="">
            <?php
                foreach ($products as $product) {
                    echo "<option value='$product[id]'>$product[title]</option>";
                }
            ?>
        </select>
        <label for="images">Images</label>
        <input type="file" name="images[]" id="images" multiple accept="image/webp">
        <button type="submit" name="update-images" >Edit product images</button>
    </form>
    <br>
    <br>
    <hr>
    <br>
    <br>
    <h1>Soft-delete</h1>
    <form action="./admin.php" method="post">
        <select name="product_id" id="">
            <?php
                foreach ($products as $product) {
                    echo "<option value='$product[id]'>$product[title]</option>";
                }
            ?>
        </select>
        <label for="hidden">Hidden:
            <input type="checkbox" name="hidden" value="1">
        </label>
        <button type="submit" name="soft-delete">Save</button>
    </form>
</body>
</html>