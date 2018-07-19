<?php

include ("connectPDO.php");

$found = true;
$username = "";
if(isset($_POST['username'])) {
	$username = $_POST['username'];
}
			
$stmt = $conn->prepare("SELECT * FROM users WHERE username = ?");
$stmt->execute(array($username));
$result = $stmt->fetchAll();

if(sizeof($result) > 0) {
	foreach($result as $row) {	
		$to = $row['email'];
		$alumni_id = $row['id'];
	}
	
	$alphabet = "abcdefghijklmnopqrstuwxyzABCDEFGHIJKLMNOPQRSTUWXYZ0123456789";
    $pass = array(); //declare $pass as an array
    $alphaLength = strlen($alphabet) - 1; //put the length -1 in cache
    for ($i = 0; $i < 8; $i++) {
        $n = rand(0, $alphaLength);
        $pass[] = $alphabet[$n];
    }
    $password = implode($pass);  //Δημιουργία νέου κωδικού
	
	$hash_password = hash('sha512', $password, FALSE); //κρυπτογραφηση του νέου κωδικού
	$change = 0;  // μεταβλητη boolean για την αλλαγή του νέου κωδικου

	$sql = "UPDATE users SET change_password = ?, password = ? WHERE id = ?";  //αποθήκευση στην βάση
	$stmt = $conn->prepare($sql);
	if($stmt->execute(array($change, $hash_password, $alumni_id))) {
		
	}
	
	//στοιχεια για το email αποστολής
	$subject = "Νέος κωδικός πρόσβασης";
	$message = "Ο νέος κωδικός πρόσβασης για την Ιστοσελίδα των Αποφοίτων είναι: ". $password ." \r\nΥπενθυμίζουμε ότι θα πρέπει να κάνετε αλλαγή του νέου κωδικού μετά την πρώτη σύνδεση.";
	$headers = 'From: <webmaster@alumni.uowm.gr>' . "\r\n";
	
	mail($to,$subject,$message,$headers);
	echo $found;
}
else
{
	$found = false;
	echo $found;
}

?>
