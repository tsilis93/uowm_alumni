<?php

session_start();
include ("../connectPDO.php");

$user_id = 0;
if(isset($_SESSION['name'])) {
	$user_id = $_SESSION['name'];
}

if(isset($_POST['username'])) {
	$username = $_POST['username'];	
}

$sql = "UPDATE users SET username = ? WHERE id = ?";
$stmt = $conn->prepare($sql);
if($stmt->execute(array($username, $user_id))) {
	echo "Οι αλλαγη του username έγινε με επιτυχία";
}
			
?>