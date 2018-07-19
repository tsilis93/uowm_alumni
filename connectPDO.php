<?php

$hostname = "/zstorage/home/ictest00552/mysql/run/mysql.sock";
$username = "root";
$password = "";
$dbname = "alumni";

try {
$conn = new PDO("mysql:unix_socket=$hostname;dbname=$dbname", $username, $password);
$conn->exec("set names utf8");
$conn->setAttribute(PDO::ATTR_EMULATE_PREPARES, false);
$conn->query('set character_set_client=utf8');
$conn->query('set character_set_connection=utf8');
$conn->query('set character_set_results=utf8');
$conn->query('set character_set_server=utf8');
}
catch(PDOException $e)
{
echo $e->getMessage();
}

?>

