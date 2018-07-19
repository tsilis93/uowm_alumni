<?php

include ("../connectPDO.php");

$content_id = $_POST['content_id'];

$images_names = array();
$images_id;
$i = 0;

$sql = "SELECT * FROM images WHERE contentID = ?";
$stmt = $conn->prepare($sql);
$stmt->execute(array($content_id));
$result = $stmt->fetchAll();

if(sizeof($result) > 0) {
	foreach ($result as $row) {
		$images_id[$i] = $row['id'];
		$filename = $row['images_path'];
		array_push($images_names, $filename);
		$i = $i + 1;
	}
}

if(sizeof($images_names) > 0) {
	foreach ($images_names as $image) {
		$filename = "../content_images/" . $image;
		if (file_exists($filename)) {
			unlink($filename);
		}
	}
	for($j=0; $j<$i; $j++) {
		$sql = "DELETE FROM images WHERE id = ?";        
		$stmt = $conn->prepare($sql);
		if($stmt->execute(array($images_id[$j])))
		{
			//echo "Η εικόνα διαγράφηκε με επιτυχία";
		}
	}
}

$stmt = $conn->prepare("SELECT * FROM contents WHERE id = ?"); 
$stmt->execute(array($content_id));
$result2 = $stmt->fetchAll();

if(sizeof($result2)>0) {
	foreach($result2 as $row) {
		$title = $row['title'];
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

$sql = "DELETE FROM contents WHERE id = ?";        
$stmt = $conn->prepare($sql);
if($stmt->execute(array($content_id)))
{
	echo "Η δημοσίευση διαγράφηκε με επιτυχία";
}

//notification στον διαχειριστή
$text = "H δημοσίευση με τίτλο '" . $title . "' διαγράφηκε από τον χρήστη " . $user; 
$admin = 1;
					
$stmt = $conn->prepare("INSERT INTO notifications (text, admin_id) VALUES (?, ?)");
$stmt->bindParam(1, $text);
$stmt->bindParam(2, $admin);
if ($stmt->execute())
{
	echo "Ο διαχειριστής θα ενημερωθεί για την διαγραφή της δημοσίευσης.");
}


?>