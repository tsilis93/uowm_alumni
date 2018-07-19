<?php

session_start();
include ("../connectPDO.php");

if(isset($_SESSION['student'])) {
	
	if(isset($_POST['ids'])) {
		$ids = $_POST['ids'];
	}
	$notifications_counter = 0;

	for($i=0; $i<count($ids); $i++) {
		$stmt = $conn->prepare("DELETE FROM notifications WHERE id = ?");
		if($result = $stmt->execute(array($ids[$i]))) {
			$notifications_counter = $notifications_counter + 1;
		}
	}
	
	if($notifications_counter = count($ids)) {
		echo "Η διαγραφή των ειδοποιήσεων έγινε με επιτυχία. Διαγράφηκαν ".count($ids)." ειδοποιήσεις.";
	}
	else
	{	
		echo "Υπήρξε πρόβλημα κατα την διαγραφή των ειδοποιήσεων.";
	}
}
?>