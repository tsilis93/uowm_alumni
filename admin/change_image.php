<?php

include ("../connectPDO.php");
if(isset($_POST['alumni_id'])) {
	$alumni_id = $_POST['alumni_id'];
}

$stmt4 = $conn->prepare("SELECT * from images WHERE userID = ?");
$stmt4->execute(array($alumni_id));
$result4 = $stmt4->fetchAll();
$images_path = "";

if (sizeof($result4)> 0) {
	foreach($result4 as $row4) {
		$images_path = $row4['images_path'];		
	}
	if(file_exists("../users_images/".$images_path)) {
		$image = "../users_images/$images_path";
		unlink($image);	
	}
	$images_path = "user.png";
	$sql = "UPDATE images SET images_path = ? WHERE userID = ?";
	$stmt = $conn->prepare($sql);
	if($stmt->execute(array($images_path, $alumni_id))) { }
}
else
{
	
}


?>