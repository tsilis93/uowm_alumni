<?php

include ("../connectPDO.php");

$story_id = $_POST['story_id'];

$images_names = array();
$images_id;
$i = 0;

$sql = "SELECT * FROM images WHERE storyID = ?";
$stmt = $conn->prepare($sql);
$stmt->execute(array($story_id));
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

$stmt2 = $conn->prepare("SELECT userID, title FROM stories WHERE id = ?");
$stmt2->execute(array($story_id));
$details = $stmt2->fetchAll();

$alumni_id = $details[0][0];
$title = $details[0][1];
$admin = 0;
$text = "Ο διαχειριστής διέγραψε την ιστορία με τίτλο '.$title.'";

$stmt = $conn->prepare("INSERT INTO notifications (text, alumni_id, admin_id) VALUES (?, ?, ?)");
$stmt->bindParam(1, $text);
$stmt->bindParam(2, $alumni_id);
$stmt->bindParam(3, $admin);
$stmt->execute();

$sql = "DELETE FROM stories WHERE id = ?";        
$stmt = $conn->prepare($sql);
if($stmt->execute(array($story_id)))
{
	echo "Η Ιστορία διαγράφηκε με επιτυχία";
}


?>
