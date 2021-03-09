<?php

session_start();

require "Db/UserOperations.php";

$errors = array();

/**
 * POST
 * 
 * email
 * password
 */

if (isset($_POST['email'])) {
    $email = $_POST['email'];
    $password = $_POST['password'];

    if (count($errors = validateInput($email, $password, $errors)) == 0) {
        $userOperations = new UserOperations();
        $user = $userOperations->getUser($email);
        if ($user != false && count($user) == 1) {
            if (password_verify($password, $user[0]['password_hash'])) {

                $_SESSION['logged_user'] = $user[0];
                header("Location: ../messanger.php");
                die();
            } else {
                $errors["password_wrong"] = "Wrong password";
            }
        } else {
            $errors["email_incorrect"] = "Incorrect email";
        }
    }
}

$_SESSION["register_form_errors"] = $errors;
header("Location: ../index.php");



function validateInput($email, $password, array $errors): array
{
    //validate email  
    $emailB = filter_var($email, FILTER_SANITIZE_EMAIL);
    if (!filter_var($emailB, FILTER_VALIDATE_EMAIL) || ($emailB != $email)) {
        $errors["email_incorrect"] = "Incorrect email";
    }

    //validate password
    $passwordB = filter_var($password, FILTER_SANITIZE_STRING);
    if ($password != $passwordB) {
        $errors["password_incorrect"] = "Password contains not allowed charcters";
    }
    if ((strlen($password) < 8) || (strlen($password) > 20)) {
        $errors["password_length"] = "Password must be between 3 and 20 characters long";
    }

    return $errors;
}
