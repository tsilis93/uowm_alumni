<?php	

session_start();
include ("../connectPDO.php");


if(isset($_POST['idTable'])) {
	$id_table = $_POST['idTable'];   //τα id ειναι δοσμένα με τέτοιο τρόπο ώστε να αντιστοιχούν στα πεδία του πίνακα στην βάση
}
if(isset($_POST['contentTable'])) {
	$content_table = $_POST['contentTable'];
}

if(isset($_POST['alumni_id'])) {
	$alumni_id = $_POST['alumni_id'];
}


$stmt2 = $conn->prepare("SELECT * FROM users WHERE id=?");
$stmt2->execute(array($alumni_id));
$result = $stmt2->fetchAll();
	
if(sizeof($result)>0) {
	foreach($result as $row) {
		$email = $row['email'];
	}
}


$query = "UPDATE users SET ";  //δημιουργία του query ανανέωσης

for($i=0; $i<sizeof($content_table); $i++) {
		
	if($i == sizeof($content_table)-1)
	{
		if($id_table[$i] == "graduation_date") { //αν ο χρήστης συμπληρωσει την ημερομηνία αποφοίτησης τοτε το ετος αποφοίτησης συμπληρωνεται αυτόματα
			$query = $query . $id_table[$i]. " = ?, graduation_year = ? WHERE id = ?";
		}
		else
		{
			$query = $query . $id_table[$i]. " = ? WHERE id = ?";
		}
	}
	else
	{
		if($id_table[$i] == "graduation_date") {
			$query = $query . $id_table[$i] . " = ?, graduation_year = ?, ";
		}
		else
		{
			$query = $query . $id_table[$i] . " = ?, ";
		}
	}
	
}

$count = count($content_table);
$graduation_date_found = false;
$graduation_date_found_pos = 0;
$new_code = false;

for($i=0; $i<$count; $i++) {
	if($id_table[$i] == "password") { //αν επιλεχτει να αλλαχτει ο κωδικός πρόσβασης τότε ενας νέος τυχαίος κωδικός δημιουργείται
		$password = randomPassword();		
		$content_table[$i] = hash('sha512', $password);
		$new_code = true;
	}
	if($id_table[$i] == "graduation_date") { //αν εντοπιστεί το πεδίο ημερομηνία αποφοίτησης
		$graduation_date_found = true;
		$graduation_date_found_pos = $i;
	}
}

if($graduation_date_found == true) {
	$date = $content_table[$graduation_date_found_pos];	//τότε πρεπει να παρουμε το ετος απο την ημερομηνία
	$year = strtok($date, '-');
	$year = intval($year);
	$content_table[$count] = $year;
	$count = $count + 1;
}
$content_table[$count] = $alumni_id;


$stmt = $conn->prepare($query);
if ($stmt->execute($content_table)) { }

if($new_code == true) {
	$headers = 'From: <webmaster@alumni.uowm.gr>' . "\r\n";   //�������� ��� ������� ��������� ��� email
	$message = "� ���� ������� ��������� (password) ��� �� profil ��� ���� �������� �����:\n\n\n---------------\npassword:".$password."\n---------------\n\n��� ������������� ��� ���� ��� 1� ������� ������ �� ������ �� �������� ��� ������ ��������� ��� ��� ������ ���������.";
	$subject = "���������� ��������� ������������� ������� ���������� (��� password)";
	$to = $email;
	mail($to,$subject,$message,$headers);
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