<?php
	
include ("../connectPDO.php");
include ("../getPublicationId.php");
	
if(isset($_GET['approve'])) {

	$id = $_GET['approve'];
	$date = date("Y-m-d");
	$status = 1;
	$publication_id = getStoryMaxPublicationid();
	$publication_id = $publication_id + 1;
		
	$sql = "UPDATE stories SET publication_date = ?, status = ?, publication_id = ? WHERE id = ?";
	$stmt = $conn->prepare($sql);
	$stmt->execute(array($date, $status, $publication_id, $id));
		
	$stmt2 = $conn->prepare("SELECT userID, title FROM stories WHERE id = ?");
	$stmt2->execute(array($id));
	$author = $stmt2->fetchAll();

	$alumni_id = $author[0][0]; 
	$title = $author[0][1];	
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
			$text = "Ο απόφοιτος ". $lastname . " " . $name . " ανέβασε νέα Ιστορία";
			$admin = 0;
						
			$stmt2 = $conn->prepare("INSERT INTO notifications (text, alumni_id, admin_id) VALUES (?, ?, ?)");
			$stmt2->bindParam(1, $text);
			$stmt2->bindParam(2, $notify_alumni_id);
			$stmt2->bindParam(3, $admin);
			$stmt2->execute();
		}	
	}	
		
header("location:admin_story_approval.php");
		
}
	
if(isset($_GET['delete'])) {
		
	$id = $_GET['delete'];
	$publication_date = date("0000-00-00");
	$comments = "";
		
	if(isset($_POST['comment'])) {
		$comments = $_POST['comment'];
	}
	//echo $comments;

	$sql = "UPDATE stories SET publication_date = ?, comments = ? WHERE id = ?";
	$stmt = $conn->prepare($sql);
	$stmt->execute(array($publication_date, $comments, $id));
		
	$stmt2 = $conn->prepare("SELECT userID, title FROM stories WHERE id = ?");
	$stmt2->execute(array($id));
	$author = $stmt2->fetchAll();

	$alumni_id = $author[0][0]; 
	$title = $author[0][1];
	$admin = 0;	
	
	if (empty($comments))
	{
		$text = "Η ιστορία με τίτλο '" . $title . "' απορρίφθηκε από τον διαχειριστή του συστήματος";  
	}
	else
	{
		$text = "Η ιστορία με τίτλο '" . $title . "' απορρίφθηκε από τον διαχειριστή του συστήματος. Υπάρχουν διαθέσιμα σχόλια από τον διαχειριστή"; 
	}
	
	$stmt = $conn->prepare("INSERT INTO notifications (text, alumni_id, admin_id) VALUES (?, ?, ?)");
	$stmt->bindParam(1, $text);
	$stmt->bindParam(2, $alumni_id);
	$stmt->bindParam(3, $admin);
	$stmt->execute();
		
header("location:admin_story_approval.php");

}
	
?>


