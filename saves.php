<?php
    require_once "./config.php";

    if (!isset($_SESSION["user_id"])) header ("Location: ./admin/login.php");

    $userId = $_SESSION["user_id"];

    $sql = "SELECT saves.*, 
        products.id AS product_id, 
        products.title, 
        products.price, 
        GROUP_CONCAT(product_images.url) AS image_urls
        FROM saves
        INNER JOIN products ON products.id = saves.product_id
        LEFT JOIN product_images ON products.id = product_images.product_id
        WHERE saves.user_id = ?
        GROUP BY saves.id, products.id, products.title, products.price;
    ";
    $stmt = $conn -> prepare($sql);
    $stmt -> bind_param("i", $userId);
    $stmt -> execute();
    $result = $stmt -> get_result();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Saves - Toys World</title>
</head>
<body>
    <?php require_once "./header.php" ?>
    <h1>Saves</h1>
    <?php
        while ($product = $result -> fetch_assoc()) {
            $image = explode(",", $product["image_urls"])[0];
                echo "<div>
                        <img loading='lazy' src='./admin/uploads/$image' height='250px' alt='Product image'>
                        <h2>$product[title]</h2>
                        <p><strong>$product[price] MDL</strong></p>
                        <a href='product.php?id=$product[id]'>See product</a>
                        <form action='./admin/admin.php' method='post'>
                            <input type='hidden' name='product_id' value='$product[product_id]'>
                            <button type='submit' name='delete-save'>Delete</button>
                        </form>
                        <form action='./admin/admin.php' method='post'>
                            <input type='hidden' name='product_id' value='$product[product_id]'>
                            <button type='submit' name='cart'>Add to cart</button>
                        </form>
                    </div>";
        }
    ?>
</body>
</html>