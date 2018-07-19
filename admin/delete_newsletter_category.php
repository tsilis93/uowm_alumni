<?php

session_start();
include ("../connectPDO.php");

if(isset($_POST['id'])) {
	$id = $_POST['id'];
}


$sql = "DELETE from newsletter_categories WHERE id = ?";
$stmt = $conn->prepare($sql);
if ($stmt->execute(array($id))) 
{
	$name = "option_id".$id;
	
	$sql2 = "ALTER TABLE newsletter DROP ".$name."";
	$stmt2 = $conn->prepare($sql2);
	if ($stmt2->execute()) {
		//echo $done;
	}
	
	$sql2 = "ALTER TABLE newsletter_content DROP ".$name."";
	$stmt2 = $conn->prepare($sql2);
	if ($stmt2->execute()) {
		//echo $done;
	}	
}


?>