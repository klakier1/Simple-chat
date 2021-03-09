<?php

require_once "DbConnection.php";

class MessageOperations
{

    private $con;

    public function __construct()
    {
        $db = new DbConnection();
        $this->con = $db->getConnection();
    }

    public function insertMessage($senderID, $receiverID, $message): bool
    {
        $st = $this->con->prepare(
            "INSERT INTO public.simple_chat_messages(
                sender_id, receiver_id, message)
                VALUES (?, ?, ?);"
        );
        return $st->execute([$senderID, $receiverID, $message]);
    }


    public function getConversation($senderID, $receiverID, $lastMsgId = 0): bool|array
    {
        if ($lastMsgId == 0) {
            $st = $this->con->prepare(
                "SELECT *
                FROM public.simple_chat_messages
                WHERE (sender_id = ? AND receiver_id = ?) 
                OR (sender_id = ? AND receiver_id = ?);"
            );
            if ($st->execute([$senderID, $receiverID, $receiverID, $senderID])) {
                return $st->fetchAll(PDO::FETCH_ASSOC);
            }
            return false;
        } else {
            $st = $this->con->prepare(
                "SELECT *
                FROM public.simple_chat_messages
                WHERE ((sender_id = ? AND receiver_id = ?) 
                OR (sender_id = ? AND receiver_id = ?))
                AND id > ?;"
            );
            if ($st->execute([$senderID, $receiverID, $receiverID, $senderID, $lastMsgId])) {
                return $st->fetchAll(PDO::FETCH_ASSOC);
            }
            return false;
        }
    }
}
