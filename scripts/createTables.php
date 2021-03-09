<?php

// run only manual!!!

require "Db/UserOperations.php";
require "Db/MessageOperation.php";

$table_name_user = "simple_chat_users";
$table_name_messages = "simple_chat_messages";

$db = new DbConnection();
$con = $db->getConnection();

$statment = $con->query("DROP TABLE IF EXISTS $table_name_user CASCADE");

if (!$statment) {
    echo "Drop table $table_name_messages failed" . "<br>";
    die;
}
echo "Drop table $table_name_messages OK" . "<br>";

$statment = $con->query("DROP TABLE IF EXISTS $table_name_messages CASCADE");

if (!$statment) {
    echo "Drop table $table_name_user failed" . "<br>";
    die;
}
echo "Drop table $table_name_user OK" . "<br>";


$statment = $con->query(
    "CREATE TABLE public.$table_name_user
        (
            id SERIAL, 
            login VARCHAR(255) NOT NULL, 
            email VARCHAR(255) NOT NULL, 
            password_hash VARCHAR(255) NOT NULL, 
            PRIMARY KEY (id)
        )"
);

if (!$statment) {
    echo "Create table $table_name_user failed" . "<br>";
    die;
}
echo "Create table $table_name_user OK" . "<br>";

$statment = $con->query(
    "CREATE TABLE public.$table_name_messages
        (
            id SERIAL,
            sender_id integer NOT NULL,
            receiver_id integer NOT NULL,
            message character varying(1000) NOT NULL,
            PRIMARY KEY (id),
            CONSTRAINT sender FOREIGN KEY (sender_id)
                REFERENCES public.simple_chat_users (id) MATCH SIMPLE
                ON UPDATE NO ACTION
                ON DELETE NO ACTION
                NOT VALID,
            CONSTRAINT receiver FOREIGN KEY (receiver_id)
                REFERENCES public.simple_chat_users (id) MATCH SIMPLE
                ON UPDATE NO ACTION
                ON DELETE NO ACTION
                NOT VALID
        )"
);

if (!$statment) {
    echo "Create table $table_name_messages failed" . "<br>";
    die;
}
echo "Create table $table_name_messages OK" . "<br>";


die();

$dummyContent = [
    ["Klakier", "lukasz.paleczek@gmail.com"],
    ["Ania", "ania.ania@gmail.com"],
    ["JohnDoe", "johndoe@gmail.com"],
];

for ($i = 0; $i < 20; $i++) {
    $arr = ["User$i", "user$i@gmail.com"];
    array_push($dummyContent, $arr);
}
$dummyPassword = password_hash("qweqweqwe", PASSWORD_DEFAULT);

$uo = new UserOperations();
foreach ($dummyContent as $key => $value) {
    $uo->insertUser($value[0], $value[1], $dummyPassword);
}

$lorem = "Lorem ipsum dolor sit amet, consectetur adipiscing elit. Quisque euismod dapibus neque, sit amet mattis quam aliquam commodo. Morbi convallis diam id tempus dignissim.";

$mo = new MessageOperations();

for ($i = 0; $i < 1000; $i++) {
    $receiver_id = random_int(2, 5);
    $sender_id = 1;
    $lorem_start = random_int(0, strlen($lorem));
    $lorem_length = random_int($lorem_start, strlen($lorem));
    $io = random_int(0, 1);

    $mo->insertMessage(
        $io ? $receiver_id : $sender_id,
        $io ? $sender_id : $receiver_id,
        substr($lorem, $lorem_start, $lorem_length)
    );
}
