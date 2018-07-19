<?php
session_start();

include ("../connectPDO.php");
  
// Creates a new csv file and store it in tmp directory
$temp_dir = sys_get_temp_dir();
$new_csv = fopen($temp_dir.'/alumni.csv', 'w');
 
if(isset($_GET['query'])) {	
	$query = $_GET['query'];
}

$params = array();
if(isset($_SESSION['params'])) {
	$params = $_SESSION['params'];
}
    	
$stmt = $conn->prepare($query);
$stmt->execute($params);
$result = $stmt->fetchAll();
  
foreach($result as $row) {
	$id = $row['id'];
	$name = $row['name'];
	$lastname = $row['lastname'];
	$father = $row['fathers_name'];
	$birthday_date = $row['birthday_date'];
	$phone = $row['phone'];
	$phone2 = $row['cell_phone'];
	$email = $row['email'];
	$registration_year = $row['registration_year'];
	$graduation_year = $row['graduation_year'];
	$aem = $row['aem'];
	$grade = $row['degree_grade'];
	$thesis_diploma = $row['diploma_thesis_topic'];
	$facebook = $row['facebook'];
	$twitter = $row['twitter'];
	$instagram = $row['instagram'];
	$linkedin = $row['linkedin'];
	$google = $row['google'];
	$youtube = $row['youtube'];
	$job_city = $row['job_city'];
	$residence_city = $row['residence_city'];
	$workpiece = $row['Workpiece'];
	$job = $row['job'];
	if($job == 0) {
		$job = "";
	}
	else if($job == 1) {
		$job = "Ιδιωτικός Υπάλληλος";
	}
	else if($job == 2) {
		$job = "Δημόσιος Υπάλληλος";
	}
	else
	{
		$job = "Ελεύθερος επαγγελματίας";
	}
	$departmentID = $row['department_id'];
	
	$stmt2 = $conn->prepare("SELECT dname, facultyid from departments WHERE id = ?");
	$stmt2->execute(array($departmentID));
	$dedomena = $stmt2->fetchAll();

	if(sizeof($dedomena) > 0) {
		$dname = $dedomena[0][0];
		$facultyid = $dedomena[0][1];
	}
	else
	{
		$dname = "";
		$facultyid = 0;
	}
		
	$stmt3 = $conn->prepare("SELECT facultyname from faculties WHERE id = ?");
	$stmt3->execute(array($facultyid));
	$result3 = $stmt3->fetchAll();
	
	if(sizeof($result3) > 0) {
		$faculty = $result3[0][0];
	}
	else
	{
		$faculty = "";
	}
		
	$txt = $name . "," . $lastname . "," . $father . "," . $birthday_date . "," . $phone . "," . $phone2 . "," . $email . "," . $registration_year . "," . $graduation_year . "," . $aem . "," . $grade . "," . $thesis_diploma . "," . $facebook . "," . $twitter . "," . $instagram . "," . $linkedin . "," . $google . "," . $youtube . "," . $job_city . "," . $residence_city . "," . $workpiece . "," . $job . "," . $dname . "," . $faculty ."\n";
	fwrite($new_csv, $txt);
		
}
fclose($new_csv);

// output headers so that the file is downloaded rather than displayed
header("Content-type: text/csv");
header("Content-disposition: attachment; filename = alumni.csv");
readfile($temp_dir."/alumni.csv");
 

?>