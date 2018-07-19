<?xml version="1.0" encoding="UTF-8"?>
<!DOCTYPE html>
<html lang="en">
<head>
  <title>UOWM Alumni</title>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  
     <link rel="stylesheet" href="css/notFound.css">
	 
</head>
<body>	 
<?php
include ("connectPDO.php");

if(isset($_GET['email']) && !empty($_GET['email']) AND isset($_GET['hash']) && !empty($_GET['hash'])){
    // Verify data
    $email = $_GET['email']; // Set email variable
    $hash = $_GET['hash']; // Set hash variable
	
	$stmt = $conn->prepare("SELECT * FROM users WHERE email = ? AND hash = ? AND active = 0");
	$stmt->execute(array($email,$hash));
	$result = $stmt->fetchAll();
	
	if(sizeof($result)>0) {
		foreach($result as $row) {
			$id = $row['id'];		
		}
			$stmt2 = $conn->prepare("UPDATE users SET active=1 WHERE id = ?");
			$stmt2->execute(array($id));
			
			echo '<div class="centered">';
			echo	'<img src="assets/UOWM-logo.png" alt="UOWM-logo"  height = "60px"><br><br>';		
			echo 	"Ο λογαριασμός σας έχει ενεργοποιηθεί, μπορείτε να συνδεθείτε ";
			echo	'<a href="register_login_form.php">εδώ</a>';
			echo '</div>';
	}
	else
	{
		echo '<div class="centered">';
		echo	'<img src="assets/UOWM-logo.png" alt="UOWM-logo"  height = "60px"><br><br>';		
		echo 	"Η διεύθυνση url είναι είτε μη έγκυρη είτε έχετε ήδη ενεργοποιήσει το λογαριασμό σας.";
		echo '</div>';		
	}
	
}
else
{
		echo '<div class="centered">';
		echo	'<img src="assets/UOWM-logo.png" alt="UOWM-logo"  height = "60px"><br><br>';		
		echo 	"Μη έγκυρη προσέγγιση, χρησιμοποιήστε τον σύνδεσμο που έχει σταλεί στο email σας";
		echo '</div>';		
	
}

?>


</body>
</html>