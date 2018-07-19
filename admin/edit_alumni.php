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
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-select/1.12.4/css/bootstrap-select.min.css">
  <script src="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-select/1.12.4/js/bootstrap-select.min.js"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.css">

  
    	<link rel="stylesheet" href="../css/edit_alumni.css"> 
				
	<link rel="stylesheet" type="text/css" href="../phones_specs/css/dialog-mobile.css"/> <!-- css & javascript για τις ειδοποιήσεις (alerts) στα κινητα -->
	<script type="text/javascript" src="../phones_specs/mcx-dialog.js"></script>
 
</head>
<body>

<header>

</header>

<?php

session_start();
include ("../connectPDO.php");

$admin_id = 0;
if(isset($_SESSION['name'])) {
	$admin_id = $_SESSION['name'];
}
else
{
header('Location: ../register_login_form.php');
}

if(isset($_GET['id'])) {
	$alumni_id = $_GET['id'];
}

$var = 0;	//αρχικοποίηση διαδικασίας ενημέρωσης του χρήστη για το ανέβασμα βιογραφικού
if(!isset($_GET['Failed'])) {
	$loginFailed = "";
	$reasons="";
	$var=1;
}
$reasons = array(
	"blank" => "Παρακαλώ επιλέξτε ένα αρχείο .pdf για υποβολή",
	"size"	=> "Το αρχείο που επιλέξατε έχει μέγεθος μεγαλύτερο από 5 MB, γι αυτό και απορρίφθηκε",
	"error"	=> "Το αρχείο δεν αποθηκεύτηκε στην βάση παρακαλώ δοκιμάστε ξανά",
	"type"	=> "Το αρχείο που επιλέξατε δεν είναι pdf. Παρακαλώ επιλέξτε ένα αρχείο .pdf για υποβολή",
);
if($var == 0) {
	if($_GET["Failed"] == 'true') {
		$message = $reasons[$_GET["reason"]];
		echo "<script type='text/javascript'> var w = Math.max(document.documentElement.clientWidth, window.innerWidth || 0); if (w < 768) {  mcxDialog.alert('$message'); } else { alert('$message'); }</script>";
	}
	else
	{
		$message = "Το βιογραφικό ανέβηκε με επιτυχία";
		echo "<script type='text/javascript'> var w = Math.max(document.documentElement.clientWidth, window.innerWidth || 0); if (w < 768) {  mcxDialog.alert('$message'); } else { alert('$message'); }</script>";
	}
}

$stmt = $conn->prepare("SELECT * from users WHERE id = ?");   //συλλογή πληροφοριών για τον απόφοιτο με id = $alumni_id
$stmt->execute(array($alumni_id));															
$result = $stmt->fetchAll();

