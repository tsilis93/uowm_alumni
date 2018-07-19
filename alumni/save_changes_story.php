<?php

include ("../connectPDO.php");

$title = $_POST['title'];
$description = htmlspecialchars($_POST['description']);
$body = htmlspecialchars($_POST['body']);
$storyid = $_POST['story_id'];
$definition = $_POST['definition'];

$stmt = $conn->prepare("SELECT * FROM stories WHERE id = ?"); 
$stmt->execute(array($storyid));
$result2 = $stmt->fetchAll();

if(sizeof($result2)>0) {
	foreach($result2 as $row) {
		$userID = $row['userID'];
	}	
}

$stmt2 = $conn->prepare("SELECT * FROM users WHERE id = ?");
$stmt2->execute(array($userID));
$result2 = $stmt2->fetchAll();
foreach($result2 as $row)
{
	$name = $row['name'];
	$lastname = $row['lastname'];
}
$user = $lastname . ' ' . $name;


$sql = "UPDATE stories SET title = ?, description = ?, body = ?, definition = ? WHERE id = ?";
$stmt = $conn->prepare($sql);
if($stmt->execute(array($title, $description, $body, $definition, $storyid))) {
	echo "Οι αλλαγές αποθηκεύτηκαν με επιτυχία";
}

//notification στον διαχειριστή
$text = "Έγιναν διορθώσεις στην Ιστορία με τίτλο '" . $title . "' από τον χρήστη " . $user; 
$admin = 1;
$alumni_id = 0;					

$stmt = $conn->prepare("INSERT INTO notifications (text, admin_id, alumni_id) VALUES (?, ?, ?)");
$stmt->bindParam(1, $text);
$stmt->bindParam(2, $admin);
$stmt->bindParam(3, $alumni_id);
if ($stmt->execute())
{
	echo "Ο διαχειριστής θα ενημερωθεί για τις διορθώσεις στην ιστορία.";
}

?>