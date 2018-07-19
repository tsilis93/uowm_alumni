<?php

session_start();
include ("../connectPDO.php");


$alumni_id = $_POST['user_id'];
$friend_alumni_id = $_POST['alumni_id'];

$sql = "INSERT INTO user_relationship (alumni_id, friend_alumni_id) VALUES (?, ?)";        
$stmt = $conn->prepare($sql);
if($stmt->execute(array($alumni_id,$friend_alumni_id)))
{
	echo "Ακολουθείτε τον απόφοιτο με επιτυχία";
}

?>