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
  	<link rel="stylesheet" href="../css/admin.css"> 
  
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-select/1.12.4/css/bootstrap-select.min.css">
  <script src="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-select/1.12.4/js/bootstrap-select.min.js"></script>
  
</head>
<body>

<?php
session_start();	

include ("../connectPDO.php");

if(isset($_SESSION['name'])) {
	$admin_id = $_SESSION['name'];
}
else
{
header('Location: ../register_login_form.php');
}

$messages = array();  //πίνακας με μνμ προς τον χρήστη ή τον διαχειριστή
$imagesTOupload = array(); // πίνακας με τις εικόνες που θα γίνουν upload
$extension = array("jpeg","jpg","png");
$bytes = 1024;
$KB = 1024;
$totalBytes = $bytes * $KB;
$UploadFolder = "../assets";
$counter = 0;
$allOk = true;

$total_files = count($_FILES['files']['tmp_name']);
if($total_files > 2)
{
	array_push($messages, "Πρέπει να ανεβάσετε 2 εικόνες με μέγεθος μικρότερο ισο από 1ΜΒ");
	array_push($messages, 'Επιλέξτε 2 εικόνες από τις '.$total_files.' και ξαναπροσπαθήστε');
}
else
{
	foreach($_FILES["files"]["tmp_name"] as $key=>$tmp_name){
		$temp = $_FILES["files"]["tmp_name"][$key];
		$name = $_FILES["files"]["name"][$key];
					
		if(empty($temp))
		{
			array_push($messages, "Πρέπει να επιλέξετε 2 εικόνες οι οποίες θα προβληθούν στο slider της σελίδας με μέγεθος μικρότερο ισο από 1ΜΒ");
			break;
		}	
		
		$ext = pathinfo($name, PATHINFO_EXTENSION);
		if(in_array($ext, $extension) == false) {
			array_push($messages, "Το αρχείο " . $name . " δεν είναι εικόνα, γι αυτό και απορρίφθηκε.");
			array_push($messages, "Πρέπει να ανεβάσετε 2 εικόνες με μέγεθος μικρότερο ισο από 1ΜΒ");
			$allOk = false;
			break;
		}	
		if($_FILES["files"]["size"][$key] > $totalBytes)
		{
			array_push($messages, "Η εικόνα " . $name. " έχει μέγεθος μεγαλύτερο από 1 MB, γι αυτό και απορρίφθηκε");
			array_push($messages, "Πρέπει να ανεβάσετε 2 εικόνες με μέγεθος μικρότερο ισο από 1ΜΒ");
			$allOk = false;
			break;
		}
		
		if($allOk == true) {
			$temp_name = explode(".", $name);
			$newfilename = $temp_name[0] . round(microtime(true)) . '.' . end($temp_name);
			move_uploaded_file($temp, $UploadFolder . "/".$newfilename);
			$counter = $counter + 1;
			
			array_push($imagesTOupload, $newfilename);	
		}
	}
}
if($counter == 0) {
	echo "<br>";
	echo '<h2>Νέο Τμήμα</h2>';
	echo '<div class="container">';  
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
		echo '<a href="new_department.php"><button class="btn btn-primary">Επιστροφή</button></a>';
		echo "<br>";
	}

	echo '</div>';
	echo '</div>';
}
else
{
	array_push($messages, $counter . " εικόνες ανέβηκαν με επιτυχία");


$title = $_POST['title'];
$description = htmlspecialchars($_POST['description']);
$welcome = htmlspecialchars($_POST['welcome']);
$dep = $_POST['where'];
$color = $_POST['color'];

$stmt = $conn->prepare("INSERT INTO departments (facultyid, dname, nav_color, promp_text, about_text) VALUES (?, ?, ?, ?, ?)");
$stmt->bindParam(1, $dep);
$stmt->bindParam(2, $title);
$stmt->bindParam(3, $color);
$stmt->bindParam(4, $welcome);
$stmt->bindParam(5, $description);
if ($stmt->execute())
{
	array_push($messages, 'Η δημιουργία του νέου τμήματος με όνομα "'. $title .'" πραγματοποιήθηκε με επιτυχία.');
	$last_id = $conn->lastInsertId();
	
	$newname = "published_department".$last_id;
	
	$sql2 = "ALTER TABLE contents ADD ".$newname." INT(11) NOT NULL";
	$stmt2 = $conn->prepare($sql2);
	if ($stmt2->execute()) {
		//echo $done;
	}
	
	$sql2 = "ALTER TABLE stories ADD ".$newname." INT(11) NOT NULL";
	$stmt2 = $conn->prepare($sql2);
	if ($stmt2->execute()) {
		//echo $done;
	}

	$userID = 0;
	$storyID = 0;
	$contentID = 0;	
	
	foreach($imagesTOupload as $name) { 
		$stmt = $conn->prepare("INSERT INTO images (images_path, departmentID, userID, storyID, contentID) VALUES (?, ?, ?, ?, ?)");
		$stmt->bindParam(1, $name);
		$stmt->bindParam(2, $last_id);
		$stmt->bindParam(3, $userID);
		$stmt->bindParam(4, $storyID);
		$stmt->bindParam(5, $contentID);
		if ($stmt->execute()) {
			
		}
		else
		{
		
		}
	}
}
else
{
	array_push($messages, 'Παρουσιάστηκε πρόβλημα στην δημιουργία του νέου τμήματος με όνομα "'. $title . '"');	
}

echo "<br>";
echo '<h2>Νέο Τμήμα</h2>';
echo '<div class="container">';  
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
echo '<a href="new_department.php"><button class="btn btn-primary">Επιστροφή</button></a>';
echo "<br>";
}

echo '</div>';
echo '</div>';

}
?>

</body>
</html>