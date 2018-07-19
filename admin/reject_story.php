<?php
		
include ("../connectPDO.php");
		
$id = $_POST['story_id1'];
$publication_date = date("0000-00-00");
$comments = "";
$editor = $_POST['editor1'];
$newstatus = 0;
		
if(isset($_POST['comments1'])) {
	$comments = $_POST['comments1'];
}

$sql = "SELECT * stories WHERE id = ?";
$stmt = $conn->prepare($sql);
$stmt->execute(array($id));
$result = $stmt->fetchAll();

foreach($result as $row) {
	$status = $row['status'];
}

if($status == 0) {	//η ιστορία είναι νέα
		
	$sql = "UPDATE stories SET publication_date = ?, comments = ? WHERE id = ?";
	$stmt = $conn->prepare($sql);
	$stmt->execute(array($publication_date, $comments, $id));
			
	$fullnamearray = explode(" ", $editor);
	$name = $fullnamearray[0];
	$lastname = $fullnamearray[1];

	$stmt2 = $conn->prepare("SELECT id FROM users WHERE (name = ? AND lastname = ?)");
	$stmt2->execute(array($name,$lastname));
	$author = $stmt2->fetchAll();

	$alumni_id = $author[0][0];
	$admin = 0;

	$stmt2 = $conn->prepare("SELECT title FROM stories WHERE id = ?");
	$stmt2->execute(array($id));
	$temp = $stmt2->fetchAll();

	$title = $temp[0][0]; 	

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
	
}
else	//η ιστορία ήταν δημοσιευμένη αλλά για κάποιο λογο "κατέβηκε"
{

	$sql = "UPDATE stories SET status = ?, comments = ? WHERE id = ?";
	$stmt = $conn->prepare($sql);
	$stmt->execute(array($newstatus, $comments, $id));
			
	$fullnamearray = explode(" ", $editor);
	$name = $fullnamearray[0];
	$lastname = $fullnamearray[1];

	$stmt2 = $conn->prepare("SELECT id FROM users WHERE (name = ? AND lastname = ?)");
	$stmt2->execute(array($name,$lastname));
	$author = $stmt2->fetchAll();

	$alumni_id = $author[0][0];
	$admin = 0;

	$stmt2 = $conn->prepare("SELECT title FROM stories WHERE id = ?");
	$stmt2->execute(array($id));
	$temp = $stmt2->fetchAll();

	$title = $temp[0][0]; 	

	if (empty($comments))
	{
		$text = "Η ιστορία με τίτλο '" . $title . "' κατέβηκε από τον διαχειριστή του συστήματος";  
	}
	else
	{
		$text = "Η ιστορία με τίτλο '" . $title . "' κατέβηκε από τον διαχειριστή του συστήματος. Υπάρχουν διαθέσιμα σχόλια από τον διαχειριστή"; 
	}

	$stmt = $conn->prepare("INSERT INTO notifications (text, alumni_id, admin_id) VALUES (?, ?, ?)");
	$stmt->bindParam(1, $text);
	$stmt->bindParam(2, $alumni_id);
	$stmt->bindParam(3, $admin);
	$stmt->execute();	
	
}

?>
