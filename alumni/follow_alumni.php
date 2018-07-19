<?php

session_start();
include ("../connectPDO.php");

if(isset($_SESSION['student'])) {
	$alumni_id = $_SESSION['student'];
}
$friend_alumni_id = $_POST['alumni_id'];

$sql = "INSERT INTO user_relationship (alumni_id, friend_alumni_id) VALUES (?, ?)";        
$stmt = $conn->prepare($sql);
if($stmt->execute(array($alumni_id,$friend_alumni_id)))
{
	echo "Ακολουθείτε τον απόφοιτο με επιτυχία";
}

?>