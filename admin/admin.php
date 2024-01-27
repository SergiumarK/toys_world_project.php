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

            // Get product_id
            $stmt2 = $conn -> prepare("SELECT * FROM products WHERE title = ?");
            $stmt2 -> bind_param("s", $title);
            $stmt2 -> execute();
            $result = $stmt2 -> get_result();
            $product = $result -> fetch_assoc();
            

            // Upload images

            if (isset($_FILES["images"]) ) {
                // verificam daca nu avem reori
                foreach ($_FILES["images"]["error"] as $imgError) {
                    if ($imgError > 0) {
                        die("one or many of images have errors");
                    }
                }

                $folder = "uploads/";
                
                for ($i = 0; $i < count($_FILES["images"]["name"]); $i++) {

                    // unique name
                    $uniqueName = uniqid() . $_FILES["images"]["name"][$i];
                    
                    // size
                    if ($_FILES["images"]["size"][$i] > 5000000) {
                        die("One or many of the images > 5 MB.");
                    }

                    // type
                    $ext = strtolower(pathinfo($_FILES["images"]["name"][$i], PATHINFO_EXTENSION));
                    IF ($ext !== "webp") {
                        die("One or many of the images is not .webp");
                    }

                    // Upload image
                    if (move_uploaded_file($_FILES["images"]["tmp_name"][$i], $folder . $uniqueName)) {
                        $stmt3 = $conn -> prepare("INSERT INTO product_images(url, product_id) VALUES(?, ?)");
                        $stmt3 -> bind_param("si", $uniqueName, $product["id"]);
                        $stmt3 -> execute();
                    }
                }
            }
            

            // header("Location: ./create.php");
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

        if (isset($_POST["update-images"])) {
            $productId = sanitizeInput($_POST["product_id"]);
        
            // Delete old images from folder
            $stmt = $conn->prepare("SELECT * FROM product_images WHERE product_id = ?");
            $stmt->bind_param("i", $productId);
            $stmt->execute();
            $results = $stmt->get_result();
            
            while ($row = $results->fetch_assoc()) {
                unlink("uploads/" . $row["url"]);
            }
        
            // Delete old images from table
            $stmt2 = $conn->prepare("DELETE FROM product_images WHERE product_id = ?");
            $stmt2->bind_param("i", $productId);
            $stmt2->execute();
        
            // Upload new images
            if (isset($_FILES["images"])) {
                foreach ($_FILES["images"]["error"] as $imgError) {
                    if ($imgError > 0) {
                        die("One or many of the images have errors.");
                    }
                }
        
                $folder = "uploads/";
        
                for ($i = 0; $i < count($_FILES["images"]["name"]); $i++) {
                    // Unique name
                    $uniqueName = uniqid() . $_FILES["images"]["name"][$i];
        
                    // Check size
                    if ($_FILES["images"]["size"][$i] > 5000000) {
                        die("One or many of the images > 5 MB.");
                    }
        
                    // Check type
                    $ext = strtolower(pathinfo($_FILES["images"]["name"][$i], PATHINFO_EXTENSION));
                    if ($ext !== "webp") {
                        die("One or many of the images is not .webp");
                    }
        
                    // Upload image
                    if (move_uploaded_file($_FILES["images"]["tmp_name"][$i], $folder . $uniqueName)) {
                        $stmt3 = $conn->prepare("INSERT INTO product_images(url, product_id) VALUES (?, ?)");
                        $stmt3->bind_param("si", $uniqueName, $productId);
                        $stmt3->execute();
                    }
                }
            }
            
            header("Location: ./update.php");
        }
        
        if (isset($_POST["soft-delete"])) {
            $productId = sanitizeInput($_POST["product_id"]);
            if (isset($_POST["hidden"]) && $_POST["hidden"] === "1")
            {
                //Hide 
                $hidden = 1;
                $sql = "UPDATE products SET hidden = ? WHERE id = ?";
                $stmt = $conn->prepare($sql);
                $stmt->bind_param("ii", $hidden, $productId);
                $stmt->execute();
            } else {
                $hidden = 0;
                $sql = "UPDATE products SET hidden = ? WHERE id = ?";
                $stmt = $conn->prepare($sql);
                $stmt->bind_param("ii", $hidden, $productId);
                $stmt->execute();
            }
            header("Location: ./update.php");
        }

        if (isset($_POST["save"])) {
            if (!isset($_SESSION["user_id"])) {
                header ("Location: ./login.php");
            }
            $productId = sanitizeInput($_POST["product_id"]);
            $userId = $_SESSION["user_id"];

            // Verificam dacca produsul nu exista deja in tabelul de produse salvate
            $sqlCheck = "SELECT * FROM saves WHERE product_id = ? AND user_id = ?";
            $stmtCheck = $conn -> prepare($sqlCheck);
            $stmtCheck -> bind_param("ii", $productId, $userId);
            $stmtCheck -> execute();
            $result = $stmtCheck -> get_result();
            $save = $result -> fetch_assoc();
            if ($save) die("Product already saved");

            $sql = "INSERT INTO saves(product_id, user_id) VALUES (?, ?)";
            $stmt = $conn -> prepare($sql);
            $stmt -> bind_param("ii", $productId, $userId);
            $stmt -> execute();
            header ("Location: ../saves.php");
        }
    }
?>