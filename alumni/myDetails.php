<?xml version="1.0" encoding="UTF-8"?>
<!DOCTYPE html>
<html lang="en">
<head>
  <title>UOWM Alumni</title>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/meyer-reset/2.0/reset.min.css">
  <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap.min.css">
  <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.2.1/jquery.min.js"></script>
  <script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/js/bootstrap.min.js"></script>
  <script src='https://cdnjs.cloudflare.com/ajax/libs/jquery-easing/1.3/jquery.easing.min.js'></script>
  
  <link rel="stylesheet" href="../css/myDetails.css"> 
  
  <link rel="stylesheet" type="text/css" href="../phones_specs/css/dialog-mobile.css"/> <!-- css & javascript για τις ειδοποιήσεις (alerts) στα κινητα -->
  <script type="text/javascript" src="../phones_specs/mcx-dialog.js"></script>
  
    
</head>

<body>

<header>

</header>

<br><br><br>
<?php

session_start();
include ("../connectPDO.php");
if(isset($_SESSION['student'])) {
	$alumni_id = $_SESSION['student'];

	$stmt = $conn->prepare("SELECT * from users WHERE id = ?");   
	$stmt->execute(array($alumni_id));															
	$result = $stmt->fetchAll();
	
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
		$postgraduate = $row['metaptuxiako'];
		$postgraduate2 = $row['didaktoriko'];
	}
}
else
{
header('Location: ../register_login_form.php');
}

$timestamp  = strtotime($birthday_date); 
$bdate =  date('Y-m-d',$timestamp);
	
$graduation_date = strtotime($graduation_date);
$graduation_date = date('Y-m-d',$graduation_date);

echo '<form id="msform">';

echo  '<ul id="progressbar">';
echo    '<li class="active">Personal Details</li>';
echo	'<li>Social Profiles</li>';
echo    '<li>Useful Details</li>';
echo  '</ul>';

echo  '<fieldset>';
echo    '<h2 class="fs-title">Προσωπικα Στοιχεια</h2>';
echo    '<h3 class="fs-subtitle">Βήμα 1 από 3</h3>';
echo	'<label for="fname"><b>Όνομα</b></label>';
echo    '<input type="text" name="fname" value="'.$name.'" id = "name" />';
echo	'<label for="lname"><b>Επώνυμο</b></label>';
echo    '<input type="text" name="lname" value="'.$lastname.'" id = "lastname"/>';
echo	'<label for="father"><b>Πατρώνυμο</b></label>';
echo	'<input type="text" name="father" value="'.$fathers_name.'" id = "fathers_name" />';
echo	'<label for="email"><b>Email</b></label>';
echo	'<input type="text" name="email" value="'.$email.'" id = "email"/>';
echo	'<label for="bdate"><b>Ημερομηνία Γέννησης</b> (mm-dd-yyyy)</label>';
if($bdate == "1970-01-01") {
	echo  '<input class="form-control fontsize" type="date" id="birthday_date" name="owner">'; 		
}
else
{
	echo  '<input class="form-control fontsize" type="date" id="birthday_date" name="owner" value="'.$bdate.'">'; 
}
echo	'<label for="phone"><b>Τηλέφωνο</b></label>';
echo	'<input type="text" name="phone" value="'.$phone.'" id = "phone"/>';
echo	'<label for="cphone"><b>Κινητό</b></label>';
echo	'<input type="text" name="cphone" value="'.$phone2.'" id = "cell_phone"/>';
echo	'<label for="city"><b>Πόλη Διαμονής</b></label>';
echo	'<input type="text" name="city" value="'.$residence_city.'" id = "residence_city"/>';
echo	'<label for="job_city"><b>Πόλη Εργασίας</b></label>';
echo	'<input type="text" name="job_city" value="'.$job_city.'" id = "job_city"/>';
echo	'<label for="work"><b>Επάγγελμα</b></label>';
echo	'<select name="work"> id ="work"';
if($job == 0) {
	echo		'<option value="0" disabled selected>--Eπιλογή--</option>';
	echo		'<option value="1">Ιδιωτικός Υπάλληλος</option>';
	echo		'<option value="2">Δημόσιος Υπάλληλος</option>';
	echo		'<option value="3">Ελεύθερος Επαγγελματίας</option>';
}
else if($job == 1) {
	echo		'<option value="1" selected="selected">Ιδιωτικός Υπάλληλος</option>';
	echo		'<option value="2">Δημόσιος Υπάλληλος</option>';
	echo		'<option value="3">Ελεύθερος Επαγγελματίας</option>';
}
else if($job == 2) {
	echo		'<option value="2" selected="selected">Δημόσιος Υπάλληλος</option>';
	echo		'<option value="1">Ιδιωτικός Υπάλληλος</option>';
	echo		'<option value="3">Ελεύθερος Επαγγελματίας</option>';
}
else if($job == 3) {
	echo		'<option value="3" selected="selected">Ελεύθερος Επαγγελματίας</option>';
	echo		'<option value="1">Ιδιωτικός Υπάλληλος</option>';
	echo		'<option value="2">Δημόσιος Υπάλληλος</option>';
}
echo	'</select>';
echo	'<label for="job"><b>Αντικείμενο Εργασίας</b></label>';
echo	'<textarea name="job" id = "Workpiece">'.$workpiece.'</textarea>';
    
