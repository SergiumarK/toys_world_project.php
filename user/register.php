<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Register - Toys World</title>
</head>
<body>
    <h1>Register</h1>
    <form action="./auth.php" method="POST">
        <input type="text" name="login" placeholder="Login" required>
        <input type="email" name="email" placeholder="Email" required>
        <input type="password" name="password" placeholder="Password" required>
        <input type="password" name="password2" placeholder="Confirm password" required>
        <button type="submit" name="register">Register</button>
        <a href="./login.php">Login</a>
    </form>
</body>
</html>