<?php

session_start();
include ("../connectPDO.php");


$alumni_id = $_POST['user_id'];
$friend_alumni_id = $_POST['alumni_id'];

$sql = "DELETE FROM user_relationship WHERE friend_alumni_id = ?";        
$stmt = $conn->prepare($sql);
if($stmt->execute(array($friend_alumni_id)))
{
	echo "Σταματήσατε να ακολουθάτε τον απόφοιτο με επιτυχία";
}

?>