<?php

require "Db/UserOperations.php";

session_start();

$errors = array();

/**
 * POST fields
 * login
 * email
 * password
 * password2
 */

if (isset($_POST['email'])) {

    $login = $_POST['login'];
    $email = $_POST['email'];
    $password1 = $_POST['password'];
    $password2 = $_POST['password2'];

    $errors = validateInput($login, $email, $password1, $password2, $errors);
    $errors = checkForDuplicates($login, $email, $errors);

    if (count($errors) != 0) {
        //validation failed
        $_SESSION["register_form_errors"] = $errors;
        header("Location: " . "../register.php");
    } else {
        //validation OK
        $userOperation = new UserOperations();

        $password_hash = password_hash($password1, PASSWORD_DEFAULT);
        if ($userOperation->insertUser($login, $email, $password_hash)) {
            $errors["success"] = "User createad";
            $_SESSION["register_form_errors"] = $errors;
            header("Location: " . "../index.php");
        } else {
            $errors["fail"] = "Can't create user";
            $_SESSION["register_form_errors"] = $errors;
            header("Location: " . "../register.php");
        }
    }
} else {
    $_SESSION["register_form_errors"] = $errors;
    header("Location: " . "../register.php");
}

function validateInput($login, $email, $password, $password2, array $errors): array
{
    if ((strlen($login) < 3) || (strlen($login) > 20)) {
        $errors["login_length"] = "Login must be between 3 and 20 characters long";
    }
    if (!ctype_alnum($login)) {
        $errors["login_charcters"] = "Login can only consist of letters and numbers";
    }

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
        $errors["password_length"] = "Password must be between 8 and 20 characters long";
    }
    if ($password != $password2) {
        $errors["password_match"] = "Passwords doesn't match";
    }

    return $errors;
}

function checkForDuplicates($login, $email, array $errors): array
{
    $userOperation = new UserOperations();
    $users = $userOperation->getUserByLoginOrEmail($login, $email);
    foreach ($users as $duplicate) {
        if ($duplicate['login'] === $login)
            $errors["login_duplicate"] = "Login exist in database";
        if ($duplicate['email'] === $email)
            $errors["email_duplicate"] = "Email exist in database";
    }

    return $errors;
}
