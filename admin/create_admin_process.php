<?php

include ("../connectPDO.php");

$created = true;

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
$cell_phone = "";
$aem = "";
$department_id = 0;
$hash = "";

$name = $_POST['name1'];
$email = $_POST['mail1'];
$lastname = $_POST['lastname1'];
$role = $_POST['role1'];

$username = $lastname.$name;
$username = greeklish($username);
$pass = randomPassword();
$password = hash('sha512', $pass);  //hash password with sha512

$created_by = 0;  //created_by = 1 =>απόφοιτος, created_by = 0 => διαχειριστής
$active = 1;	//active = 1 => ενεργός, active = 0 => ανενεργός

$headers = 'From: <webmaster@alumni.uowm.gr>' . "\r\n";   //αποστολή του κωδικού προσβασης στο email
$to = $mail;
$message2 = "Καλώς ήρθατε στην ιστοσελίδα των αποφοίτων του Πανεπιστημίου Δυτικής Μακεδονίας. Δημιουργήθηκε και υπάρχει διαθέσιμος λογαριασμός για εσάς με το ρόλο του διαχειριστή.\n\n Για να συνδεθείτε στο profil σας χρησιμοποιείστε:\n\n\n---------------\nusername:".$username."\npassword:".$pass."\n---------------\n\n";
$subject = "Ιστοσελίδα αποφοίτων Πανεπιστημίου Δυτικής Μακεδονίας (username & password )";
mail($to,$subject,$message2,$headers); //αποστολή του κωδικού προσβασης στο email 

$sql = "INSERT INTO users (lastname, name, email, aem, cell_phone, messageToadmin, department_id, created_by, active, role, fathers_name, degree_grade, phone, residence_city, linkedin, facebook, instagram, twitter, google, youtube, social, diploma_thesis_topic, job, Workpiece, job_city, metaptuxiako, didaktoriko, change_password, hash, username, password) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)";
$stmt = $conn->prepare($sql);
if ($stmt->execute(array($lastname,$name,$email,$aem,$cell_phone,$message,$department_id,$created_by,$active,$role, $father, $degree_grade, $phone, $residence_city, $linkedin, $facebook, $instagram, $twitter, $google, $youtube, $social, $diploma_thesis_topic, $job, $Workpiece, $job_city, $metaptuxiako, $didaktoriko, $change_password, $hash, $username, $password)))
{
  echo $created;
}
else
{
  $created = false;	
  echo $created;	
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