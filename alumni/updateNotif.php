<?php

session_start();

include ("../connectPDO.php");
	
$id = $_SESSION["student"];
	

$stmt = $conn->prepare("SELECT count(*) FROM notifications WHERE alumni_id = ?");
$stmt->execute(array($id));
$num_notifications = $stmt->fetch(PDO::FETCH_NUM);
		
echo json_encode($num_notifications);
exit;
	
?>