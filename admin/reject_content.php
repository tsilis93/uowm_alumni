<?php
		
include ("../connectPDO.php");
		
$id = $_POST['content_id1'];
$publication_date = date("0000-00-00");
$editor = $_POST['editor1'];
$newstatus = 0;

$stmt2 = $conn->prepare("SELECT * FROM contents WHERE id = ?");
$stmt2->execute(array($id));
$result = $stmt2->fetchAll();

foreach($result as $row) {
	$title = $row['title'];
	$status = $row['status'];
}

if($status == 0) {
	
	$sql = "UPDATE contents SET publication_date = ? WHERE id = ?";
	$stmt = $conn->prepare($sql);
	$stmt->execute(array($publication_date, $id));
			
	$fullnamearray = explode(" ", $editor);
	$name = $fullnamearray[0];
	$lastname = $fullnamearray[1];

	$stmt2 = $conn->prepare("SELECT id FROM users WHERE (name = ? AND lastname = ?)");
	$stmt2->execute(array($name,$lastname));
	$author = $stmt2->fetchAll();

	$alumni_id = $author[0][0];
	$admin = 0;
	$text = "Η δημοσίευση με τίτλο '" . $title . "' απορρίφθηκε από τον διαχειριστή του συστήματος"; 

	$stmt = $conn->prepare("INSERT INTO notifications (text, alumni_id, admin_id) VALUES (?, ?, ?)");
	$stmt->bindParam(1, $text);
	$stmt->bindParam(2, $alumni_id);
	$stmt->bindParam(3, $admin);
	$stmt->execute(); 
	
}
else
{

	$sql = "UPDATE contents SET status = ? WHERE id = ?";
	$stmt = $conn->prepare($sql);
	$stmt->execute(array($newstatus, $id));
			
	$fullnamearray = explode(" ", $editor);
	$name = $fullnamearray[0];
	$lastname = $fullnamearray[1];

	$stmt2 = $conn->prepare("SELECT id FROM users WHERE (name = ? AND lastname = ?)");
	$stmt2->execute(array($name,$lastname));
	$author = $stmt2->fetchAll();

	$alumni_id = $author[0][0];
	$admin = 0;
	$text = "Η δημοσίευση με τίτλο '" . $title . "' κατέβηκε από τον διαχειριστή του συστήματος"; 

	$stmt = $conn->prepare("INSERT INTO notifications (text, alumni_id, admin_id) VALUES (?, ?, ?)");
	$stmt->bindParam(1, $text);
	$stmt->bindParam(2, $alumni_id);
	$stmt->bindParam(3, $admin);
	$stmt->execute(); 
			
}

?>