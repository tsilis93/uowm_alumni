<?php	

session_start();
include ("../connectPDO.php");


if(isset($_POST['idTable'])) {
	$id_table = $_POST['idTable'];   //Ο„Ξ± id ΞµΞΉΞ½Ξ±ΞΉ Ξ΄ΞΏΟƒΞΌΞ­Ξ½Ξ± ΞΌΞµ Ο„Ξ­Ο„ΞΏΞΉΞΏ Ο„ΟΟΟ€ΞΏ ΟΟƒΟ„Ξµ Ξ½Ξ± Ξ±Ξ½Ο„ΞΉΟƒΟ„ΞΏΞΉΟ‡ΞΏΟΞ½ ΟƒΟ„Ξ± Ο€ΞµΞ΄Ξ―Ξ± Ο„ΞΏΟ… Ο€Ξ―Ξ½Ξ±ΞΊΞ± ΟƒΟ„Ξ·Ξ½ Ξ²Ξ¬ΟƒΞ·
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


$query = "UPDATE users SET ";  //Ξ΄Ξ·ΞΌΞΉΞΏΟ…ΟΞ³Ξ―Ξ± Ο„ΞΏΟ… query Ξ±Ξ½Ξ±Ξ½Ξ­Ο‰ΟƒΞ·Ο‚

for($i=0; $i<sizeof($content_table); $i++) {
		
	if($i == sizeof($content_table)-1)
	{
		if($id_table[$i] == "graduation_date") { //Ξ±Ξ½ ΞΏ Ο‡ΟΞ®ΟƒΟ„Ξ·Ο‚ ΟƒΟ…ΞΌΟ€Ξ»Ξ·ΟΟ‰ΟƒΞµΞΉ Ο„Ξ·Ξ½ Ξ·ΞΌΞµΟΞΏΞΌΞ·Ξ½Ξ―Ξ± Ξ±Ο€ΞΏΟ†ΞΏΞ―Ο„Ξ·ΟƒΞ·Ο‚ Ο„ΞΏΟ„Ξµ Ο„ΞΏ ΞµΟ„ΞΏΟ‚ Ξ±Ο€ΞΏΟ†ΞΏΞ―Ο„Ξ·ΟƒΞ·Ο‚ ΟƒΟ…ΞΌΟ€Ξ»Ξ·ΟΟ‰Ξ½ΞµΟ„Ξ±ΞΉ Ξ±Ο…Ο„ΟΞΌΞ±Ο„Ξ±
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
	if($id_table[$i] == "password") { //Ξ±Ξ½ ΞµΟ€ΞΉΞ»ΞµΟ‡Ο„ΞµΞΉ Ξ½Ξ± Ξ±Ξ»Ξ»Ξ±Ο‡Ο„ΞµΞΉ ΞΏ ΞΊΟ‰Ξ΄ΞΉΞΊΟΟ‚ Ο€ΟΟΟƒΞ²Ξ±ΟƒΞ·Ο‚ Ο„ΟΟ„Ξµ ΞµΞ½Ξ±Ο‚ Ξ½Ξ­ΞΏΟ‚ Ο„Ο…Ο‡Ξ±Ξ―ΞΏΟ‚ ΞΊΟ‰Ξ΄ΞΉΞΊΟΟ‚ Ξ΄Ξ·ΞΌΞΉΞΏΟ…ΟΞ³ΞµΞ―Ο„Ξ±ΞΉ
		$password = randomPassword();		
		$content_table[$i] = hash('sha512', $password);
		$new_code = true;
	}
	if($id_table[$i] == "graduation_date") { //Ξ±Ξ½ ΞµΞ½Ο„ΞΏΟ€ΞΉΟƒΟ„ΞµΞ― Ο„ΞΏ Ο€ΞµΞ΄Ξ―ΞΏ Ξ·ΞΌΞµΟΞΏΞΌΞ·Ξ½Ξ―Ξ± Ξ±Ο€ΞΏΟ†ΞΏΞ―Ο„Ξ·ΟƒΞ·Ο‚
		$graduation_date_found = true;
		$graduation_date_found_pos = $i;
	}
}

if($graduation_date_found == true) {
	$date = $content_table[$graduation_date_found_pos];	//Ο„ΟΟ„Ξµ Ο€ΟΞµΟ€ΞµΞΉ Ξ½Ξ± Ο€Ξ±ΟΞΏΟ…ΞΌΞµ Ο„ΞΏ ΞµΟ„ΞΏΟ‚ Ξ±Ο€ΞΏ Ο„Ξ·Ξ½ Ξ·ΞΌΞµΟΞΏΞΌΞ·Ξ½Ξ―Ξ±
	$year = strtok($date, '-');
	$year = intval($year);
	$content_table[$count] = $year;
	$count = $count + 1;
}
$content_table[$count] = $alumni_id;


$stmt = $conn->prepare($query);
if ($stmt->execute($content_table)) { }

if($new_code == true) {
	$headers = 'From: <webmaster@alumni.uowm.gr>' . "\r\n";   //αποστολή του κωδικού προσβασης στο email
	$message = "Ο νέος κωδικός πρόσβασης (password) για το profil σας στον ιστότοπο είναι:\n\n\n---------------\npassword:".$password."\n---------------\n\nΣας υπενθυμίζουμε ότι μετά την 1η επιτυχή είσοδο θα πρέπει να αλλάξετε τον κωδικό πρόσβασης σας για λόγους ασφαλείας.";
	$subject = "Ιστοσελίδα αποφοίτων Πανεπιστημίου Δυτικής Μακεδονίας (Νέο password)";
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