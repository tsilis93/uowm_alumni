<?php
		
include ("connectPDO.php");

if(isset($_GET['story'])) {
	$id = $_GET['story'];
}

$imagesTOupload = array(); // πίνακας με τις εικόνες που θα γίνουν upload
$extension = array("jpeg","jpg","png","gif");
$bytes = 1024;
$KB = 1024;
$totalBytes = $bytes * $KB;
$UploadFolder = "content_images";
$counter = 0;

echo $id;
foreach($_FILES["files"]["tmp_name"] as $key=>$tmp_name){
	$temp = $_FILES["files"]["tmp_name"][$key];
	$name = $_FILES["files"]["name"][$key];
				
	if(empty($temp))
	{
		//break;
		header("Location: guest/index.php");
	}	
	
	$ext = pathinfo($name, PATHINFO_EXTENSION);
	if(in_array($ext, $extension) == false){
		//Το αρχείο δεν είναι εικόνα, γι αυτό και απορρίφθηκε
		continue;
	}	
	if($_FILES["files"]["size"][$key] > $totalBytes)
	{
		//Η εικόνα έχει μέγεθος μεγαλύτερο από 1 MB, γι αυτό και απορρίφθηκε
		continue;
	}
	
	$counter = $counter + 1;
	
	$temp_name = explode(".", $name);
	$newfilename = $temp_name[0] . round(microtime(true)) . '.' . end($temp_name);
	move_uploaded_file($temp, $UploadFolder . "/".$newfilename);
	
	array_push($imagesTOupload, $newfilename);					
}

foreach($imagesTOupload as $name) { 

	$departmentID = 0;
	$userID = 0;
	$contentID = 0; 

	$stmt = $conn->prepare("INSERT INTO images (images_path, storyID, departmentID, userID, contentID) VALUES (?, ?, ?, ?, ?)");
	$stmt->bindParam(1, $name);
	$stmt->bindParam(2, $id);
	$stmt->bindParam(3, $departmentID);
	$stmt->bindParam(4, $userID);
	$stmt->bindParam(5, $contentID);	
	if ($stmt->execute()) {
		
	}
}


$stmt2 = $conn->prepare("SELECT * FROM stories WHERE id = ?");
$stmt2->execute(array($id));
$result2 = $stmt2->fetchAll();
foreach($result2 as $row)
{
	$title = $row['title'];
	$user_id = $row['userID'];
}

$stmt2 = $conn->prepare("SELECT * FROM users WHERE id = ?");
$stmt2->execute(array($user_id));
$result2 = $stmt2->fetchAll();
foreach($result2 as $row)
{
	$name = $row['name'];
	$lastname = $row['lastname'];
}
$user = $lastname . ' ' . $name;

//notification στον διαχειριστή
$text = "Προστέθηκαν " .$counter." εικόνες στην ιστορία με τίτλο '" . $title . "' από τον χρήστη " . $user;
$admin = 1;
$alumni = 0;
					
$stmt = $conn->prepare("INSERT INTO notifications (text, admin_id, alumni_id) VALUES (?, ?, ?)");
$stmt->bindParam(1, $text);
$stmt->bindParam(2, $admin);
$stmt->bindParam(3, $alumni);
if ($stmt->execute())
{
	//echo "Ο διαχειριστής θα ενημερωθεί για τις εικόνες.");
}
	
header("Location: alumni/story_overview.php?story=$id");
	
?>
	