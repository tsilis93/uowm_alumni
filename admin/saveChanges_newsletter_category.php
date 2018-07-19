<?php

session_start();
include ("../connectPDO.php");

if(isset($_POST['id'])) {
	$id = $_POST['id'];
}

if(isset($_POST['text'])) {
	$text = $_POST['text'];
}

$sql = "UPDATE newsletter_categories SET category_name = ? WHERE id = ?";
$stmt = $conn->prepare($sql);
$stmt->execute(array($text, $id));


?>