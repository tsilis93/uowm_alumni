<?php

session_start();
include ("../connectPDO.php");

if(isset($_SESSION['student'])) {
	$alumni_id = $_SESSION['student'];
}

if(isset($_POST['option'])) {   //option = 1 => Νέα του πανεπιστημίου (επιλογη Α)
	$option = $_POST['option']; //option = 2 => Προτάσεις Εργασίας - Συνεργασίας (επιλογη Β)
}
if(isset($_POST['action'])) {  //action = 1 => θέλει
	$choice = $_POST['action']; //action = 0 => δεν θέλει
}

$option_id = "option_id".$option;

$stmt = $conn->prepare("SELECT * from newsletter WHERE alumni_id = ?");  
$stmt->execute(array($alumni_id));
$result = $stmt->fetchAll();

if (sizeof($result)>0) {   //αν υπάρχει καταχωρηση
		
	$sql = "UPDATE newsletter SET ".$option_id." = ? WHERE alumni_id = ?";
	$stmt = $conn->prepare($sql);
	if($stmt->execute(array($choice, $alumni_id))) {
			
	}
		
}
else
{
	$stmt2 = $conn->prepare("INSERT INTO newsletter (".$option_id.", alumni_id) VALUES (?, ?)");
	$stmt2->bindParam(1, $choice);
	$stmt2->bindParam(2, $alumni_id);
	if ($stmt2->execute()) {
		
	}
}
	
?>