if(sizeof($result)>0) {
	
	foreach($result as $row) {
		$name = $row['name'];
		$lastname = $row['lastname'];
		$fathers_name = $row['fathers_name'];
		$birthday_date = $row['birthday_date'];
		$phone = $row['phone'];
		$phone2 = $row['cell_phone'];
		$email = $row['email'];
		$registration_year = $row['registration_year'];
		$graduation_date = $row['graduation_date'];
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
		$site = $row['social'];
		$departmentID = $row['department_id'];
		$postgraduate = $row['metaptuxiako'];
		$postgraduate2 = $row['didaktoriko'];
		$username = $row['username'];
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

	$stmt4 = $conn->prepare("SELECT * from images WHERE userID = ?");
	$stmt4->execute(array($alumni_id));
	$result4 = $stmt4->fetchAll();
	$images_path = "";

	if (sizeof($result4)> 0) {
		foreach($result4 as $row4) {
			$images_path = $row4['images_path'];		
		}
		if(file_exists("../users_images/".$images_path)) {
			
		}
		else
		{
			$images_path = "user.png";
		}
	}
	else
	{
		$images_path = "user.png";
	}


	$timestamp  = strtotime($birthday_date); 
	$bdate =  date('Y-m-d',$timestamp);
	
	$graduation_date = strtotime($graduation_date);
	$graduation_date = date('Y-m-d',$graduation_date);
	
	$empty_string = "";	
	
}
else
{
header('Location: ../notFound.php');
}

echo "<br><br><br><br><br><br>";
echo '<div class="container">';
echo '<h2 align="center">Στοιχεία Αποφοίτου</h2>';	

echo '<table id="myTable" width="100%">';
		echo	"<tbody>";
		echo		"<tr>";
		echo			'<td style="text-align: center;" rowspan="2"><img id = "image" src ="../users_images/'.$images_path.'" width=180 height=180></td>';
		echo			'<td><label for="text">Όνομα</label><input class="form-control fontsize" id="name" name="owner" value="'.$name.'" ></td>'; 
		echo			'<td><label for="text">Επώνυμο</label><input class="form-control fontsize" id="lastname" name="owner" value="'.$lastname.'" ></td>';
		echo			'<td><label for="text">Πατρώνυμο</label><input class="form-control fontsize" id="fathers_name" name="owner" value="'.$fathers_name.'" ></td>';
		echo		"</tr>";
		echo		"<tr>"; 
		if($bdate == "1970-01-01") {
			echo			'<td><label for="text">Ημερομηνία Γέννησης</label><br><input class="form-control fontsize" type="date" id="birthday_date" name="owner"></td>'; 		
		}
		else
		{
			echo			'<td><label for="text">Ημερομηνία Γέννησης</label><br>(yyyy-mm-dd)<input class="form-control fontsize" type="date" id="birthday_date" name="owner" value="'.$bdate.'"></td>'; 
		}
		echo			'<td><label for="text">Τηλέφωνο</label><input class="form-control fontsize" id="phone" name="owner" value="'.$phone.'" ></td>'; 
		echo			'<td><label for="text">Email</label><input class="form-control fontsize" id="email" name="owner" value="'.$email.'" ></td>';
		echo		"</tr>";
		echo		"<tr>";
		
		echo		  '<td><label for="text">Αριθμός Μητρώου Φοιτητή</label><input class="form-control fontsize" id="aem" name="owner" value="'.$aem.'"></td>';
		echo		  '<td><label for="text">Τμήμα</label><br>';
		echo				'<select class="selectpicker" name="where2" id = "department_id">';
		$stmt = $conn->prepare("SELECT * FROM departments");
		$stmt->execute();
		$result = $stmt->fetchAll(); 
		if($department == "") {
			echo  '<option value="">Select your option</option>';
		}
	
		foreach($result as $row) {
				if($row['dname'] == $department) {
					echo  '<option value="'.$row['id'].'" selected="selected">'.$row['dname'].'</option>';
				}
				else
				{
					echo  '<option value="'.$row['id'].'">'.$row['dname'].'</option>';
				}
		}
		echo		  '</select></td>';		
		echo		  '<td><label for="text">Έτος Εισαγωγής</label><input class="form-control fontsize" id="registration_year" name="owner" value="'.$registration_year.'"></td>';
		if($graduation_date == "1970-01-01") {
			echo		  '<td><label for="text">Ημερομηνία Αποφοίτησης</label><input class="form-control fontsize" type="date" id="graduation_date" name="owner"></td>';
		}
		else
		{
			echo		  '<td><label for="text">Ημερομηνία Αποφοίτησης</label>(yyyy-mm-dd)<input class="form-control fontsize" type="date" id="graduation_date" name="owner" value="'.$graduation_date.'"></td>';
		}		
		echo		"</tr>";
		echo		"<tr>";
		echo		  '<td><label for="text">Βαθμός Πτυχίου</label><input class="form-control fontsize" id="degree_grade" name="owner" value="'.$grade.'"></td>';
		echo		  '<td><label for="text">Θέμα Διπλωματικής</label><textarea id = "diploma_thesis_topic" class="form-control custom-control" style="resize:none; font-size:12px;">'.$thesis_diploma.'</textarea></td>';
		echo		  '<td><label for="text">Μεταπτυχιακό</label><textarea id="metaptuxiako" class="form-control custom-control" style="resize:none; font-size:12px;">'.$postgraduate.'</textarea></td>';
		echo		  '<td><label for="text">Διδακτορικό</label><textarea id = "didaktoriko" class="form-control custom-control" style="resize:none; font-size:12px;">'.$postgraduate2.'</textarea></td>';		
		echo		"</tr>";
		echo		"<tr>";
		echo		  '<td><label for="text">Twitter</label><input class="form-control fontsize" id="twitter" name="owner" value="'.$twitter.'"></td>';
		echo		  '<td><label for="text">Instagram</label><input class="form-control fontsize" id="instagram" name="owner" value="'.$instagram.'"></td>';
		echo		  '<td><label for="text">Linkedin</label><input class="form-control fontsize" id="linkedin" name="owner" value="'.$linkedin.'"></td>';	
		echo		  '<td><label for="text">Facebook</label><input class="form-control fontsize" id="facebook" name="owner" value="'.$facebook.'"></td>';		
		echo		"</tr>";
		echo		"<tr>";
		echo		  '<td><label for="text">Google+</label><input class="form-control fontsize" id="google" name="owner" value="'.$google.'"></td>';
		echo		  '<td><label for="text">Youtube</label><input class="form-control fontsize" id="youtube" name="owner" value="'.$youtube.'"></td>';
		echo		  '<td><label for="text">Άλλο</label><input class="form-control fontsize" name="optional" value="'.$site.'" id = "social"></td>';
		echo		"</tr>";
		echo		"<tr>";
		echo		  '<td><label for="text">Πόλη Διαμονής</label><input class="form-control fontsize" id="residence_city" name="owner" value="'.$residence_city.'"></td>';
		echo		  '<td><label for="text">Πόλη Εργασίας</label><input class="form-control fontsize" id="job_city" name="owner" value="'.$job_city.'"></td>';
		echo		  '<td><label for="text">Επάγγελμα</label>';
		echo				'<select class="selectpicker" data-width="100%" name="where" id = "job">';
		if($job == 0) {
			echo  '<option value="">Select your option</option>';
			echo  '<option value="1">Ιδιωτικός Υπάλληλος</option>';
			echo  '<option value="2">Δημόσιος Υπάλληλος</option>';
			echo  '<option value="3">Ελεύθερος επαγγελματίας</option>';			
		}
		else if($job == 1) {
			echo  '<option value="1" selected="selected">Ιδιωτικός Υπάλληλος</option>';
			echo  '<option value="2">Δημόσιος Υπάλληλος</option>';
			echo  '<option value="3">Ελεύθερος επαγγελματίας</option>';
		}
		else if($job == 2) {
			echo  '<option value="1">Ιδιωτικός Υπάλληλος</option>';
			echo  '<option value="2" selected="selected">Δημόσιος Υπάλληλος</option>';
			echo  '<option value="3">Ελεύθερος επαγγελματίας</option>';
		}
		else
		{
			echo  '<option value="1">Ιδιωτικός Υπάλληλος</option>';
			echo  '<option value="2">Δημόσιος Υπάλληλος</option>';
			echo  '<option value="3" selected="selected">Ελεύθερος επαγγελματίας</option>';
		}
		echo		  '</select></td>';			
		echo		  '<td><label for="text">Αντικείμενο Εργασίας</label><textarea id = "workpiece" class="form-control custom-control" style="resize:none; font-size:12px;">'.$workpiece.'</textarea></td>';
		echo		"</tr>";
		echo		"<tr>";
		echo		  '<td><label for="text">Κινητό</label><input class="form-control fontsize" id="cell_phone" name="owner" value="'.$phone2.'" ></td>';		
		echo		  '<td><label for="text">Username</label><input class="form-control fontsize" id="username" name="owner" value="'.$username.'" ></td>';
		if($alumni_id != $admin_id) {
			echo		  '<td><label for="text">Νέος Κωδικός  <input id="password" type="checkbox"> </label></td>';
		}
		echo		'</tr>';
		echo	"</tbody>";

		echo "</table>";
		echo '<br>';
		echo '<table id="myTable" width="100%">';
		echo	'<tbody>';								//διαδικασία ανεβάσματος βιογραφικού για λογαριασμό του απόφοιτου
		echo		'<form action="upload_pdfFile.php?id='.$alumni_id.'" method="post" enctype="multipart/form-data">';
		echo		'<tr>';
		echo		  '<td width="10%"><label for="text">Pdf βιογραφικό:</label></td>';
		echo		  '<td width="70%"><input name="mycvPDF" class="btn btn-default form-control" type="file" id = "myPdf"/></td>';		
		echo		  '<td width="15%" style="text-align:center;"><input type="submit" class="btn" value="Υποβολή"></input></td>';
		echo		  '<td width="5%"><a href="#" id="clear"> Clear<img src="../assets/delete.png" height="15" width="15"></a></td>';
		echo		'</tr>';
		echo		'</form>';
		echo		'<tr>';						//διαδικασία αλλαγής της εικόνας προφιλ του απόφοιτου
		echo		  '<td width="20%"><input id ="'.$alumni_id.'" type="button" value="Αλλαγή Εικόνας" class="btn btn-info" onclick="myFunction(this.id);"></td>';
		echo		  '<td width="60%"></td>';					//button αποθήκευσης αλλαγών στα δεδομένα του αποφοίτου
		echo		  '<td width="20%"><input id ="submit"  type="button" value="Αποθήκευση Αλλαγών" class="btn btn-primary" ></td>';
		echo		"</tr>";
		echo	'</tbody>';
		echo '</table>';		
echo	'<br>';
echo "</div>";

if($alumni_id == $admin_id) { //δυνατότητα αλλαγής κωδικού πρόσβασης χειροκίνητα μονο για τον διαχειριστή-απόφοιτο

echo "<br><br><br>";
echo '<div class="container">';	
echo '<h2 align="center">Αλλαγή Κωδικού Πρόσβασης</h2>';

echo '<br><p id="login"><span class="label label-info"><b>INFO </span>  Αυτή η επιλογή εμφανίζεται μόνο όταν ο διαχειριστής είναι ταυτόχρονα και απόφοιτος και επεξεργάζεται το δικό του profil</b></p><br>';

echo	'<label for="pass">Κώδικός Πρόσβασης <i id="pass-status" class="fa fa-eye" aria-hidden="true" onClick="viewPassword()"></i></label><br>';
echo    '<input type="password" class="form-control fontsize" name="pass" id="admin_password"/>';
echo	'<label for="npass">Νέος Κώδικός Πρόσβασης <i id="pass-status2" class="fa fa-eye" aria-hidden="true" onClick="viewPassword2()"></i></label>';
echo    '<input type="password" class="form-control fontsize" name="npass" id="new_password"/>';
echo	'<label for="cpass">Επαλήθευση Νέου Κωδικού <i id="pass-status3" class="fa fa-eye" aria-hidden="true" onClick="viewPassword3()"></i></label>';
echo	'<input type="password" class="form-control fontsize" name="cpass" id="con_new_pass"/>';	
echo	"<br>";
echo	'<table width="100%">';
echo		"<tr>";
echo			'<td width="90%"></td>';
echo			'<td width="10%"><input id ="change_password"  type="button" value="Αποθήκευση" class="btn btn-primary"></td>';
echo		'</tr>';
echo	'</table>';
echo	'<br>';	
echo '</div>';	
}

echo "<br><br><br>";
echo '<div class="container">';
echo '<h2 align="center">Εγγραφή του αποφοίτου σε newsletter</h2>';

echo '<p id="login"><span class="label label-info"><b>INFO </span>  Αν μία από τις κατηγορίες είναι επιλεγμένη συνεπάγεται ότι ο απόφοιτος έχει επιλέξει να δέχεται newsletter από την συγκεκριμένη κατηγορία</b> </p>';


$newsletter_categories = array();
$categories_id = array();
$counter = 0;

echo '<table id="myTable2" width="100%">';

$stmt = $conn->prepare("SELECT * from newsletter_categories");  
$stmt->execute();
$result = $stmt->fetchAll();

if (sizeof($result)>0) {   //αν υπάρχει κατηγορία
	foreach($result as $row) {
		$cname = $row['category_name'];
		$id = $row['id'];
		$newsletter_categories[$counter] = $cname;
		$categories_id[$counter] = $id;
		$counter = $counter + 1;
	}
	
	$stmt2 = $conn->prepare("SELECT * from newsletter WHERE alumni_id = ?");  
	$stmt2->execute(array($alumni_id));
	$result2 = $stmt2->fetchAll();
	
	if(sizeof($result2)>0) {
		foreach($result2 as $row2) {
			for($i=0; $i<count($newsletter_categories); $i++) {
				$option = "option_id".$categories_id[$i];
				echo '<tr>';
				echo	'<td style="width:70%">'.$newsletter_categories[$i].'</td>';
				if($row2[$option] == 1) {
					echo	'<td style="width:30%"><input type="checkbox" id="'.$option.'" checked></td>';
				}
				else
				{
					echo	'<td style="width:30%"><input type="checkbox" id="'.$option.'"></td>';
				}
				echo '</tr>';
			}
		}
	}
	else
	{
		for($i=0; $i<count($newsletter_categories); $i++) {
			$option = "option_id".$categories_id[$i];
			echo '<tr>';
				echo	'<td style="width:70%">'.$newsletter_categories[$i].'</td>';
				echo	'<td style="width:30%"><input type="checkbox" id="'.$option.'"></td>';
			echo '</tr>';
		}				
	}
	
}
else
{
	echo '<tr>';
	echo	'<td colspan="2">Δεν υπάρχει διαθέσιμη κατηγορία για newsletter</td>';
	echo '</tr>';
}

echo '</table>';
echo "<br>";
echo '</div>';

echo "<br><br><br>";
echo '<div class="container">';
echo '<h2 align="center">Προσθήκη απόφοιτου στα άτομα που ακολουθεί ο απόφοιτος</h2>';

echo '<table id="myTable" width="100%">';
echo	'<tr>';
echo 		'<td width="80%"><label for="text">Όνομα ή Επώνυμο</label><input class="form-control" id="friend_name" name="owner"></td>';
echo		'<td width="20%"><input type="button" id="submit4" class="btn btn-primary" value="Αναζήτηση"></input></td>';
echo	'</tr>';
echo '</table>';

echo '<br>';
echo '<label for="text"><u>Αποτελέσματα Αναζήτησης:</u></label>';
echo '<br><br><div id="results">';

echo	'<table id = "myTable2" width="100%">';
echo		'<thead>';
echo	      	'<tr>';
echo				'<th width="25%">Επώνυμο</th>';
echo				'<th width="25%">Όνομα</th>';
echo				'<th width="25%">Σχολή</th>';
echo				'<th width="25%">Επιλογή</th>';
echo			'</tr>';
echo		'</thead>';
echo		'<tbody>';

echo		'</tbody>';
echo	'</table>';	
echo '</div>';	
echo '<br><br>';
echo '<label for="text"><u>Απόφοιτοι-Φίλοι:</u></label>';
echo '<table class="table" id = "myTable2" width="100%">';
echo		'<thead>';
echo		    '<tr>';
echo				'<th>Επώνυμο</th>';
echo				'<th>Όνομα</th>';
echo				'<th>Σχολή</th>';
echo				'<th>Επιλογή</th>';
echo			'</tr>';
echo		'</thead>';
echo		'<tbody>';
$stmt = $conn->prepare("SELECT * FROM user_relationship WHERE alumni_id = ?");
$stmt->execute(array($alumni_id));
$result = $stmt->fetchAll();

if(sizeof($result)>0) {

	foreach($result as $row) {
		$friend_alumni_id = $row['friend_alumni_id'];

		$stmt2 = $conn->prepare("SELECT * FROM users WHERE id = ?");
		$stmt2->execute(array($friend_alumni_id));
		$result2 = $stmt2->fetchAll();
		
		foreach($result2 as $row2) {
			$did = $row2['department_id'];
			$lastname = $row2['lastname'];
			$name = $row2['name'];
			
			$stmt3 = $conn->prepare("SELECT * FROM departments WHERE id = ?");
			$stmt3->execute(array($did));
			$result3 = $stmt3->fetchAll();
					
			foreach($result3 as $row3) {
				$dname = $row3['dname'];
			}
			echo	"<tr>";
			echo		'<td class="text-center">'.$lastname.'</td>';
			echo		'<td class="text-center">'.$name.'</td>';
			echo		'<td class="text-center">'.$dname.'</td>';
			echo		'<td class="text-center"><button id = "'.$friend_alumni_id.'" class="btn btn-link" onclick="javascript:unfollow_alumni(this.id); return false;"><font color="red">Unfollow<font></button></td>';
			echo	"</tr>";		
		}
	}
}
else
{
	echo  '<tr align = "center"><td colspan = "4"> Ο απόφοιτος δεν έχει επιλέξει να ακολουθήσει κάποιον απόφοιτο</td></tr>'; 
}
echo    '</tbody>';
echo  '</table>';
echo '<br>';
echo '</div>';

echo "<br><br><br>";
echo '<div class="container">';
echo '<h2 align="center">Οι ειδοποιήσεις του απόφοιτου</h2>';

$stmt = $conn->prepare("SELECT * FROM notifications WHERE alumni_id = ?");
$stmt->execute(array($alumni_id));
$result = $stmt->fetchAll();
		

if(empty($result))
{
	echo '<br><p style="color:black" align="center"><font size="4">Δεν υπάρχουν διαθέσιμες Ειδοποιήσεις</font></p><br>';
}
else
{
	echo '<table id = "myTable2" width="100%">';
	echo		"<tr>";
	echo			'<th>Περιεχόμενο</th>';
	echo			'<th><input type="checkbox" value="" id = "select_all" onclick="all_checkboxes();"></th>';
	echo		"</tr>";
	
	foreach($result as $row) {
		$text = $row['text'];
		$id = $row['id'];
		
		echo "<tr>";
		echo	'<td style="text-align:left;">'.$text.'</td>';
		echo	'<td><input class = "messageCheckbox" type="checkbox" id="notification" value="'.$id.'"></td>';	
		echo "</tr>";
	}

	echo "</table>";
	
	echo	"<br>";
	echo	'<table width="100%">';
	echo		"<tr>";
	echo			'<td width="80%"></td>';
	echo			'<td width="20%"><input id ="delete_notifications"  type="button" value="Διαγραφή Ειδοποιήσεων" class="btn btn-primary"></td>';
	echo		'</tr>';
	echo	'</table>';
}

echo '<br>';
echo '</div>';

?>
<br><br><br><br><br><br><br><br><br><br><br>
<footer class="container-fluid text-center">
  Footer Text
</footer>


<script>
$(document).ready(function() {
	
	var unsaved = false;
	var content_ids_change_table = new Array();
	var content_change_table = new Array();
	var array_counter = 0; 
	var password = false;
	var npassword = false;	
		
	$.post("admin_header.php", {	
	}, function(data) {
		$('header').html(data);
	});
	
	$.post("../footer.php", {	
	}, function(data) {
		$('footer').html(data);
	});
	
	$("#submit4").click(function() {
		var value = $("#friend_name").val();
		var alumni_id = <?php echo(json_encode($alumni_id)); ?>;
		if(value == '') {
			alert("Πρέπει να συμπληρώσεις πρώτα το όνομα ή το επώνυμο");
		}
		else
		{
			$.post("edit_alumni_search.php", {	
				value: value,
				alumni_id: alumni_id
			}, function(data) {
				$('#results').html(data);
			});
		}
	});

	$("#clear").on("click",function(e) {
		e.preventDefault();
		$("#myPdf").val(''); 
	});	

	
	$(":input").change(function(){ //trigers change in all input fields including text type
		
		unsaved = true;
		var newsletter_found_option = false;	//σημαια αν πατηθηκε μια από τις επιλογες των newsletter
		
		var current_id = $(this).attr('id');
		
		var alumni_id = <?php echo(json_encode($alumni_id)); ?>;
		var newsletter_categories_table = <?php echo(json_encode($categories_id)); ?>;	//παρε τις επιλογες που εχει ο χρήστης
		var table_length = newsletter_categories_table.length; //μεγεθος παραπάνω πίνακα
		if(table_length > 0) {
			for(var i=0; i<table_length; i++) {
				if(current_id == "option_id"+newsletter_categories_table[i]) {		//αν όντως εχει επιλέξει μια από τις επιλογες
						unsaved = false;	//τότε δεν χρειαζεται να τον προειδοποιήσουμε για μη αποθηκευμένα δεδομένα
						newsletter_found_option = true; //σηκώνουμε την σημαια 
						
						var option = newsletter_categories_table[i];
						if(this.checked) 
						{	
							var action = 1; 
							$.post("update_newsletter.php", {
								option: option,
								action: action,
								alumni_id: alumni_id
							}, function() {
								if ($(window).width() < 768) {
									mcxDialog.alert('Εγγραψατε τον απόφοιτο στα newsletter με επιτυχία');
								}
								else 
								{
									alert('Εγγραψατε τον απόφοιτο στα newsletter με επιτυχία');
								}
							});
						}
						else
						{
							var action = 0; 
							$.post("update_newsletter.php", {
								option: option,
								action: action,
								alumni_id: alumni_id
							}, function() {
								if ($(window).width() < 768) {
									mcxDialog.alert('Απεγγραψατε τον απόφοιτο από τα newsletter με επιτυχία');
								}
								else 
								{
									alert('Απεγγραψατε τον απόφοιτο από τα newsletter με επιτυχία');
								}
							});										
						}
				}
			}	
		}
		if(newsletter_found_option == false) {  //για να τον αποτρέψουμε να "πειράξει" τα δεδομένα του αποφοίτου
			
			if(current_id == "myPdf") {
				unsaved = false;			//αν επιλέξει να ανεβάσει ένα pdf αρχείο τότε δεν χρειαζεται να τον
			}								//προειδοποιήσουμε για μη αποθηκευμένα δεδομένα
			else if(current_id == "friend_name") {
				unsaved = false;			//ομοίως αν επιλέξει να ακολουθησει εναν απόφοιτο.
			}
			else if(current_id == "select_all") {
				unsaved = false;				//ομοίως αν επιλέξει να διαγράψει όλες τις ειδοποιήσεις του απόφοιτου
			}
			else if(current_id == "notification") {		//ομοίως αν επιλέξει να διαγράψει κάποια από τις ειδοποιήσεις του αποφοίτου
				unsaved = false;
			}
			else if(current_id == "admin_password") {	//σε αυτή την περίπτωση ο χρήστης εχει δικαίωμα να αλλάξει τον κωδικό του
				unsaved = false;
				var pass = $(this).val();
				
				$.post("check_password.php", {	
					pasword: pass,
					alumni_id: alumni_id
				}, function(data) {
					
					if (!$.trim(data)){  // αν η αποκριση ειναι μηδενική τοτε ο χρήστης εβαλε λαθος κωδικο
						if ($(window).width() < 768) {
							mcxDialog.alert("Λάθος Κώδικός");
						}
						else 
						{
							alert("Λάθος Κώδικός");
						}
						$("#admin_password").css("border","2px solid red");
					}
					else
					{
						$("#admin_password").css("border","2px solid green");
						password = true;
					}
					
				});
				
			}
			else if(current_id == "new_password") {
				unsaved = false;			
				var new_password = $(this).val();
				var pass = $("#admin_password").val();
				
				if(password == false) {
					if ($(window).width() < 768) {
						mcxDialog.alert("Πρέπει να συμπληρώσετε πρώτα τον τωρινό κωδικό σας");
					}
					else 
					{
						alert("Πρέπει να συμπληρώσετε πρώτα τον τωρινό κωδικό σας");
					}
					$("#admin_password").css("border","2px solid red");
				}
				else
				{
					if(new_password !== pass) {
						$("#new_password").css("border","2px solid green");
					}
					else
					{				
						if ($(window).width() < 768) {
							mcxDialog.alert("Ο νέος κωδικός πρέπει να διαφέρει από τον τωρινό");
						}
						else 
						{
							alert("Ο νέος κωδικός πρέπει να διαφέρει από τον τωρινό");
						}				
						$("#new_password").css("border","2px solid red");
					}
				}		
				
			}
			else if(current_id == "con_new_pass") {
				unsaved = false;
				var confirmpass = $(this).val();
				var new_password = $("#new_password").val();			
				
				if(password == true) {
					if(new_password !== confirmpass) {
						if ($(window).width() < 768) {
							mcxDialog.alert("Λάθος Κώδικός");
						}
						else 
						{
							alert("Λάθος Κώδικός");
						}
						$("#con_new_pass").css("border","2px solid red");
					}
					else
					{
						$("#con_new_pass").css("border","2px solid green");
						npassword = true;
					}
				}
				else
				{
					if ($(window).width() < 768) {
						mcxDialog.alert("Πρέπει να συμπληρώσετε πρώτα τον τωρινό κωδικό σας");
					}
					else 
					{
						alert("Πρέπει να συμπληρώσετε πρώτα τον τωρινό κωδικό σας");
					}
					$("#admin_password").css("border","2px solid red");
				}
			}
			else
			{								//αλλίως αν όντως η επιλογή του έχει να κάνει με τα προσωπικά δεδομένα
				if(current_id == null) {
					current_id = "job";
				}
				var pos = content_ids_change_table.indexOf(current_id);
				var value = $(this).val();
				if(pos < 0) { //απορριπτω τα id τα οποία έχουν καταχωρηθεί ήδη στον πινακα
					if(content_ids_change_table.length > 0) {
						array_counter = array_counter + 1;
						content_ids_change_table[array_counter] = current_id;
						content_change_table[array_counter] = value;
					}
					else  // ο πινακας είναι αδειος
					{
						content_ids_change_table[array_counter] = current_id;
						content_change_table[array_counter] = value;
					}
				}
				else  //αποθηκεύω την νέα τιμή στην θέση που βρέθηκε το στοιχείο
				{
					content_change_table[pos] = value;
				}
			}
		}	
	});

	function unloadPage(){ 
		if(unsaved == true){
			return "You have unsaved changes on this page. Do you want to leave this page and discard your changes or stay on this page?";
		}
	}
	window.onbeforeunload = unloadPage;	
	
	$("#submit").click(function(){
		unsaved = false;
		var alumni_id = <?php echo(json_encode($alumni_id)); ?>;
		if(content_ids_change_table.length > 0) 
		{
			$.post("change_alumni_data.php", {
				alumni_id: alumni_id,
				idTable: content_ids_change_table,
				contentTable: content_change_table
			}, function(data) {
				if ($(window).width() < 768) {
					mcxDialog.alert("Η ανανέωση των προσωπικών δεδομένων του απόφοιτου έγινε με επιτυχια");
				}
				else 
				{
					alert("Η ανανέωση των προσωπικών δεδομένων του απόφοιτου έγινε με επιτυχια");
				}
				location.reload();
			});
		}
		else
		{
			if ($(window).width() < 768) {
				mcxDialog.alert("Πρέπει να συμπληρώσετε κάποια πεδια πριν προχωρήσετε στην αποθήκευση.");
			}
			else 
			{
				alert("Πρέπει να συμπληρώσετε κάποια πεδια πριν προχωρήσετε στην αποθήκευση.");
			}	
		}
	});	
	
	$("#delete_notifications").click(function() {
		
		var checkedValue = [];
		var counter = 0;
		var inputElements = document.getElementsByClassName('messageCheckbox');
		for(var i=0; inputElements[i]; ++i){
			if(inputElements[i].checked){
				checkedValue[counter] = inputElements[i].value;
				counter = counter + 1;
			}
		}
		if(checkedValue.length != 0) { 
		
			$.post("delete_notifications.php", {
				ids: checkedValue
			}, function(data) {
				var message;
				if(data == 1) {
					message = "Η διαγραφή των ειδοποιήσεων πραγματοποιήθηκε με επιτυχία";
				}
				else
				{
					message = "Παρουσιάστηκε πρόβλημα στην διαγραφή των ειδοποιήσεων";
				}
				if ($(window).width() < 768) {
					mcxDialog.alert(message);
				}
				else 
				{
					alert(message);
				}
				location.reload();
			});
		}
		else
		{
			if ($(window).width() < 768) {
				mcxDialog.alert("Πρέπει να επιλέξεις ειδοποιήσεις πρώτα");
			}
			else 
			{
				alert("Πρέπει να επιλέξεις ειδοποιήσεις πρώτα");
			}
			//alert("Πρέπει να επιλέξεις ειδοποιήσεις πρώτα");
		}
		
	});

	$("#change_password").click(function(){
		
		var alumni_id = <?php echo(json_encode($alumni_id)); ?>;
		var p = $("#new_password").val();
		var confirmpass = $("#con_new_pass").val();
		var pass = $("#admin_password").val();
		
		if(npassword == false) {
			if ($(window).width() < 768) {
				mcxDialog.alert("Λάθος καταχωρήσεις σε κάποια από τα πεδία");
			}
			else 
			{
				alert("Λάθος καταχωρήσεις σε κάποια από τα πεδία");
			}
			if(p == '') {
				$("#new_password").css("border","2px solid red");
			}
			if(confirmpass == '') {
				$("#con_new_pass").css("border","2px solid red");
			}
			if(pass == '') {
				$("#admin_password").css("border","2px solid red");
			}
		}
		else
		{
			$.post("update_password.php", {	
				pass: p,
				alumni_id: alumni_id
			}, function(data) {
				if ($(window).width() < 768) {
					mcxDialog.alert("Οι αλλαγη του κωδικού πρόσβασης έγινε με επιτυχία");
				}
				else 
				{
					//alert(data);
					alert("Οι αλλαγη του κωδικού πρόσβασης έγινε με επιτυχία");
				}
			});
		}
	});	
	
	function updateAlerts() {
	$.ajax({
		url : "updateNotif.php",
		type : "POST",
		success : function(data, textStatus, XMLHttpRequest)
		{
			//console.log(XMLHttpRequest);
			var response = $.parseJSON(data);
			console.log(response);
			
			if (response > 0) {
				$('#notification').attr("src", "../assets/red.png");
			}
			else {
				$('#notification').attr("src", "../assets/white.png");
			}
		}
	});
	setTimeout(updateAlerts, 1000); 
	}
	updateAlerts();	

});
</script>

<script>
function myFunction(id)
{
	
	if ($(window).width() < 768) {
		
		mcxDialog.confirm("Θέλετε σίγουρα να διαγράψετε την εικόνα του απόφοιτου? Η εικόνα θα αντικατασταθεί από την default", {
			sureBtnText: "OK",  
			sureBtnClick: function(){  
				
				$.post("change_image.php", {
					alumni_id: id
				}, function(data) { 
					mcxDialog.alert("Η εικόνα διαγράφτηκε με επιτύχια");
					location.reload();
				});
				
			}
		});
	}
	else 
	{
		var response = confirm("Θέλετε σίγουρα να διαγράψετε την εικόνα του απόφοιτου? Η εικόνα θα αντικατασταθεί από την default.");
		
		if (response == true) {

			$.post("change_image.php", {
				alumni_id: id
			}, function(data) { 
				//alert("Η εικόνα διαγράφτηκε με επιτύχια");
				alert(data);
				location.reload();
			}); 

		}
	}
	
}
</script>
<script>
function all_checkboxes() //λειτουργία selectALL checkboxes
{
	var select = document.getElementById('select_all');
	var inputElements = document.getElementsByClassName('messageCheckbox');
	for(var i=0; inputElements[i]; ++i){
		var check = inputElements[i];
		if(select.checked == true) {
			if(check.checked == true){
				continue;
			}
			else
			{
				check.checked = true;
			}
		}
		else
		{
			if(check.checked == true){
				check.checked = false;
			}
			else
			{
				continue;
			}			
		}
	}
}


function unfollow_alumni(id)
{
	var w = Math.max(document.documentElement.clientWidth, window.innerWidth || 0);  //συναρτηση υπολογισμου του πλατους της οθόνης
	var alumni_id = <?php echo(json_encode($alumni_id)); ?>;
	
	if (w < 768) 
	{	
		mcxDialog.confirm("Θέλετε σίγουρα να διαγράψετε τον απόφοιτο από τους φίλους του χρήστη?", {
			sureBtnText: "OK",  
			sureBtnClick: function(){  
				
				$.post("unfollow_alumni.php", {
					alumni_id: id,
					user_id: alumni_id
				}, function(data) { 
					mcxDialog.alert("Η διαδικασία ολοκληρώθηκε με επιτυχία");
					location.reload();
				});
				
			}
		});
	}
	else 
	{		
	
		var response = confirm("Θέλετε σίγουρα να διαγράψετε τον απόφοιτο από τους φίλους του χρήστη?");
			
		if (response == true) {

			$.post("unfollow_alumni.php", {
				alumni_id: id,
				user_id: alumni_id
			}, function(data) { 
				alert("Η διαδικασία ολοκληρώθηκε με επιτυχία");
				location.reload();
			}); 

		} 
	}
}

function follow_alumni(id)
{
	var alumni_id = <?php echo(json_encode($alumni_id)); ?>;
	var w = Math.max(document.documentElement.clientWidth, window.innerWidth || 0);  //συναρτηση υπολογισμου του πλατους της οθόνης

	$.post("follow_alumni.php", {
		alumni_id: id,
		user_id: alumni_id
	}, function(data) { 
		if (w < 768) 
		{
			mcxDialog.alert("Η διαδικασία ολοκληρώθηκε με επιτυχία. Ο απόφοιτος προστέθηκε στους φίλους του χρήστη");
		}
		else
		{
			alert("Η διαδικασία ολοκληρώθηκε με επιτυχία. Ο απόφοιτος προστέθηκε στους φίλους του χρήστη");
		}
		location.reload();
	}); 
			
}

function viewPassword()
{
  var passwordInput = document.getElementById('admin_password');
  var passStatus = document.getElementById('pass-status');
 
  if (passwordInput.type == 'password'){
    passwordInput.type='text';
    passStatus.className='fa fa-eye-slash';
    
  }
  else{
    passwordInput.type='password';
    passStatus.className='fa fa-eye';
  }
}

function viewPassword2()
{
  var passwordInput = document.getElementById('new_password');
  var passStatus = document.getElementById('pass-status2');
 
  if (passwordInput.type == 'password'){
    passwordInput.type='text';
    passStatus.className='fa fa-eye-slash';
    
  }
  else{
    passwordInput.type='password';
    passStatus.className='fa fa-eye';
  }
}

function viewPassword3()
{
  var passwordInput = document.getElementById('con_new_pass');
  var passStatus = document.getElementById('pass-status3');
 
  if (passwordInput.type == 'password'){
    passwordInput.type='text';
    passStatus.className='fa fa-eye-slash';
    
  }
  else{
    passwordInput.type='password';
    passStatus.className='fa fa-eye';
  }
}

</script>

</body>
</html>	