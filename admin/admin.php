<?php
    require_once "../config.php";

    function sanitizeInput($input) {
        return htmlspecialchars(strip_tags(trim($input)));
    }

    if($_SERVER["REQUEST_METHOD"] === "POST") {
        if (isset($_POST["create"])) {
            $title = sanitizeInput($_POST["title"]);
            $description = sanitizeInput($_POST["description"]); 
            $price = sanitizeInput($_POST["price"]);

            $stmt = $conn -> prepare("INSERT INTO products(title, description, price) values (?, ?, ?)"); 
            $stmt -> bind_param("ssd", $title, $description, $price);
            $stmt -> execute();

            header("Location: ./create.php");
        }

        if (isset($_POST["update"])) {
            $productId = sanitizeInput($_POST["product_id"]);
            $title = sanitizeInput($_POST["title"]);
            $description = sanitizeInput($_POST["description"]); 
            $price = sanitizeInput($_POST["price"]);

            $stmt = $conn -> prepare("UPDATE products SET title = ?, description = ?, price = ? WHERE id = ?"); 
            $stmt -> bind_param("ssdi", $title, $description, $price, $productId);
            $stmt -> execute();

            header("Location: ./update.php");
        }

        if (isset($_POST["delete"])) {
            $productId = sanitizeInput($_POST["product_id"]);

            $stmt = $conn -> prepare("DELETE FROM products WHERE id = ?"); 
            $stmt -> bind_param("i", $productId);
            $stmt -> execute();

            header("Location: ./delete.php");
        }
    }
        
?>