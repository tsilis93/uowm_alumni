<?php
session_start();	

include ("../connectPDO.php");

if(isset($_SESSION['student'])) {
	$alumni_id = $_SESSION['student'];
}

$stmt = $conn->prepare("SELECT * FROM users WHERE id = ?");   // Βρισκω το όνομα και το επωνυμο μου 
$stmt->execute(array($alumni_id));
$results = $stmt->fetchAll();

if (sizeof($results)> 0) {
			
	foreach ($results as $row) {
		$alumniname = $row['name'];
		$alumnilastname = $row['lastname'];
	}
}

$bytes = 1024;
$KB = 1024;
$totalBytes = $bytes * $KB;
$UploadFolder = "../users_images";

if($_FILES['myPhoto']['error'] > 0) 
{ 
	die(header("location:alumni_index.php?Failed=true&reason=blank"));
}

if($_FILES['myPhoto']['size'] > $totalBytes)
{
	die(header("location:alumni_index.php?Failed=true&reason=size"));
}
else
{
	$name = $_FILES['myPhoto']['name'];
	$temp = $_FILES['myPhoto']['tmp_name'];
	
	$temp_name = explode(".", $name);
	$filename = "imageUser" . $alumni_id . '.' . end($temp_name);
	
	$stmt = $conn->prepare("SELECT * FROM images WHERE userID = ?");
	$stmt->execute(array($alumni_id));
	$result = $stmt->fetchAll();

	if (sizeof($result)> 0) {
		
		foreach ($result as $row) {
			$prev_image_file = $row['images_path'];
		}
		if($prev_image_file == "user.png") {

		}
		else if(file_exists($UploadFolder."/".$prev_image_file)) {
			unlink($UploadFolder."/".$prev_image_file);
		}
		
		$sql = "UPDATE images SET images_path = ? WHERE userID = ?";
		$stmt = $conn->prepare($sql);
		if($stmt->execute(array($filename, $alumni_id))) {
			
			move_uploaded_file($temp, $UploadFolder . "/" . $filename);

			$stmt = $conn->prepare("SELECT * FROM user_relationship WHERE friend_alumni_id = ?");   // αν έχουν επιλέξει να με ακολουθήσουν απόφοιτοι
			$stmt->execute(array($alumni_id));														// τότε πρέπει να τους ενημερώσω ότι ανέβασα νεα εικόνα profil
			$result3 = $stmt->fetchAll();

			if (sizeof($result3)> 0) {			
				foreach($result3 as $row3) {
					$notify_alumni_id = $row3['alumni_id'];  // βρίσκω το id τους και δημιουργώ μια ειδοποίηση για αυτο
					$admin = 0;
					$text = "Ο απόφοιτος ". $alumnilastname . " " . $alumniname . " ανέβασε νέα εικόνα profil";
						
					$stmt2 = $conn->prepare("INSERT INTO notifications (text, alumni_id, admin_id) VALUES (?, ?, ?)");
					$stmt2->bindParam(1, $text);
					$stmt2->bindParam(2, $notify_alumni_id);
					$stmt2->bindParam(3, $admin);
					$stmt2->execute();
				}	
			}			
			header("location:alumni_index.php?Failed=false");
		}
		else
		{
			die(header("location:alumni_index.php?Failed=true&reason=error"));
		}
		
	}	
	else
	{
		$departmentID = 0;
		$storyID = 0;
		$contentID = 0;
		
		$stmt2 = $conn->prepare("INSERT INTO images (images_path, userID, departmentID, storyID, contentID) VALUES (?, ?, ?, ?, ?)");
		$stmt2->bindParam(1, $filename);
		$stmt2->bindParam(2, $alumni_id);
		$stmt2->bindParam(3, $departmentID);
		$stmt2->bindParam(4, $storyID);
		$stmt2->bindParam(5, $contentID);
		if ($stmt2->execute()) {
			move_uploaded_file($temp, $UploadFolder . "/" . $filename);
			
			$stmt = $conn->prepare("SELECT * FROM user_relationship WHERE friend_alumni_id = ?");   // αν έχουν επιλέξει να με ακολουθήσουν απόφοιτοι
			$stmt->execute(array($alumni_id));														// τότε πρέπει να τους ενημερώσω ότι ανέβασα εικόνα profil
			$result3 = $stmt->fetchAll();

			if (sizeof($result3)> 0) {			
				foreach($result3 as $row3) {
					$notify_alumni_id = $row3['alumni_id'];  // βρίσκω το id τους και δημιουργώ μια ειδοποίηση για αυτο
					$admin = 0;
					$text = "Ο απόφοιτος ". $lastname . " " . $name . " ανέβασε εικόνα profil";
						
					$stmt2 = $conn->prepare("INSERT INTO notifications (text, alumni_id, admin_id) VALUES (?, ?, ?)");
					$stmt2->bindParam(1, $text);
					$stmt2->bindParam(2, $notify_alumni_id);
					$stmt2->bindParam(3, $admin);
					$stmt2->execute();
				}	
			}
			header("location:alumni_index.php?Failed=false");
		}
		else
		{
			die(header("location:alumni_index.php?Failed=true&reason=error"));
		}
		
	}

}

?>
