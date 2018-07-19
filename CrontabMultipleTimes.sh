#!/usr/local/bin/php -q
<?php

$stmt = $conn->prepare("SELECT * FROM email_table"); 
$stmt->execute();													   
$result = $stmt->fetchAll();

if(sizeof($result)>0) {	//αν υπαρχουν email προς αποστολή 
	
	foreach($result as $row) {
								//παρε παραλήπτη, περιεχομενο, μηνυμα και header
		$to = $row['recipient'];	
		$subject = $row['subject'];
		$message = $row['message'];
		$header = $row['header'];
		$id = $row['id'];
		
		mail($email, $subject, $message, $header);	//στείλε τα email 1-1
		
		$stmt2 = $conn->prepare("DELETE FROM email_table WHERE id = ?"); //όταν το στειλεις διεγραψε το
		$stmt2->execute(array($id));
		
	}

}
else
{
	//αν δεν υπάρχουν μην κανεις τπτ
}


$stmt = $conn->prepare("SELECT * FROM users WHERE role = 2"); ^M
$stmt->execute();                                                                                                          ^M
$result = $stmt->fetchAll();^M

$stmt = $conn->prepare("SELECT * FROM notifications WHERE admin_id != 0"); ^M
$stmt->execute();                                                                                                          ^M
$result2 = $stmt->fetchAll();

$notification_number = sizeof($result2);


if(sizeof($result)>0) { 
        ^M
        foreach($result as $row) {
		if($notification_number > 0) {
			$email = $row['email'];

                	$subject = "Ιστοσελίδα Αποφοίτων Πανεπιστημίου Δυτικής Μακεδονίας (Notifications)";^M
                	$message = "Υπάρχουν διαθέσιμες ειδοποιήσεις στο profil σας";^M
                	$header = 'From: <webmaster@alumni.uowm.gr>' . "\r\n";^M
               

                	mail($email, $subject, $message, $header);
		} 
	}
}
?>
