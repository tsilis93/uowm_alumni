<?php

include ("../connectPDO.php");
include ("../getPublicationId.php");

$refresh = false;
if(isset($_POST['refresh'])) {
	$refresh = $_POST['refresh'];
}

$title = $_POST['title'];
$description = htmlspecialchars($_POST['description']);
$body = htmlspecialchars($_POST['body']);
$content_id = $_POST['content_id'];
$date = date("Y-m-d");

$stmt2 = $conn->prepare("SELECT userID, title FROM contents WHERE id = ?");
$stmt2->execute(array($content_id));
$details = $stmt2->fetchAll();

$alumni_id = $details[0][0];
$title = $details[0][1];
$admin = 0;

$text = "Ο διαχειριστής πραγματοποίησε αλλαγές στην δημοσίευση με τίτλο '.$title.'";

$stmt = $conn->prepare("INSERT INTO notifications (text, alumni_id, admin_id) VALUES (?, ?, ?)");
$stmt->bindParam(1, $text);
$stmt->bindParam(2, $alumni_id);
$stmt->bindParam(3, $admin);
$stmt->execute();

$publication_id = getContentMaxPublicationid();
$publication_id = $publication_id + 1

if($refresh == false) {
	$sql = "UPDATE contents SET title = ?, description = ?, body = ? WHERE id = ?";
	$stmt = $conn->prepare($sql);
	if($stmt->execute(array($title, $description, $body, $content_id))) {
		echo "Οι αλλαγές αποθηκεύτηκαν με επιτυχία";
	}
}
else
{
	$sql = "UPDATE contents SET title = ?, description = ?, body = ?, publication_date = ?, publication_id = ? WHERE id = ?";
	$stmt = $conn->prepare($sql);
	if($stmt->execute(array($title, $description, $body, $date, $publication_id, $content_id))) {
		echo "Οι αλλαγές αποθηκεύτηκαν με επιτυχία";
	}
}


?>
