<?php

session_start();
include ("../connectPDO.php");
if(isset($_SESSION['student'])) {
	$alumni_id = $_SESSION['student'];
}

if(isset($_POST['pass'])) {
	$password = $_POST['pass'];	
}
$password = hash('sha512', $password, FALSE);
$change = 1;


$sql = "UPDATE users SET change_password = ?, password = ? WHERE id = ?";
$stmt = $conn->prepare($sql);
if($stmt->execute(array($change, $password, $alumni_id))) {
	echo "Οι αλλαγη του κωδικού πρόσβασης έγινε με επιτυχία";
}
			
?>