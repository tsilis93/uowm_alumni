<?xml version="1.0" encoding="UTF-8"?>
<!DOCTYPE html>
<html lang="en">
<head>
  <title>UOWM Alumni</title>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap.min.css">
  <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.2.1/jquery.min.js"></script>
  <script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/js/bootstrap.min.js"></script>
  	<?php
	session_start();
  	
	if(isset($_SESSION['name'])) {
		echo '<link rel="stylesheet" href="css/admin.css">';
	}
	else
	{
		echo '<link rel="stylesheet" href="css/alumni_index.css">';
	}
  ?>
  
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-select/1.12.4/css/bootstrap-select.min.css">
  <script src="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-select/1.12.4/js/bootstrap-select.min.js"></script>
  
</head>
<body>

<?php
	
include ("connectPDO.php");
include ("getPublicationId.php");

$messages = array();  //πίνακας με μνμ προς τον χρήστη ή τον διαχειριστή
$imagesTOupload = array(); // πίνακας με τις εικόνες που θα γίνουν upload
$extension = array("jpeg","jpg","png","gif");
$bytes = 1024;
$KB = 1024;
$totalBytes = $bytes * $KB;
$UploadFolder = "content_images";
$counter = 0;

foreach($_FILES["files"]["tmp_name"] as $key=>$tmp_name){
	$temp = $_FILES["files"]["tmp_name"][$key];
	$name = $_FILES["files"]["name"][$key];
				
	if(empty($temp))
	{
		break;
	}	
	
	$ext = pathinfo($name, PATHINFO_EXTENSION);
	if(in_array($ext, $extension) == false){
		array_push($messages, "Το αρχείο " . $name . " δεν είναι εικόνα, γι αυτό και απορρίφθηκε.");
		continue;
	}	
	if($_FILES["files"]["size"][$key] > $totalBytes)
	{
		array_push($messages, "Η εικόνα " . $name. " έχει μέγεθος μεγαλύτερο από 1 MB, γι αυτό και απορρίφθηκε");
		continue;
	}
	
	$counter = $counter + 1;
	
	$temp_name = explode(".", $name);
	$newfilename = $temp_name[0] . round(microtime(true)) . '.' . end($temp_name);
	move_uploaded_file($temp, $UploadFolder . "/".$newfilename);
	
	array_push($imagesTOupload, $newfilename);					
}
	
$title = $_POST['title'];
$description = htmlspecialchars($_POST['description']);
$body = htmlspecialchars($_POST['body']);
$dep = 0;
if(isset($_POST['where'])) {
$dep = $_POST['where'];
}
if(isset($_POST['definition'])) {
$definition = $_POST['definition'];
}

$published_department1 = 0;
$published_department2 = 0;
$published_department3 = 0;
$published_department4 = 0;
$published_department5 = 0;
$published_department6 = 0;

$user_id = 0;
$admin_id = 0;
if(isset($_SESSION['name'])) {
	$admin_id = $_SESSION['name'];
}
if(isset($_SESSION['student'])) {
	$user_id = $_SESSION['student'];
}