echo    '<input type="button" name="Επόμενο" class="next action-button" value="Επόμενο" />  <br><br>';
echo  '</fieldset>';
echo  '<fieldset>';
echo    '<h2 class="fs-title">Στοιχεια Διασυνδεσης</h2>';
echo    '<h3 class="fs-subtitle">Βήμα 2 από 3</h3>';
echo    '<label for="facebook"><b>Facebook</b></label>';
echo	'<input type="text" name="facebook" value="'.$facebook.'" id ="facebook"/>';
echo	'<label for="twitter"><b>Twitter</b></label>';
echo	'<input type="text" name="twitter" value="'.$twitter.'" id = "twitter"/>';
echo    '<label for="instagram"><b>Instagram</b></label>';
echo	'<input type="text" name="instagram" value="'.$instagram.'" id = "instagram"/>';
echo	'<label for="linkedin"><b>Linkedin</b></label>';
echo	'<input type="text" name="linkedin" value="'.$linkedin.'" id = "linkedin"/>';
echo    '<label for="gplus"><b>Google+</b></label>';
echo	'<input type="text" name="gplus" value="'.$google.'" id = "google"/>';
echo	'<label for="youtube"><b>Youtube</b></label>';
echo	'<input type="text" name="youtube" value="'.$youtube.'" id = "youtube"/>';
echo	'<label for="optional"><b>Άλλο</b></label>';
echo	'<input type="text" name="optional" value="'.$site.'" id = "sosial"/>';
    
echo	'<input type="button" name="Προηγούμενο" class="previous action-button" value="Προηγούμενο" />';
echo    '<input type="button" name="Επόμενο" class="next action-button" value="Επόμενο" /> <br><br>';
echo  '</fieldset>';
echo  '<fieldset>';
echo    '<h2 class="fs-title">Χρησιμα Στοιχεια</h2>';
echo    '<h3 class="fs-subtitle">Βήμα 3 από 3</h3>';
echo    '<label for="ryear"><b>Έτος Εισαγωγής</b></label>';
echo	'<input type="text" name="ryear" placeholder="π.χ. 2011" value="'.$registration_year.'" id = "registration_year"/>';
echo    '<label for="gyear"><b>Ημερομηνία Αποφοίτησης</b> (mm-dd-yyyy)</label>';
if($graduation_date == "1970-01-01") {
	echo	'<input class="form-control fontsize" type="date" id="graduation_date" name="owner">';
}
else
{
	echo	'<input class="form-control fontsize" type="date" id="graduation_date" name="owner" value="'.$graduation_date.'">';
}	
echo    '<label for="aem"><b>Αριθμός Μητρώου Φοιτητή (ΑΕΜ)</b></label>';
echo	'<input type="text" name="aem" value="'.$aem.'" id = "aem"/>';
echo	'<label for="grade"><b>Βαθμός Πτυχίου</b></label>';
echo	'<input type="text" name="grade" value="'.$grade.'" id = "degree_grade"/>';
echo	'<label for="thema"><b>Θέμα Διπλωματικής</b></label>';
echo	'<input type="text" name="thema" value="'.$thesis_diploma.'" id = "thesis_diploma_topic"/>';
echo	'<label for="metaptuxiako"><b>Μεταπτυχιακό</b></label>';
echo	'<input type="text" name="metaptuxiako" value="'.$postgraduate.'" id = "metaptuxiako"/>';
echo	'<label for="didaktoriko"><b>Διδακτορικό</b></label>';
echo	'<input type="text" name="didaktoriko" value="'.$postgraduate2.'" id = "didaktoriko"/>';


echo   '<input type="button" name="Προηγούμενο" class="previous action-button" value="Προηγούμενο" />';
echo    '<input type="button" name="Αποθήκευση" class="submit action-button" value="Αποθήκευση" />';
echo  '</fieldset>';
  
echo  '</form>';

?>
<br><br><br><br>
<footer class="container-fluid text-center">
  Footer Text
</footer>


<script>
$(document).ready(function() {
	
	window.scrollTo(0,0);  //επανέφερε την οθονη στην αρχή
	
	var unsaved = false;
	var content_ids_change_table = new Array();
	var content_change_table = new Array();
	var array_counter = 0; 
		
	$.post("alumni_header.php", {	
	}, function(data) {
		$('header').html(data);
	});
	
	$.post("../footer.php", {	
	}, function(data) {
		$('footer').html(data);
	});
		
	$(":input").change(function(){ //trigers change in all input fields including text type
		
		unsaved = true;
		
		var re = /^[\d\.\-]+$/;
		
		var current_id = $(this).attr('id');
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
			
	});

	function unloadPage(){ 
		if(unsaved == true){
			return "You have unsaved changes on this page. Do you want to leave this page and discard your changes or stay on this page?";
		}
	}
	window.onbeforeunload = unloadPage;
	
	
	$(".submit").click(function(){
		unsaved = false;
		if(content_ids_change_table.length > 0) 
		{
			$.post("alumni_data.php", {
				idTable: content_ids_change_table,
				contentTable: content_change_table
			}, function(data) {
				if ($(window).width() < 768) {
					mcxDialog.alert("Η ανανέωση των προσωπικών δεδομένων σας έγινε με επιτυχια");
				}
				else 
				{
					alert("Η ανανέωση των προσωπικών δεδομένων σας έγινε με επιτυχια");
				}	
				location.replace("alumni_index.php");

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

<script src="js/index.js"> //script για το animation της φορμας</script> 

</body>
</html>
