<?php 
session_start();
include ("connectPDO.php");
$admin_id = 0;
$user_id = 0;
if(isset($_SESSION['name'])) {
	$admin_id = $_SESSION['name'];
}
if(isset($_SESSION['student'])) {
	$user_id = $_SESSION['student'];
}
$id = 0;
if(isset($_GET["id"])) {
	$id = $_GET["id"];
}

$stmt = $conn->prepare("SELECT * from users WHERE id = ?");
$stmt->execute(array($id));
$result = $stmt->fetchAll();

if (sizeof($result)>0) {
	foreach($result as $row) {
		$name = $row['name'];
		$lastname = $row['lastname'];
		$fathers_name = $row['fathers_name'];
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
		$departmentID = $row['department_id'];
		$social = $row['social'];
		$postgraduate = $row['metaptuxiako'];
		$postgraduate2 = $row['didaktoriko'];		
	}
}

$stmt2 = $conn->prepare("SELECT * from departments WHERE id = ?");
$stmt2->execute(array($departmentID));
$result2 = $stmt2->fetchAll();

if (sizeof($result2)> 0) {
	foreach($result2 as $row2) {
		$department = $row2['dname'];
		$facultyid = $row2['facultyid'];
	} 
}

$stmt3 = $conn->prepare("SELECT * from faculties WHERE id = ?");
$stmt3->execute(array($facultyid));
$result3 = $stmt3->fetchAll();

if (sizeof($result3)> 0) {
	foreach($result3 as $row3) {
		$faculty = $row3['facultyname'];
	} 
}

$stmt4 = $conn->prepare("SELECT * from images WHERE userID = ?");
$stmt4->execute(array($id));
$result4 = $stmt4->fetchAll();
$images_path = "";

if (sizeof($result4)> 0) {
	foreach($result4 as $row4) {
		$images_path = $row4['images_path'];		
	} 
}

//σε περίπτωση που κάποια δεδομένα δεν έχουν συμπληρωθεί
if(empty($images_path)) {
	$images_path = "user.png";
}

if(empty($postgraduate)) {
	$postgraduate = "Δεν υπάρχει διαθέσιμη καταχώρηση";   
}
else
{
	$postgraduate = $postgraduate .'&#13;---------------------------&#10;'.$postgraduate2;
}

if(empty($workpiece)) {
	$workpiece = "Δεν υπάρχει διαθέσιμη καταχώρηση";
}

if(empty($youtube)) {
	$youtube = "Δεν υπάρχει διαθέσιμη καταχώρηση";
}

if(empty($google)) {
	$google = "Δεν υπάρχει διαθέσιμη καταχώρηση";
}

if(empty($linkedin)) {
	$linkedin = "Δεν υπάρχει διαθέσιμη καταχώρηση";
}

if(empty($instagram)) {
	$instagram = "Δεν υπάρχει διαθέσιμη καταχώρηση";
}

if(empty($twitter)) {
	$twitter = "Δεν υπάρχει διαθέσιμη καταχώρηση";
}

if(empty($facebook )) {
	$facebook = "Δεν υπάρχει διαθέσιμη καταχώρηση";
}

if(empty($residence_city)) {
	$residence_city = "Δεν υπάρχει διαθέσιμη καταχώρηση";
}

if(empty($job_city)) {
	$job_city = "Δεν υπάρχει διαθέσιμη καταχώρηση";
}

if(empty($social)) {
	$social = "Δεν υπάρχει διαθέσιμη καταχώρηση";
}

