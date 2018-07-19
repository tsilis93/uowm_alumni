<?php
session_start();
include ("../connectPDO.php");

$done = true;

$lastname = $_POST['lastname1'];
$name = $_POST['name1'];
$mail = $_POST['mail1'];
$aem = $_POST['aem'];
$cell_phone = $_POST['phone'];
$message = $_POST['message'];
$id = $_POST['id']; 

$created_by = 1;		//created_by = 1 =>απόφοιτος, created_by = 0 => διαχειριστής
$active = 0;			//active = 1 => ενεργός, active = 0 => ανενεργός
$role = 1;				//role = 1 => απόφοιτος, role = 2 => διαχειριστής, role = 3 => απόφοιτος & διαχειριστής

//ολα τα υπόλοιπα πεδια αρχικοποιούνται με 0 ή κενό
$father = "";
$degree_grade = 0;
$phone = "";
$residence_city = "";
$linkedin = "";
$facebook = "";
$instagram = "";
$twitter = "";
$google = "";
$youtube = "";
$social = "";
$diploma_thesis_topic = "";
$job = 0;
$Workpiece = "";
$job_city = "";
$metaptuxiako = "";
$didaktoriko = "";
$change_password = 0;
$hash = "";
$username = "";
$password = "";


$sql = "INSERT INTO users (lastname, name, email, aem, cell_phone, messageToadmin, department_id, created_by, active, role, fathers_name, degree_grade, phone, residence_city, linkedin, facebook, instagram, twitter, google, youtube, social, diploma_thesis_topic, job, Workpiece, job_city, metaptuxiako, didaktoriko, change_password, hash, username, password) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)";
$stmt = $conn->prepare($sql);
if ($stmt->execute(array($lastname,$name,$mail,$aem,$cell_phone,$message,$id,$created_by,$active,$role, $father, $degree_grade, $phone, $residence_city, $linkedin, $facebook, $instagram, $twitter, $google, $youtube, $social, $diploma_thesis_topic, $job, $Workpiece, $job_city, $metaptuxiako, $didaktoriko, $change_password, $hash, $username, $password)))
{
	//echo "Η εγγραφή σας έγινε με επιτυχία και βρίσκεται σε διαδικασία έγκρισης από τον διαχειριστή";
	echo $done;
	
	$stmt2 = $conn->prepare("SELECT dname FROM departments WHERE id = ?");
	$stmt2->execute(array($id));
	$temp = $stmt2->fetchAll();

	$dname = $temp[0][0];
  
	$text = "Ένας νέος απόφοιτος με όνομα '".$name . $lastname."' έκανε αίτηση εγγραφής στο '".$dname."'";
	$admin_id = 1;
	$alumni_id = 0;

	$stmt = $conn->prepare("INSERT INTO notifications (text, admin_id, alumni_id) VALUES (?, ?, ?)");
	$stmt->bindParam(1, $text);
	$stmt->bindParam(2, $admin_id);
	$stmt->bindParam(3, $alumni_id);
	$stmt->execute();
}
else
{
	//echo "Παρουσιάστηκε πρόβλημα στην εγγραφή σας";
  	$done = false;
	echo $done;
}

?>