//όλα τα υπόλοιπα δεδομένα αρχικοποιούνται σε 0 ή ""
$comments = "";

	
if(isset($_SESSION['name'])) {  // ο συντάκτης είναι ο διαχειριστής
	
	$count = count($dep);
	for($i=0; $i<$count; $i++) {
		if($dep[$i] === '1')  	//αν η ιστορία αφορά διαφορετικά τμήματα
		{
			$published_department1 = 1;		
		}
		else if($dep[$i] === '2')
		{
			$published_department2 = 1;		
		}	
		else if($dep[$i] === '3')
		{
			$published_department3 = 1;		
		}
		else if($dep[$i] === '4')
		{
			$published_department4 = 1;		
		}
		else if($dep[$i] === '5')
		{
			$published_department5 = 1;		
		}
		else if($dep[$i] === '6')
		{
			$published_department6 = 1;		
		}	
	}	

	$publication_date = date("Y-m-d");
	$status = 1;
	$publication_id2 = getStoryMaxPublicationid();
	$publication_id = $publication_id2 + 1;
					
	$stmt = $conn->prepare("INSERT INTO stories (userID, title, description, body, publication_date, status, publication_id, definition,published_department1,published_department2,published_department3,published_department4,published_department5,published_department6, comments) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)");
	if ($stmt->execute(array($admin_id,$title,$description,$body,$publication_date,$status,$publication_id,$definition,$published_department1,$published_department2,$published_department3,$published_department4,$published_department5,$published_department6, $comments)))
	{
		$last_id = $conn->lastInsertId();
			
		array_push($messages, 'Η δημιουργία της ιστορίας με τίτλο "'. $title .'" πραγματοποιήθηκε με επιτυχία.');			
		
		//��������������� �� �������� ����� �� 0
		$userID = 0;
		$contentID = 0;
		$departmentID = 0;
		
		foreach($imagesTOupload as $name) { 
			$stmt = $conn->prepare("INSERT INTO images (images_path, storyID, userID, contentID, departmentID) VALUES (?, ?, ?, ?, ?)");
			$stmt->bindParam(1, $name);
			$stmt->bindParam(2, $last_id);
			$stmt->bindParam(3, $userID);
			$stmt->bindParam(4, $contentID);
			$stmt->bindParam(5, $departmentID);
			if ($stmt->execute()) {
				//echo "Η εικόνα " . $name. " αποθηκεύτηκε στην βάση με επιτυχία";
			}
			else
			{
				//echo "Η εικόνα " . $name. " δεν αποθηκεύτηκε στην βάση";
			}
		}
		
		if($counter > 1 ) 
		{
			array_push($messages, "Ανέβηκαν με επιτυχία ". $counter . " εικόνες");
		}
		else if ($counter != 0)
		{
			array_push($messages, "H εικόνα ανέβηκε με επιτυχία");
		}		
	}
	else
	{
		array_push($messages, 'Παρουσιάστηκε πρόβλημα στην δημιουργία της ιστορίας με τίτλο "'. $title . '"');
		if ($counter != 0) {
			array_push($messages, 'Οι εικόνες που ανέβηκαν διαγράφηκαν');
			array_push($messages, 'Παρακαλώ ξαναπροσπαθήστε');
			foreach($imagesTOupload as $name) { 
				if (file_exists($UploadFolder."/".$name)) {
					unlink($UploadFolder."/".$name);
				}
			}
		}		
	} 
				
}
else			// ο συντάκτης είναι ένας απόφοιτος
{
	$status = 0;
	$publication_id = 0;
	$departmentID = 0;
	$stmt = $conn->prepare("SELECT * FROM `users` WHERE id = ?");
	$stmt->execute(array($user_id));
	$result = $stmt->fetchAll();
	foreach ($result as $row) {	
		$departmentID = $row['department_id'];
	}
	
	if($departmentID == 1)  	//αν η ιστορία αφορά διαφορετικά τμήματα
	{
		$published_department1 = 1;		
	}
	else if($departmentID == 2)
	{
		$published_department2 = 1;		
	}	
	else if($departmentID == 3)
	{
		$published_department3 = 1;		
	}
	else if($departmentID == 4)
	{
		$published_department4 = 1;		
	}
	else if($departmentID == 5)
	{
		$published_department5 = 1;		
	}
	else if($departmentID == 6)
	{
		$published_department6 = 1;		
	}		
				
	$stmt = $conn->prepare("INSERT INTO stories (userID, title, description, body, status, definition, published_department1, published_department2, published_department3, published_department4, published_department5,published_department6, comments, publication_id) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)");
	if ($stmt->execute(array($user_id,$title,$description,$body,$status,$definition,$published_department1,$published_department2,$published_department3,$published_department4,$published_department5,$published_department6, $comments, $publication_id)))
	{
		array_push($messages, 'Η δημιουργία της ιστορίας με τίτλο "'. $title .'" πραγματοποιήθηκε με επιτυχία.');			
		$last_id = $conn->lastInsertId();
		
		//αρχικοποιούνται τα υπολοιπα πεδία με 0
		$userID = 0;
		$contentID = 0;
		$departmentID = 0;
		
		foreach($imagesTOupload as $name) { 
			$stmt = $conn->prepare("INSERT INTO images (images_path, storyID, userID, contentID, departmentID) VALUES (?, ?, ?, ?, ?)");
			$stmt->bindParam(1, $name);
			$stmt->bindParam(2, $last_id);
			$stmt->bindParam(3, $userID);
			$stmt->bindParam(4, $contentID);
			$stmt->bindParam(5, $departmentID);
			if ($stmt->execute()) {
				//echo "Η εικόνα " . $name. " αποθηκεύτηκε στην βάση με επιτυχία ";
			}
			else
			{
				//echo "Η εικόνα " . $name. " δεν αποθηκεύτηκε στην βάση";
			}
		}

		if($counter > 1 ) 
		{
			array_push($messages, "Ανέβηκαν με επιτυχία ". $counter . " εικόνες");
		}
		else if ($counter != 0)
		{
			array_push($messages, "H εικόνα ανέβηκε με επιτυχία");
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
		$text = "Νεα Ιστορία με τίτλο '" . $title . "' προστέθηκε για έγκριση από τον χρήστη " . $user; 
		$admin = 1;
		$alumni_id = 0;
					
		$stmt = $conn->prepare("INSERT INTO notifications (text, admin_id, alumni_id) VALUES (?, ?, ?)");
		$stmt->bindParam(1, $text);
		$stmt->bindParam(2, $admin);
		$stmt->bindParam(3, $alumni_id);
		if ($stmt->execute())
		{
			array_push($messages, "Ο διαχειριστής θα ενημερωθεί για να αξιολογήσει την ιστορία.");
		}
		else
		{
			//array_push($messages, "Παρουσιάστηκε πρόβλημα στην διαδικασία ενημέρωσης του διαχειριστή.");
		}	
	}
	else
	{
		array_push($messages, "Παρουσιάστηκε πρόβλημα στην δημιουργία της ιστορίας με τίτλο ". $title . "");
		if ($counter != 0) {
			array_push($messages, 'Οι εικόνες που ανέβηκαν διαγράφηκαν');
			array_push($messages, 'Παρακαλώ ξαναπροσπαθήστε');
			foreach($imagesTOupload as $name) { 
				if (file_exists($UploadFolder."/".$name)) {
					unlink($UploadFolder."/".$name);
				}
			}	
		}			
	}
}

echo "<br>";
if(isset($_SESSION['name'])) {
	echo '<h2>Η Ιστορία μου</h2>';
}
echo '<div class="container">';  
if(isset($_SESSION['student'])) {
	echo '<h2 align="center">Η Ιστορία μου</h2>';
}
echo "<br>";
echo '<ul>';
foreach($messages as $message)
{
	echo "<li>".$message."</li>";
}
echo "</ul>";
echo "<br>";
echo '<div class="form-group" id = "bloc4">';
if(isset($_SESSION['name'])) {
echo '<a href="admin/create_story_admin.php"><button class="btn btn-primary">Επιστροφή</button></a>';
echo "<br>";
}
else
{
echo '<a href="alumni/create_story_alumni.php"><button class="btn btn-primary">Επιστροφή</button></a>';
echo "<br>";
}
echo '</div>';
echo '</div>';

?>

</body>
</html>



