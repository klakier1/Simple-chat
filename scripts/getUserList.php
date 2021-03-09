<?php

require "Db/UserOperations.php";

session_start();
$response = array();

if (isset($_SESSION['logged_user'])) {
    $user = $_SESSION['logged_user'];
    $userOperations = new UserOperations();
    $usersList = $userOperations->getUsersExcept($user['id']);

    if (!$usersList) {
        $response["count"] = 0;
    } else {
        $response["count"] = count($usersList);
        $response["users"] = $usersList;
    }
} else {
    $response["error"] = "user not logged"; //normaly not possible
}

header('Content-Type: application/json');
echo json_encode($response);
