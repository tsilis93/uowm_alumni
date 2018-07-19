<?php

session_start();
include ("../connectPDO.php");

if(isset($_POST['id'])) {
	$id = $_POST['id'];
}


$sql = "DELETE from newsletter_content WHERE id = ?";
$stmt = $conn->prepare($sql);
if ($stmt->execute(array($id))) 
{
	
}

?>