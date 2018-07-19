<?php	

session_start();
include ("../connectPDO.php");

if(isset($_SESSION['student'])) {
	$alumni_id = $_SESSION['student'];
}

if(isset($_POST['idTable'])) {
	$id_table = $_POST['idTable'];   //τα id ειναι δοσμένα με τέτοιο τρόπο ώστε να αντιστοιχούν στα πεδία του πίνακα στην βάση
}
if(isset($_POST['contentTable'])) {
	$content_table = $_POST['contentTable'];
}


$query = "UPDATE users SET ";  //δημιουργία του query ανανέωσης

for($i=0; $i<sizeof($content_table); $i++) {
		
	if($i == sizeof($content_table)-1)
	{
		if($id_table[$i] == "graduation_date") { //αν ο χρήστης συμπληρωσει την ημερομηνία αποφοίτησης τοτε το ετος αποφοίτησης συμπληρωνεται αυτόματα
			$query = $query . $id_table[$i]. " = ?, graduation_year = ? WHERE id = ?";
		}
		else
		{
			$query = $query . $id_table[$i]. " = ? WHERE id = ?";
		}
	}
	else
	{
		if($id_table[$i] == "graduation_date") {
			$query = $query . $id_table[$i] . " = ?, graduation_year = ?, ";
		}
		else
		{
			$query = $query . $id_table[$i] . " = ?, ";
		}
	}
	
}

//echo $query;
$count = count($content_table);
$graduation_date_found = false;
$graduation_date_found_pos = 0;

for($i=0; $i<$count; $i++) {

	if($id_table[$i] == "graduation_date") { //αν εντοπιστεί το πεδίο ημερομηνία αποφοίτησης
		$graduation_date_found = true;
		$graduation_date_found_pos = $i;
	}
}

if($graduation_date_found == true) {
	$date = $content_table[$graduation_date_found_pos];	//τότε πρεπει να παρουμε το ετος απο την ημερομηνία
	$year = strtok($date, '-');
	$year = intval($year);
	$content_table[$count] = $year;
	$count = $count + 1;
}
$content_table[$count] = $alumni_id;

$stmt = $conn->prepare($query);
if ($stmt->execute($content_table)) {

	$stmt = $conn->prepare("SELECT * FROM users WHERE id = ?");   // Βρισκω το όνομα και το επωνυμο μου 
	$stmt->execute(array($alumni_id));
	$results = $stmt->fetchAll();

	if (sizeof($results)> 0) {
					
		foreach ($results as $row) {
			$name = $row['name'];
			$lastname = $row['lastname'];
		}
	}	
	
	$stmt2 = $conn->prepare("SELECT * FROM user_relationship WHERE friend_alumni_id = ?");   // αν με έχουν επιλέξει για να με ακολουθήσουν απόφοιτοι
	$stmt2->execute(array($alumni_id));														// τότε πρέπει να τους ενημερώσω ότι τροποποίησα τα προσωπικά στοιχεία μου
	$result3 = $stmt2->fetchAll();

	if (sizeof($result3)> 0) {			
		foreach($result3 as $row3) {
			$notify_alumni_id = $row3['alumni_id'];  // βρίσκω το id τους και δημιουργώ μια ειδοποίηση για αυτους
			$text = "Ο απόφοιτος ". $lastname . " " . $name . " ανανέωσε τα προσωπικά δεδομένα του";
						
			$stmt3 = $conn->prepare("INSERT INTO notifications (text, alumni_id) VALUES (?, ?)");
			$stmt3->bindParam(1, $text);
			$stmt3->bindParam(2, $notify_alumni_id);
			$stmt3->execute();
		}	
	}
	
	echo "Η ανανέωση των προσωπικών δεδομένων σας έγινε με επιτυχια";
}
	
?>