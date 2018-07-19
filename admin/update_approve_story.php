<?php

include ("../connectPDO.php");
include ("../getPublicationId.php");

$title = $_POST['title1'];
$description = htmlspecialchars($_POST['description1']);
$body = htmlspecialchars($_POST['body1']);
$editor = $_POST['editor1'];
$storyid = $_POST['story_id1'];
$date = date("Y-m-d");
$status = 1;
$definition = $_POST['definition1'];

$publication_id = getStoryMaxPublicationid();
$publication_id = $publication_id + 1;
	
$sql = "UPDATE stories SET title = ?, description = ?, body = ?, status = ?, publication_date = ?, publication_id = ?, definition = ? WHERE id = ?";
$stmt = $conn->prepare($sql);
$stmt->execute(array($title, $description, $body, $status, $date, $publication_id, $definition, $storyid));

$fullnamearray = explode(" ", $editor);
$name = $fullnamearray[0];
$lastname = $fullnamearray[1];

$stmt2 = $conn->prepare("SELECT id FROM users WHERE (name=? AND lastname = ?)");
$stmt2->execute(array($name,$lastname));
$author = $stmt2->fetchAll();

$alumni_id = $author[0][0];
$admin = 0; 	

$text = "Η ιστορία με τίτλο '" . $title . "' εγκρίθηκε και δημοσιεύτηκε από τον διαχειριστή του συστήματος";  
$stmt = $conn->prepare("INSERT INTO notifications (text, alumni_id, admin_id) VALUES (?, ?, ?)");
$stmt->bindParam(1, $text);
$stmt->bindParam(2, $alumni_id);
$stmt->bindParam(3, $admin);
$stmt->execute();

$stmt = $conn->prepare("SELECT * FROM user_relationship WHERE friend_alumni_id = ?");   // αν έχουν επιλέξει να ακολουθήσουν τον απόφοιτο
$stmt->execute(array($alumni_id));														// τότε πρέπει να ενημερώθουν ότι εγκρίθηκε η ιστορία του
$result3 = $stmt->fetchAll();

if (sizeof($result3)> 0) {			
	foreach($result3 as $row3) {
		$notify_alumni_id = $row3['alumni_id'];  // βρίσκω το id τους και δημιουργώ μια ειδοποίηση για αυτο
		$admin = 0;
		$text = "Ο απόφοιτος ". $lastname . " " . $name . " ανέβασε νέα Ιστορία";
					
		$stmt2 = $conn->prepare("INSERT INTO notifications (text, alumni_id, admin_id) VALUES (?, ?, ?)");
		$stmt2->bindParam(1, $text);
		$stmt2->bindParam(2, $notify_alumni_id);
		$stmt2->bindParam(3, $admin);
		$stmt2->execute();
	}	
}

?>


