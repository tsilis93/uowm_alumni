<?php

session_start();

include ("../connectPDO.php");
	
$id = $_SESSION["name"];
	

$stmt = $conn->prepare("SELECT count(*) FROM notifications WHERE admin_id != 0");
$stmt->execute();
$num_notifications = $stmt->fetch(PDO::FETCH_NUM);
		
echo json_encode($num_notifications);
exit;
	
?>