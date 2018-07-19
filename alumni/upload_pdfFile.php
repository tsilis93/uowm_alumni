<?php

session_start();	

include ("../connectPDO.php");

if(isset($_SESSION['student'])) {
	$alumni_id = $_SESSION['student'];
}

$stmt = $conn->prepare("SELECT * FROM users WHERE id = ?");   // Βρισκω το όνομα και το επωνυμο μου 
$stmt->execute(array($alumni_id));
$results = $stmt->fetchAll();

if (sizeof($result)> 0) {
			
	foreach ($result as $row) {
		$name = $row['name'];
		$lastname = $row['lastname'];
	}
}

$UploadFolder = "../cv_files";

if($_FILES['mycvPDF']['error'] > 0) 
{ 
	die(header("location:alumni_cv_upload.php?Failed=true&reason=blank"));
}
else
{
	$name = $_FILES['mycvPDF']['name'];
	$temp = $_FILES['mycvPDF']['tmp_name'];
	$type=$_FILES['mycvPDF']['type'];
	$size=$_FILES['mycvPDF']['size'];

	if ($size > 500000) {
		die(header("location:alumni_cv_upload.php?Failed=true&reason=size"));	
	}
	
	if ($type=="application/pdf") {
		
		$temp_name = explode(".", $name);
		$filename = "cvPdfUser" . $alumni_id . '.' . end($temp_name);
		
		$stmt = $conn->prepare("SELECT * FROM alumni_cv WHERE alumni_id = ?");
		$stmt->execute(array($alumni_id));
		$result = $stmt->fetchAll();

		if (sizeof($result)> 0) {
			
			foreach ($result as $row) {
				$prev_pdf_file = $row['pdf_src'];
			}
			if(file_exists($UploadFolder."/".$prev_pdf_file)) {
				unlink($UploadFolder."/".$prev_pdf_file);
			}
			
			$sql = "UPDATE alumni_cv SET pdf_src = ?, original_name = ? WHERE alumni_id = ?";
			$stmt = $conn->prepare($sql);
			if($stmt->execute(array($filename, $name, $alumni_id))) {
				move_uploaded_file($temp, $UploadFolder . "/" . $filename);
				
				$stmt = $conn->prepare("SELECT * FROM user_relationship WHERE friend_alumni_id = ?");   // αν έχουν επιλέξει να με ακολουθήσουν απόφοιτοι
				$stmt->execute(array($alumni_id));														// τότε πρέπει να τους ενημερώσω ότι ανέβασα νεο βιογραφικό
				$result3 = $stmt->fetchAll();

				if (sizeof($result3)> 0) {			
					foreach($result3 as $row3) {
						$notify_alumni_id = $row3['alumni_id'];  // βρίσκω το id τους και δημιουργώ μια ειδοποίηση για αυτο
						$text = "Ο απόφοιτος ". $lastname . " " . $name . " ανέβασε νέο βιογραφικό";
						$admin = 0;
						
						$stmt2 = $conn->prepare("INSERT INTO notifications (text, alumni_id, admin_id) VALUES (?, ?, ?)");
						$stmt2->bindParam(1, $text);
						$stmt2->bindParam(2, $notify_alumni_id);
						$stmt2->bindParam(3, $admin);
						$stmt2->execute();
					}	
				}
				header("location:alumni_cv_upload.php?Failed=false");
			}
			else
			{
				die(header("location:alumni_cv_upload.php?Failed=true&reason=error"));
			}			

		}
		else
		{
			$stmt2 = $conn->prepare("INSERT INTO alumni_cv (pdf_src, original_name, alumni_id) VALUES (?, ?, ?)");
			$stmt2->bindParam(1, $filename);
			$stmt2->bindParam(2, $name);
			$stmt2->bindParam(3, $alumni_id);
			if ($stmt2->execute()) {
				move_uploaded_file($temp, $UploadFolder . "/" . $filename);
				
				$stmt = $conn->prepare("SELECT * FROM user_relationship WHERE friend_alumni_id = ?");   // αν έχουν επιλέξει να με ακολουθήσουν απόφοιτοι
				$stmt->execute(array($alumni_id));														// τότε πρέπει να τους ενημερώσω ότι ανέβασα βιογραφικό
				$result3 = $stmt->fetchAll();

				if (sizeof($result3)> 0) {			
					foreach($result3 as $row3) {
						$notify_alumni_id = $row3['alumni_id'];  // βρίσκω το id τους και δημιουργώ μια ειδοποίηση για αυτο
						$admin = 0;
						$text = "Ο απόφοιτος ". $lastname . " " . $name . " ανέβασε βιογραφικό";
						
						$stmt2 = $conn->prepare("INSERT INTO notifications (text, alumni_id, admin_id) VALUES (?, ?, ?)");
						$stmt2->bindParam(1, $text);
						$stmt2->bindParam(2, $notify_alumni_id);
						$stmt2->bindParam(3, $admin);
						$stmt2->execute();
					}	
				}				
				header("location:alumni_cv_upload.php?Failed=false");
			}
			else
			{
				die(header("location:alumni_cv_upload.php?Failed=true&reason=error"));
			}				
		}
		
	}
	else
	{
		die(header("location:alumni_cv_upload.php?Failed=true&reason=type"));
	}
	
}

?>