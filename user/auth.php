<?php
    require_once "../config.php";

    function sanitizeInput($input) {
        return htmlspecialchars(strip_tags(trim($input)));
    }

    if ($_SERVER["REQUEST_METHOD"] === "POST") {
        if(isset($_POST["register"])) {
            $login = sanitizeInput($_POST["login"]);
            $email = sanitizeInput($_POST["email"]);
            $password = sanitizeInput($_POST["password"]);
            $password2 = sanitizeInput($_POST["password2"]);

            // Check if user exists
            $stmt = $conn -> prepare("SELECT id FROM users WHERE login = ?");
            $stmt -> bind_param("s", $login);
            $stmt -> execute();
            $stmt -> store_result();

            if($stmt -> num_rows > 0) {
                die ("User already exist.");
            } else {
                // Register
                if ($password !== $password2) {
                    die ("Passwords do not match.");
                }

                $password = password_hash($password, PASSWORD_DEFAULT);

                $stmt -> prepare("INSERT INTO users (login, email, password) VALUES (?, ?, ?)");
                $stmt -> bind_param("sss", $login, $email, $password);

                if ($stmt -> execute()) {
                    header("Loction: ./login.php");
                    exit();//inchide total 
                } else {
                    die ("Registration failed.");
                } 
            }
        }

        if(isset($_POST["login"])) {
            $login = sanitizeInput($_POST["user_login"]);
            $password = sanitizeInput($_POST["password"]);

            $stmt = $conn -> prepare("SELECT * FROM users WHERE login = ?");
            $stmt -> bind_param("s", $login);
            $stmt -> execute();
            $result = $stmt -> get_result();



            if($result -> num_rows > 0) {
                $user = $result -> fetch_assoc();

                if (password_verify($password, $user["password"])) {
                    $_SESSION["user_id"] = $user["id"];
                    setcookie("userId", $user["id"], time() + (86400 * 182), "/");
                    header("Location: ./account.php");
                    exit();
                } else {
                    die ("Wrong credentials.");
                }
            } else {
                die("Wrong credentials");
            }
        }

        if (isset($_POST["logout"])) {
            unset($_SESSION["user_id"]);
            setcookie("userId", "", time() - 86400, "/");
            header("location: ./login.php");
        }
    } else {
        die ("You can not access this page.");
    }
?>