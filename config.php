<?php
    $conn = new mysqli('localhost', 'root', '', 'toys_world_db');

    if ($conn -> connect_error) {
        die ("Connnection failed: " .$conn->connect_error);
    }

    session_start();


    // setam o valoare pentru variabila de userId
    if (isset($_COOKIE["userId"])) {
        $_SESSION["user_id"] = $_COOKIE["userId"];
    }

    function checkAdmin($id) {
        $stmt = $conn -> prepare("SELECT * FROM users WHERE id = ?");
        $stmt -> bind_param("i", $id);
        $stmt -> execute();
        $result = $stmt -> get_result();
        $user = $result -> fetch_assoc();
        if ($user["type"] === "admin") {
            return true;
        } else {
            return false;
        }
    }
?>