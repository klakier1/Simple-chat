<?php
session_start();
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="styleSingIn.css">
    <title>Simple Chat - Sign in</title>
</head>

<body>
    <header>
        <h1 class="header-logo">Simple Chat</h1>
        <nav>
            <Ul class="header-nav">
                <li><a href="register.php">Sign up</a></li>
            </Ul>
        </nav>
    </header>
    <main>
        <section class="sign-in-form">
            <h1>Sign in</h1>
            <form action="scripts/login.php" method="post">
                <label for="input_email">E-mail</label>
                <input type="email" name="email" id="input_email">

                <label for="input_password">Password</label>
                <input type="password" name="password" id="input_password">

                <input type="submit" value="Log In">
            </form>
            <?php
            if (isset($_SESSION["register_form_errors"]))
                foreach ($_SESSION["register_form_errors"] as $key => $value) {
                    echo '<p class="error">' . $value . "</p>";
                }
            $_SESSION["register_form_errors"] = null;
            ?>
        </section>
    </main>
    <footer>
        <div class="footer-signature">
            <span>Created by Łukasz Paleczek</span><br>
            <a class="footer-mailto" href="mailto:lukasz.paleczek@gmail.com">lukasz.paleczek@gmail.com</a>
        </div>
    </footer>
</body>

</html>