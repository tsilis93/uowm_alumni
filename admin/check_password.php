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

if(isset($_POST['pasword'])) {
	$password = $_POST['pasword'];	
}
$password = hash('sha512', $password, FALSE);


$stmt = $conn->prepare("SELECT * FROM users WHERE id = ?");
$stmt->execute(array($user_id));
$result = $stmt->fetchAll();
			
if(sizeof($result) > 0) {
	foreach($result as $row) {
		if($row['password'] == $password) {
			echo "Σωστό password";  //επιστρέφω response
		}
		else
		{
			// δεν επιστρέφω response
		}
	}
}

?>