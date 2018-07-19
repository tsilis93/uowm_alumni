<?php

session_start();
include ("../connectPDO.php");


if(isset($_POST['id'])) {
	$id = $_POST['id'];
}

//$id = 1;

$stmt = $conn->prepare("SELECT * FROM newsletter_content WHERE id = ?"); //παρε τις πληροφορίες για το newsletter που μας ενδιαφέρει
$stmt->execute(array($id));
$result = $stmt->fetchAll();

$stmt2 = $conn->prepare("SELECT * FROM newsletter_categories"); //παρε τον αριθμό και το όνομα από όλες τις κατηγορίες
$stmt2->execute();
$result2 = $stmt2->fetchAll();

$newsletter_categories = array(); //πίνακας κατηγοριών στις οποίες ανήκει το newsletter
$metritis = 0; //μετρητής για τον παραπάνω πίνακα

if(sizeof($result)>0) {
	foreach($result as $row) { 
		$body_html = $row['body_html'];
		$titlos = $row['titlos'];
		foreach ($result2 as $row2) {
			$id = $row2['id'];
			$option = "option_id".$id;
			if($row[$option] == 1) {						//για το newsletter που μας ενδιαφέρει
				$newsletter_categories[$metritis] = $option; //παίρνουμε (ξεχωρίζουμε) τις κατηγορίες που ανήκει ωστε να το στειλουμε στους ενδιαφερόμενους
				$metritis = $metritis + 1;
			}												// που έχουν επιλέξει να δεχονται newsletter για τις συγκεκριμένες κατηγορίες 
		}
	}
}

$alumni_ids_table = array();  //πίνακας για την αποθήκευση των id των αποφοίτων
$counter = 0;	//μετρητής για τον παραπάνω πίνακα

$category_count = count($newsletter_categories);

$stmt3 = $conn->prepare("SELECT * FROM newsletter"); //για όλες τις εγγραφές στον πίνακα που περιλαμβάνει τις επιλογές των χρηστών
$stmt3->execute();
$result3 = $stmt3->fetchAll();

if(sizeof($result3)>0) {
	foreach($result3 as $row3) {
		$alumni_id = $row3['alumni_id'];  //πάρε το id του χρήστη
		for($i=0; $i<$category_count; $i++) { //για κάθε κατηγορία στην οποία ανήκει το newsletter
			if($row3[$newsletter_categories[$i]] == 1) { // αν ο χρήστης εχει επιλέξει να την δεχεται
				if(!(in_array($alumni_id,$alumni_ids_table))) { //τσεκαρε αν το id του βρίσκεται στον πίνακα αποθήκευσης
					$alumni_ids_table[$counter] = $alumni_id; 	//αν όχι πρόσθεσε το
					$counter = $counter + 1;
				}
			}
		}	
	}
}

$alumni_email_table = array();

$counter = count($alumni_ids_table);
for($i=0; $i<$counter; $i++) {
	
	$stmt4 = $conn->prepare("SELECT * FROM users WHERE id = ?"); //βρίσκω τα email των αποφοίτων
	$stmt4->execute(array($alumni_ids_table[$i]));
	$result4 = $stmt4->fetchAll();
	
	if(sizeof($result4)>0) {
		foreach($result4 as $row4) {
			$alumni_email_table[$i] = $row4['email'];
		}
	}
}

$subject = $titlos; //σαν θέμα στο email χρησιμοποιείται ο τίτλος

//σύνθεση του περιεχομένου και του header του email
$boundary = md5(uniqid() . microtime());

$headers = "From: <webmaster@example.com>\r\n";
$headers .= "MIME-Version: 1.0\r\n";
$headers .= "Content-Type: multipart/mixed;boundary=" . $boundary . "\r\n";

//here is the content body
$message = "This is a MIME encoded message.";
$message .= "\r\n\r\n--" . $boundary . "\r\n";
$message .= "Content-type: text/plain;charset=utf-8\r\n\r\n";


//Plain text body
$message .= strip_tags($body_html);
$message .= "\r\n\r\n--" . $boundary . "\r\n";
$message .= "Content-type: text/html;charset=utf-8\r\n\r\n";

//Html body
$message .= urldecode($body_html);
$message .= "\r\n\r\n--" . $boundary . "--";

for($i=0; $i<$counter; $i++) {

	$stmt5 = $conn->prepare("INSERT INTO email_table (recipient, subject, message, header) VALUES (?, ?, ?, ?) "); //καταχωρώ στον πίνακα των email τα νέα email
	$stmt5->execute(array($alumni_email_table[$i],$subject,$message,$headers));
	
}

/* 										--Για testing--
$to = "tsilis93@gmail.com";
$subject = "Το πρώτο email με 2 parts";

if(!mail($to, $subject, $message, $headers)) 
{ 
    echo "Something went wrong!"; 
} 
else 
{ 
    echo "Message sent successfully. Check your email!"; 
} 
*/
?>