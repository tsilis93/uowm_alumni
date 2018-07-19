<?php

session_start();
include ("../connectPDO.php");

if(isset($_POST['story_id'])) {
	$story_id = $_POST['story_id'];
}

if(isset($_POST['option'])) {   
	$option = $_POST['option']; 
}
if(isset($_POST['action'])) {  //action = 1 => θέλει
	$choice = $_POST['action']; //action = 0 => δεν θέλει
}

$published = "published_department".$option;

$sql = "UPDATE stories SET ".$published." = ? WHERE id = ?";
$stmt = $conn->prepare($sql);
if($stmt->execute(array($choice, $story_id))) {
		
}

?>