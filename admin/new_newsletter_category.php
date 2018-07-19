<?php
session_start();
include ("../connectPDO.php");

if(isset($_POST['text'])) {
	$text = $_POST['text'];
}

$sql = "INSERT INTO newsletter_categories (category_name) VALUES (?)";
$stmt = $conn->prepare($sql);
if ($stmt->execute(array($text))) 
{
	$last_id = $conn->lastInsertId();
	$newname = "option_id".$last_id;
	
	$sql2 = "ALTER TABLE newsletter ADD ".$newname." INT(11) NOT NULL";
	$stmt2 = $conn->prepare($sql2);
	if ($stmt2->execute()) {
		//echo $done;
	}
	
	$sql2 = "ALTER TABLE newsletter_content ADD ".$newname." INT(11) NOT NULL";
	$stmt2 = $conn->prepare($sql2);
	if ($stmt2->execute()) {
	//	echo $done;
	}	
}

?>