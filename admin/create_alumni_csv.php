<?php
include ("../connectPDO.php");

if(isset($_POST["submit"]) ) {

	$father = "";	//arxikopoihsh olwn twn aparaithtwn agnwstwn dedomenwn
	$degree_grade = 0;
	$phone = "";
	$residence_city = "";
	$linkedin = "";
	$facebook = "";
	$instagram = "";
	$twitter = "";
	$google = "";
	$youtube = "";
	$social = "";
	$diploma_thesis_topic = "";
	$job = 0;
	$Workpiece = "";
	$job_city = "";
	$metaptuxiako = "";
	$didaktoriko = "";
	$change_password = 0;
	$message = "";
	$aem = "";
	$cell_phone = "";
	
	if(!isset($_FILES['file']) || $_FILES['file']['error'] == UPLOAD_ERR_NO_FILE) {
		die(header("location:create_alumni.php?Failed=true&reason=blank"));
	} 
	else 
	{
		$mimes = array('application/vnd.ms-excel','text/plain','text/csv','text/tsv');
		if(in_array($_FILES['file']['type'],$mimes)){
		  die(header("location:create_alumni.php?Failed=true&reason=csv"));
		}
		$file = fopen( $_FILES["file"]["tmp_name"] , 'r+' );
		
		$linecount = 0;  //μετρητής των γραμμών του csv αρχείου
		while(!feof($file))
		{
			$array = fgetcsv($file);
			$linecount++;
		}
			
		$counter = 0;  //μετρητής δεδομένων
		for($i=0; $i<$linecount; $i++) {
			$lastname = $array[$counter];
			$counter = $counter + 1;
			$name = $array[$counter];
			$counter = $counter + 1;
			$email = $array[$counter];
			$counter = $counter + 1;
			$departmentID = $array[$counter];
				
			$username = $lastname.$name;
			$username = greeklish($username);
			$pass = randomPassword();
			$password = hash('sha512', $pass);  //hash password with sha512
			$active = 0;
			$role = 1;
			$created_by = 0;
			$hash = md5(rand(0,1000)); //hash κωδικος για την επιβεβαίωση του email από τον χρήστη
			
			$page = "verify.php?email=$email&hash=$hash";
			$url = $_SERVER['REQUEST_URI']; //returns the current URL
			$parts = explode('/',$url);
			$homedirectory = $_SERVER['SERVER_NAME'];
			for ($i = 0; $i < count($parts) - 2; $i++) {
				$homedirectory .= $parts[$i] . "/";
			}
			$redirect = "https://".$homedirectory.$page;
			
			$site = "https://".$homedirectory;
			
			$headers = 'From: <webmaster@alumni.uowm.gr>' . "\r\n";   //αποστολή του κωδικού προσβασης στο email
			$message = "Καλώς ήρθατε στην ιστοσελίδα των αποφοίτων του Πανεπιστημίου Δυτικής Μακεδονίας.\nΟι διαχειριστές της ιστοσελίδας έχουν δημιουργήσει ένα προσωπικό λογαριασμό για σας.\n\n Αν επιθυμείτε να επισκευτείτε την ιστοσελίδα χρησιμοποιείστε τον παρακάτω σύνδεσμο.\n\n$site \n\n Για να συνδεθείτε στο profil σας χρησιμοποιείστε:\n\n\n---------------\nusername:".$username."\npassword:".$pass."\n---------------\n\nΣας ενημερώνουμε ότι μετά την 1η επιτυχή είσοδο θα πρέπει να αλλάξετε τον κωδικό πρόσβασης σας για λόγους ασφαλείας.\nΑν επιθυμείτε να ενεργοποιήσετε τον λογαριασμο σας πατήστε στον παρακάτω σύνδεσμο:\n\n\n $redirect";
			$subject = "Ιστοσελίδα αποφοίτων Πανεπιστημίου Δυτικής Μακεδονίας (ενεργοποίηση λογαριασμού - username & password)";

			$sql = "INSERT INTO users (lastname, name, email, aem, cell_phone, messageToadmin, department_id, created_by, active, role, fathers_name, degree_grade, phone, residence_city, linkedin, facebook, instagram, twitter, google, youtube, social, diploma_thesis_topic, job, Workpiece, job_city, metaptuxiako, didaktoriko, change_password, hash, username, password) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)";
			$stmt = $conn->prepare($sql);
			if ($stmt->execute(array($lastname,$name,$email,$aem,$cell_phone,$message,$departmentID,$created_by,$active,$role, $father, $degree_grade, $phone, $residence_city, $linkedin, $facebook, $instagram, $twitter, $google, $youtube, $social, $diploma_thesis_topic, $job, $Workpiece, $job_city, $metaptuxiako, $didaktoriko, $change_password, $hash, $username, $password)))
			{
				$stmt2 = $conn->prepare("INSERT INTO email_table (recipient, subject, message, header) VALUES (?, ?, ?, ?)");
				$stmt2->execute(array($email,$subject,$message,$headers));
				mail($email,$subject,$message,$headers);
			}
						
			$counter = $counter + 1;
		}
		fclose($file);
		header("location:create_alumni.php?Failed=false&number=".$linecount."");
	}

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