<?php
session_start();

if (!isset($_SESSION['logged_user']))
    header("Location: " . "index.php");

$user = $_SESSION['logged_user'];

$keysPassToJS = array('login', 'id', 'email');

$userFrontend = array_filter($user, function ($val, $key) use ($keysPassToJS) {
    return in_array($key, $keysPassToJS);
}, ARRAY_FILTER_USE_BOTH);

?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Simple Chat</title>
    <link rel="stylesheet" href="styleMessagner.css">
</head>

<body>
    <header>
        <h1 class="header-logo">Simple Chat</h1>
        <nav>
            <Ul class="header-nav">
                <li>Logged as <?= $user['login'] ?></li>
                <li><a href="scripts/logout.php">Log out</a></li>
            </Ul>
        </nav>
    </header>
    <main>
        <section class="chat-container">
            <div class="chat-user-list-container">
                <ul class="chat-user-list">
                </ul>
            </div>
            <div class="chat-area">
                <div class="chat">
                    <div class="chat-welcome">Select user</div>
                </div>
                <div class="chat-input-form">
                    <input type="text" name="msg" id="chat-input-text" placeholder="Type your message...">
                    <input type="button" value="Send" id="chat-send-button">
                </div>
            </div>
        </section>
    </main>
    <footer>
        <div class="footer-signature">
            <span>Created by Łukasz Paleczek</span><br>
            <a class="footer-mailto" href="mailto:lukasz.paleczek@gmail.com">lukasz.paleczek@gmail.com</a>
        </div>
    </footer>

    <script>
        const _currentUser = JSON.parse('<?= json_encode($userFrontend) ?>');
    </script>
    <script src="script.js"></script>

</body>

</html>