<?php

session_start();
include ("../connectPDO.php");

if(isset($_SESSION['student'])) {
	$alumni_id = $_SESSION['student'];
}
$friend_alumni_id = $_POST['alumni_id'];

$sql = "DELETE FROM user_relationship WHERE friend_alumni_id = ?";        
$stmt = $conn->prepare($sql);
if($stmt->execute(array($friend_alumni_id)))
{
	echo "Σταματήσατε να ακολουθάτε τον απόφοιτο με επιτυχία";
}

?>