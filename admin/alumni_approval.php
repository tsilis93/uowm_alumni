<?php

include ("../connectPDO.php");

if(isset($_GET['approve'])) {
	
	$id = $_GET['approve'];
	
	$aem = $_GET['aem'];
	$did = $_GET['did'];
	$email = $_GET['email'];
	$cell_phone = $_GET['cell_phone'];
	
	$stmt2 = $conn->prepare("SELECT * FROM users WHERE id=?");
	$stmt2->execute(array($id));
	$result = $stmt2->fetchAll();
	
	if(sizeof($result)>0) {
		foreach($result as $row) {
			$lastname = $row['lastname'];
			$name = $row['name'];		
		}
	}
	
	$created_by = 2; //approved so delete me from pending list
	//$active = 1;
	$active = 0;	//active = 1 => ενεργός, active = 0 => ανενεργός
	// στην φαση αυτή ο αποφοιτος είναι ανενεργός καθώς δεν εχει επιβεβαιώσει το email του
	$role = 1;		//role = 1 => απόφοιτος, role = 2 => διαχειριστής, role = 3 => απόφοιτος & διαχειριστής
	
	$hash = md5(rand(0,1000)); //hash κωδικος για την επιβεβαίωση του email από τον χρήστη
	$page = "verify.php?email=$email&hash=$hash";
	$url = $_SERVER['REQUEST_URI']; //returns the current URL
        $parts = explode('/',$url);
        $homedirectory = $_SERVER['SERVER_NAME'];
        for ($i = 0; $i < count($parts) - 2; $i++) {
            $homedirectory .= $parts[$i] . "/";
        }
        $redirect = "https://".$homedirectory.$page;

	
	$username = $lastname.$name;
	$username = greeklish($username);
	$pass = randomPassword();
	$password = hash('sha512', $pass);
	
	$sql = "UPDATE users SET created_by = ?, username = ?, active = ?,  hash = ?, role = ?, password = ?, aem = ?, department_id = ?, email = ?, cell_phone = ? WHERE id = ?";
	$stmt = $conn->prepare($sql);
	$stmt->execute(array($created_by, $username, $active, $hash, $role, $password, $aem, $did, $email, $cell_phone, $id));
		
	$headers = 'From: <webmaster@alumni.uowm.gr>' . "\r\n";   //αποστολή του κωδικού προσβασης στο email
	$message = "Καλώς ήρθατε στην ιστοσελίδα των αποφοίτων του Πανεπιστημίου Δυτικής Μακεδονίας.\nΓια να συνδεθείτε στο profil σας χρησιμοποιείστε:\n\n\n---------------\nusername:".$username."\npassword:".$pass."\n---------------\n\nΣας υπενθυμίζουμε ότι μετά την 1η επιτυχή είσοδο θα πρέπει να αλλάξετε τον κωδικό πρόσβασης σας για λόγους ασφαλείας.\nΤέλος, για να ολοκληρωθεί η διαδικασία ενεργοποίησης του λογαριασμού σας πατήστε στο παρακάτω link:\n\n\n $redirect";
	$subject = "Ιστοσελίδα αποφοίτων Πανεπιστημίου Δυτικής Μακεδονίας (ενεργοποίηση λογαριασμού - username & password)";
	$to = $email;
	
	mail($to,$subject,$message,$headers);
	
header("location:pending_alumni_registry.php");	

}


if (isset($_GET['reject'])) {
	
	$id = $_GET['reject'];
		
	$headers = 'From: <webmaster@alumni.uowm.gr>' . "\r\n";   //αποστολή της απορριψης της αίτησης 
	$message = "Σας ενημερώνουμε ότι η αίτηση εγγραφής σας απορρίφθηκε από τους διαχειριστές της ιστοσελίδας. Για περισσότερες πληροφορίες επικοινωνήστε μαζί μας στο χχχχ@gmail.com";
	$subject = "Ιστοσελίδα αποφοίτων Δυτικής Μακεδονίας (Απόρριψη αίτησης εγγραφής)";
	
	$stmt2 = $conn->prepare("SELECT email FROM users WHERE id=?");
	$stmt2->execute(array($id));
	$email = $stmt2->fetchAll();

	$to = $email[0][0];
	
	mail($to,$subject,$message,$headers);
	
	$sql = "DELETE FROM users WHERE id = ?";
	$stmt = $conn->prepare($sql);
	$stmt->execute(array($id));
	
header("location:pending_alumni_registry.php");	
		
}


function greeklish($Name) {
	
$greek   = array('α','ά','Ά','Α','β','Β','γ', 'Γ', 'δ','Δ','ε','έ','Ε','Έ','ζ','Ζ','η','ή','Η','θ','Θ','ι','ί','ϊ','ΐ','Ι','Ί', 'κ','Κ','λ','Λ','μ','Μ','ν','Ν','ξ','Ξ','ο','ό','Ο','Ό','π','Π','ρ','Ρ','σ','ς', 'Σ','τ','Τ','υ','ύ','Υ','Ύ','φ','Φ','χ','Χ','ψ','Ψ','ω','ώ','Ω','Ώ',' ');
$english = array('a', 'a','A','A','b','B','g','G','d','D','e','e','E','E','z','Z','i','i','I','th','Th', 'i','i','i','i','I','I','k','K','l','L','m','M','n','N','x','X','o','o','O','O','p','P' ,'r','R','s','s','S','t','T','u','u','Y','Y','f','F','ch','Ch','ps','Ps','o','o','O','O',' ');
$string  = str_replace($greek, $english, $Name);
return $string;

}

function randomPassword() {

$alphabet = "abcdefghijklmnopqrstuwxyzABCDEFGHIJKLMNOPQRSTUWXYZ0123456789";
$pass = array(); //declare $pass as an array
$alphaLength = strlen($alphabet) - 1; //put the length -1 in cache
for ($i = 0; $i < 8; $i++) {
    $n = rand(0, $alphaLength);
    $pass[] = $alphabet[$n];
}
$password = implode($pass);	//turn the array into a string
	
return $password;

}

?>
