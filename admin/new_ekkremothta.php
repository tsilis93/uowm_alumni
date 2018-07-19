<?php
session_start();
include ("../connectPDO.php");

if(isset($_POST['admin_id'])) {
	$admin_id = $_POST['admin_id'];
}

if(isset($_POST['text'])) {
	$text = $_POST['text'];
}

$sql = "INSERT INTO ekkremothtes (content, admin_id) VALUES (?, ?)";
$stmt = $conn->prepare($sql);
$stmt->execute(array($text, $admin_id));

?>	