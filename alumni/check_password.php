<?php

session_start();
include ("../connectPDO.php");
if(isset($_SESSION['student'])) {
	$alumni_id = $_SESSION['student'];
}

if(isset($_POST['pasword'])) {
	$password = $_POST['pasword'];	
}
$password = hash('sha512', $password, FALSE);


$stmt = $conn->prepare("SELECT * FROM users WHERE id = ?");
$stmt->execute(array($alumni_id));
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