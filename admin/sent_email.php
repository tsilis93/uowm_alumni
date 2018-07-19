<?php

include ("../connectPDO.php"); 

if(isset($_POST['recipients'])) {
	$ids = $_POST['recipients'];
	//$ids = [1,2,3];
}

if(isset($_POST['title']))
{
	$subject = $_POST['title'];
}
else
{
	$subject = "Αλλαγής κώδικου πρόσβασης";
}
if(isset($_POST['email_content']))
{
	$message = $_POST['email_content'];
}
else
{
	$message = "Σας υπενθυμίζουμε ότι θα πρέπει να αλλάξετε τον κωδικό πρόσβασης στο λογαριασμό σας για λόγους ασφαλείας";
}
 
// email_table
$headers = 'From: <webmaster@alumni.uowm.gr>' . "\r\n";
 
for($i=0; $i<count($ids); $i++) {	//δημιουργία της μεταβλητής των παραλήπτων
	$stmt = $conn->prepare("SELECT * FROM users WHERE id = ?");
	$stmt->execute(array($ids[$i]));
	$result = $stmt->fetchAll();
	
	foreach($result as $row) {	
		$email = $row['email'];
	}
	
	$stmt2 = $conn->prepare("INSERT INTO email_table (recipient, subject, message, header) VALUES (?, ?, ?, ?)");
	$stmt2->execute(array($email,$subject,$message,$headers));
		
}	
	/*
	if($i == 0) {
		$to = $email . ", ";
	}
	else if ($i == count($ids)-1)
	{
		$to = $to . $email;
	}
		else
	{
		$to = $to . $email . ", ";
	}

	mail($to,$subject,$message,$headers);
	*/

?>



