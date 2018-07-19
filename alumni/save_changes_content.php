<?php

include ("../connectPDO.php");

$title = $_POST['title'];
$description = htmlspecialchars($_POST['description']);
$body = htmlspecialchars($_POST['body']);
$content_id = $_POST['content_id'];

$stmt = $conn->prepare("SELECT * FROM contents WHERE id = ?"); 
$stmt->execute(array($content_id));
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


$sql = "UPDATE contents SET title = ?, description = ?, body = ? WHERE id = ?";
$stmt = $conn->prepare($sql);
if($stmt->execute(array($title, $description, $body, $content_id))) {
	echo "Οι αλλαγές αποθηκεύτηκαν με επιτυχία";
}

//notification στον διαχειριστή
$text = "Έγιναν διορθώσεις στην δημοσίευση με τίτλο '" . $title . "' από τον χρήστη " . $user; 
$admin = 1;
					

$stmt = $conn->prepare("INSERT INTO notifications (text, admin_id) VALUES (?, ?)");
$stmt->bindParam(1, $text);
$stmt->bindParam(2, $admin);
if ($stmt->execute())
{
	echo "Ο διαχειριστής θα ενημερωθεί για τις διορθώσεις στην δημοσίευση.");
}

?>