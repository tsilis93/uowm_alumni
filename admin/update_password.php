<?php

session_start();
include ("../connectPDO.php");

$user_id = 0;
if(isset($_SESSION['name'])) {
	$user_id = $_SESSION['name'];
}

if(isset($_POST['alumni_id'])) {
	$user_id = $_POST['alumni_id'];
}

if(isset($_POST['pass'])) {
	$password = $_POST['pass'];	
}
$password = hash('sha512', $password, FALSE);
$change = 1;


$sql = "UPDATE users SET change_password = ?, password = ? WHERE id = ?";
$stmt = $conn->prepare($sql);
if($stmt->execute(array($change, $password, $user_id))) {
	echo "Οι αλλαγη του κωδικού πρόσβασης έγινε με επιτυχία";
}
			
?>