<?php
    require_once "./config.php";

    $productId = $_GET["id"];
    $sql = "SELECT products.id, products.title, products.price, products.description, GROUP_CONCAT(product_images.url) AS image_urls FROM products LEFT JOIN product_images ON products.id = product_images.product_id WHERE products.id = ? GROUP BY products.id, products.title, products.price, products.description";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("i", $productId);
    $stmt->execute();
    $result = $stmt->get_result();
    $product = $result->fetch_assoc();

    if ($product === NULL) die("Product not found");

?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= $product["title"] ?> - Toys World</title>
</head>
<body>
    <?php
        include_once "./header.php";
    ?>
    <?php
        $images = explode(",", $product["image_urls"]);
        foreach($images as $image) {
            echo "<img loading='lazy' src='./admin/uploads/$image' alt='Product image' height='250px'>";
        }
    ?>
    <h1><?= $product["title"] ?></h1>
    <p><?= $product["description"] ?></p>
    <p><strong><?= $product["price"] ?> MDL</strong></p>
    <form action='./admin/admin.php' method='post'>
        <input type="hidden" name="product_id" value=<?php $product["id"] ?>>
        <button type="submit" name="save">Save</button>
    </form>
    <form action="./admin/admin.php" method='post'>
        <input type="hidden" name="product_id" value=<?php $product["id"] ?>>
        <button type="submit" name="cart">Add to cart</button>
    </form>
</body>
</html>
