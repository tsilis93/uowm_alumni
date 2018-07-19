<?php

session_start();
include ("../connectPDO.php");

if(isset($_SESSION['student'])) {
	$alumni_id = $_SESSION['student'];
} 

if(isset($_POST['recipients'])) {
	$ids = $_POST['recipients'];
}

if(isset($_POST['title']))
{
	$subject = $_POST['title'];
}
if(isset($_POST['email_content']))
{
	$message = $_POST['email_content'];
}

$stmt = $conn->prepare("SELECT * FROM users WHERE id = ?");
$stmt->execute(array($alumni_id));
$result = $stmt->fetchAll();
	
foreach($result as $row) {	
		$headers = $row['email'];
}

$email_counter = 0; 
for($i=0; $i<count($ids); $i++) {	//δημιουργία της μεταβλητής των παραλήπτων
	$stmt = $conn->prepare("SELECT * FROM users WHERE id = ?");
	$stmt->execute(array($ids[$i]));
	$result = $stmt->fetchAll();
	
	foreach($result as $row) {	
		$email = $row['email'];
	}
	
	$stmt2 = $conn->prepare("INSERT INTO email_table (recipient, subject, message, header) VALUES (?, ?, ?, ?)");
	if($stmt2->execute(array($email,$subject,$message,$headers))) {
		$email_counter = $email_counter + 1;
	}
		
}	

if($email_counter == 1 && $email_counter == count($ids)) 
{
	echo  $email_counter . " email στάλθηκε με επιτυχία";
}
else if ($email_counter == count($ids))
{
	echo  $email_counter . " email στάλθηκαν με επιτυχία";
}
else
{
	echo "Υπήρξε πρόβλημα στην αποστολή κάποιων email";
}

?>



