<?php
    require_once "./config.php";

    // Catalog
    $sqlCatalog = "SELECT products.id, products.title, products.price, GROUP_CONCAT(product_images.url) AS image_urls FROM products LEFT JOIN product_images ON products.id = product_images.product_id WHERE products.hidden = 0 GROUP BY products.id, products.title, products.price";
    $stmtCatalog = $conn -> prepare($sqlCatalog);
    $stmtCatalog -> execute();
    $resultCatalog = $stmtCatalog -> get_result(); 
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Home - Toys World</title>
</head>
<body>
    <?php
        include_once "./header.php";
    ?>
    <!-- Slider -->
    <!-- Filter -->
    <!-- Sort -->
    <!-- Cautare -->
    <!-- Categorizare -->
    <!-- Formulat de contact -->
    <!-- Catalog -->
    <div class="catalog">
        <?php
            while ($product = $resultCatalog -> fetch_assoc()) {
                $image = explode(",", $product["image_urls"])[0];
                echo "<div>
                    <img src='./admin/uploads/$image' height='250px' alt='Product image'>
                    <h2>$product[title]</h2>
                    <p><strong>$product[price] MDL</strong></p>
                    <a href='product.php?id=$product[id]'>See product</a>
                    <form action='./admin/admin.php' method='post'>
                        <input type='hidden' name='product_id' value='$product[id]'>
                        <button type='submit' name='save'>Save</button>
                    </form>
                    <form action='./admin/admin.php' method='post'>
                        <input type='hidden' name='product_id' value='$product[id]'>
                        <button type='submit' name='cart'>Add to cart</button>
                    </form>
                </div>";
            }
        ?>
    </div>
</body>
</html>