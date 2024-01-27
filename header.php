<header>
    <a href="./index.php">Toys World</a>
    <nav>
        <ul>
            <li><a href="./index.php">Home</a></li>
            <li><a href="./saves.php">Saves</a></li>
            <li><a href="./cart.php">Cart</a></li>
            <?php if (isset($_SESSION["user_id"])) { ?>
                <li><a href="./user/account.php">Account</a></li>
            <?php } else { ?>
                <li><a href="./user/login.php">Login</a></li>
            <?php } ?>
        </ul>
    </nav>
</header>