<?php

require "Db/MessageOperation.php";

session_start();
$response = array();

/**
 * POST
 * receiverId
 * message
 */


if (isset($_SESSION['logged_user'])) {
    $user = $_SESSION['logged_user'];

    $senderID = $user['id'];
    $receiverID = $_POST['receiverId'];
    $message = $_POST['message'];

    $msgOps = new MessageOperations();
    $ret = $msgOps->insertMessage($senderID, $receiverID, $message);

    if ($ret) {
        $response["result"] = true;
    } else {
        $response["result"] = false;
    }
} else {
    $response["error"] = "user not logged"; //normaly not possible
}

header('Content-Type: application/json');
echo json_encode($response);
