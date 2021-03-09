<?php

require_once "DbConnection.php";

class UserOperations
{

    private $con;

    public function __construct()
    {
        $db = new DbConnection();
        $this->con = $db->getConnection();
    }

    /**
     * Insert User
     *
     * @param mixed $login
     * @param mixed $email
     * @param mixed $password_hash
     * 
     * @return bool
     */
    public function insertUser($login, $email, $password_hash): bool
    {
        $st = $this->con->prepare(
            "INSERT INTO public.simple_chat_users(
            login, email, password_hash)
            VALUES (?, ?, ?);"
        );
        return $st->execute([$login, $email, $password_hash]);
    }


    /**
     * Check if login or email exist in user table
     * 
     * @param mixed $login
     * @param mixed $email
     * 
     * @return bool|array
     * if no duplicates returns false, else array of duplicates
     */
    public function getUserByLoginOrEmail($login, $email): bool|array
    {
        $st = $this->con->prepare(
            "SELECT login, email
            FROM public.simple_chat_users
            WHERE login = ? OR email = ?;"
        );
        if ($st->execute([$login, $email])) {
            return $st->fetchAll(PDO::FETCH_ASSOC);
        }
        return false;
    }

    public function getUser($email): bool|array
    {
        $st = $this->con->prepare(
            "SELECT *
            FROM public.simple_chat_users
            WHERE email = ?;"
        );
        if ($st->execute([$email])) {
            return $st->fetchAll(PDO::FETCH_ASSOC);
        }
        return false;
    }

    public function getUsersExcept($exceptId): bool|array
    {
        $st = $this->con->prepare(
            "SELECT id, email, login
            FROM public.simple_chat_users
            WHERE id != ?;"
        );
        if ($st->execute([$exceptId])) {
            return $st->fetchAll(PDO::FETCH_ASSOC);
        }
        return false;
    }
}
