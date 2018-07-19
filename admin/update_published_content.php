<?php

session_start();
include ("../connectPDO.php");

if(isset($_POST['content_id'])) {
	$content_id = $_POST['content_id'];
}

if(isset($_POST['option'])) {   
	$option = $_POST['option']; 
}
if(isset($_POST['action'])) {  //action = 1 => θέλει
	$choice = $_POST['action']; //action = 0 => δεν θέλει
}


$sql = "UPDATE contents SET ".$option." = ? WHERE id = ?";
$stmt = $conn->prepare($sql);
if($stmt->execute(array($choice, $content_id))) {
		
}

?>