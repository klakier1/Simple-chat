<?php

require "Db/MessageOperation.php";

session_start();
$response = array();

/**
 * POST
 * receiverId
 * lastMsgId (optional)
 */

if (isset($_SESSION['logged_user'])) {
    $user = $_SESSION['logged_user'];

    $senderID = $user['id'];
    $receiverID = $_POST['receiverId'];
    $lastMsgId = null;
    if (isset($_POST['lastMsgId'])) $lastMsgId = $_POST['lastMsgId'];


    $msgOps = new MessageOperations();
    if (!$lastMsgId)
        $msgList = $msgOps->getConversation($senderID, $receiverID);
    else
        $msgList = $msgOps->getConversation($senderID, $receiverID, $lastMsgId);

    if (!$msgList) {
        $response["count"] = 0;
    } else {
        $response["count"] = count($msgList);
        $response["msg"] = $msgList;
    }
} else {
    $response["error"] = "user not logged"; //normaly not possible
}

header('Content-Type: application/json');
echo json_encode($response);
