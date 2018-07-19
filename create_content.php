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
$dep = $_POST['where'];
$count = count($dep);  // σε πόσα σημεία θα δημοσιευτεί η ανακοίνωση

$published_index_page = 0;  //σημεία προβολής της ανακοίνωσης αρχικοποιημένα σε 0
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
	
for($i=0; $i<$count; $i++) {
	if($dep[$i] === '0') {  // αν η δημοσίευση αφορά την κεντρικη σελιδα
		$published_index_page = 1;
	}
	else if($dep[$i] === '1')  //αν η δημοσίευση αφορά συγκεκριμένο τμήμα
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
		
if(isset($_SESSION['name'])) {
											// ο συντάκτης είναι ο διαχειριστής
	$date = date("Y-m-d");
	$status = 1;
	$publication_id = getContentMaxPublicationid();
	$publication_id = $publication_id + 1;
				
	$stmt = $conn->prepare("INSERT INTO contents (userID, title, description, body, publication_date, status, publication_id,published_index_page,published_department1,published_department2,published_department3,published_department4,published_department5,published_department6) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)");
	if ($stmt->execute(array($admin_id,$title,$description,$body,$date,$status,$publication_id,$published_index_page,$published_department1,$published_department2,$published_department3,$published_department4,$published_department5,$published_department6)))
	{
		array_push($messages, 'Η δημιουργία της ανακοίνωσης με τίτλο "'. $title .'" πραγματοποιήθηκε με επιτυχία.');
		$last_id = $conn->lastInsertId();
		
		$userID = 0;
		$storyID = 0;
		$departmentID = 0;
		
		
		foreach($imagesTOupload as $name) { 
			$stmt2 = $conn->prepare("INSERT INTO images (images_path, contentID, userID, storyID, departmentID) VALUES (?, ?, ?, ?, ?)");
			if ($stmt2->execute(array($name,$last_id, $userID, $storyID, $departmentID))) {
				//echo "Η εικόνα " . $name. " αποθηκεύτηκε στην βάση με επιτυχία ";
			}
			else
			{
				//echo "Η εικόνα " . $name. " δεν αποθηκεύτηκε στην βάση";
			}
		}
		
		if($counter > 1) 
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
		array_push($messages, 'Παρουσιάστηκε πρόβλημα στην δημιουργία της δημοσίευσης  με τίτλο "'. $title . '"');
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
			
	$stmt = $conn->prepare("INSERT INTO contents (userID, title, description, body, status, published_index_page, published_department1, published_department2, published_department3, published_department4, published_department5,published_department6, publication_id) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)");
	if ($stmt->execute(array($user_id,$title,$description,$body,$status,$published_index_page,$published_department1,$published_department2,$published_department3,$published_department4,$published_department5,$published_department6, $publication_id))) 
	{
		array_push($messages, 'Η δημιουργία της δημοσίευσης με τίτλο "'. $title .'" πραγματοποιήθηκε με επιτυχία.');
		$last_id = $conn->lastInsertId();

		$userID = 0;
		$storyID = 0;
		$departmentID = 0;
				
		foreach($imagesTOupload as $name) { 
			$stmt2 = $conn->prepare("INSERT INTO images (images_path, contentID, userID, storyID, departmentID) VALUES (?, ?, ?, ?, ?)");
			if ($stmt2->execute(array($name,$last_id, $userID, $storyID, $departmentID))) {
				//echo "Η εικόνα " . $name. " αποθηκεύτηκε στην βάση με επιτυχία για το univsitecontent με id = ".$last_id." <br>";
			}
			else
			{
				//echo "Η εικόνα " . $name. " δεν αποθηκεύτηκε στην βάση <br>";
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
		$text = "Νεα καταχώρηση με τίτλο '" . $title . "' προστέθηκε για έγκριση από τον χρήστη " . $user; 
		$admin = 1;
		$alumni_id = 0;
					
		$stmt = $conn->prepare("INSERT INTO notifications (text, admin_id, alumni_id) VALUES (?, ?, ?)");
		$stmt->bindParam(1, $text);
		$stmt->bindParam(2, $admin);
		$stmt->bindParam(3, $alumni_id);
		if ($stmt->execute())
		{
			array_push($messages, "Ο διαχειριστής θα ενημερωθεί για να αξιολογήσει την δημοσίευση.");
		}
		else
		{
			//array_push($messages, "Παρουσιάστηκε πρόβλημα στην διαδικασία ενημέρωσης του διαχειριστή.");
		}
				
	}
	else
	{
		array_push($messages, 'Παρουσιάστηκε πρόβλημα στην δημιουργία της ανακοίνωσης με τίτλο "'. $title . '"');
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
	echo '<h2>Η Ανακοίνωση μου</h2>';
}
echo '<div class="container">';  
if(isset($_SESSION['student'])) {
	echo '<h2 align="center">Η Ανακοίνωση μου</h2>';
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
echo '<a href="admin/create_content_admin.php"><button class="btn btn-primary">Επιστροφή</button></a>';
echo "<br>";
}
else
{
echo '<a href="alumni/create_content_alumni.php"><button class="btn btn-primary">Επιστροφή</button></a>';
echo "<br>";
}
echo '</div>';
echo '</div>';

?>

</body>
</html>