if($job == 0) {
	$job = "Δεν υπάρχει διαθέσιμη καταχώρηση";
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

if(empty($phone) && empty($phone2)) {
	$phone = "Δεν υπάρχει διαθέσιμη καταχώρηση";
	$phone2 = "";
}
else if(empty($phone) && !empty($phone2)) {
	$phone = $phone2;
	$phone2 = "";
}

$timestamp  = strtotime($birthday_date); 
$bdate =  date('d-m-Y',$timestamp);
$empty_string = "";

if((isset($_SESSION['name'])) || (isset($_SESSION['student'])))  // αν ο χρήστης ειναι συνδεδεμένος
{
		echo "<form>";

		echo '<table id="myTable" width="100%">';
		echo	"<tbody>";
		echo		"<tr>";
		echo			'<td style="text-align: center;" width="25%" rowspan="2"><img src ="../users_images/'.$images_path.'" width=180 height=180></td>';
		echo			'<td width="25%"><label for="text">Όνομα</label><input style="font-size:12px;" class="form-control" id="owner" name="owner" value="'.$name.'" readonly="readonly" onselectstart="return false" onCopy="return false" onCut="return false"></td>'; 
		echo			'<td width="25%"><label for="text">Επώνυμο</label><input style="font-size:12px;" class="form-control" id="owner" name="owner" value="'.$lastname.'" readonly="readonly" onselectstart="return false" onCopy="return false" onCut="return false"></td>';
		echo			'<td width="25%"><label for="text">Πατρώνυμο</label><input style="font-size:12px;" class="form-control" id="owner" name="owner" value="'.$fathers_name.'" readonly="readonly" onselectstart="return false" onCopy="return false" onCut="return false"></td>';
		echo		"</tr>";
		echo		"<tr>";
		echo			'<td width="25%"><label for="text">Ημερομηνία Γέννησης</label><input style="font-size:12px;" class="form-control" id="owner" name="owner" value="'.$bdate.'" readonly="readonly" onselectstart="return false" onCopy="return false" onCut="return false"></td>'; 
		echo			'<td width="25%"><label for="text">Τηλέφωνο</label><textarea class="form-control custom-control" style="resize:none; font-size:12px;" readonly="readonly" onselectstart="return false" onCopy="return false" onCut="return false">'.$phone .'&#13;&#10;'. $phone2.'</textarea></td>'; 
		echo			'<td width="25%"><label for="text">Email</label><input style="font-size:12px;" class="form-control" id="owner" name="owner" value="'.$email.'" readonly="readonly" onselectstart="return false" onCopy="return false" onCut="return false"></td>';
		echo		"</tr>";
		echo		"<tr>";
		echo		  '<td width="25%"><label for="text">Σχολή</label><input style="font-size:12px;" class="form-control" id="owner" name="owner" value="'.$faculty.'" readonly="readonly" onselectstart="return false" onCopy="return false" onCut="return false"></td>';
		echo		  '<td width="25%"><label for="text">Τμήμα</label><textarea class="form-control custom-control" style="resize:none; font-size:12px;" readonly="readonly" onselectstart="return false" onCopy="return false" onCut="return false">'.$department.'</textarea></td>';
		echo		  '<td width="25%"><label for="text">Έτος Εισαγωγής</label><input style="font-size:12px;" class="form-control" id="owner" name="owner" value="'.$registration_year.'" readonly="readonly" onselectstart="return false" onCopy="return false" onCut="return false"></td>';
		echo		  '<td width="25%"><label for="text">Έτος Αποφοίτησης</label><input style="font-size:12px;" class="form-control" id="owner" name="owner" value="'.$graduation_year.'" readonly="readonly" onselectstart="return false" onCopy="return false" onCut="return false"></td>';
		echo		"</tr>";
		echo		"<tr>";
		echo		  '<td width="25%"><label for="text">Αριθμός Μητρώου Φοιτητή</label><input style="font-size:12px;" class="form-control" id="owner" name="owner" value="'.$aem.'" readonly="readonly" onselectstart="return false" onCopy="return false" onCut="return false"></td>';
		echo		  '<td width="25%"><label for="text">Βαθμός Πτυχίου</label><input style="font-size:12px;" class="form-control" id="owner" name="owner" value="'.$grade.'" readonly="readonly" onselectstart="return false" onCopy="return false" onCut="return false"></td>';
		echo		  '<td width="25%"><label for="text">Θέμα Διπλωματικής</label><textarea class="form-control custom-control" style="resize:none; font-size:12px;" readonly="readonly" onselectstart="return false" onCopy="return false" onCut="return false">'.$thesis_diploma.'</textarea></td>';
		echo		  '<td width="25%"><label for="text">Μεταπτυχιακά - Διδακτορικά</label><textarea class="form-control custom-control" style="resize:none; font-size:12px;" readonly="readonly" onselectstart="return false" onCopy="return false" onCut="return false">'.$postgraduate.'</textarea></td>';
		echo		"</tr>";
		echo		"<tr>";
		echo		  '<td width="25%"><label for="text">Facebook</label><input style="font-size:12px;" class="form-control" id="owner" name="owner" value="'.$facebook.'" readonly="readonly" onselectstart="return false" onCopy="return false" onCut="return false"></td>';
		echo		  '<td width="25%"><label for="text">Twitter</label><input style="font-size:12px;" class="form-control" id="owner" name="owner" value="'.$twitter.'" readonly="readonly" onselectstart="return false" onCopy="return false" onCut="return false"></td>';
		echo		  '<td width="25%"><label for="text">Instagram</label><input style="font-size:12px;" class="form-control" id="owner" name="owner" value="'.$instagram.'" readonly="readonly" onselectstart="return false" onCopy="return false" onCut="return false"></td>';
		echo		  '<td width="25%"><label for="text">Linkedin</label><input style="font-size:12px;" class="form-control" id="owner" name="owner" value="'.$linkedin.'" readonly="readonly" onselectstart="return false" onCopy="return false" onCut="return false"></td>';	
		echo		"</tr>";
		echo		"<tr>";
		echo		  '<td width="25%"><label for="text">Google+</label><input style="font-size:12px;" class="form-control" id="owner" name="owner" value="'.$google.'" readonly="readonly" onselectstart="return false" onCopy="return false" onCut="return false"></td>';
		echo		  '<td width="25%"><label for="text">Youtube</label><input style="font-size:12px;" class="form-control" id="owner" name="owner" value="'.$youtube.'" readonly="readonly" onselectstart="return false" onCopy="return false" onCut="return false"></td>';
		echo		  '<td width="25%"><label for="text">Άλλο</label><input style="font-size:12px;" class="form-control" id="owner" name="owner" value="'.$social.'" readonly="readonly" onselectstart="return false" onCopy="return false" onCut="return false"></td>';		
		echo		"</tr>";
		echo		'<tr>';
		echo		  '<td width="25%"><label for="text">Πόλη Διαμονής</label><input style="font-size:12px;" class="form-control" id="owner" name="owner" value="'.$residence_city.'" readonly="readonly" onselectstart="return false" onCopy="return false" onCut="return false"></td>';
		echo		  '<td width="25%"><label for="text">Πόλη Εργασίας</label><input style="font-size:12px;" class="form-control" id="owner" name="owner" value="'.$job_city.'" readonly="readonly" onselectstart="return false" onCopy="return false" onCut="return false"></td>';
		echo		  '<td width="25%"><label for="text">Επάγγελμα</label><input style="font-size:12px;" class="form-control" id="owner" name="owner" value="'.$job.'" readonly="readonly" onselectstart="return false" onCopy="return false" onCut="return false"></td>';
		echo		  '<td width="25%"><label for="text">Αντικείμενο Εργασίας</label><textarea class="form-control custom-control" style="resize:none; font-size:12px;" readonly="readonly" onselectstart="return false" onCopy="return false" onCut="return false">'.$workpiece.'</textarea></td>';
		echo		"</tr>";
				

		echo	"</tbody>";

		echo "</table>";

		echo "</form>";

}
else  // αν ο χρήστης είναι επισκέπτης
{    

		echo "<form>";

		echo '<table id="myTable" width="100%">';
		echo    "<tbody>";
		echo		"<tr>";
		echo			'<td style="text-align: center;" width="25%" rowspan="2"><img src ="../users_images/'.$images_path.'" width=180 height=180></td>';
		echo			'<td width="25%"><label for="text">Όνομα</label><input style="font-size:12px;" class="form-control" id="owner" name="owner" value="'.$name.'" readonly="readonly" onselectstart="return false" onCopy="return false" onCut="return false"></td>';
		echo			'<td width="25%"><label for="text">Επώνυμο</label><input style="font-size:12px;" class="form-control" id="owner" name="owner" value="'.$lastname.'" readonly="readonly" onselectstart="return false" onCopy="return false" onCut="return false"></td>';
		echo			'<td width="25%"><label for="text">Πατρώνυμο</label><input style="font-size:12px;" class="form-control" id="owner" name="owner" value="'.$fathers_name.'" readonly="readonly" onselectstart="return false" onCopy="return false" onCut="return false"></td>';
		echo		"</tr>";
		echo		"<tr>";
		echo			'<td width="25%"><label for="text">Ημερομηνία Γέννησης</label><span class="red-star">★</span><input style="font-size:12px;" class="form-control" id="owner" name="owner" value="'.$empty_string.'" readonly="readonly" onselectstart="return false" onCopy="return false" onCut="return false"></td>'; 
		echo		    '<td width="25%"><label for="text">Τηλέφωνο</label><span class="red-star">★</span><textarea class="form-control custom-control" style="resize:none; font-size:12px;" readonly="readonly" onselectstart="return false" onCopy="return false" onCut="return false">'.$empty_string.'</textarea></td>'; 
		echo			'<td width="25%"><label for="text">Email</label><input style="font-size:12px;" class="form-control" id="owner" name="owner" value="'.$email.'" readonly="readonly" onselectstart="return false" onCopy="return false" onCut="return false"></td>';
		echo		"</tr>";
		echo		"<tr>";
		echo		  '<td width="25%"><label for="text">Σχολή</label><input style="font-size:12px;" class="form-control" id="owner" name="owner" value="'.$faculty.'" readonly="readonly" onselectstart="return false" onCopy="return false" onCut="return false"></td>';
		echo		  '<td width="25%"><label for="text">Τμήμα</label><textarea class="form-control custom-control" style="resize:none; font-size:12px;" readonly="readonly" onselectstart="return false" onCopy="return false" onCut="return false">'.$department.'</textarea></td>';
		echo		  '<td width="25%"><label for="text">Έτος Εισαγωγής</label><input style="font-size:12px;" class="form-control" id="owner" name="owner" value="'.$registration_year.'" readonly="readonly" onselectstart="return false" onCopy="return false" onCut="return false"></td>';
		echo		  '<td width="25%"><label for="text">Έτος Αποφοίτησης</label><input style="font-size:12px;" class="form-control" id="owner" name="owner" value="'.$graduation_year.'" readonly="readonly" onselectstart="return false" onCopy="return false" onCut="return false"></td>';
		echo		"</tr>";
		echo		"<tr>";
		echo		  '<td width="25%"><label for="text">Αριθμός Μητρώου Φοιτητή</label><span class="red-star">★</span><input style="font-size:12px;" class="form-control" id="owner" name="owner" value="'.$empty_string.'" readonly="readonly" onselectstart="return false" onCopy="return false" onCut="return false"></td>';
		echo		  '<td width="25%"><label for="text">Βαθμός Πτυχίου</label><span class="red-star">★</span><input style="font-size:12px;" class="form-control" id="owner" name="owner" value="'.$empty_string.'" readonly="readonly" onselectstart="return false" onCopy="return false" onCut="return false"></td>';
		echo		  '<td width="25%"><label for="text">Θέμα Διπλωματικής</label><span class="red-star">★</span><textarea class="form-control custom-control" style="resize:none; font-size:12px;" readonly="readonly" onselectstart="return false" onCopy="return false" onCut="return false"></textarea></td>';
		echo		  '<td width="25%"><label for="text">Μεταπτυχιακά - Διδακτορικά</label><textarea class="form-control custom-control" style="resize:none; font-size:12px;" readonly="readonly" onselectstart="return false" onCopy="return false" onCut="return false">'.$postgraduate.'</textarea></td>'; 
		echo		"</tr>";
		echo		"<tr>";
		echo		  '<td width="25%"><label for="text">Facebook</label><span class="red-star">★</span><input style="font-size:12px;" class="form-control" id="owner" name="owner" value="'.$empty_string.'" readonly="readonly" onselectstart="return false" onCopy="return false" onCut="return false"></td>';
		echo		  '<td width="25%"><label for="text">Twitter</label><span class="red-star">★</span><input style="font-size:12px;" class="form-control" id="owner" name="owner" value="'.$empty_string.'" readonly="readonly" onselectstart="return false" onCopy="return false" onCut="return false"></td>';
		echo		  '<td width="25%"><label for="text">Instagram</label><span class="red-star">★</span><input style="font-size:12px;" class="form-control" id="owner" name="owner" value="'.$empty_string.'" readonly="readonly" onselectstart="return false" onCopy="return false" onCut="return false"></td>';
		echo		  '<td width="25%"><label for="text">Linkedin</label><span class="red-star">★</span><input style="font-size:12px;" class="form-control" id="owner" name="owner" value="'.$empty_string.'" readonly="readonly" onselectstart="return false" onCopy="return false" onCut="return false"></td>';	
		echo		"</tr>";
		echo		"<tr>";
		echo		  '<td width="25%"><label for="text">Google+</label><span class="red-star">★</span><input style="font-size:12px;" class="form-control" id="owner" name="owner" value="'.$empty_string.'" readonly="readonly" onselectstart="return false" onCopy="return false" onCut="return false"></td>';
		echo		  '<td width="25%"><label for="text">Youtube</label><span class="red-star">★</span><input style="font-size:12px;" class="form-control" id="owner" name="owner" value="'.$empty_string.'" readonly="readonly" onselectstart="return false" onCopy="return false" onCut="return false"></td>';
		echo		"</tr>";
		echo		"<tr>";
		echo		  '<td width="25%"><label for="text">Πόλη Διαμονής</label><span class="red-star">★</span><input style="font-size:12px;" class="form-control" id="owner" name="owner" value="'.$empty_string.'" readonly="readonly" onselectstart="return false" onCopy="return false" onCut="return false"></td>';
		echo		  '<td width="25%"><label for="text">Πόλη Εργασίας</label><span class="red-star">★</span><input style="font-size:12px;" class="form-control" id="owner" name="owner" value="'.$empty_string.'" readonly="readonly" onselectstart="return false" onCopy="return false" onCut="return false"></td>';
		echo		  '<td width="25%"><label for="text">Επάγγελμα</label><span class="red-star">★</span><input style="font-size:12px;" class="form-control" id="owner" name="owner" value="'.$empty_string.'" readonly="readonly" onselectstart="return false" onCopy="return false" onCut="return false"></td>';
		echo		  '<td width="25%"><label for="text">Αντικείμενο Εργασίας</label><span class="red-star">★</span><textarea class="form-control custom-control" style="resize:none; font-size:12px;" readonly="readonly" onselectstart="return false" onCopy="return false" onCut="return false">'.$empty_string.'</textarea></td>';
		echo		"</tr>";
				

		echo	"</tbody>";

		echo "</table>";

		echo "</form>";

}

?>
